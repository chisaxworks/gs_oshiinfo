import scrapy
from scrapy.linkextractors import LinkExtractor
from scrapy.spiders import CrawlSpider, Rule
from tvtx.items import TvtxItem
from scrapy.loader import ItemLoader

#今週用のスパイダー

class Tvtx01Spider(CrawlSpider):
    name = "tvtx01"
    allowed_domains = ["www.tv-tokyo.co.jp"]
    # start_urls = ["https://www.tv-tokyo.co.jp/timetable/broad_tvtokyo/thisweek"]

    rules = (
        Rule(LinkExtractor(restrict_xpaths='//a[@class="tbcms_schedule-weekly__inner"]'), callback="parse_item", follow=False),
        )

    def start_requests(self):
        url = "https://www.tv-tokyo.co.jp/timetable/broad_tvtokyo/thisweek"
        yield scrapy.Request(url, meta={'playwright':True})

    def parse_item(self, response):
        loader = ItemLoader(item=TvtxItem(), response=response)
        loader.add_value('program_id', response.url)
        loader.add_value('program_url', response.url)
        loader.add_xpath('program_title', '//h1/text()')
        loader.add_xpath('program_date', '//span[@class="tbcms_official-header__meta-onair"]/text()')
        loader.add_xpath('program_startTime', '//span[@class="tbcms_official-header__meta-onair"]/text()')
        loader.add_xpath('program_endTime', '//span[@class="tbcms_official-header__meta-onair"]/text()')

        # performer初期化
        performers = []
        h3_selectors = response.xpath('//div[@class="tbcms_program-detail"]/h3')
        for h3_selector in h3_selectors:
            h3_text = h3_selector.xpath('//div[@class="tbcms_program-detail"]/h3').get()
            if '出演' in h3_text:
                performer = h3_selector.xpath('.//following-sibling::div[@class="tbcms_program-detail__inner"]/p').xpath('string(.)').getall()
                performers.extend(performer)  # performerをextendメソッドで結合
        loader.add_value('performers', performers)

        item = loader.load_item()
        #どのテーブルにINSERTするかの区分値
        item['insert_table'] = 'broadcast'
        yield item