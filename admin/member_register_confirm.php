<?php
session_start();

// 管理者としてログインしていない場合
if (!isset($_SESSION['admin_id'])) {
  header("location: login.php");
  exit;
}

require('regist_validation.php');

// 二重登録防止
$token = uniqid('', true);
$_SESSION['token'] = $token;

$path = '.';
$title = '会員登録画面';
require('../header.php');
?>
<header class="admin-header">
  <div class="header-left">
    <h3>会員登録</h3>
  </div>
  <div class="header-right">
    <a href="member.php" class="btn">一覧へ戻る</a>
  </div>
</header>
<div class="container">
  <form action="member_registered.php" method="post">
    <table class="form">
      <tr class="id">
        <th>ID</th>
        <td>登録後に自動採番</td>
      </tr>
      <tr class="name">
        <th>氏名</th>
        <td><?php echo "{$nameSei} {$nameMei}" ?></td>
      </tr>
      <tr class="gender">
        <th>性別</th>
        <td><?php echo $gender == 1 ? '男性' : '女性' ?></td>
      </tr>
      <tr class="address">
        <th>住所</th>
        <td><?php echo "{$prefName}{$address}" ?></td>
      </tr>
      <tr class="password">
        <th>パスワード</th>
        <td>セキュリティのため非表示</td>
      </tr>
      <tr class="email">
        <th>メールアドレス</th>
        <td><?php echo $email ?></td>
      </tr>
    </table>

    <input type="hidden" name="token" value="<?php echo $token ?>">
    <div class="submit">
      <button class="btn" type="submit">登録完了</button>
    </div>
    <div class="back">
      <button class="btn" type="button" onclick=history.back()>前に戻る</button>
    </div>
  </form>
</div>
<?php
require('../footer.php');
?>