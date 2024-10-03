<?php
// SESSIONスタートと関数ファイルは親のmain_un.phpで呼び出し済

// DB接続
$pdo = db_conn();

// GETデータ取得
$fav_id = $_GET['favid'];
$event_ids = $_GET['evid'];
$program_id = $_GET['pid'];

// SESSIONからuserid取得
$userid = $_SESSION["userid"];

/*----- 推し一覧（unmanaged用） -----*/
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
            $favli .= '<a class="favli_link active_fav" href="main_un.php?favid=' . h($f_result['fav_id']) . '"><li>' . h($f_result['fav_name']) . '</li></a>';
        }else{
            $favli .= '<a class="favli_link" href="main_un.php?favid=' . h($f_result['fav_id']) . '"><li>' . h($f_result['fav_name']) . '</li></a>';
        }

        // 新着一覧の表示で使う値を作成（すべての一覧から推しに登録しているものだけが出るようにするための処理）
        if($favCount == 1){
            $forfilter .= "performers LIKE '%". h($f_result['fav_name']) ."%'";
        }else{
            $forfilter .= " OR performers LIKE '%". h($f_result['fav_name']) ."%'";
        }
    
    }

    // 結果が0件だった場合のメッセージ表示
    if($favCount == 0) {
        $favli = '<p class="fav_none">推しの登録がありません</p>';
    }

    // fav_idがない場合（全てが選択されている状態）
    // 1行目は全ての色を青（選択されている状態）にするため、2行目は「.event_detail」の閉じるボタンの遷移先に利用（絞り込み中は絞り込みの表示に戻す）
    if($fav_id == 0){
        $favall = '<a class="favli_link active_fav" href="main_un.php"><li>全て</li></a>';
        $ed_close = '<a href="main_un.php" class="ed_close_btn">×</a>';
    }else{
        $favall = '<a class="favli_link" href="main_un.php"><li>全て</li></a>';
        $ed_close = '<a href="main_un.php?favid=' . h($fav_id) . '" class="ed_close_btn">×</a>';
    }
}

/*----- 新着一覧 -----*/
$n_val = favlist_keyFavid($pdo, $fav_id);

// 絞り込みキー
if($n_val['fav_name']){
    // 推しの名前を部分一致検索する場合はこちら（先ほど取得したfav_nameを使用）
    $filter = "performers LIKE '%" . h($n_val['fav_name']) ."%'";

}else{
    // 部分一致していない時は、登録した推しの名前のものだけ画面に出る（推し一覧を取得する時に格納した値を利用）
    $filter = "(" . $forfilter . ")";
}

