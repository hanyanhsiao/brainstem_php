<?php


include("Conn.php");

if (isset($_GET['id'])) {
  // 获取URL中的ID参数
  $couponId = $_GET['id'];

  // 撰写 SQL 查询，根据ID筛选数据
  $sql = "SELECT * FROM COUPON WHERE COUPON_ID = :couponId";

  // 执行查询
  $statement = $pdo->prepare($sql);
  $statement->bindParam(':couponId', $couponId);
  $statement->execute();

  // 获取单个的详细信息
  $coupon = $statement->fetch(PDO::FETCH_ASSOC);

  // 关闭数据库连接
  unset($pdo);

  // 输出详细信息
  echo json_encode($coupon);
} else {
  // 撰写 SQL 查询，获取所有数据
  $sql = "SELECT * FROM COUPON";

  // 执行查询
  $statement = $pdo->prepare($sql);
  $statement->execute();

  // 抓出全部且封装成一个二维数组
  $coupons = $statement->fetchAll(PDO::FETCH_ASSOC);

  // 关闭数据库连接
  unset($pdo);

  // 输出所有数据
  echo json_encode($coupons);
}
