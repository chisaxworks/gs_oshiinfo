<?php

// SESSIONスタート
session_start();
// 関数ファイル呼び出し
require_once('funcs.php');
// LOGINチェック
sscheck();

// POSTデータ取得
$fav_name = $_POST["fav_name"];
$fav_type = $_POST["fav_type"];

// SESSIONからuserid取得
$userid = $_SESSION["userid"];

// DB接続
$pdo = db_conn();

// データ登録SQL作成
$stmt = $pdo->prepare('INSERT INTO
    favorite(fav_id, fav_name, fav_type, user_id, createdDate, modifiedDate)
    VALUES(NULL, :fav_name, :fav_type, :user_id, now(), now())');

$stmt->bindValue(':fav_name', $fav_name, PDO::PARAM_STR);
$stmt->bindValue(':fav_type', $fav_type, PDO::PARAM_STR);
$stmt->bindValue(':user_id', $userid, PDO::PARAM_INT);
$status = $stmt->execute();

//データ登録処理後
if($status === false){
  sql_error($stmt);
}

// 元の画面にリダイレクト
header('Location: favorite.php');


?>