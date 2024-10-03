<?php
/*----- ログイン関連部分 -----*/
// SESSIONスタート
session_start();

// 関数ファイル呼び出し
require_once('funcs.php');

// LOGINチェック
sscheck();

// DB接続
$pdo = db_conn();

/*----- 推し一覧 -----*/
// SESSIONからuserid取得
$userid = $_SESSION["userid"];

// SQLでデータ取得
$stmt = $pdo->prepare("SELECT fav_id, fav_name FROM favorite WHERE user_id = $userid");
$status = $stmt->execute();

// データ表示
if ($status==false) {
    sql_error($stmt);

}else{
    $favCount = 0; // 結果のカウント用変数

    // whileで1件ずつ取得
    while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
        $favCount++;

        $favli .= '<li>' . h($result['fav_name']) ;
        $favli .= '<a class="delete_btn" href="delete_fav.php?id=' . h($result['fav_id']) . '"> 削除 </a>';
        $favli .= '</li>';
    }

    // 結果が0件だった場合のメッセージ表示
    if ($favCount == 0) {
        $favli = "<p>推しの登録がありません</p>";
    }
}
?>

<?php include("head.php");?>
<body>
    <header>
        <h1 class="logo">Oshi Info</h1>
        <?php include("head_icon.php");?>
    </header>
    <main>
        <div class="inner">
            <div class="manage_fav_wrap">
                <div class="add_fav">
                    <h2>推し登録</h2>
                    <form action="add_fav.php" method="post" class="input_form">
                        <div class="input_item">
                            <label for="fav_name">名前（個人・グループ）</label>
                            <input type="text" name="fav_name" id="fav_name" class="inputarea">
                        </div>
                        <div class="input_item">
                            <label for="fav_type">職種（メインのもの）</label>
                            <select name="fav_type" id="fav_type" class="inputarea">
                                <option value=""></option>
                                <option value="芸人" selected>芸人</option>
                                <option value="タレント">タレント</option>
                                <option value="アイドル">アイドル</option>
                                <option value="ミュージシャン">ミュージシャン</option>
                                <option value="俳優">俳優</option>
                                <option value="声優">声優</option>
                                <option value="その他">その他</option>
                            </select>
                        </div>
                        <div>
                            <input type="submit" value="登録" id="submit_add_fav" class="act_btn">
                        </div>
                    </form>
                </div>
                <div class="favlist_area">
                    <h2>推し一覧</h2>
                    <ul class="favlist">
                        <?= $favli ?>
                    </ul>
                    <a class="sub_btn" href="main_un.php">戻る</a>
                </div>
            </div>
<?php include("foot.html");?>