// SQLでデータ取得（公演データ：未管理のものだけ/推しの登録がない場合は何も出ない）
$item="";
if($favCount == 0) {
    //推しの登録がない場合
    $item = '<p class="mg_none">表示する情報がありません</p>';

}else{
    //推しの登録がある場合
    $stmt3 = $pdo->prepare("SELECT
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
                            LEFT JOIN(
                                SELECT event_ids FROM event_mg_tbl WHERE user_id = $userid GROUP BY event_ids
                            ) AS temptbl_e ON event.event_ids = temptbl_e.event_ids
                            WHERE temptbl_e.event_ids IS NULL AND $filter AND event_date >= CURDATE()
                        UNION ALL
                            SELECT
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
                            LEFT JOIN(
                                SELECT program_id FROM program_mg_tbl WHERE user_id = $userid GROUP BY program_id
                            ) AS temptbl_p ON broadcast.program_id = temptbl_p.program_id
                            WHERE temptbl_p.program_id IS NULL AND $filter AND program_date >= CURDATE()
                        ORDER BY date, time
                        ");
    $status3 = $stmt3->execute();

    // データ表示
    if ($status3==false) {
        sql_error($stmt3);

    }else{
        $itemCount = 0; // 結果のカウント用変数

        // whileで1件ずつ取得
        while( $n_result = $stmt3->fetch(PDO::FETCH_ASSOC)){
            $itemCount++;

            // テレビ出演者が多すぎるケースがあるので200文字以上は詳細にする
            if(mb_strlen(h($n_result['performers'])) > 200){
                $performers_200 = "出演者多数のため「詳細」でご確認ください";
            }else{
                $performers_200 = h($n_result['performers']);
            }

            // event起因か、broadcast起因かで1つ目の分岐（差異は会場・局アイコン、開演の単語の有無、mg_btn_wrapのid部分）
            // 推しで絞り込みしていた場合のために2つ目のif文で分岐（絞り込んでいる場合はfav_idの値を追加）
            // $dow_jaの定義はfuncsにある
            if($n_result['tablename'] == "event"){
                if($event_ids == $n_result['id']){
                    $item .= '<div class="item item_selected">';
                }else{
                    $item .= '<div class="item">';
                }
                $item .= '<div class="item_sub"><p class="item_type ' . h($n_result['type']) . '">' . h($n_result['type_ja']) . '</p>';
                $item .= '<p class="item_name">' . h($n_result['name']) . '</p>';
                $item .= '<p class="item_data"><img src="img/venue.png" alt="会場">' . h($n_result['place']) . '</p>';
                $item .= '<p class="item_data"><img src="img/date.png" alt="日時">' . h($n_result['date']) .' '. $dow_ja[$n_result['dow']] .' '. h($n_result['time']) . '開演</p>';
                $item .= '<p class="item_data"><img src="img/entertainer.png" alt="出演者">' . h($n_result['performers']) . '</p></div>';
                if($fav_id){
                    $item .= '<div class="mg_btn_wrap"><a class="mgact_btn ' . h($n_result['type']) . '" href="main_un.php?evid=' . h($n_result['id']) . '&favid=' . $fav_id . '">詳細</a>';
                }else{
                    $item .= '<div class="mg_btn_wrap"><a class="mgact_btn ' . h($n_result['type']) . '" href="main_un.php?evid=' . h($n_result['id']) . '">詳細</a>';
                }
    
                $item .= '<a class="mgact_btn start_mg" href="add_mg.php?evid=' . h($n_result['id']) . '">管理する</a>';
                $item .= '<a class="mgsub_btn outof_mg" href="outof_mg.php?evid=' . h($n_result['id']) . '">管理しない</a></div></div>';
            }elseif($n_result['tablename'] == "broadcast"){
                if($program_id == $n_result['id']){
                    $item .= '<div class="item item_selected">';
                }else{
                    $item .= '<div class="item">';
                }
                $item .= '<div class="item_sub"><p class="item_type ' . h($n_result['type']) . '">' . h($n_result['type_ja']) . '</p>';
                $item .= '<p class="item_name">' . h($n_result['name']) . '</p>';
                $item .= '<p class="item_data"><img src="img/venue.png" alt="放送局">' . h($n_result['place']) . '</p>';
                $item .= '<p class="item_data"><img src="img/date.png" alt="日時">' . h($n_result['date']) .' '. $dow_ja[$n_result['dow']] .' '. h($n_result['time']) . '〜</p>';
                $item .= '<p class="item_data"><img src="img/entertainer.png" alt="出演者">' . $performers_200 . '</p></div>';
                if($fav_id){
                    $item .= '<div class="mg_btn_wrap"><a class="mgact_btn ' . h($n_result['type']) . '" href="main_un.php?pid=' . h($n_result['id']) . '&favid=' . $fav_id . '">詳細</a>';
                }else{
                    $item .= '<div class="mg_btn_wrap"><a class="mgact_btn ' . h($n_result['type']) . '" href="main_un.php?pid=' . h($n_result['id']) . '">詳細</a>';
                }
    
                $item .= '<a class="mgact_btn start_mg" href="add_mg.php?pid=' . h($n_result['id']) . '">管理する</a>';
                $item .= '<a class="mgsub_btn outof_mg" href="outof_mg.php?pid=' . h($n_result['id']) . '">管理しない</a></div></div>';
            }

        }
        // 結果が0件だった場合のメッセージ表示
        if ($itemCount == 0) {
            $item = '<p class="mg_none">表示する情報がありません</p>';
        }
    }
}

// event_idsがある場合は以下の処理が走る
if($event_ids){

    /*--- 公演詳細 ---*/
    $e_result = event_detail($pdo, $event_ids);
    $google_calendar = event_googlecal($e_result);

    /*--- 販売情報一覧 ---*/
    $ticket_info = ticketinfo($pdo, $event_ids, $dow_ja);
}

// program_idがある場合は以下の処理が走る
if($program_id){

    /*--- 番組詳細 ---*/
    $p_result = program_detail($pdo, $program_id);
    $google_calendar = program_googlecal($p_result);

}

// 詳細画面の管理ボタン（Unmanagedのみ）
$mgwd = "";
if($e_result['event_ids']){
    $mgwd .= '<a class="mgact_btn start_mg" href="add_mg.php?evid=' . h($e_result['event_ids']) . '">管理する</a>';
    $mgwd .= '<a class="mgsub_btn outof_mg" href="outof_mg.php?evid=' . h($e_result['event_ids']) . '">管理しない</a>';
}
if($p_result['program_id']){
    $mgwd .= '<a class="mgact_btn start_mg" href="add_mg.php?pid=' . h($p_result['program_id']) . '">管理する</a>';
    $mgwd .= '<a class="mgsub_btn outof_mg" href="outof_mg.php?pid=' . h($p_result['program_id']) . '">管理しない</a>';
}

?>