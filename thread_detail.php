<?php
session_start();

$title = 'スレッド詳細';
require('header.php');
require('db_connect.php');

// データの取得
// スレッド・会員
$sql = "SELECT threads.id AS 'thread_id', member_id, title, content, threads.created_at, name_sei, name_mei FROM threads JOIN members on threads.member_id = members.id WHERE threads.id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $_REQUEST['id']);
$stmt->execute();
$thread = $stmt->fetch(PDO::FETCH_ASSOC);
// コメント
$sql = "SELECT comments.id AS 'comment_id', comments.created_at, comment, name_sei, name_mei FROM comments JOIN members ON comments.member_id = members.id WHERE thread_id = :thread_id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':thread_id', $thread['thread_id']);
$stmt->execute();
$comments = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $comments[] = $row;
}
// コメント数
$commentCount = $stmt->rowCount();
// いいね数
$sql = "SELECT COUNT(member_id) AS count, comment_id FROM likes GROUP BY comment_id";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$likeCount = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $likeCount[$row['comment_id']] = $row['count'];
}
// いいね
$sql = "SELECT comment_id FROM likes WHERE member_id = :member_id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':member_id', $_SESSION['id']);
$stmt->execute();
$liked = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $liked[] = $row['comment_id'];
}

// ページング
if (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) {
  $page = $_REQUEST['page'];
} else {
  $page = 1;
}
$maxPage = ceil($commentCount / 5);
$startNo = ($page - 1) * 5;
$displayData = array_slice($comments, $startNo, 5, true);

// 二重登録防止トークンの発行
$token = uniqid('', true);
$_SESSION['token'] = $token;

if (isset($_SESSION['error'])) {
  $error = $_SESSION['error'];
}
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
      <p>
        <?php echo htmlspecialchars($commentCount) ?>コメント
        <?php echo date('n/d/y H:i',  strtotime(htmlspecialchars($thread['created_at']))) ?>
      </p>
    </div>

    <div class="thread-detail-divider">
      <div class="pagination">
        <div class="page-left">
          <?php if ($page >= 2) : ?>
          <a href="thread_detail.php?id=<?php echo $thread['thread_id'] ?>&page=<?php echo ($page - 1) ?>"
            class="page-btn pref">＜前へ</a>
          <?php else : ?>
          <span class="page-btn not-paging">＜前へ</span>
          <?php endif ?>
        </div>
        <div class="page-right">
          <?php if ($page < $maxPage) : ?>
          <a href="thread_detail.php?id=<?php echo $thread['thread_id'] ?>&page=<?php echo ($page + 1) ?>"
            class="page-btn next">次へ＞</a>
          <?php else : ?>
          <span class="page-btn not-paging">次へ＞</span>
          <?php endif ?>
        </div>
      </div>
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

    <?php if (!empty($comments)) : ?>
    <div class="comments">
      <?php foreach ($displayData as $comment) : ?>
      <div class="comments-item">
        <p>
          <?php echo htmlspecialchars($comment['comment_id']) ?>.
          <?php echo htmlspecialchars($comment['name_sei']) ?> <?php echo htmlspecialchars($comment['name_mei']) ?>
          <?php echo date('Y.n.d H:i',  strtotime(htmlspecialchars($comment['created_at']))) ?>
        </p>
        <p>
          <?php echo $comment['comment'] ?>
        </p>
        <div class="likes-form">
          <?php if (!in_array($comment['comment_id'], $liked)) : ?>
          <form action="like.php" method="post" class="likes">
            <input name="thread_id" type="hidden" value="<?php echo $thread['thread_id'] ?>">
            <input name="page" type="hidden" value="<?php echo $page ?>">
            <input name="comment_id" type="hidden" value="<?php echo $comment['comment_id'] ?>">
            <button type="submit">
              <i class="like far fa-heart"></i>
            </button>
          </form>
          <?php else : ?>
          <form action="unlike.php" method="post" class="likes">
            <input name="thread_id" type="hidden" value="<?php echo $thread['thread_id'] ?>">
            <input name="page" type="hidden" value="<?php echo $page ?>">
            <input name="comment_id" type="hidden" value="<?php echo $comment['comment_id'] ?>">
            <button type="submit">
              <i class="unlike fas fa-heart"></i>
            </button>
          </form>
          <?php endif ?>
          <?php if (array_key_exists($comment['comment_id'], $likeCount)) : ?>
          <?php echo $likeCount[$comment['comment_id']] ?>
          <?php else : ?>
          0
          <?php endif ?>
        </div>
      </div>
      <?php endforeach ?>
    </div>
    <?php endif ?>

    <div class="thread-detail-divider">
      <div class="pagination">
        <div class="page-left">
          <?php if ($page >= 2) : ?>
          <a href="thread_detail.php?id=<?php echo $thread['thread_id'] ?>&page=<?php echo ($page - 1) ?>"
            class="page-btn pref">＜前へ</a>
          <?php else : ?>
          <span class="page-btn not-paging">＜前へ</span>
          <?php endif ?>
        </div>
        <div class="page-right">
          <?php if ($page < $maxPage) : ?>
          <a href="thread_detail.php?id=<?php echo $thread['thread_id'] ?>&page=<?php echo ($page + 1) ?>"
            class="page-btn next">次へ＞</a>
          <?php else : ?>
          <span class="page-btn not-paging">次へ＞</span>
          <?php endif ?>
        </div>
      </div>
    </div>

    <?php if (isset($_SESSION['id'])) : ?>
    <div class="comment">
      <form action="comment_registered.php" method="post">
        <textarea name="comment" cols="30" rows="10"></textarea>
        <?php if (isset($error)) : ?>
        <div class="error-messages">
          <p><?php echo $error ?></p>
        </div>
        <?php endif ?>
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="hidden" name="thread_id" value="<?php echo $thread['thread_id']; ?>">
        <div class="submit">
          <button class="btn" type="submit">コメントする</button>
        </div>
      </form>
    </div>
    <?php endif ?>
  </div>
</div>
<?php
$_SESSION['error'] = '';
require('footer.php');
?>