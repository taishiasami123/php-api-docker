<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: *');
  header('Access-Control-Allow-Methods: *');

  $_GET['page'] = 1;
  $_GET['limit'] = 1;
  $_GET['query'] = 1;

  // jsonを返して死ぬやつ
  function sendResponse($obj) {
    echo json_encode($obj);
    die();
  }

  // $requestUri = $_SERVER['REQUEST_URI'];
  // if (strpos($requestUri, '?') != false) {
  //   $paramsExcludedUri = split('?', $requestUri);
  // }

  sendResponse('test');
?>