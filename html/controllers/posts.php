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
    $selectUserByTokenFetchAllResult = Db::selectUserByTokenFetchAll($token);
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errMsg = 'tokenがおかしい';
        sendResponse($errMsg);
    }

    // tokenが見つかったら投稿一覧引っ張る
    if ($keyword === '') {
        $selectAllPostFetchAllResult = Db::selectAllPostWithoutParamsFetchAll();
    } else {
        $searchKeyword = '%' . $keyword . '%';
        $selectAllPostFetchAllResult = Db::selectAllPostWithParamsFetchAll($searchKeyword);
    }

    // usersテーブル全体を一旦引っ張る
    $selectAllUserFetchAllResult = Db::selectAllUserFetchAll();

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
    $selectUserByTokenFetchAllResult = Db::selectUserByTokenFetchAll($token);
    $selectedId = $selectUserByTokenFetchAllResult[0]['id'];
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errMsg = 'tokenがおかしい';
        sendResponse($errMsg);
    }

    // postsテーブルにinsertする
    Db::insertPostDB($text, $selectedId);
    $insertedId = Db::getPdo()->lastInsertId();

    // insertしたカラムをselectする
    $selectPostByInsertedIdFetchAllResult = Db::selectPostByInsertedIdFetchAllResult($insertedId);
    $insertedUserId = $selectPostByInsertedIdFetchAllResult[0]['user_id'];

    // insertしたuserをselectする
    $selectUserByInsertedUserIdFetchAllResult = Db::selectUserByInsertedUserIdFetchAll($insertedUserId);

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
    $selectUserByTokenFetchAllResult = Db::selectUserByTokenFetchAll($token);
    $selectedId = $selectUserByTokenFetchAllResult[0]['id'];
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errMsg = 'tokenがおかしい';
        sendResponse($errMsg);
    }

    // postsテーブルから編集する投稿のuser_idを拾う
    $selectUserIdByIdFetchAllResult = Db::selectUserByIdFetchAll($id);
    $selectedUserId = $selectUserIdByIdFetchAllResult[0]['user_id'];

    // ログイン中のidと編集しようとしている投稿のuser_idを突き合わせ
    if ($selectedId !== $selectedUserId) {
        $errorMessage = '自分のPostじゃないよ！';
        sendResponse($errorMessage);
    }

    // postsテーブルをupdateする
    Db::updatePostDB($text, $id);

    // updateしたカラムをselectする
    $selectPostByUpdatedPostIdFetchAllResult = Db::selectPostByUpdatedPostIdFetchAll($id);

    // updateしたuserをselectする
    $selectUserBySelectedIdFetchAllResult = Db::selectUserBySelectedIdFetchAll($selectedId);

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
    $selectUserByTokenFetchAllResult = Db::selectUserByTokenFetchAll($token);
    $selectedToken = $selectUserByTokenFetchAllResult[0]['token'];
    $selectedId = $selectUserByTokenFetchAllResult[0]['id'];

    // tokenが見つからなかったらエラー吐く
    if ($selectedToken === null) {
        $errMsg = 'tokenがおかしい';
        sendResponse($errMsg);
    }

    // postsテーブルから削除する投稿のuser_idを拾う
    $selectUserIdByIdFetchAllResult = Db::selectUserByIdFetchAll($id);

    // ログイン中のidと削除しようとしている投稿のuser_idを突き合わせ
    if ($selectedId !== $selectUserIdByIdFetchAllResult[0]['user_id']) {
        $errorMessage = '自分のPostじゃないよ！';
        sendResponse($errorMessage);
    }

    // postテーブルから削除
    Db::deletePostDB($id);
    $message = '正常にPost削除されました';
    sendResponse($message);
}
