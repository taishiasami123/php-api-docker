<?php
  // dbに繋ぐ
  require_once('../dbconnect.php');

  // // jsonを取得
  // $json = file_get_contents("php://input");
  // $params = json_decode($json, true)['sign_up_user_params'];
  // $name = $params['name'];
  // $bio = $params['bio'];
  // $email = $params['email'];
  // $pwd = $params['password'];
  // $pwdCfm = $params['password_confirmation'];

  // // token生成
  // $salt = "phpapi";
  // $seed = $salt.$email;
  // $token = hash('sha256', $seed);

  // // blankチェック
  // if ($name == "") {
  //   $errMsg = "Validation failed: Name can't be blank";
  //   sendResponse($errMsg);
  // } elseif ($bio == "") {
  //   $errMsg = "Validation failed: Bio can't be blank";
  //   sendResponse($errMsg);
  // } elseif ($email == "") {
  //   $errMsg = "Validation failed: Email can't be blank";
  //   sendResponse($errMsg);
  // } elseif ($pwd == "") {
  //   $errMsg = "Validation failed: Password can't be blank";
  //   sendResponse($errMsg);

  // // pwd一致チェック
  // } elseif ($pwd != $pwdCfm) {
  //   $errMsg = "Validation failed: Password confirmation doesn't match password";
  //   sendResponse($errMsg);
  // }

  // // email重複チェック
  // $emailChkStmt = $db->prepare("SELECT * FROM users WHERE email = :email");
  // $emailChkStmt->bindValue(':email', $email, PDO::PARAM_STR);
  // try {
  //   $emailChkStmt->execute();
  // } catch (Exception $e) {
  //   sendResponse($e);
  // }
  // $emailChkFetchAllResult = $emailChkStmt->fetchAll(PDO::FETCH_ASSOC);
  // if (count($emailChkFetchAllResult) > 0) {
  //   $errMsg = "そのemailは登録されている";
  //   sendResponse($errMsg);
  // }

  // // db登録処理
  // $RgstStmt = $db->prepare('INSERT INTO users SET name = :name, bio = :bio, email = :email, password = :pwd, token = :token, created_at = NOW()');
  // $RgstStmt->bindValue(':name', $name, PDO::PARAM_STR);
  // $RgstStmt->bindValue(':bio', $bio, PDO::PARAM_STR);
  // $RgstStmt->bindValue(':email', $email, PDO::PARAM_STR);
  // $pwd = hash('sha256', $pwd); // pwdハッシュ化
  // $RgstStmt->bindValue(':pwd', $pwd, PDO::PARAM_STR);
  // $RgstStmt->bindValue(':token', $token, PDO::PARAM_STR);
  // try {
  //   $RgstStmt->execute();
  // } catch (Exception $e) {
  //   sendResponse($e);
  // }

  // // dbからemailが一致するレコードを取得して返却
  // $rtrnStmt = $db->prepare('SELECT * FROM users WHERE email = :email');
  // $rtrnStmt->bindValue(':email', $email, PDO::PARAM_STR);
  // try {
  //   $rtrnStmt->execute();
  // } catch (Exception $e) {
  //   sendResponse($e);
  // }
  // $rtrnFtchAllRslt = $rtrnStmt->fetchAll(PDO::FETCH_ASSOC);
  // unset($rtrnFtchAllRslt[0]['password']); // 配列からpassword要素を削除
  // sendResponse($rtrnFtchAllRslt[0]);

?>