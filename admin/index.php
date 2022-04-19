<?php
session_start();

// 管理者としてログインしていない場合
if (!isset($_SESSION['admin_id'])) {
  header("location: login.php");
  exit;
}

$path = '.';
$title = '管理画面トップ画面';
require('../header.php');
?>
<header class="admin-header">
  <div class="header-left">
    <h3>掲示板管理画面メインメニュー</h3>
  </div>
  <div class="header-right">
    <p>ようこそ<?php echo $_SESSION['admin_name'] ?>さん</p>
    <a class="btn" href="logout.php">ログアウト</a>
  </div>
</header>

<div class="container">
  <div class="back">
    <a class="btn" href="member.php">会員一覧</a>
  </div>
</div>

<?php
require('../footer.php');
?>