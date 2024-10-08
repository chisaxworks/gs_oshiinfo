# OShi Info （推し情報管理ツール）
卒業制作提出専用リポジトリ

- デプロイしたURL・ID・PWなどは、卒業制作提出のフォームに記載しておりますので、そちらをご覧ください。

# こちらのリポジトリの概要
実環境の中身まるまるは膨大なため、環境構築後更新を入れているファイル類を格納しています。不足がありましたらご連絡お願いします。

格納しているファイルは以下のとおりです。
- UI側ファイル（HTML・CSS・JS・PHP）
  - **/var/www/html**ディレクトリの中身のうち、googleAPIクライアントインストール時に作成させるvendorディレクトリ以外の、全てを格納しています。
- Scrapyのスクレイピング設定ファイル（Python）
  - Scrapyのプロジェクトやスパイダー作成時に、**/home/ubuntu/scrapy**ディレクトリの配下に自動生成される設定ファイルのうち、今回カスタマイズを入れているファイルのみ格納しています。
  - 各スパイダーのpyファイル、settings.py、item.py、pipeline.pyが対象
- SQLファイル
  - DB・Tableの構成内容のみ格納しています。後述の通り、基本は1日に1回データを自動で取得している環境から、データはエクスポートしていません。
- crontabの設定情報
  - 今回cronを使って、お笑いライブ、テレビ、ラジオの情報を1日1回取得しているため、その設定情報をテキストファイルに転記したものを格納しています。
  - 取得先や時間などは企画書にも表でまとめていますのでご覧ください。
 
# その他
ご不明点あればご連絡をお願いいたします。よろしくお願いします。