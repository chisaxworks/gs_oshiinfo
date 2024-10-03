<?php
// タイムゾーン設定（今日・明日の日付取得用）
date_default_timezone_set('Asia/Tokyo');
/*-------------------
    今日の情報 tdy
-------------------*/

$allCount_tdy = 0; //初期設定

/*----- 番組 -----*/
$stmt1 = $pdo->prepare("SELECT program_date, program_title, DATE_FORMAT(program_startTime, '%H:%i') as program_startTime, broadcast.program_id as program_id, pmg_id
                        FROM broadcast
                        JOIN program_mg_tbl
                        ON broadcast.program_id = program_mg_tbl.program_id
                        WHERE program_date = (CURDATE()) AND undermg_flg = 1 AND user_id = $userid");
$status1 = $stmt1->execute();

// データ表示
$np_list_tdy="";
if ($status1==false) {
    sql_error($stmt1);

}else{
    $npCount_tdy = 0; //結果のカウント用変数

    // whileで1件ずつ取得
    while($np_result = $stmt1->fetch(PDO::FETCH_ASSOC)){
        $npCount_tdy++;
        $np_list_tdy .= '<li><a class="notify_link" href="main_mg.php?pmgid=' . h($np_result['pmg_id']) . '&pid=' . h($np_result['program_id']) . '">'.$np_result['program_title'].'（'.$np_result['program_startTime'].'放送開始）</a></li>';
    }

    if($npCount_tdy != 0){
        $allCount_tdy += 1;
        $np_head_tdy = '<div class="notify_item">
                    <div class="notify_item_sub">
                        <img src="img/tv.png" alt="番組">
                        <span>管理対象の「番組が放送」されます</span>
                    </div><ul>';
        $np_foot_tdy = '</ul></div>';
    }
}

/*----- 公演開催 -----*/
$stmt2 = $pdo->prepare("SELECT event_date, event_name, DATE_FORMAT(event_startTime, '%H:%i') as event_startTime, event.event_ids as event_ids, emg_id
                        FROM event
                        JOIN event_mg_tbl
                        ON event.event_ids = event_mg_tbl.event_ids
                        WHERE event_date = (CURDATE()) AND undermg_flg = 1 AND user_id = $userid");
$status2 = $stmt2->execute();

// データ表示
$ne_list_tdy="";
if ($status2==false) {
    sql_error($stmt2);

}else{
    $neCount_tdy = 0; //結果のカウント用変数

    // whileで1件ずつ取得
    while($ne_result = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $neCount_tdy++;
        $ne_list_tdy .= '<li><a class="notify_link" href="main_mg.php?emgid=' . h($ne_result['emg_id']) . '&evid=' . h($ne_result['event_ids']) . '">'.$ne_result['event_name'].'（'.$ne_result['event_startTime'].'開演）</a></li>';
    }

    if($neCount_tdy != 0){
        $allCount_tdy += 1;
        $ne_head_tdy = '<div class="notify_item">
                    <div class="notify_item_sub">
                        <img src="img/live.png" alt="ライブ">
                        <span>管理対象の「公演が開催」されます</span>
                    </div><ul>';
        $ne_foot_tdy = '</ul></div>';
    }
}

/*----- チケット販売開始 -----*/
$stmt3 = $pdo->prepare("SELECT ti_startDate, DATE_FORMAT(ti_startTime, '%H:%i') as ti_startTime, sm_name, event_name, ticket_info.event_ids as event_ids, emg_id
                        FROM ticket_info
                        JOIN event ON ticket_info.event_ids = event.event_ids
                        JOIN event_mg_tbl ON ticket_info.event_ids = event_mg_tbl.event_ids
                        JOIN sales_methods ON ticket_info.sm_id = sales_methods.sm_id
                        WHERE ti_startDate = (CURDATE()) AND undermg_flg = 1 AND user_id = $userid");
$status3 = $stmt3->execute();

// データ表示
$nts_list_tdy="";
if ($status3==false) {
    sql_error($stmt3);

}else{
    $ntsCount_tdy = 0; //結果のカウント用変数

    // whileで1件ずつ取得
    while($nts_result = $stmt3->fetch(PDO::FETCH_ASSOC)){
        $ntsCount_tdy++;
        $nts_list_tdy .= '<li><a class="notify_link" href="main_mg.php?emgid=' . h($nts_result['emg_id']) . '&evid=' . h($nts_result['event_ids']) . '">'.$nts_result['event_name'].'（'.$nts_result['sm_name'].' '.$nts_result['ti_startTime'].'開始）</a></li>';
    }

    if($ntsCount_tdy != 0){
        $allCount_tdy += 1;
        $nts_head_tdy = '<div class="notify_item">
                    <div class="notify_item_sub">
                        <img src="img/ticket1.png" alt="チケット販売開始">
                        <span>管理対象公演のチケットが「販売開始」されます</span>
                    </div><ul>';
        $nts_foot_tdy = '</ul></div>';
    }
}

/*----- チケット販売終了（先行のみ） -----*/
$stmt4 = $pdo->prepare("SELECT ti_endDate, DATE_FORMAT(ti_endTime, '%H:%i') as ti_endTime, sm_name, event_name, ticket_info.event_ids as event_ids, emg_id
                        FROM ticket_info
                        JOIN event ON ticket_info.event_ids = event.event_ids
                        JOIN event_mg_tbl ON ticket_info.event_ids = event_mg_tbl.event_ids
                        JOIN sales_methods ON ticket_info.sm_id = sales_methods.sm_id
                        WHERE ti_endDate = (CURDATE()) AND undermg_flg = 1 AND user_id = $userid AND ticket_info.sm_id = 2");
$status4 = $stmt4->execute();

// データ表示
$nte_list_tdy="";
if ($status4==false) {
    sql_error($stmt4);

}else{
    $nteCount_tdy = 0; //結果のカウント用変数

    // whileで1件ずつ取得
    while($nte_result = $stmt4->fetch(PDO::FETCH_ASSOC)){
        $nteCount_tdy++;
        $nte_list_tdy .= '<li><a class="notify_link" href="main_mg.php?emgid=' . h($nte_result['emg_id']) . '&evid=' . h($nte_result['event_ids']) . '">'.$nte_result['event_name'].'（'.$nte_result['ti_endTime'].'終了）</a></li>';
    }

    if($nteCount_tdy != 0){
        $allCount_tdy += 1;
        $nte_head_tdy = '<div class="notify_item">
                    <div class="notify_item_sub">
                        <img src="img/ticket2.png" alt="チケット販売終了">
                        <span>管理対象公演のチケットの「先行販売が終了」します</span>
                    </div><ul>';
        $nte_foot_tdy = '</ul></div>';
    }
}

/*----- 全体 -----*/
if($allCount_tdy == 0){
    $notify_none_tdy = 'はありません';
}

$today = date("m/d");

/*-------------------
    明日の情報 tmr
-------------------*/

$allCount_tmr = 0; //初期設定

/*----- 番組 -----*/
$stmt1 = $pdo->prepare("SELECT program_date, program_title, DATE_FORMAT(program_startTime, '%H:%i') as program_startTime, broadcast.program_id as program_id, pmg_id
                        FROM broadcast
                        JOIN program_mg_tbl
                        ON broadcast.program_id = program_mg_tbl.program_id
                        WHERE program_date = (CURDATE() + INTERVAL 1 DAY) AND undermg_flg = 1 AND user_id = $userid");
$status1 = $stmt1->execute();

// データ表示
$np_list_tmr="";
if ($status1==false) {
    sql_error($stmt1);

}else{
    $npCount_tmr = 0; //結果のカウント用変数

    // whileで1件ずつ取得
    while($np_result = $stmt1->fetch(PDO::FETCH_ASSOC)){
        $npCount_tmr++;
        $np_list_tmr .= '<li><a class="notify_link" href="main_mg.php?pmgid=' . h($np_result['pmg_id']) . '&pid=' . h($np_result['program_id']) . '">'.$np_result['program_title'].'（'.$np_result['program_startTime'].'放送開始）</a></li>';
    }

    if($npCount_tmr != 0){
        $allCount_tmr += 1;
        $np_head_tmr = '<div class="notify_item">
                    <div class="notify_item_sub">
                        <img src="img/tv.png" alt="番組">
                        <span>管理対象の「番組が放送」されます</span>
                    </div><ul>';
        $np_foot_tmr = '</ul></div>';
    }
}

/*----- 公演開催 -----*/
$stmt2 = $pdo->prepare("SELECT event_date, event_name, DATE_FORMAT(event_startTime, '%H:%i') as event_startTime, event.event_ids as event_ids, emg_id
                        FROM event
                        JOIN event_mg_tbl
                        ON event.event_ids = event_mg_tbl.event_ids
                        WHERE event_date = (CURDATE() + INTERVAL 1 DAY) AND undermg_flg = 1 AND user_id = $userid");
$status2 = $stmt2->execute();

// データ表示
$ne_list_tmr="";
if ($status2==false) {
    sql_error($stmt2);

}else{
    $neCount_tmr = 0; //結果のカウント用変数

    // whileで1件ずつ取得
    while($ne_result = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $neCount_tmr++;
        $ne_list_tmr .= '<li><a class="notify_link" href="main_mg.php?emgid=' . h($ne_result['emg_id']) . '&evid=' . h($ne_result['event_ids']) . '">'.$ne_result['event_name'].'（'.$ne_result['event_startTime'].'開演）</a></li>';
    }

    if($neCount_tmr != 0){
        $allCount_tmr += 1;
        $ne_head_tmr = '<div class="notify_item">
                    <div class="notify_item_sub">
                        <img src="img/live.png" alt="ライブ">
                        <span>管理対象の「公演が開催」されます</span>
                    </div><ul>';
        $ne_foot_tmr = '</ul></div>';
    }
}

/*----- チケット販売開始 -----*/
$stmt3 = $pdo->prepare("SELECT ti_startDate, DATE_FORMAT(ti_startTime, '%H:%i') as ti_startTime, sm_name, event_name, ticket_info.event_ids as event_ids, emg_id
                        FROM ticket_info
                        JOIN event ON ticket_info.event_ids = event.event_ids
                        JOIN event_mg_tbl ON ticket_info.event_ids = event_mg_tbl.event_ids
                        JOIN sales_methods ON ticket_info.sm_id = sales_methods.sm_id
                        WHERE ti_startDate = (CURDATE() + INTERVAL 1 DAY) AND undermg_flg = 1 AND user_id = $userid");
$status3 = $stmt3->execute();

// データ表示
$nts_list_tmr="";
if ($status3==false) {
    sql_error($stmt3);

}else{
    $ntsCount_tmr = 0; //結果のカウント用変数

    // whileで1件ずつ取得
    while($nts_result = $stmt3->fetch(PDO::FETCH_ASSOC)){
        $ntsCount_tmr++;
        $nts_list_tmr .= '<li><a class="notify_link" href="main_mg.php?emgid=' . h($nts_result['emg_id']) . '&evid=' . h($nts_result['event_ids']) . '">'.$nts_result['event_name'].'（'.$nts_result['sm_name'].' '.$nts_result['ti_startTime'].'開始）</a></li>';
    }

    if($ntsCount_tmr != 0){
        $allCount_tmr += 1;
        $nts_head_tmr = '<div class="notify_item">
                    <div class="notify_item_sub">
                        <img src="img/ticket1.png" alt="チケット販売開始">
                        <span>管理対象公演のチケットが「販売開始」されます</span>
                    </div><ul>';
        $nts_foot_tmr = '</ul></div>';
    }
}

/*----- チケット販売終了（先行のみ） -----*/
$stmt4 = $pdo->prepare("SELECT ti_endDate, DATE_FORMAT(ti_endTime, '%H:%i') as ti_endTime, sm_name, event_name, ticket_info.event_ids as event_ids, emg_id
                        FROM ticket_info
                        JOIN event ON ticket_info.event_ids = event.event_ids
                        JOIN event_mg_tbl ON ticket_info.event_ids = event_mg_tbl.event_ids
                        JOIN sales_methods ON ticket_info.sm_id = sales_methods.sm_id
                        WHERE ti_endDate = (CURDATE() + INTERVAL 1 DAY) AND undermg_flg = 1 AND user_id = $userid AND ticket_info.sm_id = 2");
$status4 = $stmt4->execute();

// データ表示
$nte_list_tmr="";
if ($status4==false) {
    sql_error($stmt4);

}else{
    $nteCount_tmr = 0; //結果のカウント用変数

    // whileで1件ずつ取得
    while($nte_result = $stmt4->fetch(PDO::FETCH_ASSOC)){
        $nteCount_tmr++;
        $nte_list_tmr .= '<li><a class="notify_link" href="main_mg.php?emgid=' . h($nte_result['emg_id']) . '&evid=' . h($nte_result['event_ids']) . '">'.$nte_result['event_name'].'（'.$nte_result['ti_endTime'].'終了）</a></li>';
    }

    if($nteCount_tmr != 0){
        $allCount_tmr += 1;
        $nte_head_tmr = '<div class="notify_item">
                    <div class="notify_item_sub">
                        <img src="img/ticket2.png" alt="チケット販売終了">
                        <span>管理対象公演のチケットの「先行販売が終了」します</span>
                    </div><ul>';
        $nte_foot_tmr = '</ul></div>';
    }
}

/*----- 全体 -----*/
if($allCount_tmr == 0){
    $notify_none_tmr = 'はありません';
}

$tommorow = date("m/d", strtotime("+1 day"));

/*-------------------
    通知有無チェック
-------------------*/

$allCount = $allCount_tdy + $allCount_tmr;
if($allCount > 0){
    $bell = '<img src="img/bell_red.png" alt="お知らせ有り">';
}else{
    $bell = '<img src="img/bell.png" alt="お知らせ無し">';
}

?>