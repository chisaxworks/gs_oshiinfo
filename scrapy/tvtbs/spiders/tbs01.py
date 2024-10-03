import scrapy
from scrapy.linkextractors import LinkExtractor
from scrapy.spiders import CrawlSpider, Rule
from tvtbs.items import TvtbsItem
from scrapy.loader import ItemLoader

class Tbs01Spider(CrawlSpider):
    name = "tbs01"
    allowed_domains = ["www.tbs.co.jp"]
    start_urls = ["https://www.tbs.co.jp/tv"]

    rules = (
        # tobeでは[@class="variety"]は外す
        Rule(LinkExtractor(restrict_xpaths='//tr/td/div[@class="variety"]/a'), callback="parse_item", follow=False),
        Rule(LinkExtractor(restrict_xpaths='//li[@class="btn-nextweek"]/a')),
        )

    def parse_item(self, response):
        sections = response.xpath('//div[@class="sections"]')
        
        loader = ItemLoader(item=TvtbsItem(), response=response)
        loader.add_value('program_id', response.url)
        loader.add_value('program_url', response.url)
        loader.add_xpath('program_title', '//h1/span[@class="maintitle"]/text()')
        loader.add_xpath('program_date', '//div[@class="title-rig"]/p/text()')
        loader.add_xpath('program_startTime', '//div[@class="title-rig"]/p/text()')
        
        # performer初期化
        performers = []
        
        for section in sections:
            h2 = section.xpath('.//h2/span/text()').get()
            if '出演者' in h2 or 'ゲスト' in h2:
                performer = section.xpath('.//p[@class="txt"]/text()').getall()
                performers.extend(performer)  # performerをextendメソッドで結合
                
        loader.add_value('performers', performers)
        
        item = loader.load_item()
        #どのテーブルにINSERTするかの区分値
        item['insert_table'] = 'broadcast'
        yield item