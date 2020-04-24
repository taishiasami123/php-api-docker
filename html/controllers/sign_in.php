<?php
// ログイン機能
function signIn()
{
    // jsonを取得
    $json = file_get_contents('php://input');
    $params = json_decode($json, true)['sign_in_user_params'];
    $email = $params['email'];
    $password = $params['password'];
    $password = hash('sha256', $password);  // ハッシュ化
    $passwordConfirm = $params['password_confirmation'];
    $passwordConfirm = hash('sha256', $passwordConfirm); //ハッシュ化

    // email欄が空だったらエラー吐く
    if ($email === '') {
        $errorMessage = 'そのemailもしくはpasswordが違います';
        sendResponse($errorMessage);
    }

    // emailに入力された値と一致する行をdbから拾ってくる
    $selectUserByEmailFromUsersFetchAllResult = Db::selectUserByEmailFromUsersFetchAll($email);

    // 一致するものがなかったらエラー吐く
    if (count($selectUserByEmailFromUsersFetchAllResult) === 0) {
        $errorMessage = 'そのemailもしくはpasswordが違います';
        sendResponse($errorMessage);
    }

    // 一致するものがあったら値取り出す
    $emailFromUserTable = $selectUserByEmailFromUsersFetchAllResult[0]['email'];
    $passwordFromUsersTable = $selectUserByEmailFromUsersFetchAllResult[0]['password'];

    // パスワード一致チェック
    if ($password !== $passwordConfirm || $password !== $passwordFromUsersTable) {
        $errorMessage = 'そのemailもしくはpasswordが違います';
        sendResponse($errorMessage);
    }

    // dbからemailが一致するレコードを取得して返却
    $selectUserAgainByEmailFromUsersFetchAllResult = Db::selectUserByEmailFromUsersFetchAll($emailFromUserTable);
    unset($selectUserAgainByEmailFromUsersFetchAllResult[0]['password']); // 配列からpassword要素を削除
    sendResponse($selectUserAgainByEmailFromUsersFetchAllResult[0]);
}
