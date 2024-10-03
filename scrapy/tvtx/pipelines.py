# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: https://docs.scrapy.org/en/latest/topics/item-pipeline.html


# useful for handling different item types with a single interface
from itemadapter import ItemAdapter
import mysql.connector
from mysql.connector import Error

class TvtxSQLPipeline:
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
        if item.get('insert_table') == 'broadcast':
            self.c.execute('''
                INSERT INTO broadcast(program_id, program_title, program_url, program_date, program_startTime, program_endTime, performers, broadcast_station, broadcast_type, broadcast_type_ja, createdDate, modifiedDate)
                VALUES(%s,%s,%s,%s,%s,%s,%s,'テレビ東京','tv','テレビ',now(),now())
                ON DUPLICATE KEY UPDATE program_title = VALUES(program_title), performers = VALUES(performers), modifiedDate = now()
            ''',(
                item.get('program_id'),
                item.get('program_title'),
                item.get('program_url'),
                item.get('program_date'),
                item.get('program_startTime'),
                item.get('program_endTime'),
                item.get('performers')
            ))
            self.connection.commit()
        return item
    
    def close_spider(self, spider):
        self.connection.close()
