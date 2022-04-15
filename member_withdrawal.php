<?php
require('db_connect.php');

session_start();

// ログインしていない場合
if (!isset($_SESSION['id'])) {
  header("location: index.php");
  exit;
}

if (isset($_SESSION['id']) && isset($_POST['withdrawal']) && $_POST['withdrawal'] === '1') {
  try {
    $sql = "UPDATE members SET deleted_at = now() WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  session_destroy();
  header("location: index.php");
  exit;
}

$title = '退会ページ';
require('header.php');
?>
<header>
  <div class="header-right">
    <a class="btn" href="index.php">トップに戻る</a>
  </div>
</header>

<div class="container">
  <h1>退会</h1>
  <form action="member_withdrawal.php" method="post" class="withdrawal">
    <p>退会しますか？</p>
    <input type="hidden" name="withdrawal" value="1">
    <div class="submit">
      <button class="btn" type="submit">退会する</button>
    </div>
  </form>
</div>
<?php
require('footer.php');
?>