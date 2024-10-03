# Define here the models for your scraped items
#
# See documentation in:
# https://docs.scrapy.org/en/latest/topics/items.html

import scrapy
from itemloaders.processors import TakeFirst, MapCompose, Join

def get_pid(element):
    if element:
        pid = element[-23:-5]
        return pid
    return element

def get_date(element):
    if element:
        date = element.split('日')[0].replace('年','-').replace('月','-')
        return date
    return element

def get_stime(element):
    if element:
        time_tmp = element.split(') ')[1]
        stime = time_tmp.split('分～')[0].replace('時',':')
        return stime
    return element
    
def get_etime(element):
    if element:
        etime = element.split('分～')[1].replace('時',':').replace('分','')
        return etime
    return element

class TvtxItem(scrapy.Item):
    insert_table = scrapy.Field()
    program_id = scrapy.Field(
        input_processor = MapCompose(get_pid),
        output_processor=TakeFirst()
    )
    program_url = scrapy.Field(
        output_processor=TakeFirst()
    )
    program_title = scrapy.Field(
        output_processor=TakeFirst()
    )
    program_date = scrapy.Field(
        input_processor = MapCompose(get_date),
        output_processor=TakeFirst()
    )
    program_startTime = scrapy.Field(
        input_processor = MapCompose(get_stime),
        output_processor=TakeFirst()
    )
    program_endTime = scrapy.Field(
        input_processor = MapCompose(get_etime),
        output_processor=TakeFirst()
    )
    performers = scrapy.Field(
        output_processor=TakeFirst()
    )