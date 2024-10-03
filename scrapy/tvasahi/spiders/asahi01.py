import scrapy
from scrapy.linkextractors import LinkExtractor
from scrapy.spiders import CrawlSpider, Rule
from tvasahi.items import TvasahiItem
from scrapy.loader import ItemLoader

class Asahi01Spider(CrawlSpider):
    name = "asahi01"
    allowed_domains = ["www.tv-asahi.co.jp"]
    start_urls = ["https://www.tv-asahi.co.jp/bangumi"]

    rules = (
        Rule(LinkExtractor(restrict_xpaths='//span[@class="prog_name"]/a[@class="bangumiDetailOpen"]'), callback="parse_item", follow=False),
        Rule(LinkExtractor(restrict_xpaths='//div[@id="nextWeek"]/p/a')),
        )

    def parse_item(self, response):
        loader = ItemLoader(item=TvasahiItem(), response=response)
        loader.add_value('program_id', response.url)
        loader.add_xpath('program_url', '//div[@id="titleleft"]/a/@href')
        loader.add_xpath('program_title', '//dt[@class="name"]/following-sibling::dd[1]/text()')
        loader.add_xpath('program_date', '//dt[@class="date"]/following-sibling::dd[1]/text()')
        loader.add_xpath('program_startTime', '//dt[@class="date"]/following-sibling::dd[1]/text()')
        loader.add_xpath('program_endTime', '//dt[@class="date"]/following-sibling::dd[1]/text()')
        loader.add_xpath('performers', '//div[@class="main02"][1]')
        
        item = loader.load_item()
        #どのテーブルにINSERTするかの区分値
        item['insert_table'] = 'broadcast'
        yield item