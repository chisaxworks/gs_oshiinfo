<!-- ログインFORM -->
<!-- メールログイン -->
<?php
// SESSIONスタート
session_start();

// 関数ファイル呼び出し
require_once('funcs.php');

// SESSIONにエラー情報があったら表示する
if(isset($_SESSION["error_msg"])){
    $alert = '<div class="alert">' . $_SESSION['error_msg'] . '</div>';
    unset($_SESSION['error_msg']); // メッセージを表示した後にセッション変数をクリア
}

// LOGINチェック(index専用：SESSION残ってたらmainに移動する)
if(isset($_SESSION["chk_ssid"]) && $_SESSION["chk_ssid"] = session_id()){

    // セッションID払出しし直し
    session_regenerate_id(true);
    $_SESSION["chk_ssid"] = session_id();

    // メインに移動
    header('Location: main_un.php');
}

?>

<?php include("head.php");?>
<body id="index">
    <main>
        <div class="logreg_inner">
            <div class="logreg_copy">
                <h1>
                    推しの情報収集を楽にする！<br>
                    推し情報管理アプリ Oshi Info
                </h1>
            </div>
            <!-- ログイン画面 -->
            <div class="logreg_wrap">
                <h2>メールアドレスでログイン</h2>
                <?= $alert ?>
                <form action="login_m_done.php" method="post" class="input_form">
                    <div class="input_item">
                        <label for="email">メールアドレス</label>
                        <input type="email" name="email" id="email" class="inputarea">
                    </div>
                    <div class="input_item">
                        <label for="password">パスワード</label>
                        <input type="password" name="password" id="password" class="inputarea">
                    </div>
                    <button type="submit" id="login" class="act_btn">ログイン</button>
                </form>
                <a href="register.php" class="sub_btn">ユーザ登録はこちら</a>
                <p class="reg_info">※Googleログインの場合はユーザ登録作業は不要です</p>
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