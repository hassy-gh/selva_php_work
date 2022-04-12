<?php
session_start();

// ログイン済みか確認
if (isset($_SESSION['id'])) {
  header("location: index.php");
  exit;
}

$email = $_POST['email'];
$password = $_POST['password'];
require('db_connect.php');

// バリデーション
if (isset($_POST['submit'])) {
  $errors = array();
  if (empty($email)) {
    $errors['login']['email'] = '※メールアドレス（ID）は必須入力です';
  }
  if (empty($password)) {
    $errors['login']['password'] = '※パスワードは必須入力です';
  }
  if (!empty($email) && !empty($password)) {
    $sql = "SELECT * FROM members WHERE email = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $member = $stmt->fetch();
    if (password_verify($password, $member['password'])) {
      $_SESSION['id'] = $member['id'];
      $_SESSION['name_sei'] = $member['name_sei'];
      $_SESSION['name_mei'] = $member['name_mei'];
      header("location: index.php");
    } else {
      $errors['login']['invalid'] = '※メールアドレス（ID）もしくはパスワードが間違っています';
    }
  }
}

$title = 'ログインフォーム';
require('header.php');
?>
<div class="container">
  <h1>ログイン</h1>
  <form action="" method="post">
    <input name="submit" type="text" hidden>
    <table class="form">
      <tr class="email">
        <th>メールアドレス（ID）</th>
        <td><input name="email" type="text" value="<?php echo $email ?>"></td>
      </tr>
      <?php if (isset($errors['login']['email'])) : ?>
      <tr class="error-messages">
        <th></th>
        <td>
          <p><?php echo $errors['login']['email'] ?></p>
        </td>
      </tr>
      <?php endif ?>

      <tr class="password">
        <th>パスワード</th>
        <td>
          <input name="password" type="password">
        </td>
      </tr>
      <?php if (isset($errors['login']['password'])) : ?>
      <tr class="error-messages">
        <th></th>
        <td>
          <p><?php echo $errors['login']['password'] ?></p>
        </td>
      </tr>
      <?php endif ?>

      <?php if (isset($errors['login']['invalid'])) : ?>
      <tr class="error-messages">
        <th></th>
        <td>
          <p><?php echo $errors['login']['invalid'] ?></p>
        </td>
      </tr>
      <?php endif ?>
    </table>

    <div class="submit">
      <button class="btn" type="submit">ログイン</button>
    </div>
    <div class="back">
      <a class="btn" href="index.php">トップに戻る</a>
    </div>
  </form>
</div>