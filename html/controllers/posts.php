<?php
// ---------- 投稿一覧機能 ----------
function postList() {
  // dbに繋ぐ
  require_once(dirname(__FILE__) . '/../dbconnect.php');

  // jsonを取得
  $header = getallheaders();
  $bearerToken = $header['Authorization'];
  $token = substr($bearerToken, 7, strlen($bearerToken) - 7);
  $json = file_get_contents("php://input");
  $page = $_GET['page'];
  $limit = $_GET['limit'];
  $keyword = $_GET['query'];

  // dbにtokenを探しに行く
  $slctUsrStmt = $db->prepare('SELECT token FROM users WHERE token = :token');
  $slctUsrStmt->bindValue(':token', $token, PDO::PARAM_STR);
  try {
    $slctUsrStmt->execute();
  } catch (Exception $e) {
    sendResponse($e);
  }
  $slctUsrStmtFtchAllRslt = $slctUsrStmt->fetchAll(PDO::FETCH_ASSOC);
  $slctdToken = $slctUsrStmtFtchAllRslt[0]['token'];

  // tokenが見つからなかったらエラー吐く
  if (count($slctdToken) == 0) {
    $errMsg = "tokenがおかしい";
    sendResponse($errMsg);
  }

  // tokenが見つかったら投稿一覧引っ張る
  if ($keyword == "") {
    $rtrnPostStmt = $db->prepare('SELECT id, text, user_id, created_at, updated_at FROM posts');
  } else {
    $rtrnPostStmt = $db->prepare('SELECT id, text, user_id, created_at, updated_at FROM posts WHERE text LIKE :srchKwd');
    $srchKwd = "%".$keyword."%";
    $rtrnPostStmt->bindValue(':srchKwd', $srchKwd, PDO::PARAM_STR);
  }
  try {
    $rtrnPostStmt->execute();
  } catch (Exception $e) {
    sendResponse($e);
  }
  $rtrnFtchAllPostRslt = $rtrnPostStmt->fetchAll(PDO::FETCH_ASSOC);

  // usersテーブル全体を一旦引っ張る
  $rtrnUserStmt = $db->prepare('SELECT * FROM users');
  $rtrnUserStmt->bindValue(':id', $slctdUserId, PDO::PARAM_STR);
  try {
    $rtrnUserStmt->execute();
  } catch (Exception $e) {
    sendResponse($e);
  }
  $rtrnFtchAllUserRslt = $rtrnUserStmt->fetchAll(PDO::FETCH_ASSOC);

  // usersテーブルのidを検索する
  foreach($rtrnFtchAllPostRslt as &$post){
    foreach($rtrnFtchAllUserRslt as $user) {
      if($user['id'] == $post['user_id']) {
        unset($post['user_id'], $user['email'], $user['password'], $user['token']);
        $post['user'] = $user;
        break;
      }
    }
  }

  // $page, $limitがブランクだった場合に値を代入
  if ($page == "") {
    $page = 1;
  }
  if ($limit == "") {
    $limit = 25;
  }

  // 返す配列の開始位置，終了位置を変数に代入
  $startPoint = $page * $limit - $limit;
  $endPoint = $page * $limit - 1;

  // 結果の配列を受け取る変数を作って，そいつを返す
  $echoRslt = [];
  for ($i = $startPoint; $i <= $endPoint && $i < count($rtrnFtchAllPostRslt); $i++) {
    $echoRslt[] = $rtrnFtchAllPostRslt[$i];
  }
  sendResponse($echoRslt);
}

// ---------- 投稿作成機能 ----------
function submitPost() {
  sendResponse('submitPost');
}

// ---------- 投稿編集機能 ----------
function editPost() {
  sendResponse('editPost');
}

// ---------- 投稿削除機能 ----------
function deletePost() {
  sendResponse('deletePost');
}
?>