<?php
// ---------- ユーザー一覧機能 ----------
function userList()
{
    // jsonを取得
    $header = getallheaders();
    $bearerToken = $header['Authorization'];
    $token = substr($bearerToken, 7, strlen($bearerToken) - 7);
    $page = $_GET['page'];
    $limit = $_GET['limit'];
    $keyword = $_GET['query'];

    // dbにtokenを探しに行く
    $selectUserByTokenFromUsersFetchAllResult = Db::selectUserByTokenFromUsersFetchAll($token);

    // tokenが見つからなかったらエラー吐く
    if ($selectUserByTokenFromUsersFetchAllResult === false || count($selectUserByTokenFromUsersFetchAllResult) === 0) {
        $errorMessage = 'tokenがおかしい';
        sendResponse($errorMessage, 401);
    }

    // tokenが見つかったらユーザー一覧引っ張る
    if ($keyword === '') {
        $selectUserFromUsersFetchAllResult = Db::selectAllUserFromUsersWithoutParamsFetchAllForPublic();
    } else {
        $searchKeyword = '%' . $keyword . '%';
        $selectUserFromUsersFetchAllResult = Db::selectAllUserFromUsersWithParamsFetchAllForPublic($searchKeyword);
    }

    // $page, $limitがブランクだった場合に値を代入
    if ($page === '') {
        $page = 1;
    }
    if ($limit === '') {
        $limit = 25;
    }

    // 返す配列の開始位置，終了位置を変数に代入
    $startPoint = $page * $limit - $limit;
    $endPoint = $page * $limit - 1;

    // 結果の配列を受け取る変数を作って，そいつを返す
    $returnResult = [];
    for ($i = $startPoint; $i <= $endPoint && $i < count($selectUserFromUsersFetchAllResult); $i++) {
        $returnResult[] = $selectUserFromUsersFetchAllResult[$i];
    }
    sendResponse($returnResult);
}





// ---------- ユーザー編集機能 ----------
function editUser($userId)
{
    // jsonを取得
    $header = getallheaders();
    $bearerToken = $header['Authorization'];
    $token = substr($bearerToken, 7, strlen($bearerToken) - 7);
    $json = file_get_contents('php://input');
    $params = json_decode($json, true)['user_params'];
    $name = $params['name'];
    $bio = $params['bio'];

    // dbにtokenを探しに行く
    $selectUserByTokenFromUsersFetchAllResult = Db::selectUserByTokenFromUsersFetchAll($token);

    // tokenが見つからなかったらエラー吐く
    if ($selectUserByTokenFromUsersFetchAllResult === false || count($selectUserByTokenFromUsersFetchAllResult) === 0) {
        $errorMessage = 'tokenがおかしい';
        sendResponse($errorMessage, 401);
    }

    // user idを取得する
    $userIdFromUsersTable = $selectUserByTokenFromUsersFetchAllResult[0]['id'];

    // 入力されたidとdbから拾ったidを比較して一致しなかったらエラー吐く
    if ($userId !== $userIdFromUsersTable) {
        $errorMessage = 'tokenがおかしい';
        sendResponse($errorMessage, 401);
    }

    // dbのnameとbioをupdateする
    if (Db::updateUserSetUsers($name, $bio, $userId) === false) {
        $errorMessage = 'ユーザー編集に失敗しました';
        sendResponse($errorMessage, 500);
    }

    // updateしたレコードを返却
    $selectUserAgainByTokenFromUsersFetchAllResult = Db::selectUserByTokenFromUsersFetchAll($token);
    sendResponse($selectUserAgainByTokenFromUsersFetchAllResult[0]);
}





// ---------- ユーザー削除機能 ----------
function deleteUser($userId)
{
    // jsonを取得
    $header = getallheaders();
    $bearerToken = $header['Authorization'];
    $token = substr($bearerToken, 7, strlen($bearerToken) - 7);

    // dbにtokenを探しに行く
    $selectUserByTokenFromUsersFetchAllResult = Db::selectUserByTokenFromUsersFetchAll($token);

    // tokenが見つからなかったらエラー吐く
    if ($selectUserByTokenFromUsersFetchAllResult === false || count($selectUserByTokenFromUsersFetchAllResult) === 0) {
        $errorMessage = 'tokenがおかしい';
        sendResponse($errorMessage, 401);
    }

    // user idを取得する
    $userIdFromUsersTable = $selectUserByTokenFromUsersFetchAllResult[0]['id'];

    // 入力されたidとdbから拾ったidを比較して一致しなかったらエラー吐く
    if ($userId !== $userIdFromUsersTable) {
        $errorMessage = 'tokenがおかしい';
        sendResponse($errorMessage, 401);
    }

    // ユーザー削除
    if (Db::deleteUserFromUsers($userId) === false) {
        $errorMessage = 'User削除に失敗しました';
        sendResponse($errorMessage, 500);
    }

    $message = '正常にUser削除されました';
    sendResponse($message);
}





// ---------- タイムライン機能 ----------
function timeline($userId)
{
    // jsonを取得
    $header = getallheaders();
    $bearerToken = $header['Authorization'];
    $token = substr($bearerToken, 7, strlen($bearerToken) - 7);
    $page = $_GET['page'];
    $limit = $_GET['limit'];
    $keyword = $_GET['query'];

    // dbにtokenを探しに行く
    $selectUserByTokenFromUsersFetchAllResult = Db::selectUserByTokenFromUsersFetchAll($token);

    // tokenが見つからなかったらエラー吐く
    if ($selectUserByTokenFromUsersFetchAllResult === false || count($selectUserByTokenFromUsersFetchAllResult) === 0) {
        $errorMessage = 'tokenがおかしい';
        sendResponse($errorMessage, 401);
    }

    // tokenが見つかったら投稿一覧引っ張る
    if ($keyword === '') {
        $selectPostByUserIdFromPostsFetchAllResult = Db::selectPostByUserIdFromPostsWithoutParamsFetchAllForPublic($userId);
    } else {
        $searchKeyword = '%' . $keyword . '%';
        $selectPostByUserIdFromPostsFetchAllResult = Db::selectPostByUserIdFromPostsWithParamsFetchAllForPublic($userId, $searchKeyword);
    }

    // usersテーブル全体を一旦引っ張る
    $selectUserFromUsersFetchAllResult = Db::selectAllUserFromUsersFetchAll();

    // usersテーブルのidを検索する
    foreach ($selectPostByUserIdFromPostsFetchAllResult as &$post) {
        foreach ($selectUserFromUsersFetchAllResult as $user) {
            if ($user['id'] === $post['user_id']) {
                unset($post['user_id'], $user['email'], $user['password'], $user['token']);
                $post['user'] = $user;
                break;
            }
        }
    }

    // $page, $limitがブランクだった場合に値を代入
    if ($page === '') {
        $page = 1;
    }
    if ($limit === '') {
        $limit = 25;
    }

    // 返す配列の開始位置，終了位置を変数に代入
    $startPoint = $page * $limit - $limit;
    $endPoint = $page * $limit - 1;

    // 結果の配列を受け取る変数を作って，そいつを返す
    $returnResult = [];
    for ($i = $startPoint; $i <= $endPoint && $i < count($selectPostByUserIdFromPostsFetchAllResult); $i++) {
        $returnResult[] = $selectPostByUserIdFromPostsFetchAllResult[$i];
    }
    sendResponse($returnResult);
}
