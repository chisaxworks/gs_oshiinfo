<!-- 退会処理＆完了表示PHP -->
<?php

// SESSIONスタート
session_start();
// 関数ファイル呼び出し
require_once('funcs.php');
//LOGINチェック
sscheck();

// GETデータ取得
$userid = $_GET['uid'];

// DB接続します
$pdo = db_conn();

// SQLでデータ取得（削除しました表示で使用するため）
$stmt1 = $pdo->prepare('SELECT * FROM user WHERE user_id = :userid');
$stmt1->bindValue(':userid', $userid, PDO::PARAM_INT);
$status1 = $stmt1->execute();

// データ取得処後
if ($status1 === false) {
    sql_error($stmt1);
} else {
    $result = $stmt1->fetch();
}

// データ更新SQL
$stmt2 = $pdo->prepare(
    'UPDATE user SET isActive = 0, modifiedDate = now() WHERE user_id = :userid;'
);
$stmt2->bindValue(':userid', $userid, PDO::PARAM_INT);
$status2 = $stmt2->execute(); //実行

//データ削除処理後
if ($status2 === false) {
    sql_error($stmt2);
}

// ログアウト処理
sslogout();

?>

<?php include("head.php");?>
<body>
    <header>
        <h1 class="logo">Oshi Info</h1>
    </header>
    <main>
        <div class="inner">
            <p class="delete_user"><?= h($result['user_name'])?>さんのアカウントが削除されました。ご利用ありがとうございました。</p>
            <a href="index.php" class="sub_btn">ログイン画面へ</a>
        </div>
<?php include("foot.html");?>