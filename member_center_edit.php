<?php
// 包含 Conn.php 文件
include("Conn.php");


// 設定CORS標頭，允許 'content-type' 標頭欄位。
 header("Access-Control-Allow-Methods: GET, POST");
 header("Access-Control-Allow-Headers: Content-Type");
 header("Access-Control-Allow-Credentials: true");

  // 獲取從表單提交的資料
  $name = $_POST['name'];
  $nickname = $_POST['nickname'];
  $phone = $_POST['phone'];
  $gender = $_POST['gender'];
  $email = $_POST['email'];

  $sql = "UPDATE MEMBER_DATA SET USERNAME = ?, NICKNAME = ?, PHONE = ?,  GENDER = ? WHERE MEMBER_ACCOUNT = ?";
  $statement = $pdo->prepare($sql);
  $statement->bindValue(1, $name);
  $statement->bindValue(2, $nickname);
  $statement->bindValue(3, $phone);
  $statement->bindValue(4, $gender);
  $statement->bindValue(5, $email); 


  $affectedRow = $statement->execute();

  if ($affectedRow > 0) {
    echo '註冊成功';
  } else {
    echo '發生錯誤：' . $statement->errorInfo()[2];
  }

?>
