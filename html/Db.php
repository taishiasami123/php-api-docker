<?php
// DBに接続する
class Db
{
  private static $pdo = null;
  public static function getPdo()
  {
    if (self::$pdo === null) {
      try {
        self::$pdo = new PDO('mysql:dbname=sns_api;host=mysql;charset=utf8', 'root', 'root');
      } catch (PDOException $e) {
        echo json_encode('DB接続エラー:' . $e->getMessage());
        die();
      }
    }
    return self::$pdo;
  }

  /**
   * sqlを実行するラッパー関数
   */
  public static function prepareAndExecute(string $sql, array $params = null, bool $isUpdate = false)
  {
    $stmt = Db::getPdo()->prepare($sql);
    if (isset($params)) {
      /* パラメタがある */
      foreach ($params as $key => $ary) {
        $value = $ary[0];
        $type = $ary[1];
        $stmt->bindValue($key, $value, $type);
      }
    }
    $result = $stmt->execute();
    if ($isUpdate) {
      /* 更新系 */
      return $result;
    }
    /* 参照系 */
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * emailに合致するユーザを取得する
   */
  public static function selectUserByEmailFetchAll(string $email)
  {
    $sql = "SELECT * FROM users WHERE email = :email";
    $params = [
      ':email' => [$email, PDO::PARAM_STR],
    ];
    return Db::prepareAndExecute($sql, $params);
  }

  /**
   * サインアップ。新規登録
   * passwordはhash化してから渡すこと
   */
  public static function insertUser(string $name, string $bio, string $email, string $password, string $token)
  {
    $sql = 'INSERT INTO users SET name = :name, bio = :bio, email = :email, password = :password, token = :token, created_at = NOW()';
    $params = [
      ':name' => [$name, PDO::PARAM_STR],
      ':bio' => [$bio, PDO::PARAM_STR],
      ':email' => [$email, PDO::PARAM_STR],
      ':password' => [$password, PDO::PARAM_STR],
      ':token' => [$token, PDO::PARAM_STR],
    ];
    return Db::prepareAndExecute($sql, $params, true);
  }
}
