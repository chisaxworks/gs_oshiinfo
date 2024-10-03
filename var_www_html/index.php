<!-- ログインFORM -->
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
                <h2>ログイン</h2>
                <?= $alert ?>
                <!-- Googleログイン -->
                <script src="https://accounts.google.com/gsi/client" async></script>
                <div class="wrap">
                    <div id="g_id_onload"
                        data-client_id="*****"
                        data-context="signin"
                        data-ux_mode="redirect"
                        data-login_uri="https://oshiinfo.com/login_g_done.php"
                        data-auto_prompt="false">
                    </div>
                    <div class="g_id_signin"
                        data-type="standard"
                        data-shape="rectangular"
                        data-theme="outline"
                        data-text="signin_with"
                        data-size="large"
                        data-width="300"
                        data-logo_alignment="left">
                    </div>
                </div>
                <!-- Googleログインここまで -->
                <a href="login.php" class="act_btn">メールアドレスでログイン</a>
            </div>
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