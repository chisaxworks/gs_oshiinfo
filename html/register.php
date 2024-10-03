<!-- ユーザ登録FORM -->
<?php
// SESSIONスタート
session_start();

// SESSIONにエラー情報があったら表示する
if(isset($_SESSION["error_msg"])){
    $alert = '<div class="alert">' . $_SESSION['error_msg'] . '</div>';
    unset($_SESSION['error_msg']); // メッセージを表示した後にセッション変数をクリア
}

// 関数ファイル呼び出し
require_once('funcs.php');
// ログアウト処理
sslogout();

?>

<?php include("head.php");?>
<body id="reg">
    <main>
        <div class="logreg_inner">
            <div class="logreg_copy">
                <h1>
                    推しの情報収集を楽にする！<br>
                    推し情報管理アプリ Oshi Info
                </h1>
            </div>
            <!-- ユーザ登録画面 -->
            <div class="logreg_wrap">
                <h2>メールアドレスでユーザ登録</h2>
                <?= $alert ?>
                <form action="register_done.php" method="post" class="input_form">
                    <div class="input_item">
                        <label for="reg_name">名前</label>
                        <input type="text" name="reg_name" id="reg_name" class="inputarea">
                    </div>
                    <div class="input_item">
                        <label for="reg_email">メール</label>
                        <input type="email" name="reg_email" id="reg_email" class="inputarea">
                    </div>
                    <div class="input_item">
                        <label for="reg_password">パスワード</label>
                        <input type="password" name="reg_password" id="reg_password" class="inputarea">
                    </div>
                    <button type="submit" id="register" class="act_btn">登録</button>
                </form>
                <a href="index.php" class="sub_btn">ログインはこちら</a>
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