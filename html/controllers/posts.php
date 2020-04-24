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
    $selectUserByTokenFromUsersFetchAllResult = Db::selectUserByTokenFromUsersFetchAll($token);
    $tokenFromUsersTable = $selectUserByTokenFromUsersFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($tokenFromUsersTable === null) {
        $errMsg = 'tokenがおかしい';
        sendResponse($errMsg);
    }

    // tokenが見つかったら投稿一覧引っ張る
    if ($keyword === '') {
        $selectAllPostFromPostsFetchAllResult = Db::selectAllPostFromPostsWithoutParamsFetchAllForPublic();
    } else {
        $searchKeyword = '%' . $keyword . '%';
        $selectAllPostFromPostsFetchAllResult = Db::selectAllPostFromPostsWithParamsFetchAllForPublic($searchKeyword);
    }

    // usersテーブル全体を一旦引っ張る
    $selectAllUserFromUsersFetchAllResult = Db::selectAllUserFromUsersFetchAll();

    // usersテーブルのidを検索する
    foreach ($selectAllPostFromPostsFetchAllResult as &$post) {
        foreach ($selectAllUserFromUsersFetchAllResult as $user) {
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
    for ($i = $startPoint; $i <= $endPoint && $i < count($selectAllPostFromPostsFetchAllResult); $i++) {
        $returnResult[] = $selectAllPostFromPostsFetchAllResult[$i];
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
    $selectUserByTokenFromUsersFetchAllResult = Db::selectUserByTokenFromUsersFetchAll($token);
    $userIdFromUsersTable = $selectUserByTokenFromUsersFetchAllResult[0]['id'];
    $tokenFromUsersTable = $selectUserByTokenFromUsersFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($tokenFromUsersTable === null) {
        $errMsg = 'tokenがおかしい';
        sendResponse($errMsg);
    }

    // postsテーブルにinsertする
    $insertedPostId = Db::insertPostIntoPostsAndReturnInsertedPostId($text, $userIdFromUsersTable);

    // insertしたカラムをselectする
    $selectPostByInsertedIdFromPostsFetchAllResult = Db::selectPostByPostIdFromPostsFetchAll($insertedPostId);
    $insertedUserId = $selectPostByInsertedIdFromPostsFetchAllResult[0]['user_id'];

    // insertしたuserをselectする
    $selectUserByInsertedUserIdFetchAllResult = Db::selectUserByUserIdFromUsersFetchAllForPublic($insertedUserId);

    // レスポンスを返す
    $selectPostByInsertedIdFromPostsFetchAllResult[0]['user'] = &$selectUserByInsertedUserIdFetchAllResult[0];
    unset($selectPostByInsertedIdFromPostsFetchAllResult[0]['user_id']);
    sendResponse($selectPostByInsertedIdFromPostsFetchAllResult[0]);
}





// ---------- 投稿編集機能 ----------
function editPost($postId)
{
    // jsonを取得
    $header = getallheaders();
    $bearerToken = $header['Authorization'];
    $token = substr($bearerToken, 7, strlen($bearerToken) - 7);
    $json = file_get_contents('php://input');
    $params = json_decode($json, true)['post_params'];
    $text = $params['text'];

    // usersテーブルからtokenに紐づくuserのidを拾ってくる
    $selectUserByTokenFromUsersFetchAllResult = Db::selectUserByTokenFromUsersFetchAll($token);
    $userIdFromUsersTable = $selectUserByTokenFromUsersFetchAllResult[0]['id'];
    $tokenFromUsersTable = $selectUserByTokenFromUsersFetchAllResult[0]['token'];

    // tokenが見つからなかったらエラー吐く
    if ($tokenFromUsersTable === null) {
        $errMsg = 'tokenがおかしい';
        sendResponse($errMsg);
    }

    // postsテーブルから編集する投稿のuser_idを拾う
    $selectPostByPostIdFromPostsFetchAllResult = Db::selectPostByPostIdFromPostsFetchAll($postId);
    $userIdFromPostsTable = $selectPostByPostIdFromPostsFetchAllResult[0]['user_id'];

    // ログイン中のidと編集しようとしている投稿のuser_idを突き合わせ
    if ($userIdFromUsersTable !== $userIdFromPostsTable) {
        $errorMessage = '自分のPostじゃないよ！';
        sendResponse($errorMessage);
    }

    // postsテーブルをupdateする
    Db::updatePostSetPosts($text, $postId);

    // updateしたカラムをselectする
    $selectPostAgainByPostIdFromPostsFetchAllResult = Db::selectPostByPostIdFromPostsFetchAll($postId);

    // updateしたuserをselectする
    $selectUserBySelectedIdFetchAllResult = Db::selectUserByUserIdFromUsersFetchAllForPublic($userIdFromUsersTable);

    // レスポンスを返す
    $selectPostAgainByPostIdFromPostsFetchAllResult[0]['user'] = &$selectUserBySelectedIdFetchAllResult[0];
    unset($selectPostAgainByPostIdFromPostsFetchAllResult[0]['user_id']);
    sendResponse($selectPostAgainByPostIdFromPostsFetchAllResult[0]);
}





// ---------- 投稿削除機能 ----------
function deletePost($postId)
{
    // jsonを取得
    $header = getallheaders();
    $bearerToken = $header['Authorization'];
    $token = substr($bearerToken, 7, strlen($bearerToken) - 7);

    // dbにtokenを探しに行く
    $selectUserByTokenFromUsersFetchAllResult = Db::selectUserByTokenFromUsersFetchAll($token);
    $tokenFromUsersTable = $selectUserByTokenFromUsersFetchAllResult[0]['token'];
    $userIdFromUsersTable = $selectUserByTokenFromUsersFetchAllResult[0]['id'];

    // tokenが見つからなかったらエラー吐く
    if ($tokenFromUsersTable === null) {
        $errMsg = 'tokenがおかしい';
        sendResponse($errMsg);
    }

    // postsテーブルから削除する投稿のuser_idを拾う
    $selectUserIdByIdFetchAllResult = Db::selectPostByPostIdFromPostsFetchAll($postId);

    // ログイン中のidと削除しようとしている投稿のuser_idを突き合わせ
    if ($userIdFromUsersTable !== $selectUserIdByIdFetchAllResult[0]['user_id']) {
        $errorMessage = '自分のPostじゃないよ！';
        sendResponse($errorMessage);
    }

    // postテーブルから削除
    Db::deletePostFromPosts($postId);
    $message = '正常にPost削除されました';
    sendResponse($message);
}
