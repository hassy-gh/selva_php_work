<?php
$title = '会員登録フォーム';
require_once('header.php');
?>
<div class="form">
  <form action="member_register_confirm" method="POST">
    <h1>会員情報登録フォーム</h1>
    <div class="name">
      <span>氏名</span>
      <label>姓 <input name="last-name" type="text"></label>
      <label>名 <input name="first-name" type="text"></label>
    </div>

    <div class="gender">
      <span>性別</span>
      <label><input name="gender" type="radio"> 男性</label>
      <label><input name="gender" type="radio"> 女性</label>
    </div>

    <div class="address">
      <span>住所</span>
      <label>
        都道府県
        <select name="prefecture">
          <option value="">大阪</option>
          <option value="">東京</option>
        </select>
      </label>
      <br>
      <span>　　</span>
      <label>それ以降の住所 <input name="address" type="text"></label>
    </div>

    <div class="password">
      <span>パスワード</span><input name="password" type="password">
    </div>

    <div class="password">
      <span>パスワード確認</span><input name="password-confirm" type="password">
    </div>

    <div class="email">
      <span>メールアドレス</span><input name="email" type="email">
    </div>

    <input class="btn" type="submit" value="確認画面へ">
  </form>
</div>
<?php
require_once('footer.php')
?>