import scrapy
from tvfuji.items import TvfujiItem
from scrapy.loader import ItemLoader

#今週用のスパイダー

class Fuji01Spider(scrapy.Spider):
    name = "fuji01"
    allowed_domains = ["www.fujitv.co.jp"]
    # start_urls = ["https://www.fujitv.co.jp/timetable/weekly"]

    def start_requests(self):
        url = "https://www.fujitv.co.jp/timetable/weekly"
        yield scrapy.Request(url, meta={'playwright':True})

    def parse(self, response):
        sections = response.xpath('//dd/a[@class="pgtitle"]')
        for section in sections:
            loader = ItemLoader(item=TvfujiItem(), selector=section)
            loader.add_xpath('program_id', './/@sutc')
            loader.add_xpath('program_url', './/@href')
            loader.add_xpath('program_title', './/@maintitle')
            loader.add_xpath('program_date', './/@sutc')
            loader.add_xpath('program_startTime', './/@sutc')
            loader.add_xpath('program_endTime', './/@eutc')
            loader.add_xpath('performers', './/@cast')

            item = loader.load_item()
            #どのテーブルにINSERTするかの区分値
            item['insert_table'] = 'broadcast'
            yield item