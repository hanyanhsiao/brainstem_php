<?php

// 此檔為範例，請依據個人需求做更改

//資料庫連線(這行一定要寫!!!!!!!!!)
include("Conn.php");

// 撰寫 SQL 查詢
$sql = "SELECT * FROM MEMBER_DATA";

// 執行查詢
$statement = $pdo->prepare($sql);
$statement->execute();

//抓出全部且依照順序封裝成一個二維陣列
$testResult  = $statement->fetchAll();

// 建立最終的 JSON 格式陣列
$jsonArray = array();

// -----------在這下面做資料處理回傳你想要的格式-----------------

if (count($testResult) > 0) {
    foreach($testResult as $index => $row) {
        $MEMBERSHIP_NUMBER = $row['MEMBERSHIP_NUMBER'];//編號
        $MEMBER_ACCOUNT = $row['MEMBER_ACCOUNT'];//帳號=信箱
        $USERNAME = $row['USERNAME'];//姓名
        $PHONE = $row['PHONE'];//電話
        $REGISTRATION_DATE = $row['REGISTRATION_DATE'];//註冊日期
        $MEMBER_STATUS = $row['MEMBER_STATUS'];//啟用狀態
        $jsonArray []=array(
            "munber" => $MEMBERSHIP_NUMBER,
            "account" => $MEMBER_ACCOUNT,
            "name" => $USERNAME,
            "phone" => $PHONE,
            "registration_date" => $REGISTRATION_DATE,
            "status" => $MEMBER_STATUS
        ) ;
    }
}

// $jsonArray =;

// -----------在這上面做資料處理回傳你想要的格式-----------------

// 關閉資料庫連線
unset($pdo);

// 轉換成 JSON 格式輸出 
echo json_encode($jsonArray);
