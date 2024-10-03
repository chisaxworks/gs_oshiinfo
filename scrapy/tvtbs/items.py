# Define here the models for your scraped items
#
# See documentation in:
# https://docs.scrapy.org/en/latest/topics/items.html

import scrapy
from itemloaders.processors import TakeFirst, MapCompose, Join

def get_pid(element):
    if element:
        pid = element.replace('http://www.tbs.co.jp/tv/','').replace('.html','')
        return pid
    return element
    
def url_toSSL(element):
    if element:
        purl = element.replace('http','https')
        return purl
    return element
    
def get_date(element):
    if element:
        date = element.split('日')[0].replace('年','-').replace('月','-')
        return date
    return element
    
def get_time(element):
    if element:
        if 'あさ' in element:
            time = element.split(') ')[1].replace('時',':').replace('あさ ','').replace('分〜','')
        elif 'ひる' in element:
            time = element.split(') ')[1].replace('時',':').replace('ひる ','').replace('分〜','')
        elif '深夜' in element:
            time = element.split(') ')[1].replace('時',':').replace('深夜 ','').replace('分〜','')
        elif 'ごご 1時' in element:
            time = element.split(') ')[1].replace('ごご 1時','13:').replace('分〜','')
        elif 'ごご 2時' in element:
            time = element.split(') ')[1].replace('ごご 2時','14:').replace('分〜','')
        elif 'ごご 3時' in element:
            time = element.split(') ')[1].replace('ごご 3時','15:').replace('分〜','')
        elif 'ごご 4時' in element:
            time = element.split(') ')[1].replace('ごご 4時','16:').replace('分〜','')
        elif 'ごご 5時' in element:
            time = element.split(') ')[1].replace('ごご 5時','17:').replace('分〜','')
        elif 'ごご 6時' in element:
            time = element.split(') ')[1].replace('ごご 6時','18:').replace('分〜','')
        elif 'よる 7時' in element:
            time = element.split(') ')[1].replace('よる 7時','19:').replace('分〜','')
        elif 'よる 8時' in element:
            time = element.split(') ')[1].replace('よる 8時','20:').replace('分〜','')
        elif 'よる 9時' in element:
            time = element.split(') ')[1].replace('よる 9時','21:').replace('分〜','')
        elif 'よる 10時' in element:
            time = element.split(') ')[1].replace('よる 10時','22:').replace('分〜','')
        elif 'よる 11時' in element:
            time = element.split(') ')[1].replace('よる 11時','23:').replace('分〜','')
        else:
            time = element.split(') ')[1].replace('時',':').replace('分〜','')
        return time
    return element

class TvtbsItem(scrapy.Item):
    insert_table = scrapy.Field()
    program_id = scrapy.Field(
        input_processor = MapCompose(get_pid),
        output_processor=TakeFirst()
    )
    program_url = scrapy.Field(
        input_processor = MapCompose(url_toSSL),
        output_processor=TakeFirst()
    )
    program_title = scrapy.Field(
        output_processor=Join(' ')
    )
    program_date = scrapy.Field(
        input_processor = MapCompose(str.rstrip, get_date),
        output_processor=TakeFirst()
    )
    program_startTime = scrapy.Field(
        input_processor = MapCompose(get_time),
        output_processor=TakeFirst()
    )
    performers = scrapy.Field(
        output_processor=Join(' ')
    )