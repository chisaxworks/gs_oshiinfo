<?php
// SESSIONスタート
session_start();
// 関数ファイル呼び出し
require_once('funcs.php');
// LOGINチェック
sscheck();
?>
<!-- managed処理部分 -->
<?php include("managed.php");?>

<!-- HTML部分 -->
<?php include("head.php");?>
<body>
    <header>
        <h1 class="logo">Oshi Info</h1>
        <div class="tab_wrap">
            <a href="main_un.php" class="tab_item">未整理情報</a>
            <a href="main_mg.php" class="tab_item active_tab">管理情報</a>
        </div>
        <?php include("head_icon.php");?>
    </header>
    <main>
        <div class="inner">
            <!-- main_area -->
            <div class="main_area">
                <!-- 推し情報 -->
                <h3 class="sp_title sp_filter">推し一覧</h3>
                <div class="filter_area">
                    <ul class="filter_favlist">
                        <?= $favall ?>
                        <?= $favli ?>
                    </ul>
                    <div class="mg_btn_wrap">
                        <a href="favorite.php" class="mgsub_btn">推し管理画面</a>
                    </div>
                </div>
                <!-- 一覧 -->
                <h3 class="sp_title">情報一覧</h3>
                <div class="data_area">
                    <?= $item ?>
                </div>
                <!-- 詳細（eventかprogramかどちらかだけ表示） -->
                <!-- event -->
                <div class="event_detail">
                    <?= $ed_close ?>
                    <h2>公演詳細</h2>
                    <div class="detail_item_wrap">
                        <p class="item_type <?= h($e_result['event_type']) ?>"><?= h($e_result['event_type_ja']) ?></p>
                        <div class="detail_item">
                            <h3>公演名</h3>
                            <p><?= h($e_result['event_name']) ?></p>
                        </div>
                        <div class="detail_item">
                            <h3>会場</h3>
                            <p><?= h($e_result['venue']) ?></p>
                        </div>
                        <div class="detail_item">
                            <h3>公演日</h3>
                            <p><?= h($e_result['event_date']).' '.$dow_ja[$e_result['dow']] ?></p>
                        </div>
                        <div class="detail_item">
                            <h3>開演時間 (開場時間)</h3>
                            <p><?= h($e_result['event_startTime']).' ('.h($e_result['event_openTime']).')' ?><x/p>
                        </div>
                        <div class="detail_item">
                            <h3>出演者</h3>
                            <p><?= h($e_result['performers']) ?></p>
                        </div>
                    </div>
                    <?= $google_calendar?>
                    <h2>チケット情報</h2>
                    <div class="ticketinfo_wrap">
                        <?= $ticket_info ?>
                    </div>
                    <!-- 管理のみエリア -->
                    <div class="mg_btn_wrap_detail">
                        <?= $mgwd ?>
                    </div>
                </div>
                <!-- program -->
                <div class="program_detail">
                    <?= $ed_close ?>
                    <h2>番組詳細</h2>
                    <div class="detail_item_wrap">
                        <p class="item_type <?= h($p_result['broadcast_type']) ?>"><?= h($p_result['broadcast_type_ja']) ?></p>
                        <div class="detail_item">
                            <h3>放送局</h3>
                            <p><?= h($p_result['broadcast_station']) ?></p>
                        </div>
                        <div class="detail_item">
                            <h3>番組名</h3>
                            <p><?= h($p_result['program_title']) ?></p>
                        </div>
                        <div class="detail_item">
                            <h3>放送日時</h3>
                            <p><?= h($p_result['program_date']).' '.$dow_ja[$p_result['dow']].' '.h($p_result['program_startTime']) ?></p>
                        </div>
                        <div class="detail_item">
                            <h3>出演者</h3>
                            <p><?= h($p_result['performers']) ?></p>
                        </div>
                        <div class="detail_item">
                            <a class="program_url" href="<?= h($p_result['program_url']) ?>" target="_blank">番組表ページ</a>
                        </div>
                    </div>
                    <?= $google_calendar?>
                    <!-- 管理のみエリア -->
                    <div class="mg_btn_wrap_detail">
                        <?= $mgwd ?>
                    </div>
                </div>
            </div>
<?php include("foot.html");?>