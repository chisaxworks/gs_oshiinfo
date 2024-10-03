# Define here the models for your scraped items
#
# See documentation in:
# https://docs.scrapy.org/en/latest/topics/items.html

import scrapy
from itemloaders.processors import TakeFirst, MapCompose, Join

def get_ids(element):
    if element:
        try:
            #showを取得
            if 'show=' in element:
                sid = element.split('&')[1].replace('show=','')
            else:
                raise ValueError("Error: 'show=' not found in element")
            #shownoを取得
            if 'showno=' in element:
                sno = element.split('&')[4].replace("';","").replace('showno=','')
            else:
                raise ValueError("Error: 'showno=' not found in element")
            #showとshownoを返す
            return sid, sno
        except IndexError:
            raise ValueError("Error: Element format is incorrect or missing required parts")
    return element

def get_opentime(element):
    if element:
        if '開場' in element:
            opentime = element.split('\u3000')[0].replace('開場','')
        else:
            raise ValueError("Error: '開場' not found in element")
        return opentime
    return element
    
def get_starttime(element):
    if element:
        starttime = element.split('\u3000')[1].replace('開演','')
        return starttime
    return element
    
def get_performer(element):
    if element:
        performers = element.replace('出演者:','')
        return performers
    return element

def get_ymd(element):
    if element:
        ymd = element.split('日')[0].replace('年','-').replace('月','-')
        return ymd
    return element

def get_sm(element):
    if element:
        try:
            #showを取得
            if '一般発売' in element:
                sm_id = 1
            elif '先行' in element:
                sm_id = 2
            else:
                raise ValueError("Error: this sales method is unknown")
            return sm_id
        except IndexError:
            raise ValueError("Error: Element format is incorrect or missing required parts")
    return element

def get_tidate(element):
    if element:
        tidate = element.split('日')[0].replace('年','-').replace('月','-')
        return tidate
    return element
    
def get_titime(element):
    if element:
        titime = element.split('\u3000')[1].replace('時',':').replace('分','')
        return titime
    return element

class Fany01Item(scrapy.Item):
    insert_table = scrapy.Field()
    event_ids = scrapy.Field(
        input_processor = MapCompose(get_ids),
        output_processor=Join('_')
    )
    event_name = scrapy.Field(
        input_processor = MapCompose(str.rstrip),
        output_processor=TakeFirst()
    )
    venue = scrapy.Field(
        input_processor = MapCompose(str.rstrip),
        output_processor=TakeFirst()
    )
    event_date = scrapy.Field(
        input_processor = MapCompose(get_ymd),
        output_processor=TakeFirst()
    )
    event_startTime = scrapy.Field(
        input_processor = MapCompose(str.rstrip, get_starttime),
        output_processor=TakeFirst()
    )
    event_openTime = scrapy.Field(
        input_processor = MapCompose(str.rstrip, get_opentime),
        output_processor=TakeFirst()
    )
    performers = scrapy.Field(
        input_processor = MapCompose(str.rstrip, get_performer),
        output_processor=Join('、')
    )
    ti_ev_ids = scrapy.Field(
        input_processor = MapCompose(get_ids),
        output_processor=Join('_')
    )
    sm_id = scrapy.Field(
        input_processor = MapCompose(get_sm),
        output_processor=TakeFirst()
    )
    ti_startDate = scrapy.Field(
        input_processor = MapCompose(str.rstrip, get_tidate),
        output_processor=TakeFirst()
    )
    ti_startTime = scrapy.Field(
        input_processor = MapCompose(str.rstrip, get_titime),
        output_processor=TakeFirst()
    )
    ti_endDate = scrapy.Field(
        input_processor = MapCompose(str.rstrip, get_tidate),
        output_processor=TakeFirst()
    )
    ti_endTime = scrapy.Field(
        input_processor = MapCompose(str.rstrip, get_titime),
        output_processor=TakeFirst()
    )
    ti_url = scrapy.Field(
        input_processor = MapCompose(str.rstrip),
        output_processor=TakeFirst()
    )