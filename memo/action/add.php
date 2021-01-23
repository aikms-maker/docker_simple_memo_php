<?php
  require '../../common/auth.php';
  require '../../common/database.php';

  if(!isLogin()){
    header('Location: ../login/');
    exit;
  }

  $user_id = getLoginUserId();
  $database_handler = getDatabaseConnection();

  try{
    $title = "新規メモ";
    $sql = 'insert into memos (user_id, title, content) value (:user_id, :title, null)';
    $statement = $database_handler->prepare($sql);
    $result = $statement->execute(array(
      ':user_id' => $user_id,
      ':title' => $title
    ));
    
    $_SESSION['select_memo'] = [
      'id' => $database_handler->lastInsertId(),
      'title' => $title,
      'content' => '',
    ];

  }catch(Throwable $e){
    echo $e->getMessage();
    exit;
  }
  header('Location: ../../memo');
  exit;
?>