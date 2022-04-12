<?php
require('thread_validation.php');

// ログインしていないか確認
if (!(isset($_SESSION['id']))) {
  header("location: index.php");
  exit;
}

// 二重登録防止トークンの発行
$token = uniqid('', true);
$_SESSION['token'] = $token;

$title = 'スレッド作成確認画面';
require('header.php');
require('db_connect.php');
?>
<div class="container">
  <h1>スレッド作成確認画面</h1>
  <form action="thread_registered.php" method="post">
    <table class="form">
      <tr class="title">
        <th>スレッド<br>タイトル</th>
        <td><?php echo $_SESSION['title'] ?></td>
      </tr>

      <tr class="content">
        <th>コメント</th>
        <td>
          <p><?php echo $_SESSION['content'] ?></p>
        </td>
      </tr>
    </table>

    <input type="hidden" name="token" value="<?php echo $token; ?>">
    <div class="submit">
      <button class="btn" type="submit">スレッドを作成する</button>
    </div>
    <div class="back">
      <button class="btn" type="button" onclick=history.back()>前に戻る</button>
    </div>
  </form>
</div>
<?php
require('footer.php');
?>