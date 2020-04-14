<?php
// ログイン機能
function signIn()
{
    // jsonを取得
    $json = file_get_contents("php://input");
    $params = json_decode($json, true)['sign_in_user_params'];
    $email = $params['email'];
    $password = $params['password'];
    $password = hash('sha256', $password);  // ハッシュ化
    $passwordConfirm = $params['password_confirmation'];
    $passwordConfirm = hash('sha256', $passwordConfirm); //ハッシュ化

    // email欄が空だったらエラー吐く
    if ($email === "") {
        $errorMessage = "そのemailもしくはpasswordが違います";
        sendResponse($errorMessage);
    }

    // emailに入力された値と一致する行をdbから拾ってくる
    $selectUserByEmail = Db::getPdo()->prepare('SELECT email, password FROM users WHERE email = :email');
    $selectUserByEmail->bindValue(':email', $email, PDO::PARAM_STR);
    try {
        $selectUserByEmail->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserByEmailFetchAllResult = $selectUserByEmail->fetchAll(PDO::FETCH_ASSOC);

    // 一致するものがなかったらエラー吐く
    if (count($selectUserByEmailFetchAllResult) === 0) {
        $errorMessage = "そのemailもしくはpasswordが違います";
        sendResponse($errorMessage);
    }

    // 一致するものがあったら値取り出す
    $selectedEmail = $selectUserByEmailFetchAllResult[0]['email'];
    $selectedPassword = $selectUserByEmailFetchAllResult[0]['password'];

    // パスワード一致チェック
    if ($password != $passwordConfirm || $password != $selectedPassword) {
        $errorMessage = "そのemailもしくはpasswordが違います";
        sendResponse($errorMessage);
    }

    // dbからemailが一致するレコードを取得して返却
    $selectUserAgainByEmail = Db::getPdo()->prepare('SELECT * FROM users WHERE email = :email');
    $selectUserAgainByEmail->bindValue(':email', $selectedEmail, PDO::PARAM_STR);
    try {
        $selectUserAgainByEmail->execute();
    } catch (Exception $e) {
        sendResponse($e);
    }
    $selectUserAgainByEmailFetchAllResult = $selectUserAgainByEmail->fetchAll(PDO::FETCH_ASSOC);
    unset($selectUserAgainByEmailFetchAllResult[0]['password']); // 配列からpassword要素を削除
    sendResponse($selectUserAgainByEmailFetchAllResult[0]);
}
