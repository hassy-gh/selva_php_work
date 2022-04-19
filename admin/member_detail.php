<?php
session_start();

// 管理者としてログインしていない場合
if (!isset($_SESSION['admin_id'])) {
  header("location: login.php");
  exit;
}

require('../db_connect.php');

// データの取得
$sql = "SELECT * FROM members WHERE id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$member = $stmt->fetch(PDO::FETCH_ASSOC);

$path = '.';
$title = '会員詳細画面';
require('../header.php');
?>
<header class="admin-header">
  <div class="header-left">
    <h3>会員詳細</h3>
  </div>
  <div class="header-right">
    <a href="member.php" class="btn">一覧へ戻る</a>
  </div>
</header>
<div class="container">
  <table class="member-detail">
    <tr class="id">
      <th>ID</th>
      <td><?php echo $member['id'] ?></td>
    </tr>

    <tr class="name">
      <th>氏名</th>
      <td><?php echo "{$member['name_sei']} {$member['name_mei']}" ?></td>
    </tr>

    <tr class="gender">
      <th>性別</th>
      <td><?php echo $member['gender'] == 1 ? '男性' : '女性' ?></td>
    </tr>

    <tr class="address">
      <th>住所</th>
      <td><?php echo "{$member['pref_name']}{$member['address']}" ?></td>
    </tr>

    <tr class="password">
      <th>パスワード</th>
      <td>セキュリティのため非表示</td>
    </tr>

    <tr class="email">
      <th>メールアドレス</th>
      <td><?php echo $member['email'] ?></td>
    </tr>
  </table>

  <div class="submit">
    <a href="member_edit.php?id=<?php echo $_GET['id'] ?>" class="btn">編集</a>
  </div>
  <div class="back">
    <a href="member_delete.php?id=<?php echo $_GET['id'] ?>" class="btn">削除</a>
  </div>
</div>