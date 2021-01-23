<?php
  require '../../common/auth.php';
  require '../../common/database.php';

  if(!isLogin()){
    header('Location: ../login/');
    exit;
  }

  $id= $_GET['id'];
  $user_id = getLoginUserId();

  $database_handler = getDatabaseConnection();
  $sql = 'select id, title, content from memos where id= :id and user_id = :user_id';
  if($statement = $database_handler->prepare($sql)){
    $statement->bindParam(":id", $id);
    $statement->bindParam("user_id", $user_id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
  }

  $_SESSION['select_memo'] = [
    'id'=> $result['id'],
    'title'=> $result['title'],
    'content'=> $result['content']
  ];
  header('Location: ../../memo/');
  exit;
?>