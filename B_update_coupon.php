<?php
include("Conn.php");

header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 获取POST请求中的数据
  $couponId = $_POST['id'];
  $couponName = $_POST['name'];
  $couponDetail = $_POST['detail'];
  $couponBegin = $_POST['begin'];
  $couponEnd = $_POST['end'];
  $couponDiscount = $_POST['discount'];
  $minimumLimit = $_POST['limit'];

  // 更新数据库中的优惠券记录
  $sql = "UPDATE COUPON SET
            COUPON_NAME = :name,
            COUPON_DETAIL = :detail,
            COUPON_BEGIN = :begin,
            COUPON_END = :end,
            COUPON_DISCOUNT = :discount,
            MINIMUM_LIMIT = :limit
          WHERE COUPON_ID = :couponId";

  $statement = $pdo->prepare($sql);
  $statement->bindParam(':name', $couponName);
  $statement->bindParam(':detail', $couponDetail);
  $statement->bindParam(':begin', $couponBegin);
  $statement->bindParam(':end', $couponEnd);
  $statement->bindParam(':discount', $couponDiscount);
  $statement->bindParam(':limit', $minimumLimit);
  $statement->bindParam(':couponId', $couponId);

  if ($statement->execute()) {
    // 更新成功
    $response = array('status' => 'success', 'message' => '优惠券已成功更新');
    echo json_encode($response);
  } else {
    // 更新失败
    $response = array('status' => 'error', 'message' => '优惠券更新失败');
    echo json_encode($response);
  }

  // 关闭数据库连接
  unset($pdo);
} else {
  // 非POST请求，返回错误信息
  $response = array('status' => 'error', 'message' => '无效的请求');
  echo json_encode($response);
}
?>
