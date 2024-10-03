import scrapy
from tvntv.items import TvntvItem
from scrapy.loader import ItemLoader
from scrapy_playwright.page import PageMethod

#翌週用のスパイダー

class Tvntv02Spider(scrapy.Spider):
    name = "tvntv02"
    allowed_domains = ["www.ntv.co.jp"]
    # start_urls = ["https://www.ntv.co.jp/program"]

    def start_requests(self):
        url = "https://www.ntv.co.jp/program"
        yield scrapy.Request(
            url,
            meta=dict(
                playwright=True,
                playwright_include_page = True,
                playwright_page_methods =[
                    PageMethod("click","a.is-right"),
                ]
            ))

    async def parse(self, response):
        detailpage = response.xpath('//a[@class="program-table__schedule-description"]/@href').getall()
        for dpage in detailpage:
            if dpage is not None:
                durl = 'https://www.ntv.co.jp' + dpage
                yield scrapy.Request(durl, meta={'playwright':True},callback=self.parse_second)

    async def parse_second(self, response):
        if response.url != 'https://www.ntv.co.jp/program/detail/?programid=undefined':
            loader = ItemLoader(item=TvntvItem(), response=response)
            loader.add_value('program_id', response.url)
            loader.add_value('program_url', response.url)
            loader.add_xpath('program_title', '//div[@class="program-detail-title"]/text()')
            loader.add_xpath('program_date', '//div[@class="program-detail-date"]/text()')
            loader.add_xpath('program_startTime', '//div[@class="program-detail-date"]/text()')
            loader.add_xpath('program_endTime', '//div[@class="program-detail-date"]/text()')
            loader.add_xpath('performers', '//div[@class="program-detail-box-desc-text"]/text()')

            item = loader.load_item()
            #どのテーブルにINSERTするかの区分値
            item['insert_table'] = 'broadcast'
            yield item