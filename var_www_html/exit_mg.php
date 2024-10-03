<?php

// SESSIONスタート
session_start();
// 関数ファイル呼び出し
require_once('funcs.php');
// LOGINチェック
sscheck();

// GETデータ取得
$emg_id = $_GET['emgid'];
$pmg_id = $_GET['pmgid'];

// DB接続
$pdo = db_conn();

//データ更新SQL作成（undermg_flg = 2は完了）
if($emg_id){
    $stmt = $pdo->prepare(
        'UPDATE event_mg_tbl SET undermg_flg = 2, modifiedDate = now() WHERE emg_id = :emg_id;'
    );
    $stmt->bindValue(':emg_id', $emg_id, PDO::PARAM_INT);
}else if($pmg_id){
    $stmt = $pdo->prepare(
        'UPDATE program_mg_tbl SET undermg_flg = 2, modifiedDate = now() WHERE pmg_id = :pmg_id;'
    );
    $stmt->bindValue(':pmg_id', $pmg_id, PDO::PARAM_INT);
}

$status = $stmt->execute(); //実行

//データ削除処理後
if ($status === false) {
    sql_error($stmt);
}

// 管理画面に戻る
header('Location: main_mg.php');


?>