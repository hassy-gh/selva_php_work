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
    if ($_SESSION['password']) {
      $sql = "UPDATE members SET name_sei = :name_sei, name_mei = :name_mei, gender = :gender, pref_name = :pref_name, address = :address, password = :password, email = :email, updated_at = now() WHERE id = :id";
    } else {
      $sql = "UPDATE members SET name_sei = :name_sei, name_mei = :name_mei, gender = :gender, pref_name = :pref_name, address = :address, email = :email, updated_at = now() WHERE id = :id";
    }
    $stmt = $dbh->prepare($sql);
    // パスワードの暗号化

    $stmt->bindParam(':id', $_SESSION['member_id'], PDO::PARAM_INT);
    $stmt->bindParam(':name_sei', $_SESSION['name_sei'], PDO::PARAM_STR);
    $stmt->bindParam(':name_mei', $_SESSION['name_mei'], PDO::PARAM_STR);
    $stmt->bindParam(':gender', $_SESSION['gender'], PDO::PARAM_INT);
    $stmt->bindParam(':pref_name', $_SESSION['pref_name'], PDO::PARAM_STR);
    $stmt->bindParam(':address', $_SESSION['address'], PDO::PARAM_STR);
    if ($_SESSION['password']) {
      $hashPassword = password_hash($_SESSION['password'], PASSWORD_DEFAULT);
      $stmt->bindParam(':password', $hashPassword, PDO::PARAM_STR);
    }
    $stmt->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
    $stmt->execute();
    header("location: member.php");
    $_SESSION['member_id'];
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