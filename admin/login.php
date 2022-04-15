<?php
session_start();

// 管理者としてログインしている場合
if (isset($_SESSION['admin_id'])) {
  header("location: index.php");
  exit;
}

$login_id = $_POST['login_id'];
$password = $_POST['password'];
require('../db_connect.php');

// 管理者データの取得
$sql = "SELECT * FROM administers WHERE login_id = :login_id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':login_id', $login_id, PDO::PARAM_STR);
$stmt->execute();
$admin = $stmt->fetch();


if (isset($_POST['post'])) {
  $errors = array();

  // バリデーション
  if (empty($login_id)) {
    $errors['login_id'] = '※ログインIDは必須入力です';
  } elseif (7 > mb_strlen($login_id) || mb_strlen($login_id) > 10) {
    $errors['login_id'] = '※ログインIDは7〜10文字で入力してください';
  }
  if (empty($password)) {
    $errors['password'] = '※パスワードは必須入力です';
  } elseif (8 > mb_strlen($password) || mb_strlen($password) > 20) {
    $errors['password'] = '※パスワードは8〜20文字で入力してください';
  }
  if (!empty($login_id) && !empty($password)) {
    if ($password == $admin['password']) {
      $_SESSION['admin_id'] = $admin['id'];
      $_SESSION['admin_name'] = $admin['name'];
      header("location: index.php");
    } else {
      $errors['invalid'] = '※ログインIDもしくはパスワードが間違っています';
    }
  }
}

$path = '.';
$title = 'ログインフォーム';
require('../header.php');
?>
<header class="admin-header"></header>
<div class="container">
  <h1>管理画面</h1>
  <form action="login.php" method="post" class="admin-login">
    <input name="post" type="hidden">
    <table class="form">
      <tr>
        <th>ログインID</th>
        <td>
          <input name="login_id" type="text" value="<?php echo $login_id ?>">
        </td>
      </tr>
      <?php if (isset($errors['login_id'])) : ?>
      <tr class="error-messages">
        <th></th>
        <td>
          <p><?php echo $errors['login_id'] ?></p>
        </td>
      </tr>
      <?php endif ?>

      <tr>
        <th>パスワード</th>
        <td>
          <input name="password" type="password">
        </td>
      </tr>
      <?php if (isset($errors['password'])) : ?>
      <tr class="error-messages">
        <th></th>
        <td>
          <p><?php echo $errors['password'] ?></p>
        </td>
      </tr>
      <?php endif ?>

      <?php if (isset($errors['invalid'])) : ?>
      <tr class="error-messages">
        <th></th>
        <td>
          <p><?php echo $errors['invalid'] ?></p>
        </td>
      </tr>
      <?php endif ?>
    </table>
    <div class="submit">
      <button class="btn" type="submit">ログイン</button>
    </div>
  </form>
</div>
<footer class="admin-footer"></footer>
<?php
require('../footer.php');
?>