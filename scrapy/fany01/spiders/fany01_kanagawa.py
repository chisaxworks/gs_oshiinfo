import scrapy
from scrapy.linkextractors import LinkExtractor
from scrapy.spiders import CrawlSpider, Rule
from fany01.items import Fany01Item
from scrapy.loader import ItemLoader
import re

class Fany01KanagawaSpider(CrawlSpider):
    name = "fany01_kanagawa"
    allowed_domains = ["yoshimoto.funity.jp","ty.funity.jp"]
    start_urls = ["https://yoshimoto.funity.jp/kglist/?prefecture_code=14"]

    rules = (
        Rule(LinkExtractor(restrict_xpaths='//a[@class="next page-numbers"]'), callback="parse_item", follow=True),
        )

    def parse_start_url(self, response):
        buttons = response.xpath('//button[@class="btn-7"]/@onclick').getall()
        for button in buttons:
            url = re.search(r"location.href='(.*?)'", button).group(1)
            yield response.follow(url, self.parse_item_second)
    
    def parse_item(self, response):
        buttons = response.xpath('//button[@class="btn-7"]/@onclick').getall()
        for button in buttons:
            url = re.search(r"location.href='(.*?)'", button).group(1)
            yield response.follow(url, self.parse_item_second)
    
    def parse_item_second(self, response):
        table = response.xpath('//div[@class="retrievalArea"]')
        for overview in table:
            loader = ItemLoader(item=Fany01Item(), selector=overview)

            loader.add_xpath('event_ids', './/div[@class="textStatus"]/ul[1]/li/div[@class="btnSpace"]/button/@onclick')
            loader.add_xpath('event_name', './/h4/text()')
            loader.add_xpath('venue', './/div[@class="textDetail"]/dl[2]/dd/text()[1]')
            loader.add_xpath('event_date', './/div[@class="textDetail"]/dl[1]/dd[1]/text()')
            loader.add_xpath('event_startTime', './/div[@class="textDetail"]/dl[1]/dd[2]/text()')
            loader.add_xpath('event_openTime', './/div[@class="textDetail"]/dl[1]/dd[2]/text()')
            loader.add_xpath('performers', './/td[@class="PerformanceDetails linePink"]/p[1]/text()')

            item = loader.load_item()
            #どのテーブルにINSERTするかの区分値
            item['insert_table'] = 'event'
            yield item

            #孫ページへの遷移
            buttons = overview.xpath('//button/@onclick').getall()
            for button in buttons:
                url = re.search(r"location.href='(.*?)'", button).group(1)
                yield response.follow(url, self.parse_item_third)

    def parse_item_third(self, response):
        loader = ItemLoader(item=Fany01Item(), response=response)
        loader.add_value('ti_url', response.url)
        loader.add_value('ti_ev_ids', response.url)
        loader.add_xpath('sm_id', './/table[@class="table-funity stick-to-next width-fill"]/tr/th')
        loader.add_xpath('ti_startDate', './/label[@id="InitAction_reserveStartDate"]/text()')
        loader.add_xpath('ti_startTime', './/label[@id="InitAction_reserveStartDate"]/text()')
        loader.add_xpath('ti_endDate', './/label[@id="InitAction_reserveLimitDate"]/text()')
        loader.add_xpath('ti_endTime', './/label[@id="InitAction_reserveLimitDate"]/text()')
        item = loader.load_item()
        
        #どのテーブルにINSERTするかの区分値
        item['insert_table'] = 'ticket_info'
        yield item