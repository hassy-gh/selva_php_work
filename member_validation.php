<?php
session_start();
require('db_connect.php');
require('prefectures.php');

// 値の取得
$_SESSION['name_sei'] = $_POST['name_sei'];
$_SESSION['name_mei'] = $_POST['name_mei'];
$_SESSION['gender'] = $_POST['gender'];
$_SESSION['pref_name'] = $_POST['pref_name'];
$_SESSION['address'] = $_POST['address'];
$_SESSION['password'] = $_POST['password'];
$_SESSION['password-confirm'] = $_POST['password-confirm'];
$_SESSION['email'] = $_POST['email'];

// バリデーション
$errors = array();
// 氏名(姓)
if (empty($_SESSION['name_sei'])) {
  $errors['name_sei']['presence'] = '※氏名（姓）は必須入力です';
} elseif (mb_strlen($_SESSION['name_sei']) > 20) {
  $errors['name_sei']['max-length'] = '※氏名(姓)は20文字以内で入力してください';
}

// 氏名(名)
if (empty($_SESSION['name_mei'])) {
  $errors['name_mei']['presence'] = '※氏名（名）は必須入力です';
} elseif (mb_strlen($_SESSION['name_mei']) > 20) {
  $errors['name_mei']['max-length'] = '※氏名(名)は20文字以内で入力してください';
}

// 性別
if (empty($_SESSION['gender'])) {
  $errors['gender']['presence'] = '※性別は必須入力です';
} elseif (!($_SESSION['gender'] == 1 || $_SESSION['gender'] == 2)) {
  $errors['gender']['wrong-gender'] = '※入力された性別が正しくありません';
}

// 住所（都道府県）
if ($_SESSION['pref_name'] == 'blank') {
  $errors['pref_name']['presence'] = '※住所（都道府県）は必須入力です';
} elseif (!(in_array($_SESSION['pref_name'], $prefectures))) {
  $errors['pref_name']['wrong-prefecture'] = '※入力された都道府県が正しくありません';
}

// 住所(それ以降の住所)
if (mb_strlen($_SESSION['address']) > 100) {
  $errors['address']['max-length'] = '※住所(それ以降の住所)は100文字以内で入力してください';
}

// パスワード
if (empty($_SESSION['password'])) {
  $errors['password']['presence'] = '※パスワードは必須入力です';
} elseif ((mb_strlen($_SESSION['password']) < 8 || mb_strlen($_SESSION['password']) > 20) && (!(preg_match(
  "/^[a-zA-Z0-9]+$/",
  $_SESSION['password']
)))) {
  $errors['password']['length'] = '※パスワードは8〜20文字で入力してください';
  $errors['password']['character'] = '※パスワードは半角英数字で入力してください';
} elseif (mb_strlen($_SESSION['password']) < 8 || mb_strlen($_SESSION['password']) > 20) {
  $errors['password']['length'] = '※パスワードは8〜20文字で入力してください';
} elseif (!(preg_match("/^[a-zA-Z0-9]+$/", $_SESSION['password']))) {
  $errors['password']['character'] = '※パスワードは半角英数字で入力してください';
}

// パスワード確認
if (empty($_SESSION['password-confirm'])) {
  $errors['password-confirm']['presence'] = '※パスワード確認用は必須入力です';
} elseif ((mb_strlen($_SESSION['password-confirm']) < 8 || mb_strlen($_SESSION['password-confirm']) > 20) &&
  (!(preg_match("/^[a-zA-Z0-9]+$/", $_SESSION['password-confirm'])))
) {
  $errors['password-confirm']['length'] = '※パスワード確認用は8〜20文字で入力してください';
  $errors['password-confirm']['character'] = '※パスワード確認用は半角英数字で入力してください';
} elseif (mb_strlen($_SESSION['password-confirm']) < 8 || mb_strlen($_SESSION['password-confirm']) > 20) {
  $errors['password-confirm']['length'] = '※パスワード確認用は8〜20文字で入力してください';
} elseif (!(preg_match("/^[a-zA-Z0-9]+$/", $_SESSION['password-confirm']))) {
  $errors['password-confirm']['character'] = '※パスワード確認用は半角英数字で入力してください';
}
if ($_SESSION['password'] != $_SESSION['password-confirm']) {
  $errors['password-confirm']['not-match'] = '※パスワードが一致しません';
}

// メールアドレス
// 正規表現
$member = $dbh->prepare('SELECT COUNT(*) AS count FROM members WHERE email=?');
$member->execute(array(
  $_POST['email']
));
$record = $member->fetch();
if ($record['count'] > 0) {
  $errors['email']['duplicate'] = '※すでにメールアドレスが存在します';
}
$regEmail = "/^[a-zA-Z0-9_+-]+(\.[a-zA-Z0-9_+-]+)*@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/";
if (empty($_SESSION['email'])) {
  $errors['email']['presence'] = '※メールアドレスは必須入力です';
} elseif (mb_strlen($_SESSION['email']) > 200 && !(preg_match($regEmail, $_SESSION['email']))) {
  $errors['email']['max-length'] = '※メールアドレスは200文字以内で入力してください';
  $errors['email']['character'] = '入力されたメールアドレスが正しくありません';
} elseif (mb_strlen($_SESSION['email']) > 200) {
  $errors['email']['max-length'] = '※メールアドレスは200文字以内で入力してください';
} elseif (!(preg_match($regEmail, $_SESSION['email']))) {
  $errors['email']['character'] = '入力されたメールアドレスが正しくありません';
}


// 値の挿入
if (empty($errors)) {
  $nameSei = $_SESSION['name_sei'];
  $nameMei = $_SESSION['name_mei'];
  $gender = $_SESSION['gender'];
  $prefName = $_SESSION['prefName'];
  $address = $_SESSION['address'];
  $password = $_SESSION['password'];
  $passwordConfirm = $_SESSION['password-confirm'];
  $email = $_SESSION['email'];
} else {
  $_SESSION['errors'] = $errors;
  header("location: member_regist.php");
}