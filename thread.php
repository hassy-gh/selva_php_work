<?php
session_start();

$title = 'スレッド一覧';
require('header.php');
require('db_connect.php');

// データの取得
if (empty($_POST['search'])) {
  $sql = "SELECT * FROM threads ORDER BY created_at DESC";
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $threads = array();
  while ($row = $stmt->fetch()) {
    $threads[] = $row;
  }
} else {
  $sql = "SELECT * FROM threads WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(['%' . $_POST['search'] . '%', '%' . $_POST['search'] . '%']);
  $threads = array();
  while ($row = $stmt->fetch()) {
    $threads[] = $row;
  }
}
?>
<?php if (isset($_SESSION['id'])) : ?>
<header>
  <div class="header-right">
    <a class="btn" href="thread_regist.php">新規スレッド作成</a>
  </div>
</header>
<?php endif ?>
<div class="container">
  <form class="search" action="" method="post">
    <input name="search" type="text">
    <button class="btn" type="submit">スレッド検索</button>
  </form>
  <table class="threads">
    <?php foreach ($threads as $thread) : ?>
    <tr class="thread">
      <td class="id">ID: <?php echo htmlspecialchars($thread['id'])  ?></td>
      <td class="title"><?php echo htmlspecialchars($thread['title'])  ?></td>
      <td class="created-at"><?php echo date('Y.n.d. H:i',  strtotime(htmlspecialchars($thread['created_at'])));  ?>
      </td>
    </tr>
    <?php endforeach ?>
  </table>
  <div class="back">
    <a class="btn" href="index.php">トップに戻る</a>
  </div>
</div>
<?php
require('footer.php');
?>