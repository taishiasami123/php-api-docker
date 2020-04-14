<?php

require_once dirname(__FILE__) . '/Db.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');

// jsonを返して死ぬやつ
function sendResponse($obj)
{
    echo json_encode($obj);
    die();
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
    signUp();
} elseif ($substrUri === 'sign_in') {
    require_once(dirname(__FILE__) . '/controllers/sign_in.php');
    signIn();
} elseif (strpos($substrUri, 'users') === 0) {
    require_once(dirname(__FILE__) . '/controllers/users.php');
    if (strpos($substrUri, 'timeline') !== false) {
        $id = substr($substrUri, 6, strlen($substrUri) - 6);
        timeline($id);
    } else {
        $id = substr($substrUri, 6, strlen($substrUri) - 6);
        $substrUri = substr($substrUri, 0, 5);
        if ($method === 'GET') {
            userList();
        } elseif ($method === 'PUT') {
            editUser($id);
        } elseif ($method === 'DELETE') {
            deleteUser($id);
        }
    }
} elseif (strpos($substrUri, 'posts') === 0) {
    require_once(dirname(__FILE__) . '/controllers/posts.php');
    $id = substr($substrUri, 6, strlen($substrUri) - 6);
    $substrUri = substr($substrUri, 0, 5);
    if ($method === 'GET') {
        postList();
    } elseif ($method === 'POST') {
        submitPost();
    } elseif ($method === 'PUT') {
        editPost($id);
    } elseif ($method === 'DELETE') {
        deletePost($id);
    }
}
