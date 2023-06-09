<?php

// 資料庫連線(這行一定要寫!!!!!!!!!)
include("Conn.php");

// 確認收到的請求方法是 POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 確認收到的資料
  $couponId = $_POST['couponId'];
  $couponStatus = $_POST['couponStatus'];

  // 撰寫 SQL 更新語句
  $sql = "UPDATE COUPON SET COUPON_STATUS = :status WHERE COUPON_ID = :id";

  // 預備語句，綁定參數並執行更新
  $statement = $pdo->prepare($sql);
  $statement->bindParam(':status', $couponStatus, PDO::PARAM_INT);
  $statement->bindParam(':id', $couponId, PDO::PARAM_INT);
  $statement->execute();

  // 回傳成功訊息
  $response = array(
    'success' => true,
    'message' => 'Coupon status updated successfully.'
  );
  echo json_encode($response);
} else {
  // 回傳錯誤訊息
  $response = array(
    'success' => false,
    'message' => 'Invalid request method.'
  );
  echo json_encode($response);
}

// 關閉資料庫連線
unset($pdo);
?>
