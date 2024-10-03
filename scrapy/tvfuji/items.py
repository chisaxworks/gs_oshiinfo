# Define here the models for your scraped items
#
# See documentation in:
# https://docs.scrapy.org/en/latest/topics/items.html

import scrapy
from itemloaders.processors import TakeFirst, MapCompose, Join

def get_date(element):
    if element:
        year = element[:4]
        month = element[4:6]
        day = element[6:8]
        date = year + '-' + month + '-' + day
        return date
    return element

def get_stime(element):
    if element:
        shour = element[-6:-4]
        smin = element[-4:-2]
        stime = shour + ':' + smin
        return stime
    return element

def get_etime(element):
    if element:
        ehour = element[-6:-4]
        emin = element[-4:-2]
        etime = ehour + ':' + emin
        return etime
    return element

class TvfujiItem(scrapy.Item):
    insert_table = scrapy.Field()
    program_id = scrapy.Field(
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