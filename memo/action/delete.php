<?php
  require '../../common/auth.php';
  require '../../common/database.php';

  if(!isLogin()){
    header('Location: ../login/');
    exit;
  }

  $edit_id = $_POST['edit_id'];
  $user_id = getLoginUserId();

  $database_handler = getDatabaseConnection();

  try{
    $sql = 'delete from memos where id = :id and user_id = :user_id';
    if($statement = $database_handler->prepare($sql)){
      $statement->bindParam(':id', $edit_id);
      $statement->bindParam(':user_id', $user_id);
      $statement->execute();
    }
  }catch(Throwable $e){
    echo $e->getMessage();
    exit;
  }

  unset($_SESSION['select_memo']);
  header('Location: ../../memo');
  exit;
  
?>