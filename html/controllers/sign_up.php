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
    } elseif ($password != $passwordConfirm) { // password一致チェック
        $errorMessage = "Validation failed: Password confirmation doesn't match password";
        sendResponse($errorMessage);
    }

    // email重複チェック
    $selectUserByEmailFetchAllResult = Db::selectUserByEmailFetchAll($email);
    if (count($selectUserByEmailFetchAllResult) > 0) {
        $errorMessage = "そのemailは登録されている";
        sendResponse($errorMessage);
    }

    // db登録処理
    $password = hash('sha256', $password); // passwordハッシュ化
    Db::insertUser($name, $bio, $email, $password, $token);

    // dbからemailが一致するレコードを取得して返却
    $selectUserAgainByEmailFetchAllResult = Db::selectUserByEmailFetchAll($email);
    unset($selectUserAgainByEmailFetchAllResult[0]['password']); // 配列からpassword要素を削除
    sendResponse($selectUserAgainByEmailFetchAllResult[0]);
}
