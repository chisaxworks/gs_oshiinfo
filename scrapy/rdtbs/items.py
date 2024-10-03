# Define here the models for your scraped items
#
# See documentation in:
# https://docs.scrapy.org/en/latest/topics/items.html

import scrapy
from itemloaders.processors import TakeFirst, MapCompose, Join

def get_stime(element):
    if element:
        time = element.split(' ～')[0]
        return time
    return element
    
def trim_stime(element):
    if element:
        timeforid = element.split(' ～')[0].replace(':','')
        return timeforid
    return element

def get_etime(element):
    if element:
        time = element.split('～ ')[1]
        return time
    return element

class RdtbsItem(scrapy.Item):
    insert_table = scrapy.Field()
    program_title = scrapy.Field(
        output_processor=TakeFirst()
    )
    performers = scrapy.Field(
        output_processor=TakeFirst()
    )
    program_date = scrapy.Field(
        output_processor=TakeFirst()
    )
    program_startTime = scrapy.Field(
        input_processor = MapCompose(get_stime),
        output_processor=TakeFirst()
    )
    program_startTimeForId = scrapy.Field(
        input_processor = MapCompose(trim_stime),
        output_processor=TakeFirst()
    )
    program_endTime = scrapy.Field(
        input_processor = MapCompose(get_etime),
        output_processor=TakeFirst()
    )
    program_url = scrapy.Field(
        output_processor=TakeFirst()
    )