<?php
// ログイン機能
function sign_in(){
  require_once(dirname(__FILE__) . '/../dbconnect.php');

  // jsonを取得
  $json = file_get_contents("php://input");
  $params = json_decode($json, true)['sign_in_user_params'];
  $email = $params['email'];
  $pwd = $params['password'];
  $pwd = hash('sha256', $pwd);  // ハッシュ化
  $pwdCfm = $params['password_confirmation'];
  $pwdCfm = hash('sha256', $pwdCfm); //ハッシュ化

  // email欄が空だったらエラー吐く
  if ($email == "") {
    $errMsg = "そのemailもしくはpasswordが違います";
    sendResponse($errMsg);
  }

  // emailに入力された値と一致する行をdbから拾ってくる
  $slctUsrStmt = $db->prepare('SELECT email, password FROM users WHERE email = :email');
  $slctUsrStmt->bindValue(':email', $email, PDO::PARAM_STR);
  try {
    $slctUsrStmt->execute();
  } catch (Exception $e) {
    sendResponse($e);
  }
  $slctUsrFtchAllRslt = $slctUsrStmt->fetchAll(PDO::FETCH_ASSOC);

  // 一致するものがなかったらエラー吐く
  if (count($slctUsrFtchAllRslt) == 0) {
    $errMsg = "そのemailもしくはpasswordが違います";
    sendResponse($errMsg);
  }

  // 一致するものがあったら値取り出す
  $slctEmail = $slctUsrFtchAllRslt[0]['email'];
  $slctPwd = $slctUsrFtchAllRslt[0]['password'];

  // パスワード一致チェック
  if ($pwd != $pwdCfm || $pwd != $slctPwd) {
    $errMsg = "そのemailもしくはpasswordが違います";
    sendResponse($errMsg);
  }

  // dbからemailが一致するレコードを取得して返却
  $rtrnStmt = $db->prepare('SELECT * FROM users WHERE email = :email');
  $rtrnStmt->bindValue(':email', $email, PDO::PARAM_STR);
  try {
    $rtrnStmt->execute();
  } catch (Exception $e) {
    sendResponse($e);
  }
  $rtrnFtchAllRslt = $rtrnStmt->fetchAll(PDO::FETCH_ASSOC);
  unset($rtrnFtchAllRslt[0]['password']); // 配列からpassword要素を削除
  sendResponse($rtrnFtchAllRslt[0]);
}
sign_in();
?>