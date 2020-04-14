<?php
// ---------- 投稿一覧機能 ----------
function postList()
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
        $errMsg = "tokenがおかしい";
        sendResponse($errMsg);
    }

    // tokenが見つかったら投稿一覧引っ張る
    if ($keyword === "") {
        $selectPost = Db::getPdo()->prepare('SELECT id, text, user_id, created_at, updated_at FROM posts ORDER BY updated_at DESC');
    } else {
        $selectPost = Db::getPdo()->prepare('SELECT id, text, user_id, created_at, updated_at FROM posts WHERE text LIKE :searchKeyword ORDER BY updated_at DESC');
        $searchKeyword = "%" . $keyword . "%";
        $selectPost->bindValue(':searchKeyword', $searchKeyword, PDO::PARAM_STR);
    }
    try {
        $selectPost->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectPostFetchAllResult = $selectPost->fetchAll(PDO::FETCH_ASSOC);

    // usersテーブル全体を一旦引っ張る
    $selectUser = Db::getPdo()->prepare('SELECT * FROM users');
    try {
        $selectUser->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserFetchAllResult = $selectUser->fetchAll(PDO::FETCH_ASSOC);

    // usersテーブルのidを検索する
    foreach ($selectPostFetchAllResult as &$post) {
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
    for ($i = $startPoint; $i <= $endPoint && $i < count($selectPostFetchAllResult); $i++) {
        $returnResult[] = $selectPostFetchAllResult[$i];
    }
    sendResponse($returnResult);
}

// ---------- 投稿作成機能 ----------
function submitPost()
{
    // jsonを取得
    $header = getallheaders();
    $bearerToken = $header['Authorization'];
    $token = substr($bearerToken, 7, strlen($bearerToken) - 7);
    $json = file_get_contents("php://input");
    $params = json_decode($json, true)['post_params'];
    $text = $params['text'];

    // usersテーブルからtokenに紐づくuserのidを拾ってくる
    $selectUserByToken = Db::getPdo()->prepare('SELECT id, token FROM users WHERE token = :token');
    $selectUserByToken->bindValue(':token', $token, PDO::PARAM_STR);
    try {
        $selectUserByToken->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserByTokenFetchAllResult = $selectUserByToken->fetchAll(PDO::FETCH_ASSOC);
    $selectedId = $selectUserByTokenFetchAllResult[0]['id'];
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errMsg = "tokenがおかしい";
        sendResponse($errMsg);
    }

    // postsテーブルにinsertする
    $insertPost = Db::getPdo()->prepare('INSERT INTO posts SET text = :text, user_id = :userId, created_at = NOW()');
    $insertPost->bindValue(':text', $text, PDO::PARAM_STR);
    $insertPost->bindValue(':userId', $selectedId, PDO::PARAM_INT);
    try {
        $insertPost->execute();
        $insertedId = Db::getPdo()->lastInsertId();
    } catch (Exception $e) {
        sendResponse($e);
    }

    // insertしたカラムをselectする
    $selectPostByInsertedId = Db::getPdo()->prepare('SELECT * FROM posts WHERE id = :id');
    $selectPostByInsertedId->bindValue(':id', $insertedId, PDO::PARAM_INT);
    try {
        $selectPostByInsertedId->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectPostByInsertedIdFetchAllResult = $selectPostByInsertedId->fetchAll(PDO::FETCH_ASSOC);
    $insertedUserId = $selectPostByInsertedIdFetchAllResult[0]['user_id'];

    // insertしたuserをselectする
    $selectUserByInsertedUserId = Db::getPdo()->prepare('SELECT id, name, bio, created_at, updated_at FROM users WHERE id = :id');
    $selectUserByInsertedUserId->bindValue(':id', $insertedUserId, PDO::PARAM_INT);
    try {
        $selectUserByInsertedUserId->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserByInsertedUserIdFetchAllResult = $selectUserByInsertedUserId->fetchAll(PDO::FETCH_ASSOC);

    // レスポンスを返す
    $selectPostByInsertedIdFetchAllResult[0]['user'] =& $selectUserByInsertedUserIdFetchAllResult[0];
    unset($selectPostByInsertedIdFetchAllResult[0]['user_id']);
    sendResponse($selectPostByInsertedIdFetchAllResult[0]);
}

// ---------- 投稿編集機能 ----------
function editPost($id)
{
    // jsonを取得
    $header = getallheaders();
    $bearerToken = $header['Authorization'];
    $token = substr($bearerToken, 7, strlen($bearerToken) - 7);
    $json = file_get_contents("php://input");
    $params = json_decode($json, true)['post_params'];
    $text = $params['text'];

    // usersテーブルからtokenに紐づくuserのidを拾ってくる
    $selectUserByToken = Db::getPdo()->prepare('SELECT id, token FROM users WHERE token = :token');
    $selectUserByToken->bindValue(':token', $token, PDO::PARAM_STR);
    try {
        $selectUserByToken->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserByTokenFetchAllResult = $selectUserByToken->fetchAll(PDO::FETCH_ASSOC);
    $selectedId = $selectUserByTokenFetchAllResult[0]['id'];
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errMsg = "tokenがおかしい";
        sendResponse($errMsg);
    }

    // postsテーブルから編集する投稿のuser_idを拾う
    $selectUserIdById = Db::getPdo()->prepare('SELECT user_id FROM posts WHERE id = :id');
    $selectUserIdById->bindValue(':id', $id, PDO::PARAM_INT);
    try {
        $selectUserIdById->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserIdByIdFetchAllResult = $selectUserIdById->fetchAll(PDO::FETCH_ASSOC);
    $selectedUserId = $selectUserIdByIdFetchAllResult[0]['user_id'];

    // ログイン中のidと編集しようとしている投稿のuser_idを突き合わせ
    if ($selectedId !== $selectedUserId) {
        $errorMessage = '自分のPostじゃないよ！';
        sendResponse($errorMessage);
    }

    // postsテーブルをupdateする
    $updatePost = Db::getPdo()->prepare('UPDATE posts SET text = :text WHERE id = :id');
    $updatePost->bindValue(':text', $text, PDO::PARAM_STR);
    $updatePost->bindValue(':id', $id, PDO::PARAM_INT);
    try {
        $updatePost->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }

    // updateしたカラムをselectする
    $selectPostByInsertedId = Db::getPdo()->prepare('SELECT * FROM posts WHERE id = :id');
    $selectPostByInsertedId->bindValue(':id', $id, PDO::PARAM_INT);
    try {
        $selectPostByInsertedId->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectPostByInsertedIdFetchAllResult = $selectPostByInsertedId->fetchAll(PDO::FETCH_ASSOC);

    // updateしたuserをselectする
    $selectUserBySelectedId = Db::getPdo()->prepare('SELECT id, name, bio, created_at, updated_at FROM users WHERE id = :id');
    $selectUserBySelectedId->bindValue(':id', $selectedId, PDO::PARAM_INT);
    try {
        $selectUserBySelectedId->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserBySelectedIdFetchAllResult = $selectUserBySelectedId->fetchAll(PDO::FETCH_ASSOC);

    // レスポンスを返す
    $selectPostByInsertedIdFetchAllResult[0]['user'] =& $selectUserBySelectedIdFetchAllResult[0];
    unset($selectPostByInsertedIdFetchAllResult[0]['user_id']);
    sendResponse($selectPostByInsertedIdFetchAllResult[0]);
}

// ---------- 投稿削除機能 ----------
function deletePost($id)
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
        $errMsg = "tokenがおかしい";
        sendResponse($errMsg);
    }

    // postsテーブルから編集する投稿のuser_idを拾う
    $selectUserIdById = Db::getPdo()->prepare('SELECT user_id FROM posts WHERE id = :id');
    $selectUserIdById->bindValue(':id', $id, PDO::PARAM_INT);
    try {
        $selectUserIdById->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserIdByIdFetchAllResult = $selectUserIdById->fetchAll(PDO::FETCH_ASSOC);

    // ログイン中のidと削除しようとしている投稿のuser_idを突き合わせ
    if ($selectedId !== $selectUserIdByIdFetchAllResult[0]['user_id']) {
        $errorMessage = '自分のPostじゃないよ！';
        sendResponse($errorMessage);
    }

    $deletePost = Db::getPdo()->prepare('DELETE FROM posts WHERE id = :id');
    $deletePost->bindValue(':id', $id, PDO::PARAM_STR);
    try {
        $deletePost->execute();
        $msg = '正常にPost削除されました';
        sendResponse($msg);
    } catch (Exception $e) {
        sendResponse($e);
    }
}
