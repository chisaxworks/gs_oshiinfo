<?php
// SESSIONスタートと関数ファイルは親のmain_mg.phpで呼び出し済

// DB接続
$pdo = db_conn();

// GETデータ取得
$fav_id = $_GET['favid'];
$event_ids = $_GET['evid'];
$program_id = $_GET['pid'];
//(managedのみ)
$emgid= $_GET['emgid'];
$pmgid= $_GET['pmgid'];

// SESSIONからuserid取得
$userid = $_SESSION["userid"];

/*----- 推し一覧（managed用） -----*/
// SQLでデータ取得
$stmt1 = $pdo->prepare("SELECT fav_id, fav_name FROM favorite WHERE user_id = $userid");
$status1 = $stmt1->execute();

// データ表示
if ($status1==false) {
    sql_error($stmt1);

}else{
    $favCount = 0; // 結果のカウント用変数

    // whileで1件ずつ取得
    while( $f_result = $stmt1->fetch(PDO::FETCH_ASSOC)){
        $favCount++;

        // SQLで取得したfav_idとGETで取得したfav_idが一致するかで分岐
        if($f_result['fav_id'] == $fav_id){
            $favli .= '<a class="favli_link active_fav" href="main_mg.php?favid=' . h($f_result['fav_id']) . '"><li>' . h($f_result['fav_name']) . '</li></a>';
        }else{
            $favli .= '<a class="favli_link" href="main_mg.php?favid=' . h($f_result['fav_id']) . '"><li>' . h($f_result['fav_name']) . '</li></a>';
        }
    
    }

    // 結果が0件だった場合のメッセージ表示
    if ($favCount == 0) {
        $favli = '<p class="fav_none">推しの登録がありません</p>';
    }

    // fav_idがない場合（全てが選択されている状態）
    // 1行目は全ての色を青（選択されている状態）にするため、2行目は「.event_detail」の閉じるボタンの遷移先に利用（絞り込み中は絞り込みの表示に戻す）
    if($fav_id == 0){
        $favall = '<a class="favli_link active_fav" href="main_mg.php"><li>全て</li></a>';
        $ed_close = '<a href="main_mg.php" class="ed_close_btn">×</a>';
    }else{
        $favall = '<a class="favli_link" href="main_mg.php"><li>全て</li></a>';
        $ed_close = '<a href="main_mg.php?favid=' . h($fav_id) . '" class="ed_close_btn">×</a>';
    }

}

/*----- 管理一覧 -----*/
$m_val = favlist_keyFavid($pdo, $fav_id);

// 絞り込みキー（先ほど取得した推しの名前を部分検索）
// メモ：unmanagedと同じ記述にしていないのは管理に入っているもの＝そもそも推しのものだけだから
$fav_name = '%'. h($m_val['fav_name']) .'%';

