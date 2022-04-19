<?php
session_start();

// 管理者としてログインしていない場合
if (!isset($_SESSION['admin_id'])) {
  header("location: login.php");
  exit;
}

if (!$_REQUEST['page']) {
  $_SESSION['toggle'] = '';
  $_SESSION['sql'] = '';
}

require('../prefectures.php');
require('../db_connect.php');

// データの取得
// メンバー一覧
$sql = "SELECT * FROM members WHERE 1";
if ($_SESSION['sql']) {
  $sql = $_SESSION['sql'];
}
if ($_POST['sql']) {
  $sql = $_POST['sql'];
}


// 検索機能
if ($_POST['id']) {
  $_SESSION['member_id'] = $_POST['id'];
  $sql .= " AND id = :id";
}
if (isset($_POST['gender'])) {
  $_SESSION['gender'] = $_POST['gender'];
  if (array_key_exists('man', $_SESSION['gender']) && array_key_exists('woman', $_SESSION['gender'])) {
    $sql .= " AND (gender = 1 OR gender = 2)";
  } elseif (array_key_exists('man', $_SESSION['gender'])) {
    $sql .= " AND gender = 1";
  } elseif (array_key_exists('woman', $_SESSION['gender'])) {
    $sql .= " AND gender = 2";
  }
}
if ($_POST['pref_name']) {
  $_SESSION['pref_name'] = $_POST['pref_name'];
  $sql .= " AND pref_name = :pref_name";
}
if ($_POST['free_word']) {
  $_SESSION['free_word'] = $_POST['free_word'];
  $sql .= " AND (name_sei LIKE :free_word OR name_mei LIKE :free_word OR email LIKE :free_word)";
}

// 並べ替え機能
if (isset($_POST['toggle'])) {
  if ($_POST['toggle'] == '0') {
    $_SESSION['toggle'] = 'asc';
  } else {
    $_SESSION['toggle'] = 'desc';
  }
}

$_SESSION['sql'] = $sql;

if ($_REQUEST['page']) {
  if ($_SESSION['toggle'] == 'asc' || $_SESSION['toggle'] == '') {
    $stmt = $dbh->prepare($_SESSION['sql'] . " ORDER BY id DESC");
  } else {
    $stmt = $dbh->prepare($_SESSION['sql'] . " ORDER BY id ASC");
  }
} else {
  if ($_SESSION['toggle'] == 'asc' || $_SESSION['toggle'] == '') {
    $stmt = $dbh->prepare($sql . " ORDER BY id DESC");
  } else {
    $stmt = $dbh->prepare($sql . " ORDER BY id ASC");
  }
}

if ($_SESSION['sql'] && strpos($_SESSION['sql'], ':id') !== false) {
  $stmt->bindParam(':id', $_SESSION['member_id'], PDO::PARAM_INT);
} elseif ($_POST['id']) {
  $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
}
if ($_SESSION['sql'] && strpos($_SESSION['sql'], ':pref_name') !== false) {
  $stmt->bindParam(':pref_name', $_SESSION['pref_name'], PDO::PARAM_STR);
} elseif ($_POST['pref_name']) {
  $stmt->bindParam(':pref_name', $_POST['pref_name'], PDO::PARAM_STR);
}
if ($_SESSION['sql'] && strpos($_SESSION['sql'], ':free_word') !== false) {
  $free_word = '%' . $_SESSION['free_word'] . '%';
  $stmt->bindParam(':free_word', $free_word, PDO::PARAM_STR);
} elseif ($_POST['free_word']) {
  $free_word = '%' . $_POST['free_word'] . '%';
  $stmt->bindParam(':free_word', $free_word, PDO::PARAM_STR);
}
$stmt->execute();
$members = array();
$memberCount = 0;
while ($row = $stmt->fetch()) {
  if (is_null($row['deleted_at'])) {
    $members[] = $row;
    $memberCount += 1;
  }
}

// ページング

if (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) {
  $page = $_REQUEST['page'];
} else {
  $page = 1;
}
$maxPage = ceil($memberCount / 10);
if ($page == 1 || $page == $maxPage) {
  $range = 2;
} elseif ($page == 2 || $page == $maxPage - 1) {
  $range = 1;
} else {
  $range = 0;
}
$startNo = ($page - 1) * 10;
$displayData = array_slice($members, $startNo, 10, true);

$path = '.';
$title = '会員一覧ページ';
require('../header.php');
?>
<header class="admin-header">
  <div class="header-left">
    <h3>会員一覧</h3>
  </div>
  <div class="header-right">
    <a class="btn" href="index.php">トップへ戻る</a>
  </div>
</header>

