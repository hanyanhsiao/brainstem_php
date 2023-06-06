<?php
// 包含 Conn.php 文件
include("Conn.php");

// 设置CORS标头，允许 'content-type' 标头字段。
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

// 获取从表单提交的数据
$name = $_POST['name'];
$nickname = $_POST['nickname'];
$phone = $_POST['phone'];
$gender = $_POST['gender'];
$email = $_POST['email'];

// 检查是否有上传图片
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
  // 处理图像文件的上传
  $imageDir = "./pic/img/member_photo/"; // 图像文件存储目录
  $image = $_FILES['image']; // 获取图像文件

  $tmpName = $image['tmp_name']; // 临时文件路径
  $extension = pathinfo($image['name'], PATHINFO_EXTENSION); // 图像文件扩展名

  // 生成唯一的文件名
  $fileName = uniqid() . '.' . $extension;

  // 移动文件到目标目录
  $targetPath = $imageDir . $fileName;
  if (move_uploaded_file($tmpName, $targetPath)) {
    // 文件移动成功，将文件路径保存到数据库
    $sql = "UPDATE MEMBER_DATA SET USERNAME = ?, NICKNAME = ?, PHONE = ?, GENDER = ?, MEMBER_PHOTO = ? WHERE MEMBER_ACCOUNT = ?";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1, $name);
    $statement->bindValue(2, $nickname);
    $statement->bindValue(3, $phone);
    $statement->bindValue(4, $gender);
    $statement->bindValue(5, $targetPath);
    $statement->bindValue(6, $email);

    $affectedRow = $statement->execute();

    if ($affectedRow > 0) {
      echo '更新成功';
    } else {
      echo '发生错误：' . $statement->errorInfo()[2];
    }
  } else {
    echo '文件上传失败';
  }
} else {
  // 没有上传图片的情况下，直接将其他数据保存到数据库
  $sql = "UPDATE MEMBER_DATA SET USERNAME = ?, NICKNAME = ?, PHONE = ?, GENDER = ? WHERE MEMBER_ACCOUNT = ?";
  $statement = $pdo->prepare($sql);
  $statement->bindValue(1, $name);
  $statement->bindValue(2, $nickname);
  $statement->bindValue(3, $phone);
  $statement->bindValue(4, $gender);
  $statement->bindValue(5, $email);

  $affectedRow = $statement->execute();

  if ($affectedRow > 0) {
    echo '更新成功';
  } else {
    echo '发生错误：' . $statement->errorInfo()[2];
  }
}

