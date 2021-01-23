<?php 
  session_start();
  require '../../common/validation.php';
  require '../../common/database.php';

  // パラメーター取得
  $user_email = $_POST['user_email'];
  $user_password = $_POST['user_password'];

  // バリデーション
  $_SESSION['errors'] = [];

  emptyCheck($_SESSION['errors'], $user_email, "メールアドレスを入力してください。");
  emptyCheck($_SESSION['errors'], $user_password, "パスワードを入力してください。");

  // size check
  maxSizeCheckForMail($_SESSION['errors'], $user_email, "メールアドレスは64文字以内で入力してください。");
  maxSizeCheck($_SESSION['errors'], $user_password, "パスワードは16文字以内で入力してください。");
  minSizeCheck($_SESSION['errors'], $user_password, "パスワードは8文字以上入力してください。");

  if(!$_SESSION['errors']){
    // mail check
    mailFormatCheck($_SESSION['errors'], $user_email, "正しいメールアドレスを入力してください。");

    // 半角英数 check
    halfAlphanumericCheck($_SESSION['errors'], $user_password, "パスワードは半角英数で入力してください。");
  }

  if($_SESSION['errors']){
    header('Location: ../../login/');
    exit;
  }


  try{
    // login
    $database_handler = getDatabaseConnection();
    $sql = 'select id, name, password from users where email = ?';
    $statement = $database_handler->prepare($sql);
    $statement->execute([$user_email]);

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if(!$user){
      $_SESSION['errors'] = ['メールアドレスが間違っています。'];
      header('Location: ../../login');
      exit;
    };

    $id = $user['id'];
    $name = $user['name'];
    $password = $user['password'];

    if(!password_verify($user_password, $password)){
      $_SESSION['errors'] = ['パスワードが間違っています。'];
      header('Location: ../../login');
      exit;
    }

    $_SESSION['user'] = [
      'name' => $name,
      'id' => $id
    ];

    $sql = 'select id, title, content from memos where user_id = ? order by updated_at desc limit 1';
    $statement = $database_handler->prepare($sql);
    $statement->execute([$id]);

    $target_memo = $statement->fetch(PDO::FETCH_ASSOC);

    if($target_memo){
      $_SESSION['select_memo'] = [
        'id'=> $target_memo['id'],
        'title'=> $target_memo['title'],
        'content'=> $target_memo['content']
      ];
    }


  }catch(Throwable $e){
    echo $e->getMessage();
    exit;
  }

  header('Location: ../../memo/');
  exit;
?>