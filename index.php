<?php
session_start();
$title = 'トップページ';
require('header.php');
?>
<header>
  <?php if (isset($_SESSION['id'])) : ?>
  <div class="header-left">
    <p><?php echo "ようこそ" . $_SESSION['name_sei'] . "様" ?></p>
  </div>
  <div class="header-right">
    <a class="btn" href="thread.php">スレッド一覧</a>
    <a class="btn" href="thread_regist.php">新規スレッド作成</a>
    <a class="btn" href="logout.php">ログアウト</a>
  </div>
  <?php else : ?>
  <div class="header-right">
    <a class="btn" href="thread.php">スレッド一覧</a>
    <a class="btn" href="member_regist.php">新規会員登録</a>
    <a class="btn" href="login.php">ログイン</a>
  </div>
  <?php endif ?>
</header>
<?php
require('footer.php');
?>