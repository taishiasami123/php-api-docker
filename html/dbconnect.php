<?php
  try {
    $db = new PDO('mysql:dbname=sns_api;host=mysql;charset=utf8', 'root', 'root');
  } catch (PDOException $e) {
    echo json_encode('DB接続エラー:' . $e->getMessage());
    die();
  }
?>