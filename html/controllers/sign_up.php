<?php
// 新規登録機能
function signUp()
{
    // jsonを取得
    $json = file_get_contents("php://input");
    $params = json_decode($json, true)['sign_up_user_params'];
    $name = $params['name'];
    $bio = $params['bio'];
    $email = $params['email'];
    $password = $params['password'];
    $passwordConfirm = $params['password_confirmation'];

    // token生成
    $salt = "phpapi";
    $seed = $salt . $email;
    $token = hash('sha256', $seed);

    // blankチェック
    if ($name === "") {
        $errorMessage = "Validation failed: Name can't be blank";
        sendResponse($errorMessage);
    } elseif ($bio === "") {
        $errorMessage = "Validation failed: Bio can't be blank";
        sendResponse($errorMessage);
    } elseif ($email === "") {
        $errorMessage = "Validation failed: Email can't be blank";
        sendResponse($errorMessage);
    } elseif ($password === "") {
        $errorMessage = "Validation failed: Password can't be blank";
        sendResponse($errorMessage);

    // password一致チェック
    } elseif ($password != $passwordConfirm) {
        $errorMessage = "Validation failed: Password confirmation doesn't match password";
        sendResponse($errorMessage);
    }

    // email重複チェック
    $selectUserByEmail = Db::getPdo()->prepare("SELECT * FROM users WHERE email = :email");
    $selectUserByEmail->bindValue(':email', $email, PDO::PARAM_STR);
    try {
        $selectUserByEmail->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserByEmailFetchAllResult = $selectUserByEmail->fetchAll(PDO::FETCH_ASSOC);
    if (count($selectUserByEmailFetchAllResult) > 0) {
        $errorMessage = "そのemailは登録されている";
        sendResponse($errorMessage);
    }

    // db登録処理
    $insertUser = Db::getPdo()->prepare('INSERT INTO users SET name = :name, bio = :bio, email = :email, password = :password, token = :token, created_at = NOW()');
    $insertUser->bindValue(':name', $name, PDO::PARAM_STR);
    $insertUser->bindValue(':bio', $bio, PDO::PARAM_STR);
    $insertUser->bindValue(':email', $email, PDO::PARAM_STR);
    $password = hash('sha256', $password); // passwordハッシュ化
    $insertUser->bindValue(':password', $password, PDO::PARAM_STR);
    $insertUser->bindValue(':token', $token, PDO::PARAM_STR);
    try {
        $insertUser->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }

    // dbからemailが一致するレコードを取得して返却
    $selectUserAgainByEmail = Db::getPdo()->prepare('SELECT * FROM users WHERE email = :email');
    $selectUserAgainByEmail->bindValue(':email', $email, PDO::PARAM_STR);
    try {
        $selectUserAgainByEmail->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserAgainByEmailFetchAllResult = $selectUserAgainByEmail->fetchAll(PDO::FETCH_ASSOC);
    unset($selectUserAgainByEmailFetchAllResult[0]['password']); // 配列からpassword要素を削除
    sendResponse($selectUserAgainByEmailFetchAllResult[0]);
}
