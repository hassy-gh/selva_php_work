<?php
session_start();

// 管理者としてログインしていない場合
if (!isset($_SESSION['admin_id'])) {
  header("location: login.php");
  exit;
}

// エラーがあるかどうか
if (isset($_SESSION['errors'])) {
  $errors = $_SESSION['errors'];
}

require('../db_connect.php');

// データの取得
try {
  $sql = "SELECT * FROM members WHERE id = :id";
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':id', $_GET['id']);
  $stmt->execute();
  $member = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo $e->getMessage();
}

$path = '.';
$title = '会員編集ページ';
require('../header.php');
require('../prefectures.php');
?>
<header class="admin-header">
  <div class="header-left">
    <h3>会員編集</h3>
  </div>
  <div class="header-right">
    <a href="member.php" class="btn">一覧へ戻る</a>
  </div>
</header>

<div class="container">
  <form action="member_update_confirm.php" method="post" class="member-register">
    <table class="form">
      <tr class="id">
        <th>ID</th>
        <td><?php echo $member['id'] ?><input name="id" type="hidden" value="<?php echo $member['id'] ?>"></td>
      </tr>

      <tr class="name">
        <th>氏名</th>
        <td>
          <label>姓 <input name="name_sei" type="text"
              value="<?php echo $_SESSION['name_sei'] ? $_SESSION['name_sei'] : $member['name_sei'] ?>"></label>
          <label>名 <input name="name_mei" type="text"
              value="<?php echo $_SESSION['name_mei'] ? $_SESSION['name_mei'] : $member['name_mei'] ?>"></label>
        </td>
      </tr>
      <?php if (isset($errors['name_sei']) || isset($errors['name_mei'])) : ?>
      <tr class="error-messages">
        <th></th>
        <td>
          <?php foreach ($errors['name_sei'] as $error) : ?>
          <p><?php echo $error ?></p>
          <?php endforeach ?>
          <?php foreach ($errors['name_mei'] as $error) : ?>
          <p><?php echo $error ?></p>
          <?php endforeach ?>
        </td>
      </tr>
      <?php endif ?>

      <tr class="gender">
        <th>性別</th>
        <td>
          <label><input name="gender" type="radio" value="1" <?php if ($_SESSION['gender']) {
                                                                echo $_SESSION['gender'] == 1 ? 'checked' : '';
                                                              } else {
                                                                echo $member['gender'] == 1 ? 'checked' : '';
                                                              } ?>>男性</label>
          <label><input name="gender" type="radio" value="2" <?php if ($_SESSION['gender']) {
                                                                echo $_SESSION['gender'] == 2 ? 'checked' : '';
                                                              } else {
                                                                echo $member['gender'] == 2 ? 'checked' : '';
                                                              } ?>>女性</label>
        </td>
      </tr>
      <?php if (isset($errors['gender'])) : ?>
      <tr class="error-messages">
        <th></th>
        <td>
          <?php foreach ($errors['gender'] as $error) : ?>
          <p><?php echo $error ?></p>
          <?php endforeach ?>
        </td>
      </tr>
      <?php endif ?>

      <tr class="address">
        <th>住所</th>
        <td>
          <label>
            都道府県
            <select name="pref_name">
              <option value="blank" hidden>選択してください</option>
              <?php foreach ($prefectures as $pref) : ?>
              <option value="<?php echo $pref ?>" <?php if ($_SESSION['pref_name']) {
                                                      echo $_SESSION['pref_name'] == $pref ? 'selected' : '';
                                                    } else {
                                                      echo $member['pref_name'] == $pref ? 'selected' : '';
                                                    } ?>>
                <?php echo $pref ?>
              </option>
              <?php endforeach ?>
            </select>
          </label>
          <br>
          <label>それ以降の住所 <input name="address" type="text"
              value="<?php echo $_SESSION['address'] ? $_SESSION['address'] : $member['address'] ?>"></label>
        </td>
      </tr>
      <?php if (isset($errors['pref_name']) || isset($errors['address'])) : ?>
      <tr class="error-messages">
        <th></th>
        <td>
          <?php foreach ($errors['pref_name'] as $error) : ?>
          <p><?php echo $error ?></p>
          <?php endforeach ?>
          <?php if (isset($errors['address'])) : ?>
          <?php foreach ($errors['address'] as $error) : ?>
          <p><?php echo $error ?></p>
          <?php endforeach ?>
          <?php endif ?>
        </td>
      </tr>
      <?php endif ?>

      <tr class="password">
        <th>パスワード</th>
        <td><input name="password" type="password"
            value="<?php echo $_SESSION['password'] ? $_SESSION['password'] : '' ?>"></td>
      </tr>
      <?php if (isset($errors['password'])) : ?>
      <tr class="error-messages">
        <th></th>
        <td>
          <?php foreach ($errors['password'] as $error) : ?>
          <p><?php echo $error ?></p>
          <?php endforeach ?>
        </td>
      </tr>
      <?php endif ?>

      <tr class="password-confirm">
        <th>パスワード確認</th>
        <td><input name="password_confirm" type="password"
            value="<?php echo $_SESSION['password_confirm'] ? $_SESSION['password_confirm'] : '' ?>"></td>
      </tr>
      <?php if (isset($errors['password_confirm'])) : ?>
      <tr class="error-messages">
        <th></th>
        <td>
          <?php foreach ($errors['password_confirm'] as $error) : ?>
          <p><?php echo $error ?></p>
          <?php endforeach ?>
        </td>
      </tr>
      <?php endif ?>

      <tr class="email">
        <th>メールアドレス</th>
        <td><input name="email" type="text"
            value="<?php echo $_SESSION['email'] ? $_SESSION['email'] : $member['email'] ?>"></td>
      </tr>
      <?php if (isset($errors['email'])) : ?>
      <tr class="error-messages">
        <th></th>
        <td>
          <?php foreach ($errors['email'] as $error) : ?>
          <p><?php echo $error ?></p>
          <?php endforeach ?>
        </td>
      </tr>
      <?php endif ?>
    </table>
    <div class="submit">
      <button class="btn" type="submit">確認画面へ</button>
    </div>
  </form>
</div>
<?
require('../footer.php');
$_SESSION['name_sei'] = '';
$_SESSION['name_mei'] = '';
$_SESSION['gender'] = '';
$_SESSION['pref_name'] = '';
$_SESSION['address'] = '';
$_SESSION['password'] = '';
$_SESSION['password_confirm'] = '';
$_SESSION['email'] = '';
$_SESSION['errors'] = '';
?>