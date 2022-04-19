<?php
session_start();

// 管理者としてログインしていない場合
if (!isset($_SESSION['admin_id'])) {
  header("location: login.php");
  exit;
}

require('../db_connect.php');

// データの削除
$sql = "UPDATE members SET deleted_at = now() WHERE id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
header("location: member.php");