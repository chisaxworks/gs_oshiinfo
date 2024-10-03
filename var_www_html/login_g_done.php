<!-- ログイン処理PHP（リダイレクト） -->
<!-- Googleログイン -->
<?php

// SESSIONスタート
session_start();

// Googleログイン関連
require_once('vendor/autoload.php');
$idToken = $_POST['credential'];
$clientid = '*****';
define( 'CLIENT_ID', $clientid );
$client = new Google_Client( [ 'client_id' => CLIENT_ID ] ); 
$payload = $client->verifyIdToken( $idToken );

// ログイン情報をセッションに記載
if($payload) {
    //ログイン成功時
    $_SESSION["chk_ssid"] = session_id();
    $google_sub = $payload["sub"];
    $name = $payload["name"];
    $email = $payload["email"];

    // 関数ファイル呼び出し
    require_once('funcs.php');
    // PHPからDB接続
    $pdo = db_conn();

    // データ取得SQL（ユーザテーブル）
    $stmt = $pdo->prepare("SELECT user_id, user_name, user_email, google_sub FROM user WHERE user_email = :email AND isActive = 1");
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $status = $stmt->execute();

    // エラーハンドリング
    if($status === false){
        sql_error($stmt);
    }

    // データ取得（1レコード）
    $val = $stmt->fetch();
    if(!$val){
        // Userデータなし→新規作成
        $stmt = $pdo->prepare('INSERT INTO user(user_id, user_name, user_email, google_sub, isActive, createdDate, modifiedDate)
        VALUES(NULL, :reg_name, :reg_email, :google_sub, 1, now(), now())');
        $stmt->bindValue(':reg_name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':reg_email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':google_sub', $google_sub, PDO::PARAM_STR);
    
        $status = $stmt->execute();

        // セッションに格納
        $_SESSION["username"] = $name;
        $_SESSION["useremail"] = $email;

        header('Location: main_un.php');

    }elseif(!$val["google_sub"]){
        // Userデータはあり、Googleログインのキーを保持していないのでUpdate
        $stmt = $pdo->prepare('UPDATE user SET google_sub = :google_sub WHERE user_email = :email');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':google_sub', $google_sub, PDO::PARAM_STR);

        $status = $stmt->execute();

        // セッションに格納
        $_SESSION["userid"] = $val["user_id"];
        $_SESSION["username"] = $val["user_name"];
        $_SESSION["useremail"] = $val["user_email"];

        header('Location: main_un.php');

    }else{
        // Googleログインキー（Sub）がある
        // セッションに格納
        $_SESSION["userid"] = $val["user_id"];
        $_SESSION["username"] = $val["user_name"];
        $_SESSION["useremail"] = $val["user_email"];

        header('Location: main_un.php');
    }

}else{
    //ログイン失敗時
    $_SESSION["error_msg"] = "ログインエラー";
    header('Location: index.php');
}

exit();