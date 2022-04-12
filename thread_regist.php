<?php
session_start();

// ログインしていないか確認
if (!(isset($_SESSION['id']))) {
  header("location: index.php");
  exit;
}

// エラーがないか確認
if (isset($_SESSION['errors'])) {
  $errors = $_SESSION['errors'];
}

$title = 'スレッド作成フォーム';
require('header.php');
?>
<div class="container">
  <h1>スレッド作成フォーム</h1>
  <form action="thread_register_confirm.php" method="post">
    <table class="form">
      <tr>
        <th>スレッド<br>タイトル</th>
        <td><input name="title" type="text" value="<?php if (isset($_SESSION['title'])) {
                                                      echo $_SESSION['title'];
                                                    } ?>"></td>
      </tr>
      <?php if (isset($errors['title'])) : ?>
      <tr class="error-messages">
        <th></th>
        <td>
          <?php foreach ($errors['title'] as $error) : ?>
          <p><?php echo $error ?></p>
          <?php endforeach ?>
        </td>
      </tr>
      <?php endif ?>

      <tr>
        <th>コメント</th>
        <td>
          <textarea name="content" cols="60" rows="10"><?php if (isset($_SESSION['content'])) {
                                                          echo $_SESSION['content'];
                                                        } ?></textarea>
        </td>
      </tr>
      <?php if (isset($errors['content'])) : ?>
      <tr class="error-messages">
        <th></th>
        <td>
          <?php foreach ($errors['content'] as $error) : ?>
          <p><?php echo $error ?></p>
          <?php endforeach ?>
        </td>
      </tr>
      <?php endif ?>
    </table>

    <input type="hidden" name="submit">
    <div class="submit">
      <button class="btn" type="submit">確認画面へ</button>
    </div>
    <div class="back">
      <a class="btn" href="index.php">トップに戻る</a>
    </div>
  </form>
</div>
<?php
require('footer.php');
$_SESSION['title'] = '';
$_SESSION['content'] = '';
$_SESSION['errors'] = array();
?>