// SQLでデータ取得（公演データ：管理のものだけ、undermg_flg = 1は管理中）
$stmt3 = $pdo->prepare("SELECT 
                            emg_id as mg_id,
                            event.event_ids as id,
                            event_name as name,
                            venue as place,
                            performers,
                            event_type as type,
                            event_type_ja as type_ja,
                            DATE_FORMAT(event_date, '%Y/%m/%d') as date,
                            DATE_FORMAT(event_startTime, '%H:%i') as time,
                            DAYOFWEEK(event_date) as dow,
                            'event' as tablename
                        FROM event
                        JOIN event_mg_tbl ON event.event_ids = event_mg_tbl.event_ids
                        WHERE performers LIKE :fav_name AND undermg_flg = 1 AND user_id = $userid AND event_date >= CURDATE()
                    UNION ALL
                        SELECT
                            pmg_id as mg_id,
                            broadcast.program_id as id,
                            program_title as name,
                            broadcast_station as place,
                            performers,
                            broadcast_type as type,
                            broadcast_type_ja as type_ja,
                            DATE_FORMAT(program_date, '%Y/%m/%d') as date,
                            DATE_FORMAT(program_startTime, '%H:%i') as time,
                            DAYOFWEEK(program_date) as dow,
                            'broadcast' as tablename
                        FROM broadcast
                        JOIN program_mg_tbl ON broadcast.program_id = program_mg_tbl.program_id
                        WHERE performers LIKE :fav_name AND undermg_flg = 1 AND user_id = $userid AND program_date >= CURDATE()
                    ORDER BY date, time
                    ");

$stmt3->bindValue(':fav_name', $fav_name, PDO::PARAM_STR);
$status3 = $stmt3->execute();

// データ表示
$item="";
if ($status3==false) {
    sql_error($stmt3);

}else{
    $itemCount = 0; // 結果のカウント用変数

    // whileで1件ずつ取得
    while( $m_result = $stmt3->fetch(PDO::FETCH_ASSOC)){
        $itemCount++;

        // テレビ出演者が多すぎるケースがあるので200文字以上は詳細にする
        if(mb_strlen(h($m_result['performers'])) > 200){
            $performers_200 = "出演者多数のため「詳細」でご確認ください";
        }else{
            $performers_200 = h($m_result['performers']);
        }

        // event起因か、broadcast起因かで1つ目の分岐（差異は会場・局アイコン、開演の単語の有無、mg_btn_wrapのid部分）
        // 推しで絞り込みしていた場合のために2つ目のif文で分岐（絞り込んでいる場合はfav_idの値を追加）
        // $dow_jaの定義はfuncsにある
        if($m_result['tablename'] == "event"){
            if($event_ids == $m_result['id']){
                $item .= '<div class="item item_selected">';
            }else{
                $item .= '<div class="item">';
            }
            $item .= '<div class="item_sub"><p class="item_type ' . h($m_result['type']) . '">' . h($m_result['type_ja']) . '</p>';
            $item .= '<p class="item_name">' . h($m_result['name']) . '</p>';
            $item .= '<p class="item_data"><img src="img/venue.png" alt="会場">' . h($m_result['place']) . '</p>';
            $item .= '<p class="item_data"><img src="img/date.png" alt="日時">' . h($m_result['date']) .' '. $dow_ja[$m_result['dow']] .' '. h($m_result['time']) . '開演</p>';
            $item .= '<p class="item_data"><img src="img/entertainer.png" alt="出演者">' . h($m_result['performers']) . '</p></div>';
            if($fav_id){
                $item .= '<div class="mg_btn_wrap"><a class="mgact_btn ' . h($m_result['type']) . '" href="main_mg.php?emgid=' . h($m_result['mg_id']) . '&evid=' . h($m_result['id']) . '&favid=' . $fav_id . '">詳細</a>';
            }else{
                $item .= '<div class="mg_btn_wrap"><a class="mgact_btn ' . h($m_result['type']) . '" href="main_mg.php?emgid=' . h($m_result['mg_id']) . '&evid=' . h($m_result['id']) . '">詳細</a>';
            }
            $item .= '<a class="mgact_btn exit_mg" href="exit_mg.php?emgid=' . h($m_result['mg_id']) . '">完了する</a></div></div>';
        }elseif($m_result['tablename'] == "broadcast"){
            if($program_id == $m_result['id']){
                $item .= '<div class="item item_selected">';
            }else{
                $item .= '<div class="item">';
            }
            $item .= '<div class="item_sub"><p class="item_type ' . h($m_result['type']) . '">' . h($m_result['type_ja']) . '</p>';
            $item .= '<p class="item_name">' . h($m_result['name']) . '</p>';
            $item .= '<p class="item_data"><img src="img/venue.png" alt="放送局">' . h($m_result['place']) . '</p>';
            $item .= '<p class="item_data"><img src="img/date.png" alt="日時">' . h($m_result['date']) .' '. $dow_ja[$m_result['dow']] .' '. h($m_result['time']) . '〜</p>';
            $item .= '<p class="item_data"><img src="img/entertainer.png" alt="出演者">' . $performers_200 . '</p></div>';
            if($fav_id){
                $item .= '<div class="mg_btn_wrap"><a class="mgact_btn ' . h($m_result['type']) . '" href="main_mg.php?pmgid=' . h($m_result['mg_id']) . '&pid=' . h($m_result['id']) . '&favid=' . $fav_id . '">詳細</a>';
            }else{
                $item .= '<div class="mg_btn_wrap"><a class="mgact_btn ' . h($m_result['type']) . '" href="main_mg.php?pmgid=' . h($m_result['mg_id']) . '&pid=' . h($m_result['id']) . '">詳細</a>';
            }
            $item .= '<a class="mgact_btn exit_mg" href="exit_mg.php?pmgid=' . h($m_result['mg_id']) . '">完了する</a></div></div>';
        }
    }
    // 結果が0件だった場合のメッセージ表示
    if ($itemCount == 0) {
        $item = '<p class="mg_none">管理している情報がありません</p>';
    }
}

// event_idsがある場合は以下の処理が走る
if($event_ids){

    /*--- 公演詳細 ---*/
    $e_result = event_detail($pdo, $event_ids);
    $google_calendar = event_googlecal($e_result);

    /*--- 販売情報一覧 ---*/
    $ticket_info = ticketinfo($pdo, $event_ids,$dow_ja);
}

// program_idがある場合は以下の処理が走る
if($program_id){

    /*--- 番組詳細 ---*/
    $p_result = program_detail($pdo, $program_id);
    $google_calendar = program_googlecal($p_result);

}

// 詳細画面の完了ボタン（Managedのみ）
$mgwd = "";
if($event_ids){
    $mgwd = '<a class="mgact_btn exit_mg" href="exit_mg.php?emgid=' . h($emgid) . '">完了する</a>';
}
if($program_id){
    $mgwd = '<a class="mgact_btn exit_mg" href="exit_mg.php?pmgid=' . h($pmgid) . '">完了する</a>';
}

?>