<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: *');
  header('Access-Control-Allow-Methods: *');

  // jsonを返して死ぬやつ
  function sendResponse($obj) {
    echo json_encode($obj);
    die();
  }

  // URLから各機能の文字列を取り出す
  $requestUri = $_SERVER['REQUEST_URI'];
  if (strpos($requestUri, '?') != false) {
    $paramsExcludedUri = explode('?', $requestUri)['0'];
  } else {
    $paramsExcludedUri = $requestUri;
  }
  $substrUri = substr($paramsExcludedUri, 9, strlen($paramsExcludedUri) - 9);

  sendResponse('test');
?>