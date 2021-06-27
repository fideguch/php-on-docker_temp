<?php

include('../app/_parts/_header.php');

class Post {
  function show() {
    printf("%d | %s | %s" . nl2br("\n"), $this->id, $this->column_char, $this->column_int);
  }
}

try {
  $dsn      = "mysql:dbname=" . getenv('MYSQL_DATABASE') . ";host=db;port=3306;";
  $user     = "root";
  $password = getenv('MYSQL_ROOT_PASSWORD');
  $pdo = new PDO(
    $dsn,
    $user,
    $password,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]
  );

  $pdo->query("DROP TABLE IF EXISTS t_table");

  $pdo->query("
    CREATE TABLE t_table (
      id INT NOT NULL AUTO_INCREMENT,
      column_char VARCHAR(140),
      column_int INT,
    PRIMARY KEY
      (id)
    )
  ");

  $pdo->query("
    INSERT INTO
      t_table (column_char, column_int)
    VALUES
      ('hoge', 12),
      ('hogehoge', 0),
      ('test', -1)
  ");

  $dbName = $pdo->query("SELECT database()");
  $tableName = $pdo->query("SHOW tables WHERE Tables_in_my_db='t_table'");
  printf("Connected to DB( %s ) and all rows in table( %s )".nl2br("\n"),
    $dbName->fetch()['database()'],
    $tableName->fetch()['Tables_in_my_db']
  );

  $stmt = $pdo->query("SELECT * FROM t_table");
  $rows = $stmt->fetchALL(PDO::FETCH_CLASS, 'Post');
  echo "id | column_char | column_int".nl2br("\n");
  foreach ($rows AS $row):
    $row->show();
  endforeach;
} catch (PDOException $e) {
  echo "error!".nl2br(PHP_EOL);
  echo $e->getMessage() . PHP_EOL;
  exit;
}
?>

<?php

include('../app/_parts/_footer.php');