<div class="container">
  <div class="submit member-regist">
    <a href="member_regist.php" class="btn">会員登録</a>
  </div>
  <form action="member.php" method="post" class="member-search">
    <table class="form" border="1">
      <tr>
        <th>ID</th>
        <td><input name="id" type="text"></td>
      </tr>

      <tr>
        <th>性別</th>
        <td>
          <label><input name="gender[man]" type="checkbox" value="1"> 男性</label>
          <label><input name="gender[woman]" type="checkbox" value="2"> 女性</label>
        </td>
      </tr>

      <tr>
        <th>都道府県</th>
        <td>
          <select name="pref_name">
            <option hidden value="">選択してください</option>
            <?php foreach ($prefectures as $pref) : ?>
            <option value="<?php echo $pref ?>"><?php echo $pref ?></option>
            <?php endforeach ?>
          </select>
        </td>
      </tr>

      <tr>
        <th>フリーワード</th>
        <td><input name="free_word" type="text"></td>
      </tr>
    </table>

    <div class="submit">
      <button class="btn" type="submit">検索する</button>
    </div>
  </form>

  <div class="members-wrapper">
    <table class="members" border="1">
      <thead>
        <tr>
          <th class="id">
            ID
            <form action="" method="post" class="toggle">
              <?php if ($_SESSION['toggle'] == 'desc') : ?>
              <input name="toggle" type="hidden" value="0">
              <input name="sql" type="hidden" value="<?php echo $sql ?>">
              <button type="submit">
                <i class="toggle-btn fas fa-chevron-up"></i>
              </button>
              <?php else : ?>
              <input name="toggle" type="hidden" value="1">
              <input name="sql" type="hidden" value="<?php echo $sql ?>">
              <button type="submit">
                <i class="toggle-btn fas fa-chevron-down"></i>
              </button>
              <?php endif ?>
            </form>
          </th>
          <th class="name">氏名</th>
          <th class="gender">性別</th>
          <th class="address">住所</th>
          <th class="created-at">
            登録日時
            <form action="" method="post" class="toggle">
              <?php if ($_SESSION['toggle'] == 'desc') : ?>
              <input name="toggle" type="hidden" value="0">
              <input name="sql" type="hidden" value="<?php echo $sql ?>">
              <button type="submit">
                <i class="toggle-btn fas fa-chevron-up"></i>
              </button>
              <?php else : ?>
              <input name="toggle" type="hidden" value="1">
              <input name="sql" type="hidden" value="<?php echo $sql ?>">
              <button type="submit">
                <i class="toggle-btn fas fa-chevron-down"></i>
              </button>
              <?php endif ?>
            </form>
          </th>
          <th class="edit">編集</th>
          <th class="detail">詳細</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($displayData as $member) : ?>
        <tr>
          <td class="id"><?php echo htmlspecialchars($member['id']) ?></td>
          <td class="name">
            <a
              href="member_detail.php?id=<?php echo $member['id'] ?>"><?php echo "{$member['name_sei']} {$member['name_mei']}" ?></a>
          </td>
          <td class="gender"><?php echo $member['gender'] == '1' ? '男性' : '女性' ?></td>
          <td class="address">
            <?php echo "{$member['pref_name']}{$member['address']}" ?>
          </td>
          <td class="created-at">
            <?php echo date('Y/n/d', strtotime($member['created_at'])) ?>
          </td>
          <td class="edit">
            <a href="member_edit.php?id=<?php echo $member['id'] ?>">編集</a>
          </td>
          <td class="detail">
            <a href="member_detail.php?id=<?php echo $member['id'] ?>">詳細</a>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>

    <?php if ($memberCount > 10) : ?>
    <div class="member-pagination">
      <?php if ($page >= 2) : ?>
      <div class="page-left">
        <a href="member.php?page=<?php echo ($page - 1) ?>" class="page-btn pref">＜前へ</a>
      </div>
      <?php endif ?>
      <div class="paging">
        <?php for ($i = 1; $i <= $maxPage; $i++) : ?>
        <?php if ($i >= $page - $range && $i <= $page + $range) : ?>
        <?php if ($i == $page) : ?>
        <span class="now-page"><?php echo $i ?></span>
        <?php else : ?>
        <a href="member.php?page=<?php echo $i ?>" class="page-number"><?php echo $i ?></a>
        <?php endif ?>
        <?php endif ?>
        <?php endfor ?>
      </div>
      <?php if ($page < $maxPage) : ?>
      <div class="page-right">
        <a href="member.php?page=<?php echo ($page + 1) ?>" class="page-btn next">次へ＞</a>
      </div>
      <?php endif ?>
    </div>
    <?php endif ?>
  </div>
</div>
<?php
require('../footer.php');
$_SESSION['member_id'] = '';
$_SESSION['name_sei'] = '';
$_SESSION['name_mei'] = '';
$_SESSION['gender'] = '';
$_SESSION['pref_name'] = '';
$_SESSION['address'] = '';
$_SESSION['password'] = '';
$_SESSION['password_confirm'] = '';
$_SESSION['email'] = '';
$_SESSION['errors'] = '';
?>