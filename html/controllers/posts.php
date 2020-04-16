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
    $selectUserByTokenFetchAllResult = db::selectUserByTokenFetchAll($token);
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errMsg = 'tokenがおかしい';
        sendResponse($errMsg);
    }

    // tokenが見つかったら投稿一覧引っ張る
    if ($keyword === '') {
        $selectAllPostFetchAllResult = db::selectAllPostWithoutParamsFetchAll();
    } else {
        $searchKeyword = '%' . $keyword . '%';
        $selectAllPostFetchAllResult = db::selectAllPostWithParamsFetchAll($searchKeyword);
    }

    // usersテーブル全体を一旦引っ張る
    $selectAllUserFetchAllResult = db::selectAllUserFetchAll();

    // usersテーブルのidを検索する
    foreach ($selectAllPostFetchAllResult as &$post) {
        foreach ($selectAllUserFetchAllResult as $user) {
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
    for ($i = $startPoint; $i <= $endPoint && $i < count($selectAllPostFetchAllResult); $i++) {
        $returnResult[] = $selectAllPostFetchAllResult[$i];
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
    $json = file_get_contents('php://input');
    $params = json_decode($json, true)['post_params'];
    $text = $params['text'];

    // usersテーブルからtokenに紐づくuserのidを拾ってくる
    $selectUserByTokenFetchAllResult = db::selectUserByTokenFetchAll($token);
    $selectedId = $selectUserByTokenFetchAllResult[0]['id'];
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errMsg = 'tokenがおかしい';
        sendResponse($errMsg);
    }

    // postsテーブルにinsertする
    db::insertPostDB($text, $selectedId);
    $insertedId = db::getPdo()->lastInsertId();

    // insertしたカラムをselectする
    $selectPostByInsertedIdFetchAllResult = db::selectPostByInsertedIdFetchAllResult($insertedId);
    $insertedUserId = $selectPostByInsertedIdFetchAllResult[0]['user_id'];

    // insertしたuserをselectする
    $selectUserByInsertedUserIdFetchAllResult = db::selectUserByInsertedUserIdFetchAll($insertedUserId);

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
    $json = file_get_contents('php://input');
    $params = json_decode($json, true)['post_params'];
    $text = $params['text'];

    // usersテーブルからtokenに紐づくuserのidを拾ってくる
    $selectUserByTokenFetchAllResult = db::selectUserByTokenFetchAll($token);
    $selectedId = $selectUserByTokenFetchAllResult[0]['id'];
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errMsg = 'tokenがおかしい';
        sendResponse($errMsg);
    }

    // postsテーブルから編集する投稿のuser_idを拾う
    $selectUserIdByIdFetchAllResult = db::selectUserByIdFetchAll($id);
    $selectedUserId = $selectUserIdByIdFetchAllResult[0]['user_id'];

    // ログイン中のidと編集しようとしている投稿のuser_idを突き合わせ
    if ($selectedId !== $selectedUserId) {
        $errorMessage = '自分のPostじゃないよ！';
        sendResponse($errorMessage);
    }

    // postsテーブルをupdateする
    db::updatePostDB($text, $id);

    // updateしたカラムをselectする
    $selectPostByUpdatedPostIdFetchAllResult = db::selectPostByUpdatedPostIdFetchAll($id);

    // updateしたuserをselectする
    $selectUserBySelectedIdFetchAllResult = db::selectUserBySelectedIdFetchAll($selectedId);

    // レスポンスを返す
    $selectPostByUpdatedPostIdFetchAllResult[0]['user'] =& $selectUserBySelectedIdFetchAllResult[0];
    unset($selectPostByUpdatedPostIdFetchAllResult[0]['user_id']);
    sendResponse($selectPostByUpdatedPostIdFetchAllResult[0]);
}





// ---------- 投稿削除機能 ----------
function deletePost($id)
{
    // jsonを取得
    $header = getallheaders();
    $bearerToken = $header['Authorization'];
    $token = substr($bearerToken, 7, strlen($bearerToken) - 7);

    // dbにtokenを探しに行く
    $selectUserByTokenFetchAllResult = db::selectUserByTokenFetchAll($token);
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];
    $selectedId = $selectUserByTokenFetchAllResult[0]['id'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errMsg = 'tokenがおかしい';
        sendResponse($errMsg);
    }

    // postsテーブルから削除する投稿のuser_idを拾う
    $selectUserIdByIdFetchAllResult = db::selectUserByIdFetchAll($id);

    // ログイン中のidと削除しようとしている投稿のuser_idを突き合わせ
    if ($selectedId !== $selectUserIdByIdFetchAllResult[0]['user_id']) {
        $errorMessage = '自分のPostじゃないよ！';
        sendResponse($errorMessage);
    }

    // postテーブルから削除
    db::deletePostDB($id);
}
