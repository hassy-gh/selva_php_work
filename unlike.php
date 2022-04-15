<?php
session_start();

$thread_id = $_POST['thread_id'];
$page = $_POST['page'];

// ログインしていない場合
if (!isset($_SESSION['id'])) {
  header("location: member_regist.php");
  exit;
}

require('db_connect.php');
try {
  // 入力情報をデータベースに登録
  $sql = "DELETE FROM likes WHERE member_id = :member_id AND comment_id = :comment_id";
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':member_id', $_SESSION['id'], PDO::PARAM_INT);
  $stmt->bindParam(':comment_id', $_POST['comment_id'], PDO::PARAM_INT);
  $stmt->execute();
  header("location: thread_detail.php?id={$thread_id}&page={$page}");
} catch (PDOException $e) {
  echo $e->getMessage();
}