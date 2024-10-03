# Define here the models for your scraped items
#
# See documentation in:
# https://docs.scrapy.org/en/latest/topics/items.html

import scrapy
from itemloaders.processors import TakeFirst, MapCompose, Join

def get_pid(element):
    if element:
        pid = element.replace('https://www.ntv.co.jp/program/detail/?programid=','')
        return pid
    return element

def get_date(element):
    if element:
        date = '2024-' + element.split('日（')[0].replace('月','-')
        return date
    return element

def get_stime(element):
    if element:
        temp = element.split('）')[1]
        if '朝' in temp:
            time = temp.split('～')[0].replace('朝 ','')
        elif '深夜' in temp:
            time = temp.split('～')[0].replace('深夜 ','')
        elif '昼 0' in element:
            time = temp.split('～')[0].replace('昼 0','12')
        elif '昼 1' in element:
            time = temp.split('～')[0].replace('昼 1','13')
        elif '昼 2' in element:
            time = temp.split('～')[0].replace('昼 2','14')
        elif '昼 3' in element:
            time = temp.split('～')[0].replace('昼 3','15')
        elif '夕方 3' in element:
            time = temp.split('～')[0].replace('夕方 3','15')
        elif '夕方 4' in element:
            time = temp.split('～')[0].replace('夕方 4','16')
        elif '夕方 5' in element:
            time = temp.split('～')[0].replace('夕方 5','17')
        elif '夜 6' in element:
            time = temp.split('～')[0].replace('夜 6','18')
        elif '夜 7' in element:
            time = temp.split('～')[0].replace('夜 7','19')
        elif '夜 8' in element:
            time = temp.split('～')[0].replace('夜 8','20')
        elif '夜 9' in element:
            time = temp.split('～')[0].replace('夜 9','21')
        elif '夜 10' in element:
            time = temp.split('～')[0].replace('夜 10','22')
        elif '夜 11' in element:
            time = temp.split('～')[0].replace('夜 11','23')
        return time
    return element

def get_etime(element):
    if element:
        if '朝' in element:
            time = element.split('～')[1]
        elif '深夜' in element:
            time = element.split('～')[1]
        elif '昼' in element:
            temp = element.split('～')[1]
            if '0:' in temp:
                time = temp.replace('0:','12:')
            elif '1:' in temp:
                time = temp.replace('1:','13:')
            elif '2:' in temp:
                time = temp.replace('2:','14:')
            elif '3:' in temp:
                time = temp.replace('3:','15:')
            elif '4:' in temp:
                time = temp.replace('4:','16:')
            elif '5:' in temp:
                time = temp.replace('5:','17:')
            elif '6:' in temp:
                time = temp.replace('6:','18:')                
            else:
                time = temp
        elif '夕方' in element:
            temp = element.split('～')[1]
            if '4:' in temp:
                time = temp.replace('4:','16:')
            elif '5:' in temp:
                time = temp.replace('5:','17:')
            elif '6:' in temp:
                time = temp.replace('6:','18:')     
            elif '7:' in temp:
                time = temp.replace('7:','19:')
            elif '8:' in temp:
                time = temp.replace('8:','20:')   
            else:
                time = temp
        elif '）夜' in element:
            temp = element.split('～')[1]
            if '6:' in temp:
                time = temp.replace('6:','18:')     
            elif '7:' in temp:
                time = temp.replace('7:','19:')
            elif '8:' in temp:
                time = temp.replace('8:','20:')   
            elif '9:' in temp:
                time = temp.replace('9:','21:')
            elif '10:' in temp:
                time = temp.replace('10:','22:')
            elif '11:' in temp:
                time = temp.replace('11:','23:')  
            else:
                time = temp
        return time
    return element

class TvntvItem(scrapy.Item):
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
        output_processor=Join('、')
    )