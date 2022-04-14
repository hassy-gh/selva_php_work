<?php
session_start();
// ログインしていないか確認
if (!(isset($_SESSION['id']))) {
  header("location: index.php");
  exit;
}

require('db_connect.php');

// 二重登録防止
$postToken = isset($_POST['token']) ? $_POST['token'] : '';
$sessionToken = isset($_SESSION['token']) ? $_SESSION['token'] : '';
unset($_SESSION['token']);

// コメント投稿
if (empty($_POST['comment'])) {
  $_SESSION['error'] = '※コメントを入力してください';
  header("location: thread_detail.php?id={$_POST['thread_id']}");
} elseif (mb_strlen($_POST['comment']) > 500) {
  $_SESSION['error'] = '※コメントは500文字以内で入力してください';
  header("location: thread_detail.php?id={$_POST['thread_id']}");
} elseif ($postToken != '' && $postToken == $sessionToken) {
  try {
    // 入力情報をデータベースに登録
    $sql = "INSERT INTO comments (member_id, thread_id, comment, created_at, updated_at)
          VALUES (:member_id, :thread_id, :comment, now(), now())";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':member_id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->bindParam(':thread_id', $_POST['thread_id'], PDO::PARAM_INT);
    $stmt->bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);
    $stmt->execute();
    header("location: thread_detail.php?id={$_POST['thread_id']}");
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}
