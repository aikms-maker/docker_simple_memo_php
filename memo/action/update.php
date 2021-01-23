<?php
  require '../../common/auth.php';
  require '../../common/database.php';

  if(!isLogin()){
    header('Location: ../login/');
  }

  $edit_id = $_POST['edit_id'];
  $edit_title = $_POST['edit_title'];
  $edit_content = $_POST['edit_content'];

  $user_id = getLoginUserId();

  $database_handler = getDatabaseConnection();

  try{
    $sql = 'update memos set title = :title, content = :content, updated_at = now() where id = :id and user_id = :user_id';
    if($statement = $database_handler->prepare($sql)){
      $statement->bindParam(':title', $edit_title);
      $statement->bindParam(':content', $edit_content);
      $statement->bindParam(':id', $edit_id);
      $statement->bindParam(':user_id', $user_id);
      $statement->execute();
    }

    $sql = 'select id, title, content from memos where id = :id, and user_id = :user_id';
    if($statement = $database_handler->prepare($sql)){
      $statement->bindParam(':id', $edit_id);
      $statement->bindParam(':user_id', $user_id);
      $statement->execute();

      $result = $statement->fetch(PDO::FETCH_ASSOC);
      $_SESSION['select_memo'] = [
        'id'=> $result['id'],
        'title'=> $result['title'],
        'content'=> $result['content']
      ];
    }
  }catch(Throwable $e){
    echo $e->getMessage();
    exit;
  }

  header('Location: ../../memo');
  exit;
?>