<?php

// SESSIONスタート
session_start();
// 関数ファイル呼び出し
require_once('funcs.php');
// LOGINチェック
sscheck();

// GETデータ取得
$fav_id = $_GET['id'];

// DB接続
$pdo = db_conn();

//データ削除SQL作成
$stmt = $pdo->prepare(
    'DELETE FROM favorite WHERE fav_id = :fav_id;'
);

$stmt->bindValue(':fav_id', $fav_id, PDO::PARAM_INT);
$status = $stmt->execute(); //実行

//データ削除処理後
if ($status === false) {
    sql_error($stmt);
}

// 元の画面にリダイレクト
header('Location: favorite.php');


?>