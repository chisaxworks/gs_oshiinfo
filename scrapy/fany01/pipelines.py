# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: https://docs.scrapy.org/en/latest/topics/item-pipeline.html


# useful for handling different item types with a single interface
from itemadapter import ItemAdapter
import mysql.connector
from mysql.connector import Error

class Fany01SQLPipeline:
    def open_spider(self, spider):
        try:
            self.connection = mysql.connector.connect(
                host="localhost",
                user="root",
                password="",
                database="scrapy",
                charset='utf8mb4',
                collation='utf8mb4_general_ci'
            )
            
            if self.connection.is_connected():
                db_Info = self.connection.get_server_info()
                print("Connected to MySQL Server version ", db_Info)
                cursor = self.connection.cursor()
                cursor.execute("select database();")
                record = cursor.fetchone()
                print("You're connected to database: ", record)
                return self.connection
            
        except Error as e:
            print("Error while connecting to MySQL", e)
            return None
        
    def process_item(self, item, spider):
        self.c = self.connection.cursor()
        try:
            if item.get('insert_table') == 'event':
                self.c.execute('''
                    INSERT INTO event(event_ids, event_name, venue, event_date, event_startTime, event_openTime, performers, source, event_type, event_type_ja, createdDate, modifiedDate)
                    VALUES(%s,%s,%s,%s,%s,%s,%s,'FANY','comedy_show','お笑いライブ',now(),now())
                    ON DUPLICATE KEY UPDATE performers = VALUES(performers), modifiedDate = now()
                ''',(
                    item.get('event_ids'),
                    item.get('event_name'),
                    item.get('venue'),
                    item.get('event_date'),
                    item.get('event_startTime'),
                    item.get('event_openTime'),
                    item.get('performers')
                ))
            elif item.get('insert_table') == 'ticket_info' and item.get('ti_startDate') != None:
                self.c.execute('''
                    INSERT INTO ticket_info(ti_ids, event_ids, sm_id, agency_id, ti_startDate, ti_startTime, ti_endDate, ti_endTime, ti_url, createdDate, modifiedDate)
                    VALUES(CONCAT(%s,'_',%s),%s,%s,1,%s,%s,%s,%s,%s,now(),now())
                    ON DUPLICATE KEY UPDATE ti_url = VALUES(ti_url), modifiedDate = now()
                ''',(
                    item.get('ti_ev_ids'),
                    item.get('sm_id'),
                    item.get('ti_ev_ids'),
                    item.get('sm_id'),
                    item.get('ti_startDate'),
                    item.get('ti_startTime'),
                    item.get('ti_endDate'),
                    item.get('ti_endTime'),
                    item.get('ti_url')
                ))
            self.connection.commit()
        
        except mysql.connector.errors.IntegrityError as e:
            print("IntegrityError", e)

        return item
    
    def close_spider(self, spider):
        self.connection.close()