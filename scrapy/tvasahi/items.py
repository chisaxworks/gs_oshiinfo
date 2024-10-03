# Define here the models for your scraped items
#
# See documentation in:
# https://docs.scrapy.org/en/latest/topics/items.html

import scrapy
from itemloaders.processors import TakeFirst, MapCompose, Join

def get_pid(element):
    if element:
        pid = element.replace('https://www.tv-asahi.co.jp/pr/contents/','').replace('.html','')
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
        time = time_tmp.split(' 〜')[0]
        return time
    return element
    
def get_etime(element):
    if element:
        time = element.split('〜 ')[1]
        return time
    return element

def get_performers(element):
    if element:
        if '◇出演者' in element:
            performers_tmp = element.split('◇出演者')[1].replace('<!--<main02end>-->','').replace('<div id="mydiv1" style="display:none">','')
            performers_tmp2 = performers_tmp.split('◇')[0].replace('<br>','').replace('<b>','').replace('</b>','').replace('・\n','、').replace('\n','、')
            performers = performers_tmp2.replace('、、','、')
        elif '◇キャスト' in element:
            performers_tmp = element.split('◇キャスト')[1].replace('<!--<main02end>-->','').replace('<div id="mydiv1" style="display:none">','')
            performers_tmp2 = performers_tmp.split('◇')[0].replace('<br>','').replace('<b>','').replace('</b>','').replace('・\n','、').replace('\n','、')
            performers = performers_tmp2.replace('、、','、')
        else:
            performers = None
        return performers
    return element

class TvasahiItem(scrapy.Item):
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
        input_processor = MapCompose(get_performers),
        output_processor=TakeFirst()
    )