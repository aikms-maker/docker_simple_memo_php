<?php
  
  session_start();
  require '../../common/validation.php';
  require '../../common/database.php';

  // パラメータ取得
  $user_name = $_POST['user_name'];
  $user_email = $_POST['user_email'];
  $user_password = $_POST['user_password'];

  // バリデーション
  $_SESSION['errors'] = [];

  // empty check
  emptyCheck($_SESSION['errors'], $user_name, "ユーザー名を入力してください。");
  emptyCheck($_SESSION['errors'], $user_email, "メールアドレスを入力してください。");
  emptyCheck($_SESSION['errors'], $user_password, "パスワードを入力してください。");

  // size check
  maxSizeCheck($_SESSION['errors'], $user_name, "ユーザー名は16文字以内で入力してください。");
  maxSizeCheckForMail($_SESSION['errors'], $user_email, "メールアドレスは64文字以内で入力してください。");
  maxSizeCheck($_SESSION['errors'], $user_name, "パスワードは16文字以内で入力してください。");
  minSizeCheck($_SESSION['errors'], $user_password, "パスワードは8文字以上入力してください。");

  if(!$_SESSION['errors']){
    // mail format check
    mailFormatCheck($_SESSION['errors'], $user_email, "正しいメールアドレスを入力してください。");

    // 半角英数 check
    halfAlphanumericCheck($_SESSION['errors'], $user_name, "ユーザー名は半角英数で入力してください。");
    halfAlphanumericCheck($_SESSION['errors'], $user_password, "パスワードは半角英数で入力してください。");

    // mail duplicate check
    mailDuplicationCheck($_SESSION['errors'], $user_email, "既に登録されているメールアドレスです。");

  }

  if($_SESSION['errors']){
    header('Location: ../../user/');
    exit;
  }

  // connect DB
  $database_handler = getDatabaseConnection();

  try{
    if($statement = $database_handler->
    prepare('insert into users (name, email, password) values (:name, :email, :password)')){
      $password = password_hash($user_password, PASSWORD_DEFAULT);

      $statement->bindParam(':name', htmlspecialchars($user_name));
      $statement->bindParam(':email', $user_email);
      $statement->bindParam(':password', $password);
      $statement->execute();
    }
  }catch (Throwable $e){
    echo $e->getMessage();
    exit;
  }

  // リダイレクト
  header('Location: ../../memo/');
  exit;
?>