<?php
if(!isset($_SESSION)){
  session_start();
}

/**
 * ログイン状態チェック
 * @return boolean
 */
function isLogin(){
  if(isset($_SESSION['user'])){
    return true;
  }
  return false;
}

/**
 * ログイン中のユーザー名取得
 * @return String
 */
function getLoginUserName(){
  if(isset($_SESSION['user'])){
    $name = $_SESSION['user']['name'];

    if(7 < mb_strlen($name)){
      $name = mb_substr($name, 0, 7). "...";
    }
    return $name;
  }
  return "";
}

/**
 * ログイン中のユーザーID取得
 * @return |null
 */
function getLoginUserId(){
  if(isset($_SESSION['user'])){
    return $_SESSION['user']['id'];
  }
  return null;
}
?>