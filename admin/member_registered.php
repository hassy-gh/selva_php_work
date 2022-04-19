<?php
session_start();

// 管理者としてログインしていない場合
if (!isset($_SESSION['admin_id'])) {
  header("location: login.php");
  exit;
}

// 二重登録防止
$token = isset($_POST['token']) ? $_POST['token'] : '';
$sessionToken = isset($_SESSION['token']) ? $_SESSION['token'] : '';
unset($_SESSION['token']);

require('../db_connect.php');

if ($token != '' && $token == $sessionToken) {
  try {
    $sql = "INSERT INTO members (name_sei, name_mei, gender, pref_name, address, password, email, created_at, updated_at) VALUES (:name_sei, :name_mei, :gender, :pref_name, :address, :password, :email, now(), now())";
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
    header("location: member.php");
    $_SESSION['name_sei'] = '';
    $_SESSION['name_mei'] = '';
    $_SESSION['gender'] = '';
    $_SESSION['pref_name'] = '';
    $_SESSION['address'] = '';
    $_SESSION['password'] = '';
    $_SESSION['password_confirm'] = '';
    $_SESSION['email'] = '';
    $_SESSION['errors'] = '';
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
} else {
  header("location: member.php");
  exit;
}