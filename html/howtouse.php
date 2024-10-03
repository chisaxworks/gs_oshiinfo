<?php
// SESSIONスタート
session_start();
// 関数ファイル呼び出し
require_once('funcs.php');
// LOGINチェック
sscheck();

$userid = $_SESSION["userid"];

?>
<?php include("head.php");?>
<body>
    <main>
        <div class="howtouse_inner">
            <h2 class="how_h2">Oshi Infoの使い方</h2>
            <div class="how_item">
                <h3 class="how_h3">STEP1：推しの登録</h3>
                <p class="bold">推しの登録がない状態では情報が表示されませんので、推しの登録をお願いします。</p>
                <br>
                <p class="bold">■操作方法■</p>
                <p class="normal">
                    「推し管理画面」のボタンをクリックして「推し登録画面」に遷移します。<br>
                    推しのお名前を入力して、「登録」ボタンを押すと登録できます。<br>
                    職種は当面芸人をデフォルト値にしていますが、変更も可能です。
                </p>
                <br>
                <p class="bold">■削除したい場合■</p>
                <p class="normal">
                    推しの登録を削除したい場合は、推し一覧から削除ボタンを押してください。
                </p>
            </div>
            <div class="how_item">
                <h3 class="how_h3">STEP2：情報の確認</h3>
                <p class="bold">推しを登録すると、まずは未管理情報に、推しの出演情報が表示されます。<br>
                情報の日付（公演日・放送日）が過去のものは表示されません。</p>
                <br>
                <p class="bold">■絞り込み機能■</p>
                <p class="normal">
                    推しが複数いる場合は、推しの名前をクリックすると、情報が推しの名前で絞り込み表示されます。<br>
                </p><br>
                <p class="bold">■詳細画面■</p>
                <p class="normal">
                    「詳細」ボタンクリックで、詳細が確認できます。<br>
                    また、ライブの場合はチケット情報も表示しています。チケットサイトの名前からチケット販売ページへも飛ぶことができます。
                    番組の場合は番組HPまたは番組表ページへ遷移します。
                </p><br>
                <p class="bold">■Googleカレンダー登録機能（詳細画面内）■</p>
                <p class="normal">
                    詳細画面で「Googleカレンダーに登録」ボタンをクリックすると、Googleカレンダーが立ち上がり、ご自身のカレンダーに登録ができます。
                    終わりの時間が未定の情報（現状はFANYとTBS）は仮の終了時間が入っていますので適宜修正してください。
            </div>
            <div class="how_item">
                <h3 class="how_h3">STEP3：情報の管理</h3>
                <p class="bold">＜未整理情報タブ画面での作業＞</p>
                <p class="bold">■管理する■</p>
                <p class="normal">
                    番組を見る・予約する、ライブに行く・チケットを申し込む予定などで、情報を忘れないようにしたい場合は、「管理」ボタンをクリックして管理してください。
                    「管理」ボタンをクリックすると、情報が「管理情報」のタブ画面に移動します。上部の「管理情報」の文字をクリックして確認してください。
                </p><br>
                <p class="bold">■管理しない■</p>
                <p class="normal">
                    管理が不要なものは、「管理しない」ボタンを押すと非表示になります。
                </p><br>
                <p class="bold">＜管理情報タブ画面での作業＞</p>
                <p class="bold">■完了にする■</p>
                <p class="normal">
                    申し込みが終わった、予約した、などで管理が不要となった場合は、「完了」ボタンをクリックすると非表示にできます。
                    また、情報の日付（公演日・放送日）が過去のものは自動的に表示されません。
                </p>
            </div>
            <div class="how_item">
                <h3 class="how_h3">アラート機能</h3>
                <p class="bold">
                    管理対象のものは前日・当日に、右上のベルマークをクリックした場所にアラートを表示します。<br>
                    アラートがある日はベルは赤くなります。アラートがないときは黒です。
                </p><br>
                <p class="bold">■アラート内容■</p>
                <p class="normal">
                    ・番組放送の前日、当日<br>
                    ・ライブの前日、当日<br>
                    ・チケットの先行＆一般販売開始の前日、当日<br>
                    ・チケットの先行販売終了の前日、当日
                </p>
            </div>
            <div class="how_item">
                <h3 class="how_h3">その他</h3>
                <p class="bold">■ログアウト■</p>
                <p class="normal">
                    ログアウトは右上の歯車アイコンからログアウトの文字をクリックしてログアウトしてください。<br>
                </p><br>
                <p class="bold">■アカウント削除■</p>
                <p class="normal">
                    アカウントを削除したい場合は下記のボタンをクリックするとアカウントが無効化されます。<br>
                </p>
                <a id="deleteuser_btn" href="delete_user.php?uid=<?= h($userid) ?>" class="mgsub_btn">アカウントを削除する</a>
            </div>
            <h2 class="how_h2">現在取得している情報について</h2>
            <div class="how_item">
                <p class="bold">
                    2024/9/30時点で取得している情報は以下の通りです。順次拡大予定です。
                </p><br>
                <p class="bold">■お笑いライブ■</p>
                <p class="normal">
                    FANYチケットの東京都・千葉県・埼玉県・神奈川県の公演情報<br>
                    （朝6:00から、7:45ごろまでに取得）
                </p><br>
                <p class="bold">■テレビ■</p>
                <p class="normal">
                    以下のテレビ局の1週間先までの番組表情報<br>
                    日本テレビ、テレビ朝日、TBS（バラエティのみ）、テレビ東京、フジテレビ<br>
                    （夕方15:30から、18:00ごろまでに取得）
                </p><br>
                <p class="bold">■ラジオ■</p>
                <p class="normal">
                    以下のラジオ局の1週間先までの番組表情報<br>
                    ニッポン放送、TBSラジオ、文化放送<br>
                    （昼11:15から、12:00ごろまでに取得）
                </p>
            </div>
        </div>
    </main>
    <footer class="logreg_footer">
        <div class="copyright">
            &copy; 2024 OSHI INFO | CHISAXWORKS
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>