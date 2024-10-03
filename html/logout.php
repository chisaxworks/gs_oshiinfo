<!-- ログアウト画面 -->
<?php
// SESSIONスタート
session_start();

// 関数ファイル呼び出し
require_once('funcs.php');
// ログアウト処理
sslogout();

// ログイン画面にリダイレクト
header('Location: index.php');
?>