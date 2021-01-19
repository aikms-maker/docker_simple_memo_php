<?php

/**
 * empty check
 * @param $errors
 * @param $check_value
 * @param $message
 */
function emptyCheck(&$errors, $check_value, $message){
  if(empty(trim($check_value))){
    array_push($errors, $message);
  }
}

/**
 * min word size check
 * @param $errors
 * @param $check_value
 * @param $message
 * @param $min_size
 */
function minSizeCheck(&$errors, $check_value, $message, $min_size=8){
  if(mb_strlen($check_value) < $min_size){
    array_push($errors, $message);
  }
}

/**
 * max word size check
 * @param $errors
 * @param $check_value
 * @param $message
 * @param $max_size
 */
function maxSizeCheck(&$errors, $check_value, $message, $max_size=16){
  if($max_size < mb_strlen($check_value)){
    array_push($errors, $message);
  }
}

/**
 * mail max size check
 */
function maxSizeCheckForMail(&$errors, $check_value, $message, $max_size=64){
  if($max_size < mb_strlen($check_value)){
    array_push($errors, $message);
  }
}

/**
 * mail address format check
 * @param $errors
 * @param $check_value
 * @param $message
 */
function mailFormatCheck(&$errors, $check_value, $message){
  if(filter_var($check_value, FILTER_VALIDATE_EMAIL) == false){
    array_push($errors, $message);
  }
}

/**
 * 半角英数字 check
 * @param $errors
 * @param $check_value
 * @param $message
 */
function halfAlphanumericCheck(&$errors, $check_value, $message) {
  if (preg_match("/^[a-zA-Z0-9]+$/", $check_value) == false) {
      array_push($errors, $message);
  }
}

/**
 * mail duplicate check
 */
function mailDuplicationCheck(&$errors, $check_value, $message){
  $database_handler = getDatabaseConnection();
  if($statement = $database_handler->prepare('select id from users where email = :user_email')){
    $statement->bindParam('user_email', $check_value);
    $statement->execute();
  }

  $result = $statement->fetch(PDO::FETCH_ASSOC);
  if($result){
    array_push($errors, $message);
  }
}
?>