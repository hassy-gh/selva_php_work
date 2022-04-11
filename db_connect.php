<?php
class dbConnect
{
  public $host;
  public $dbName;
  public $user;
  public $pass;
  public $DSN;

  public function __construct()
  {
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
      // localhost
      $this->host = 'localhost';
      $this->dbName = 'selva_php_work';
      $this->user = 'root';
      $this->pass = 'root';
      $this->DSN = $this->retDSN($this->host, $this->dbName);
    } else {
      // 本番環境
      $this->host = 'os3-290-34592.vs.sakura.ne.jp';
      $this->dbName = 'selva_php_work';
      $this->user = 'root';
      $this->pass = 'Sh01074115';
      $this->DSN = $this->retDSN($this->host, $this->dbName);
    }
  }

  public function retDSN($host, $dbName)
  {
    return 'mysql:dbhost=' . $host . ';dbname=' . $dbName . ';charset=utf8';
  }
}
try {
  $objDBInfo = new dbConnect();
  $dbh = new PDO($objDBInfo->DSN, $objDBInfo->user, $objDBInfo->pass);
} catch (PDOException $e) {
  echo 'データベース接続エラー　：' . $e->getMessage();
}