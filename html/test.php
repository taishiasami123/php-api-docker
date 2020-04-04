<?php
  function sendResponse($obj) {
    echo json_encode($obj);
    die();
  }
  sendResponse('ok!!');
?>