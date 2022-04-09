<?php
session_start();
require('prefectures.php');

// 値の取得
$_SESSION['last-name'] = $_POST['last-name'];
$_SESSION['first-name'] = $_POST['first-name'];
$_SESSION['gender'] = $_POST['gender'];
$_SESSION['prefecture'] = $_POST['prefecture'];
$_SESSION['address'] = $_POST['address'];
$_SESSION['password'] = $_POST['password'];
$_SESSION['password-confirm'] = $_POST['password-confirm'];
$_SESSION['email'] = $_POST['email'];

// バリデーション
$errors = array();
// 氏名(姓)
if (empty($_SESSION['last-name'])) {
  $errors['last-name']['presence'] = '※氏名（姓）は必須入力です';
} elseif (strlen($_SESSION['last-name']) > 20) {
  $errors['last-name']['max-length'] = '※氏名(姓)は20文字以内で入力してください';
}

// 氏名(名)
if (empty($_SESSION['first-name'])) {
  $errors['first-name']['presence'] = '※氏名（名）は必須入力です';
} elseif (strlen($_SESSION['first-name']) > 20) {
  $errors['first-name']['max-length'] = '※氏名(姓)は20文字以内で入力してください';
}

// 性別
if (empty($_SESSION['gender'])) {
  $errors['gender']['presence'] = '※性別は必須入力です';
} elseif (!($_SESSION['gender'] == '男性' || $_SESSION['gender'] == '女性')) {
  $errors['gender']['wrong-gender'] = '※入力された性別が正しくありません';
}

// 住所（都道府県）
if ($_SESSION['prefecture'] == 'blank') {
  $errors['prefecture']['presence'] = '※住所（都道府県）は必須入力です';
} elseif (!(in_array($_SESSION['prefecture'], $prefectures))) {
  $errors['prefecture']['wrong-prefecture'] = '※入力された都道府県が正しくありません';
}

// 住所(それ以降の住所)
if (strlen($_SESSION['address']) > 100) {
  $errors['address']['max-length'] = '※住所(それ以降の住所)は100文字以内で入力してください';
}

// パスワード
if (empty($_SESSION['password'])) {
  $errors['password']['presence'] = '※パスワードは必須入力です';
} elseif ((strlen($_SESSION['password']) < 8 || strlen($_SESSION['password']) > 20) && (!(preg_match(
  "/^[a-zA-Z0-9]+$/",
  $_SESSION['password']
)))) {
  $errors['password']['length'] = '※パスワードは8〜20文字で入力してください';
  $errors['password']['character'] = '※パスワードは半角英数字で入力してください';
} elseif (strlen($_SESSION['password']) < 8 || strlen($_SESSION['password']) > 20) {
  $errors['password']['length'] = '※パスワードは8〜20文字で入力してください';
} elseif (!(preg_match("/^[a-zA-Z0-9]+$/", $_SESSION['password']))) {
  $errors['password']['character'] = '※パスワードは半角英数字で入力してください';
}

// パスワード確認
if (empty($_SESSION['password-confirm'])) {
  $errors['password-confirm']['presence'] = '※パスワード確認用は必須入力です';
} elseif ((strlen($_SESSION['password-confirm']) < 8 || strlen($_SESSION['password-confirm']) > 20) &&
  (!(preg_match("/^[a-zA-Z0-9]+$/", $_SESSION['password-confirm'])))
) {
  $errors['password-confirm']['length'] = '※パスワード確認用は8〜20文字で入力してください';
  $errors['password-confirm']['character'] = '※パスワード確認用は半角英数字で入力してください';
} elseif (strlen($_SESSION['password-confirm']) < 8 || strlen($_SESSION['password-confirm']) > 20) {
  $errors['password-confirm']['length'] = '※パスワード確認用は8〜20文字で入力してください';
} elseif (!(preg_match("/^[a-zA-Z0-9]+$/", $_SESSION['password-confirm']))) {
  $errors['password-confirm']['character'] = '※パスワード確認用は半角英数字で入力してください';
}
if ($_SESSION['password'] != $_SESSION['password-confirm']) {
  $errors['password-confirm']['not-match'] = '※パスワードが一致しません';
}

// メールアドレス
// 正規表現
$regEmail = "/^[a-zA-Z0-9_+-]+(\.[a-zA-Z0-9_+-]+)*@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/";
if (empty($_SESSION['email'])) {
  $errors['email']['presence'] = '※メールアドレスは必須入力です';
} elseif (strlen($_SESSION['email']) > 200 && !(preg_match($regEmail, $_SESSION['email']))) {
  $errors['email']['max-length'] = '※メールアドレスは200文字以内で入力してください';
  $errors['email']['character'] = '入力されたメールアドレスが正しくありません';
} elseif (strlen($_SESSION['email']) > 200) {
  $errors['email']['max-length'] = '※メールアドレスは200文字以内で入力してください';
} elseif (!(preg_match($regEmail, $_SESSION['email']))) {
  $errors['email']['character'] = '入力されたメールアドレスが正しくありません';
}

// 値の挿入
if (empty($errors)) {
  $lastName = $_SESSION['last-name'];
  $firstName = $_SESSION['first-name'];
  $gender = $_SESSION['gender'];
  $prefecture = $_SESSION['prefecture'];
  $address = $_SESSION['address'];
  $password = $_SESSION['password'];
  $passwordConfirm = $_SESSION['password-confirm'];
  $email = $_SESSION['email'];
} else {
  $_SESSION['errors'] = $errors;
  header("location: member_regist.php");
}