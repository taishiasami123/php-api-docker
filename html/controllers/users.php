<?php
// ---------- ユーザー一覧機能 ---------- 
function userList() {
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

  // tokenが見つかったらユーザー一覧引っ張る
  if ($keyword == "") {
    $rtrnStmt = $db->prepare('SELECT id, name, created_at, updated_at FROM users');
  } else {
    $rtrnStmt = $db->prepare('SELECT id, name, created_at, updated_at FROM users WHERE name LIKE :srchKwd OR bio LIKE :srchKwd');
    $srchKwd = "%".$keyword."%";
    $rtrnStmt->bindValue(':srchKwd', $srchKwd, PDO::PARAM_STR);
  }
  try {
    $rtrnStmt->execute();
  } catch (Exception $e) {
    sendResponse($e);
  }
  $rtrnFtchAllRslt = $rtrnStmt->fetchAll(PDO::FETCH_ASSOC);

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
  for ($i = $startPoint; $i <= $endPoint && $i < count($rtrnFtchAllRslt); $i++) {
    $echoRslt[] = $rtrnFtchAllRslt[$i];
  }
  sendResponse($echoRslt);
}

// ---------- ユーザー編集機能 ----------
function editUser() {
  // dbに繋ぐ
  require_once(dirname(__FILE__) . '/../dbconnect.php');

  // jsonを取得
  $header = getallheaders();
  $bearerToken = $header['Authorization'];
  $token = substr($bearerToken, 7, strlen($bearerToken) - 7);
  $json = file_get_contents("php://input");
  $params = json_decode($json, true)['user_params'];
  $name = $params['name'];
  $bio = $params['bio'];

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

  sendResponse($slctdToken);
}

// ---------- ユーザー削除機能 ----------
function deleteUser() {
  sendResponse('deleteUser');
}

// タイムライン機能
function timeline() {
  sendResponse('timeline');
}
?>