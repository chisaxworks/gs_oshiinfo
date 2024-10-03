<!-- ユーザ登録処理＆完了表示PHP -->
<?php
// SESSIONスタート
session_start();

// POSTデータ取得
$reg_name = $_POST["reg_name"];
$reg_email = $_POST["reg_email"];
$reg_pw = $_POST["reg_password"];
// PWハッシュ化
$pwhash = password_hash($reg_pw, PASSWORD_DEFAULT); 

// 関数ファイル呼び出し
require_once('funcs.php');
//PHPからDB接続
$pdo = db_conn();

// データ有無取得SQL（ユーザテーブル）そのメールアドレスで有効なユーザ登録があるか
$stmt1 = $pdo->prepare('SELECT * FROM user WHERE user_email = :reg_email AND isActive = 1');
$stmt1->bindValue(':reg_email', $reg_email, PDO::PARAM_STR);
$status1 = $stmt1->execute();

if($status1 === false){
  sql_error($stmt1);
}

$val = $stmt1->fetch();

if(!is_null($val['google_sub']) && is_null($val['password'])){
  // Googleで作成したアカウントで有効なものがあればパスワードを設定する
  $user_id = $val['user_id'];
  $stmt = $pdo->prepare('UPDATE user SET password = :pwhash, modifiedDate = now() WHERE user_id = :user_id;');
  $stmt->bindValue(':pwhash', $pwhash, PDO::PARAM_STR);
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

  $status = $stmt->execute();

  // データ登録処理後
  if($status === false){
    sql_error($stmt);
  }
  
  // ログイン成功と同じ処理
  $_SESSION["chk_ssid"] = session_id();
  $_SESSION["userid"] = $val["user_id"];
  $_SESSION["username"] = $val["user_name"];
  $_SESSION["useremail"] = $val["user_email"];

  // ログイン後の状態にリダイレクト
  header('Location: main_un.php');

}elseif($val){
  // データ（同じメールアドレスかつ有効）があったらアラートを出す
  $_SESSION["error_msg"] = "そのメールアドレスは既に登録されています";
  header('Location: register.php');

}else{
  // メールアドレスがない、または、メールアドレスあるが有効ではない
  // データ登録SQL（ユーザテーブル）
  $stmt = $pdo->prepare('INSERT INTO user(user_id, user_name, user_email, password, isActive, createdDate, modifiedDate)
      VALUES(NULL, :reg_name, :reg_email, :pwhash, 1, now(), now())');

  $stmt->bindValue(':reg_name', $reg_name, PDO::PARAM_STR);
  $stmt->bindValue(':reg_email', $reg_email, PDO::PARAM_STR);
  $stmt->bindValue(':pwhash', $pwhash, PDO::PARAM_STR);

  $status = $stmt->execute();

  // データ登録処理後
  if($status === false){
    sql_error($stmt);
  }

  // ログイン成功と同じ処理
  $_SESSION["chk_ssid"] = session_id();
  $_SESSION["username"] = $reg_name;
  $_SESSION["useremail"] = $reg_email;

  // ログイン後の状態にリダイレクト
  header('Location: main_un.php');

}