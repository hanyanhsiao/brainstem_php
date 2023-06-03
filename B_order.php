<?php

include("Conn.php");

if (isset($_GET['id'])) {
  // 获取URL中的会员ID参数
  $orderId = $_GET['id'];

  $sql = "SELECT OD.ORDER_ID, OD.ORDER_CODE, OD.MEMBER_ID, MD.MEMBERSHIP_NUMBER, MD.NICKNAME, MD.USERNAME, MD.GENDER, MD.MEMBER_ACCOUNT, MD.MEMBER_PASSWORD, MD.PHONE, MD.MEMBER_PHOTO, MD.BIRTHDAY, MD.REGISTRATION_DATE, MD.MEMBER_STATUS, OD.DETAIL_SUM, OD.COUPON_ID, CP.COUPON_CODE, CP.COUPON_NAME, CP.COUPON_DETAIL, CP.COUPON_BEGIN, CP.COUPON_END, CP.COUPON_DISCOUNT, CP.MINIMUM_LIMIT, CP.COUPON_STATUS, OD.ORDER_DATE, OD.SUM_PRICE, ODT.GAME_ID, GD.GAME_NAME, GD.GAME_COVER, GD.GAME_INTRO, GD.ORIGINAL_PRICE AS GAME_ORIGINAL_PRICE, GD.GAME_STATUS, GD.RELEASE_DATE, GD.PUBLISHER, GD.DEVELOPER, GD.RATING_ID, GD.SYSTEM_REQUIREMENT, GD.TOTAL_COMMENTS, ODT.ORIGINAL_PRICE, ODT.DISCOUNT_PERCENTAGE, ODT.AFTER_DISCOUNT_PRICE
  FROM ORDER_DATA OD
  JOIN MEMBER_DATA MD ON OD.MEMBER_ID = MD.MEMBER_ID
  JOIN COUPON CP ON OD.COUPON_ID = CP.COUPON_ID
  JOIN ORDER_DETAIL ODT ON OD.ORDER_ID = ODT.ORDER_ID
  JOIN GAME_DATA GD ON ODT.GAME_ID = GD.GAME_ID
  WHERE OD.ORDER_ID = :orderId"; 

  // 执行查询
  $statement = $pdo->prepare($sql);
  $statement->bindParam(':orderId', $orderId);
  $statement->execute();

  // 获取单个会员的详细信息
  $order = $statement->fetch(PDO::FETCH_ASSOC);

  // 进行商品折扣计算
  $originalPrice = $order['ORIGINAL_PRICE'];
  $discountPercentage = $order['DISCOUNT_PERCENTAGE'];
  $couponDiscount = $order['COUPON_DISCOUNT'];

  // 根据折扣条件判断是否应用折扣
  if ($originalPrice > 1000) {
    // 根据折扣计算折扣后的价格
    $afterDiscountPrice = $originalPrice - $discountPercentage;
  } else {
    // 不满足折扣条件时，折扣后的价格与原价格相同
    $afterDiscountPrice = $originalPrice;
  }

  // 将折扣后的价格保存到订单数据中
  $order['AFTER_DISCOUNT_PRICE'] = $afterDiscountPrice;

  // 计算最终价格（折扣后价格减去优惠券折扣）
  $order['SUM_PRICE'] = $afterDiscountPrice - $couponDiscount;

  // 关闭数据库连接
  unset($pdo);

  // 输出会员详细信息
  echo json_encode($order);
} else {
  // 撰写 SQL 查询，获取所有会员数据
  $sql = "SELECT OD.ORDER_ID, OD.ORDER_CODE, MD.MEMBERSHIP_NUMBER, MD.MEMBER_ACCOUNT, OD.ORDER_DATE, SUM(OD.SUM_PRICE) AS TOTAL_SUM_PRICE
  FROM ORDER_DATA OD
  JOIN MEMBER_DATA MD ON OD.MEMBER_ID = MD.MEMBER_ID
  GROUP BY OD.ORDER_ID, OD.ORDER_CODE, MD.MEMBERSHIP_NUMBER, MD.MEMBER_ACCOUNT, OD.ORDER_DATE";

  // 执行查询
  $statement = $pdo->prepare($sql);
  $statement->execute();

  // 抓出全部且封装成一个二维数组
  $orders = $statement->fetchAll(PDO::FETCH_ASSOC);

  // 进行商品折扣计算
  foreach ($orders as &$order) {
    $originalPrice = $order['TOTAL_SUM_PRICE'];
    $discountPercentage = 500; // 折扣金额

    // 根据折扣计算折扣后的价格
    if ($originalPrice > 1000) {
      $afterDiscountPrice = $originalPrice - $discountPercentage;
    } else {
      $afterDiscountPrice = $originalPrice;
    }
    
    // 将折扣后的价格保存到订单数据中
    $order['AFTER_DISCOUNT_PRICE'] = $afterDiscountPrice;
  }

  // 关闭数据库连接
  unset($pdo);

  // 输出所有订单数据
  echo json_encode($orders);
}

?>