<?php
session_start();

// ログインしていないか確認
if (!(isset($_SESSION['id']))) {
  header("location: index.php");
  exit;
}

// 二重登録防止
$postToken = isset($_POST['token']) ? $_POST['token'] : '';
$sessionToken = isset($_SESSION['token']) ? $_SESSION['token'] : '';
unset($_SESSION['token']);

require('db_connect.php');

if ($postToken != '' && $postToken == $sessionToken) {
  try {
    // 入力情報をデータベースに登録
    $sql = "INSERT INTO threads (member_id, title, content, created_at, updated_at)
        VALUES (:member_id, :title, :content, now(), now())";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':member_id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->bindParam(':title', $_SESSION['title'], PDO::PARAM_STR);
    $stmt->bindParam(':content', $_SESSION['content'], PDO::PARAM_STR);
    $stmt->execute();
    $_SESSION['title'] = '';
    $_SESSION['content'] = '';
    header("location: thread.php");
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}