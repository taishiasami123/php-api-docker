<?php
// dbに接続する
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

    // sqlのprepareとbindValueを実行する関数
    public static function prepareAndBindValue(string $sql, array $params = [])
    {
        $stmt = Db::getPdo()->prepare($sql);
        if (!empty($params)) {
            // パラメタがある
            foreach ($params as $key => $ary) {
                $value = $ary[0];
                $type = $ary[1];
                $stmt->bindValue($key, $value, $type);
            }
        }
        return $stmt;
    }

    // sqlのexecuteを実行し結果を返す関数
    public static function execute(string $sql, array $params = [])
    {
        $stmt = self::prepareAndBindValue($sql, $params);
        return $stmt->execute();
    }

    // sqlのexecuteとfetchAllを実行しfetchAllの結果を返す関数
    public static function executeAndFetchAll(string $sql, array $params = [])
    {
        $stmt = self::prepareAndBindValue($sql, $params);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }





    // selectUser系，特定のユーザーを取得するもの
    // emailに合致するユーザを取得する
    public static function selectUserByEmailFetchAll(string $email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $params = [
            ':email' => [$email, PDO::PARAM_STR],
        ];
        return Db::executeAndFetchAll($sql, $params);
    }

    // tokenに合致するユーザーを取得する
    public static function selectUserByTokenFetchAll(string $token)
    {
        $sql = 'SELECT * FROM users WHERE token = :token';
        $params = [
            ':token' => [$token, PDO::PARAM_STR],
        ];
        return Db::executeAndFetchAll($sql, $params);
    }

    // insertされたuserのidに合致するuserを再度取得する
    public static function selectUserByInsertedUserIdFetchAll(int $insertedUserId)
    {
        $sql = 'SELECT id, name, bio, created_at, updated_at FROM users WHERE id = :id';
        $params = [
            'id' => [$insertedUserId, PDO::PARAM_INT],
        ];
        return Db::executeAndFetchAll($sql, $params);
    }

    // updateされたuserのidに合致するuserを再度取得する
    public static function selectUserBySelectedIdFetchAll($selectedId)
    {
        $sql = 'SELECT id, name, bio, created_at, updated_at FROM users WHERE id = :id';
        $params = [
            ':id' => [$selectedId, PDO::PARAM_INT],
        ];
        return Db::executeAndFetchAll($sql, $params);
    }





    // selectUser系，全てのユーザーを取得するもの
    // 全userを取得する
    public static function selectAllUserFetchAll()
    {
        $sql = 'SELECT * FROM users';
        return Db::executeAndFetchAll($sql);
    }

    // 更新順でユーザー一覧を取得する
    public static function selectAllUserWithoutParamsFetchAll()
    {
        $sql = 'SELECT id, name, bio, created_at, updated_at FROM users ORDER BY updated_at DESC';
        return Db::executeAndFetchAll($sql);
    }

    // 更新順でユーザー一覧を取得する(キーワード検索)
    public static function selectAllUserWithParamsFetchAll(string $searchKeyword)
    {
        $sql = 'SELECT id, name, bio, created_at, updated_at FROM users WHERE name LIKE :searchKeyword OR bio LIKE :searchKeyword ORDER BY updated_at DESC';
        $params = [
            ':searchKeyword' => [$searchKeyword, PDO::PARAM_STR],
        ];
        return Db::executeAndFetchAll($sql, $params);
    }





    // selectPost系
    // 全postを更新順で取得する
    public static function selectAllPostWithoutParamsFetchAll()
    {
        $sql = 'SELECT id, text, user_id, created_at, updated_at FROM posts ORDER BY updated_at DESC';
        return Db::executeAndFetchAll($sql);
    }

    // 全postを更新順で取得する(キーワード検索)
    public static function selectAllPostWithParamsFetchAll(string $searchKeyword)
    {
        $sql = 'SELECT id, text, user_id, created_at, updated_at FROM posts WHERE text LIKE :searchKeyword ORDER BY updated_at DESC';
        $params = [
            'searchKeyword' => [$searchKeyword, PDO::PARAM_STR],
        ];
        return Db::executeAndFetchAll($sql, $params);
    }

    // ユーザーIDに合致するカラムをpostsテーブルから取得する
    public static function selectPostByUserIdWithoutParamsFetchAll(int $id)
    {
        $sql = 'SELECT * FROM posts WHERE user_id = :userId ORDER BY updated_at DESC';
        $params = [
            ':userId' => [$id, PDO::PARAM_INT],
        ];
        return Db::executeAndFetchAll($sql, $params);
    }

    // ユーザーIDに合致するカラムをpostsテーブルから取得する(キーワード検索)
    public static function selectPostByUserIdWithParamsFetchAll(int $id, string $searchKeyword)
    {
        $sql = 'SELECT * FROM posts WHERE user_id = :userId AND text LIKE :searchKeyword ORDER BY updated_at DESC';
        $params = [
            ':userId' => [$id, PDO::PARAM_INT],
            ':searchKeyword' => [$searchKeyword, PDO::PARAM_STR],
        ];
        return Db::executeAndFetchAll($sql, $params);
    }

    // insertされたpostのidに合致するpostを再度取得する
    public static function selectPostByInsertedIdFetchAllResult(int $insertedId)
    {
        $sql = 'SELECT * FROM posts WHERE id = :id';
        $params = [
            'id' => [$insertedId, PDO::PARAM_INT],
        ];
        return Db::executeAndFetchAll($sql, $params);
    }

    // idに合致するuser_idを持つカラムを選択する
    public static function selectUserByIdFetchAll(int $id)
    {
        $sql = 'SELECT user_id FROM posts WHERE id = :id';
        $params = [
            ':id' => [$id, PDO::PARAM_INT],
        ];
        return Db::executeAndFetchAll($sql, $params);
    }

    // updateされたpostを再度取得する
    public static function selectPostByUpdatedPostIdFetchAll(int $id)
    {
        $sql = 'SELECT * FROM posts WHERE id = :id';
        $params = [
            ':id' => [$id, PDO::PARAM_INT],
        ];
        return Db::executeAndFetchAll($sql, $params);
    }





    // 機能系
    // 新規登録
    public static function insertUserDB(string $name, string $bio, string $email, string $password, string $token)
    {
        $sql = 'INSERT INTO users SET name = :name, bio = :bio, email = :email, password = :password, token = :token, created_at = NOW()';
        $params = [
            ':name' => [$name, PDO::PARAM_STR],
            ':bio' => [$bio, PDO::PARAM_STR],
            ':email' => [$email, PDO::PARAM_STR],
            ':password' => [$password, PDO::PARAM_STR],
            ':token' => [$token, PDO::PARAM_STR],
        ];
        return Db::execute($sql, $params);
    }

    // ユーザー編集
    public static function updateUserDB(string $name, string $bio, int $id)
    {
        $sql = 'UPDATE users SET name = :name, bio = :bio WHERE id = :id';
        $params = [
            ':name' => [$name, PDO::PARAM_STR],
            ':bio' => [$bio, PDO::PARAM_STR],
            ':id' => [$id, PDO::PARAM_STR],
        ];
        return Db::execute($sql, $params);
    }

    // ユーザー削除
    public static function deleteUserDB(int $id)
    {
        $sql = 'DELETE FROM users WHERE id = :id';
        $params = [
            ':id' => [$id, PDO::PARAM_STR],
        ];
        return Db::execute($sql, $params);
    }

    // 投稿作成
    public static function insertPostDB(string $text, int $selectedId)
    {
        $sql = 'INSERT INTO posts SET text = :text, user_id = :userId, created_at = NOW()';
        $params = [
            ':text' => [$text, PDO::PARAM_STR],
            ':userId' => [$selectedId, PDO::PARAM_INT],
        ];
        return Db::execute($sql, $params);
    }

    // 投稿編集
    public static function updatePostDb(string $text, int $id)
    {
        $sql = 'UPDATE posts SET text = :text WHERE id = :id';
        $params = [
            ':text' => [$text, PDO::PARAM_STR],
            ':id' => [$id, PDO::PARAM_INT],
        ];
        return Db::execute($sql, $params);
    }

    // 投稿削除
    public static function deletePostDB($id)
    {
        $sql = 'DELETE FROM posts WHERE id = :id';
        $params = [
            ':id' => [$id, PDO::PARAM_INT],
        ];
        return Db::execute($sql, $params, false);
    }
}
