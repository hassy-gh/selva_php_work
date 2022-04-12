<?php
session_start();

// 二重登録防止トークン
$token = isset($_POST['token']) ? $_POST['token'] : '';
$sessionToken = isset($_SESSION['token']) ? $_SESSION['token'] : '';
unset($_SESSION['token']);

$title = "会員登録完了";
require('header.php');
require('db_connect.php');
?>
<?php if ($token != '' && $token == $sessionToken) : ?>
<?php
  try {
    // 入力情報をデータベースに登録
    $sql = "INSERT INTO members (name_sei, name_mei, gender, pref_name, address, password, email, created_at, updated_at)
        VALUES (:name_sei, :name_mei, :gender, :pref_name, :address, :password, :email, now(), now())";
    $stmt = $dbh->prepare($sql);
    // パスワードの暗号化
    $hashPassword = password_hash($_SESSION['password'], PASSWORD_DEFAULT);
    $stmt->bindParam(':name_sei', $_SESSION['name_sei'], PDO::PARAM_STR);
    $stmt->bindParam(':name_mei', $_SESSION['name_mei'], PDO::PARAM_STR);
    $stmt->bindParam(':gender', $_SESSION['gender'], PDO::PARAM_INT);
    $stmt->bindParam(':pref_name', $_SESSION['pref_name'], PDO::PARAM_STR);
    $stmt->bindParam(':address', $_SESSION['address'], PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashPassword, PDO::PARAM_STR);
    $stmt->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
    $stmt->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
  ?>
<div class="container member-registered">
  <h1>会員登録完了</h1>
  <p>会員登録が完了しました。</p>
  <div class="back">
    <a class="btn" href="index.php">トップに戻る</a>
  </div>
</div>
<?php else : ?>
<div class="container member-registered">
  <h1>不正な登録です。</h1>
  <div class="back">
    <a class="btn" href="index.php">トップに戻る</a>
  </div>
</div>
<?php endif ?>
<?php
require('footer.php')
?>