<!-- ログイン処理PHP（リダイレクト） -->
<!-- メールログイン -->
<?php

// SESSIONスタート
session_start();

// POSTデータ取得
$email = $_POST["email"];
$pw = $_POST["password"];

// 関数ファイル呼び出し
require_once('funcs.php');
//PHPからDB接続
$pdo = db_conn();

// データ取得SQL（ユーザテーブル）
$stmt = $pdo->prepare('SELECT * FROM user WHERE user_email = :email AND isActive = 1');
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$status = $stmt->execute();

// エラーハンドリング
if($status === false){
    sql_error($stmt);
}

// データ取得（1レコード）
$val = $stmt->fetch();
var_dump($val);

if(!$val){
    // userデータなし
    $_SESSION["error_msg"] = "ユーザ情報がありません";
    header('Location: login.php');

}elseif($val['google_sub'] && is_null($val['password'])){
    $_SESSION["error_msg"] = "Googleアカウントでログイン可能です";
    header('Location: login.php');

}else{
    //パスワード比較
    $pwcheck = password_verify($pw, $val['password']);
    if($pwcheck){ 
        //ログイン成功時
        $_SESSION["chk_ssid"] = session_id();
        $_SESSION["userid"] = $val["user_id"];
        $_SESSION["username"] = $val["user_name"];
        $_SESSION["useremail"] = $val["user_email"];

        header('Location: main_un.php');
    }else{
        //ログイン失敗時
        $_SESSION["error_msg"] = "パスワードが間違っています";
        header('Location: login.php');
    }
}

exit();