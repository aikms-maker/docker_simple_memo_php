<?php
/**
 * PDOを使用してDBに接続
 */
function getDatabaseConnection(){
  try{
    $database_handler = new PDO ('mysql:host=db;dbname=simple_memo;charset=utf8mb4', 'root', 'password');
  }catch(PDOException $e){
    echo "FAILL TO CONNECT DB";
    echo $e->getMessage();
    exit;
  }
  return $database_handler;
}
?>