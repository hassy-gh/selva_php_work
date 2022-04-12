<?php
session_start();

// 値の取得
$_SESSION['title'] = $_POST['title'];
$_SESSION['content'] = $_POST['content'];

if (isset($_POST['submit'])) {

  // バリデーション
  $errors = array();
  // タイトル
  if (empty($_SESSION['title'])) {
    $errors['title']['presence'] = '※タイトルは必須入力です';
  } elseif (strlen($_SESSION['title']) > 100) {
    $errors['title']['max-length'] = '※タイトルは100文字以内で入力してください';
  }

  // コメント
  if (empty($_SESSION['content'])) {
    $errors['content']['presence'] = '※コメントは必須入力です';
  } elseif (strlen($_SESSION['content']) > 500) {
    $errors['content']['max-length'] = '※コメントは500文字以内で入力してください';
  }

  // 値の挿入
  if (empty($errors)) {
    $title = $_SESSION['title'];
    $content = $_SESSION['content'];
  } else {
    $_SESSION['errors'] = $errors;
    header("location: thread_regist.php");
  }
}