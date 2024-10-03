<?php include("notification.php");?>
<div class="icon_wrap">
    <button class="notify">
        <?= $bell ?>
    </button>
    <button class="setting">
        <img src="img/setting.png" alt="設定">
    </button>
</div>
<div class="notify_wrap">
    <div class="notify_subwrap">
        <h3>今日 <?= $today ?> の お知らせ<img class="notify_img" src="img/info.png" alt=""><?= $notify_none_tdy ?></h3>
            <!-- 番組 -->
            <?= $np_head_tdy ?>
            <?= $np_list_tdy ?>
            <?= $np_foot_tdy ?>
            <!-- 公演開催 -->
            <?= $ne_head_tdy ?>
            <?= $ne_list_tdy ?>
            <?= $ne_foot_tdy ?>
            <!-- チケ販売開始 -->
            <?= $nts_head_tdy ?>
            <?= $nts_list_tdy ?>
            <?= $nts_foot_tdy ?>
            <!-- チケ先行終了 -->
            <?= $nte_head_tdy ?>
            <?= $nte_list_tdy ?>
            <?= $nte_foot_tdy ?>
    </div>
    <div class="notify_subwrap">
        <h3>明日 <?= $tommorow ?> の お知らせ<img class="notify_img" src="img/info.png" alt=""><?= $notify_none_tmr ?></h3>
            <!-- 番組 -->
            <?= $np_head_tmr ?>
            <?= $np_list_tmr ?>
            <?= $np_foot_tmr ?>
            <!-- 公演開催 -->
            <?= $ne_head_tmr ?>
            <?= $ne_list_tmr ?>
            <?= $ne_foot_tmr ?>
            <!-- チケ販売開始 -->
            <?= $nts_head_tmr ?>
            <?= $nts_list_tmr ?>
            <?= $nts_foot_tmr ?>
            <!-- チケ先行終了 -->
            <?= $nte_head_tmr ?>
            <?= $nte_list_tmr ?>
            <?= $nte_foot_tmr ?>
    </div>
</div>
<div class="setting_wrap">
    <div class="setting_subwrap">
        <p class="username"><?= $_SESSION["username"] ?>さん</p>
        <a href="howtouse.php" target="_blank">使い方</a>
        <a href="logout.php" class="logout_btn">ログアウト</a>
    </div>
</div>