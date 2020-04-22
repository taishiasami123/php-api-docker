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
    $selectUserByTokenFetchAllResult = Db::selectUserByTokenFetchAll($token);
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errorMessage = 'tokenがおかしい';
        sendResponse($errorMessage);
    }

    // tokenが見つかったらユーザー一覧引っ張る
    if ($keyword === '') {
        $selectUserFetchAllResult = Db::selectAllUserWithoutParamsFetchAll();
    } else {
        $searchKeyword = '%' . $keyword . '%';
        $selectUserFetchAllResult = Db::selectAllUserWithParamsFetchAll($searchKeyword);
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
    for ($i = $startPoint; $i <= $endPoint && $i < count($selectUserFetchAllResult); $i++) {
        $returnResult[] = $selectUserFetchAllResult[$i];
    }
    sendResponse($returnResult);
}





// ---------- ユーザー編集機能 ----------
function editUser($id)
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
    $selectUserByTokenFetchAllResult = Db::selectUserByTokenFetchAll($token);
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];
    $selectedId = $selectUserByTokenFetchAllResult[0]['id'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errorMessage = 'tokenがおかしい';
        sendResponse($errorMessage);
    }

    // 入力されたidとdbから拾ったidを比較して一致しなかったらエラー吐く
    if ($id !== $selectedId) {
        $errorMessage = '自分のユーザーじゃないよ!';
        sendResponse($errorMessage);
    }

    // dbのnameとbioをupdateする
    Db::updateUserDB($name, $bio, $id);

    // updateしたレコードを返却
    $selectUserAgainByIdFetchAllResult = Db::selectUserByTokenFetchAll($token);
    sendResponse($selectUserAgainByIdFetchAllResult[0]);
}





// ---------- ユーザー削除機能 ----------
function deleteUser($id)
{
    // jsonを取得
    $header = getallheaders();
    $bearerToken = $header['Authorization'];
    $token = substr($bearerToken, 7, strlen($bearerToken) - 7);

    // dbにtokenを探しに行く
    $selectUserByTokenFetchAllResult = Db::selectUserByTokenFetchAll($token);
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];
    $selectedId = $selectUserByTokenFetchAllResult[0]['id'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errorMessage = 'tokenがおかしい';
        sendResponse($errorMessage);
    }

    // 入力されたidとdbから拾ったidを比較して一致しなかったらエラー吐く
    if ($id !== $selectedId) {
        $errorMessage = '自分のユーザーじゃないよ!';
        sendResponse($errorMessage);
    }

    // ユーザー削除
    Db::deleteUserDB($id);
    $message = '正常にUser削除されました';
    sendResponse($message);
}





// ---------- タイムライン機能 ----------
function timeline($id)
{
    // jsonを取得
    $header = getallheaders();
    $bearerToken = $header['Authorization'];
    $token = substr($bearerToken, 7, strlen($bearerToken) - 7);
    $page = $_GET['page'];
    $limit = $_GET['limit'];
    $keyword = $_GET['query'];

    // dbにtokenを探しに行く
    $selectUserByTokenFetchAllResult = Db::selectUserByTokenFetchAll($token);
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errorMessage = 'tokenがおかしい';
        sendResponse($errorMessage);
    }

    // tokenが見つかったら投稿一覧引っ張る
    if ($keyword === '') {
        $selectPostByUserIdFetchAllResult = Db::selectAllPostWithoutParamsFetchAll($id);
    } else {
        $searchKeyword = '%' . $keyword . '%';
        $selectPostByUserIdFetchAllResult = Db::selectAllPostWithParamsFetchAll($id, $searchKeyword);
    }

    // usersテーブル全体を一旦引っ張る
    $selectUserFetchAllResult = Db::selectAllUserFetchAll();

    // usersテーブルのidを検索する
    foreach ($selectPostByUserIdFetchAllResult as &$post) {
        foreach ($selectUserFetchAllResult as $user) {
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
    for ($i = $startPoint; $i <= $endPoint && $i < count($selectPostByUserIdFetchAllResult); $i++) {
        $returnResult[] = $selectPostByUserIdFetchAllResult[$i];
    }
    sendResponse($returnResult);
}
