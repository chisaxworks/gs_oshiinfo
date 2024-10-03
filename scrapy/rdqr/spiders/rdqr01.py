import scrapy
from rdqr.items import RdqrItem
from scrapy.loader import ItemLoader

class Rdqr01Spider(scrapy.Spider):
    name = "rdqr01"
    allowed_domains = ["radiko.jp"]
    start_urls = ["https://radiko.jp/index/QRR"]

    def parse(self, response):
        sections = response.xpath('//a[@class="item__link item_program"]')
        for section in sections:
            loader = ItemLoader(item=RdqrItem(), selector=section)
            loader.add_xpath('program_title', './/p[@class="title"]/text()')
            loader.add_xpath('performers', './/p[@class="cast"]/text()')
            loader.add_xpath('program_date', './/ancestor::div[2]/@data-day')
            loader.add_xpath('program_startTime', './/p[@class="time"]/text()')
            loader.add_xpath('program_startTimeForId', './/p[@class="time"]/text()')
            loader.add_xpath('program_endTime', './/p[@class="time"]/text()')
            loader.add_xpath('program_url','.//parent::div/following-sibling::div[1]//p[@class="colorbox__text text-left text-small"]/a/@href')
            item = loader.load_item()
            
            #どのテーブルにINSERTするかの区分値
            item['insert_table'] = 'broadcast'
            
            yield item