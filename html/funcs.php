<?php
// 関数化したものを格納

/* --- 共通的な関数 --- */
// SESSION確認
function sscheck(){
    if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]!=session_id()){

        $_SESSION["error_msg"] = "ログインしてください";
        header('Location: index.php');
        exit(); //処理を止める ここから下は処理されない

    }else{
        session_regenerate_id(true);
        $_SESSION["chk_ssid"] = session_id();
      
    }
}

// ログアウト用SESSION一括破棄関数
function sslogout() {
    // SESSIONを初期化（空っぽにする）
    $_SESSION = array();

    // Cookieに保存してある"SessionIDの保存期間を過去にして破棄
    if (isset($_COOKIE[session_name()])) { //session_name()は、セッションID名を返す関数
        setcookie(session_name(), '', time()-42000, '/');
    }

    // サーバ側での、セッションIDの破棄
    session_destroy();
}

// DB接続関数
function db_conn(){
    try {
        $pdo = new PDO('mysql:dbname=scrapy;charset=utf8;host=localhost','root','');
        return $pdo;
    
    } catch (PDOException $e) {
        exit('DBConnectError:'.$e->getMessage());
    
    }
}

// XSS対策（echoする場所で使用）
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// SQLエラー関数
function sql_error($stmt){
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));

}

/* --- 共通の変数（曜日） --- */
$dow_ja = [
    1 => '(日)',
    2 => '(月)',
    3 => '(火)',
    4 => '(水)',
    5 => '(木)',
    6 => '(金)',
    7 => '(土)'
]; // 曜日を日本語変換

/*----- 新着一覧用（推しの名前取得）-----*/
// SQLでデータ取得（推しのidから推しの名前を取得→後半で文字列部分一致で絞り込むために必要）
function favlist_keyFavid($pdo, $fav_id){
    $stmt2 = $pdo->prepare("SELECT fav_name FROM favorite WHERE fav_id = :fav_id");
    $stmt2->bindValue(':fav_id', $fav_id, PDO::PARAM_INT);
    $status2 = $stmt2->execute();
    
    // エラーハンドリング
    if($status2 === false){
        sql_error($stmt2);
    } else {
        $result = $stmt2->fetch();
    }

    return $result;
}

