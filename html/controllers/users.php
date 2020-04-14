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
    $selectUserByToken = Db::getPdo()->prepare('SELECT token FROM users WHERE token = :token');
    $selectUserByToken->bindValue(':token', $token, PDO::PARAM_STR);
    try {
        $selectUserByToken->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserByTokenFetchAllResult = $selectUserByToken->fetchAll(PDO::FETCH_ASSOC);
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errorMessage = "tokenがおかしい";
        sendResponse($errorMessage);
    }

    // tokenが見つかったらユーザー一覧引っ張る
    if ($keyword === '') {
        $selectUser = Db::getPdo()->prepare('SELECT id, name, bio, created_at, updated_at FROM users ORDER BY updated_at DESC');
    } else {
        $selectUser = Db::getPdo()->prepare('SELECT id, name, bio, created_at, updated_at FROM users WHERE name LIKE :searchKeyword OR bio LIKE :searchKeyword ORDER BY updated_at DESC');
        $searchKeyword = '%' . $keyword . '%';
        $selectUser->bindValue(':searchKeyword', $searchKeyword, PDO::PARAM_STR);
    }
    try {
        $selectUser->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserFetchAllResult = $selectUser->fetchAll(PDO::FETCH_ASSOC);

    // $page, $limitがブランクだった場合に値を代入
    if ($page === "") {
        $page = 1;
    }
    if ($limit === "") {
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
    $json = file_get_contents("php://input");
    $params = json_decode($json, true)['user_params'];
    $name = $params['name'];
    $bio = $params['bio'];

    // dbにtokenを探しに行く
    $selectUserByToken = Db::getPdo()->prepare('SELECT id, token FROM users WHERE token = :token');
    $selectUserByToken->bindValue(':token', $token, PDO::PARAM_STR);
    try {
        $selectUserByToken->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserByTokenFetchAllResult = $selectUserByToken->fetchAll(PDO::FETCH_ASSOC);
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];
    $selectedId = $selectUserByTokenFetchAllResult[0]['id'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errorMessage = "tokenがおかしい";
        sendResponse($errorMessage);
    }

    // 入力されたidとdbから拾ったidを比較して一致しなかったらエラー吐く
    if ($id !== $selectedId) {
        $errorMessage = '自分のユーザーじゃないよ!';
        sendResponse($errorMessage);
    }

    // dbのnameとbioをupdateする
    $updateUser = Db::getPdo()->prepare('UPDATE users SET name = :name, bio = :bio WHERE id = :id');
    $updateUser->bindValue(':id', $id, PDO::PARAM_INT);
    $updateUser->bindValue(':name', $name, PDO::PARAM_STR);
    $updateUser->bindValue(':bio', $bio, PDO::PARAM_STR);
    try {
        $updateUser->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }

    // updateしたレコードを返却
    $selectUserById = Db::getPdo()->prepare('SELECT id, name, bio, email, created_at, updated_at FROM users WHERE id = :id');
    $selectUserById->bindValue(':id', $id, PDO::PARAM_INT);
    try {
        $selectUserById->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserByIdFetchAllResult = $selectUserById->fetchAll(PDO::FETCH_ASSOC);
    sendResponse($selectUserByIdFetchAllResult[0]);
}

// ---------- ユーザー削除機能 ----------
function deleteUser($id)
{
    // jsonを取得
    $header = getallheaders();
    $bearerToken = $header['Authorization'];
    $token = substr($bearerToken, 7, strlen($bearerToken) - 7);

    // dbにtokenを探しに行く
    $selectUserByToken = Db::getPdo()->prepare('SELECT id, token FROM users WHERE token = :token');
    $selectUserByToken->bindValue(':token', $token, PDO::PARAM_STR);
    try {
        $selectUserByToken->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserByTokenFetchAllResult = $selectUserByToken->fetchAll(PDO::FETCH_ASSOC);
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];
    $selectedId = $selectUserByTokenFetchAllResult[0]['id'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errorMessage = "tokenがおかしい";
        sendResponse($errorMessage);
    }

    // 入力されたidとdbから拾ったidを比較して一致しなかったらエラー吐く
    if ($id !== $selectedId) {
        $errorMessage = '自分のユーザーじゃないよ!';
        sendResponse($errorMessage);
    }

    $deleteUser = Db::getPdo()->prepare('DELETE FROM users WHERE id = :id');
    $deleteUser->bindValue(':id', $id, PDO::PARAM_STR);
    try {
        $deleteUser->execute();
        $msg = '正常にUser削除されました';
        sendResponse($msg);
    } catch (Exception $e) {
        sendResponse($e);
    }
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
    $selectUserByToken = Db::getPdo()->prepare('SELECT token FROM users WHERE token = :token');
    $selectUserByToken->bindValue(':token', $token, PDO::PARAM_STR);
    try {
        $selectUserByToken->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserByTokenFetchAllResult = $selectUserByToken->fetchAll(PDO::FETCH_ASSOC);
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errorMessage = "tokenがおかしい";
        sendResponse($errorMessage);
    }

    // tokenが見つかったら投稿一覧引っ張る
    if ($keyword === '') {
        $selectPostByUserId = Db::getPdo()->prepare('SELECT * FROM posts WHERE user_id = :userId ORDER BY updated_at DESC');
        $selectPostByUserId->bindValue(':userId', $id, PDO::PARAM_INT);
    } else {
        $selectPostByUserId = Db::getPdo()->prepare('SELECT * FROM posts WHERE user_id = :userId AND text LIKE :searchKeyword ORDER BY updated_at DESC');
        $selectPostByUserId->bindValue(':userId', $id, PDO::PARAM_INT);
        $searchKeyword = '%' . $keyword . '%';
        $selectPostByUserId->bindValue(':searchKeyword', $searchKeyword, PDO::PARAM_STR);
    }
    try {
        $selectPostByUserId->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectPostByUserIdFetchAllResult = $selectPostByUserId->fetchAll(PDO::FETCH_ASSOC);

    // usersテーブル全体を一旦引っ張る
    $selectUser = Db::getPdo()->prepare('SELECT * FROM users');
    try {
        $selectUser->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserFetchAllResult = $selectUser->fetchAll(PDO::FETCH_ASSOC);

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
    if ($page === "") {
        $page = 1;
    }
    if ($limit === "") {
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
