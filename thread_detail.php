<?php
session_start();

$title = 'スレッド詳細';
require('header.php');
require('db_connect.php');

// データの取得
$sql = "SELECT * FROM threads JOIN members ON threads.member_id = members.id WHERE threads.id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $_REQUEST['id']);
$stmt->execute();
$thread = $stmt->fetch();
?>
<header>
  <div class="header-right">
    <a class="btn" href="thread.php">スレッド一覧に戻る</a>
  </div>
</header>
<div class="container">
  <div class="thread-detail">
    <div class="thread-detail-header">
      <h1><?php echo htmlspecialchars($thread['title']) ?></h1>
      <p><?php echo date('n/d/y H:i',  strtotime(htmlspecialchars($thread['created_at']))) ?></p>
    </div>

    <div class="thread-detail-divider">

    </div>

    <div class="thread-detail-content">
      <p>
        投稿者：<?php echo htmlspecialchars($thread['name_sei']) ?> <?php echo htmlspecialchars($thread['name_mei']) ?>
        <?php echo date('Y.n.d H:i',  strtotime(htmlspecialchars($thread['created_at']))) ?>
      </p>
      <p>
        <?php echo htmlspecialchars($thread['content']) ?>
      </p>
    </div>

    <?php if (isset($_SESSION['id'])) : ?>
    <div class="thread-detail-divider">

    </div>

    <div class="comment">
      <form action="" method="post">
        <textarea name="" cols="30" rows="10"></textarea>
        <div class="submit">
          <button class="btn" type="submit">コメントする</button>
        </div>
      </form>
    </div>
    <?php endif ?>
  </div>
</div>
<?php
require('footer.php');
?>