/* --- 番組詳細エリア関数 --- */
// 番組詳細情報
function program_detail($pdo, $program_id){
    // SQLでデータ取得（番組データ）
    $stmt4 = $pdo->prepare("SELECT program_id, program_title, program_url, performers, broadcast_station, broadcast_type, broadcast_type_ja,
                            DATE_FORMAT(program_date, '%Y/%m/%d') as program_date, DAYOFWEEK(program_date) as dow,
                            DATE_FORMAT(program_startTime, '%H:%i') as program_startTime,
                            DATE_FORMAT(program_date, '%Y%m%d') as forgoogle_date,
                            DATE_FORMAT(program_startTime, '%H%i%s') as forgoogle_startTime,
                            DATE_FORMAT(program_endTime, '%H%i%s') as forgoogle_endTime
                            FROM broadcast
                            WHERE program_id = :program_id");
    $stmt4->bindValue(':program_id', $program_id, PDO::PARAM_STR);
    $status4 = $stmt4->execute();

    // データ表示
    if ($status4==false) {
        sql_error($stmt4);
    }else{
        $p_result = $stmt4->fetch();
    }

    return $p_result;
}

// 番組詳細用Googleカレンダー
function program_googlecal($result){
    // 終了時間は取得できない局用に仮で1時間にセット
    $forgoogle_endTime =  $result['forgoogle_startTime'] + 10000;

    // リンクの設定
    $google_calendar = '';
    $google_calendar .= '<a target="_blank" href="https://www.google.com/calendar/render?action=TEMPLATE';
    $google_calendar .= '&text=' . h($result['program_title']);
    $google_calendar .= '&dates=' . h($result['forgoogle_date'])  . 'T' . h($result['forgoogle_startTime']);
    if($result['forgoogle_endTime']){
        $google_calendar .= '/' . h($result['forgoogle_date'])  . 'T' . h($result['forgoogle_endTime']);
    }else{
        $google_calendar .= '/' . h($result['forgoogle_date'])  . 'T' . h($forgoogle_endTime);
        $google_calendar .= '&details=終了時刻は1時間後に仮置きしていますので、番組情報をご確認ください';
    }
    $google_calendar .= '&location='. h($result['broadcast_station']);
    $google_calendar .= '" class="google_btn">Googleカレンダーに登録</a>';

    return $google_calendar;
}

/* --- 公演詳細エリア関数 --- */
// 公演詳細情報
function event_detail($pdo, $event_ids){
    // SQLでデータ取得（公演データ）
    $stmt4 = $pdo->prepare("SELECT event_ids, event_name, venue, performers, event_type, event_type_ja,
                                DATE_FORMAT(event_date, '%Y/%m/%d') as event_date,
                                DAYOFWEEK(event_date) as dow,
                                DATE_FORMAT(event_startTime, '%H:%i') as event_startTime,
                                DATE_FORMAT(event_openTime,'%H:%i') as event_openTime,
                                DATE_FORMAT(event_date, '%Y%m%d') as forgoogle_date,
                                DATE_FORMAT(event_startTime, '%H%i%s') as forgoogle_startTime
                            FROM event
                            WHERE event_ids = :event_ids");
    $stmt4->bindValue(':event_ids', $event_ids, PDO::PARAM_STR);
    $status4 = $stmt4->execute();

    // データ表示
    if ($status4==false) {
        sql_error($stmt4);
    }else{
        $e_result = $stmt4->fetch();
    }

    return $e_result;
}

// 公演詳細用Googleカレンダー
function event_googlecal($result){
    // 終了時間はまちまちなので仮で1時間にセット
    $forgoogle_endTime =  $result['forgoogle_startTime'] + 10000;
    // リンクの設定
    $google_calendar = '';
    $google_calendar .= '<a target="_blank" href="https://www.google.com/calendar/render?action=TEMPLATE';
    $google_calendar .= '&text=' . h($result['event_name']);
    $google_calendar .= '&dates=' . h($result['forgoogle_date'])  . 'T' . h($result['forgoogle_startTime']);
    $google_calendar .= '/' . h($result['forgoogle_date'])  . 'T' . h($forgoogle_endTime);
    $google_calendar .= '&location='. h($result['venue']);
    $google_calendar .= '&details=終了時刻は1時間後に仮置きしていますので、劇場の情報をご確認ください';
    $google_calendar .= '" class="google_btn">Googleカレンダーに登録</a>';

    return $google_calendar;
}

// チケット販売情報
function ticketinfo($pdo, $event_ids,$dow_ja){
    // SQLでデータ取得（販売データ）
    $stmt5 = $pdo->prepare("SELECT
                            DATE_FORMAT(ti_startDate, '%m/%d') as ti_s_Date,
                            DAYOFWEEK(ti_startDate) as s_dow,
                            DATE_FORMAT(ti_startTime, '%H:%i') as ti_startTime, 
                            DATE_FORMAT(ti_endDate, '%m/%d') as ti_e_Date,
                            DAYOFWEEK(ti_endDate) as e_dow,
                            DATE_FORMAT(ti_endTime, '%H:%i') as ti_endTime,
                            ti_url, agency_name, sm_name, ticket_info.sm_id as sm_id
                            FROM ticket_info
                            JOIN ticket_agency on ticket_info.agency_id = ticket_agency.agency_id
                            JOIN sales_methods on ticket_info.sm_id = sales_methods.sm_id
                            WHERE event_ids = :event_ids AND ti_endDate >= CURDATE()
                            ORDER BY ticket_info.sm_id DESC");
    $stmt5->bindValue(':event_ids', $event_ids, PDO::PARAM_STR);
    $status5 = $stmt5->execute();

    // データ表示
    $ticket_info="";
    if ($status5==false) {
        sql_error($stmt5);

    }else{
        $tiInfoCount = 0; // 結果のカウント用変数

        // whileで1件ずつ取得
        while( $ti_result = $stmt5->fetch(PDO::FETCH_ASSOC)){
            $tiInfoCount++;

            if($ti_result['sm_id'] == 1){
                $ticket_info .= '<div class="ticketinfo_item"><p class="ti_type">' . h($ti_result['sm_name']) . '</p>';
            }else{
                $ticket_info .= '<div class="ticketinfo_item"><p class="ti_type_sp">' . h($ti_result['sm_name']) . '</p>';
            }
            $ticket_info .= '<a target="_blank" href="' . h($ti_result['ti_url']) . '"><p class="ti_agency">' . h($ti_result['agency_name']) . '</p></a>';
            $ticket_info .= '<p><span class="ti_stitle">販売開始日時：</span>' . h($ti_result['ti_s_Date']) .' '. $dow_ja[$ti_result['s_dow']] .' '. h($ti_result['ti_startTime']) . '</p>';
            $ticket_info .= '<p><span class="ti_stitle">販売終了日時：</span>' . h($ti_result['ti_e_Date']) .' '. $dow_ja[$ti_result['e_dow']] .' '. h($ti_result['ti_endTime']) . '</p></div>';
        }

        // 結果が0件だった場合のメッセージ表示
        if ($tiInfoCount == 0) {
            $ticket_info = "<span>チケット販売情報がありません</span>";
        }
    }

    return $ticket_info;
}

?>