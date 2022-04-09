<?php
require('member_validation.php');
$title = "会員情報確認";
require('header.php');
?>
<div class="container">
  <h1>会員情報確認画面</h1>
  <form action="member_registered.php" method="post">
    <table class="form">
      <tr class="name">
        <th>氏名</th>
        <td><?php echo "{$lastName}　{$firstName}" ?></td>
      </tr>

      <tr class="gender">
        <th>性別</th>
        <td><?php echo $gender ?></td>
      </tr>

      <tr class="address">
        <th>住所</th>
        <td><?php echo "{$prefecture}{$address}" ?></td>
      </tr>

      <tr class="password">
        <th>パスワード</th>
        <td>セキュリティのため非表示</td>
      </tr>

      <tr class="email">
        <th>メールアドレス</th>
        <td><?php echo $email ?></td>
      </tr>
    </table>
    <div class="submit">
      <button class="btn" type="submit">登録完了</button>
    </div>
    <div class="back">
      <button class="btn" type="button" onclick=history.back()>前に戻る</button>
    </div>
  </form>
</div>
<?php
require('footer.php')
?>