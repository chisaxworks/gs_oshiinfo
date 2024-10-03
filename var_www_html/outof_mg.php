<?php

// SESSIONスタート
session_start();
// 関数ファイル呼び出し
require_once('funcs.php');
// LOGINチェック
sscheck();

// GETデータ取得
$event_ids = $_GET['evid'];
$program_id = $_GET['pid'];

// SESSIONからuserid取得
$userid = $_SESSION["userid"];

// DB接続
$pdo = db_conn();

// データ登録SQL作成（管理外として登録、undermg_flg = 0は管理外）
if($event_ids){
  $stmt = $pdo->prepare('INSERT INTO
        event_mg_tbl(emg_id, event_ids, user_id, undermg_flg, createdDate, modifiedDate)
        VALUES(NULL, :event_ids, :user_id, 0, now(), now())');

  $stmt->bindValue(':event_ids', $event_ids, PDO::PARAM_STR);
  $stmt->bindValue(':user_id', $userid, PDO::PARAM_INT);

}else if($program_id){
  $stmt = $pdo->prepare('INSERT INTO
        program_mg_tbl(pmg_id, program_id, user_id, undermg_flg, createdDate, modifiedDate)
        VALUES(NULL, :program_id, :user_id, 0, now(), now())');

  $stmt->bindValue(':program_id', $program_id, PDO::PARAM_STR);
  $stmt->bindValue(':user_id', $userid, PDO::PARAM_INT);
}

$status = $stmt->execute();

//データ登録処理後
if($status === false){
  sql_error($stmt);
}

// 未整理画面に戻る
header('Location: main_un.php');

?>