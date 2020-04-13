<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');

// jsonを返して死ぬやつ
function sendResponse($obj)
{
    echo json_encode($obj);
    die();
}

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
}

// URLから各機能の文字列を取り出す
$requestUri = $_SERVER['REQUEST_URI'];
if (strpos($requestUri, '?') !== false) {
    $paramsExcludedUri = explode('?', $requestUri)['0'];
} else {
    $paramsExcludedUri = $requestUri;
}
$substrUri = substr($paramsExcludedUri, 9, strlen($paramsExcludedUri) - 9);
$method = $_SERVER['REQUEST_METHOD'];

// 各機能を呼び出す
if ($substrUri === 'sign_up') {
    require_once(dirname(__FILE__) . '/controllers/sign_up.php');
    signUp($db);
} elseif ($substrUri === 'sign_in') {
    require_once(dirname(__FILE__) . '/controllers/sign_in.php');
    signIn($db);
} elseif (strpos($substrUri, 'users') === 0) {
    require_once(dirname(__FILE__) . '/controllers/users.php');
    if (strpos($substrUri, 'timeline') !== false) {
        $id = substr($substrUri, 6, strlen($substrUri) - 6);
        timeline($db, $id);
    } else {
        $id = substr($substrUri, 6, strlen($substrUri) - 6);
        $substrUri = substr($substrUri, 0, 5);
        if ($method === 'GET') {
            userList($db);
        } elseif ($method === 'PUT') {
            editUser($db, $id);
        } elseif ($method === 'DELETE') {
            deleteUser($db, $id);
        }
    }
} elseif (strpos($substrUri, 'posts') === 0) {
    require_once(dirname(__FILE__) . '/controllers/posts.php');
    $id = substr($substrUri, 6, strlen($substrUri) - 6);
    $substrUri = substr($substrUri, 0, 5);
    if ($method === 'GET') {
        postList($db);
    } elseif ($method === 'POST') {
        submitPost($db);
    } elseif ($method === 'PUT') {
        editPost($db, $id);
    } elseif ($method === 'DELETE') {
        deletePost($db, $id);
    }
}
