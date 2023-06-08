<?php

include("Conn.php");

// =========請求==========
// 設定CORS標頭，允許 'content-type' 標頭欄位。
header("Access-Control-Allow-Credentials: true");

// 撰寫 SQL 查詢
$sql = 
"SELECT WL.GAME_ID from WISH_LIST WL
LEFT JOIN MEMBER_DATA MD on WL.MEMBER_ID = MD.MEMBER_ID
where MD.MEMBER_ID = ?;";


// 執行查詢
$statement = $pdo->prepare($sql);
$statement->bindValue(1,$_GET["id"]);
$statement->execute();


//抓出全部且依照順序封裝成一個二維陣列
$testResult  = $statement->fetchAll();

// 建立最終的 JSON 格式陣列
// $jsonArray = array();

// -----------在這下面做資料處理回傳你想要的格式-----------------

// if (count($testResult) > 0) {
//     foreach($testResult as $index => $testRow){

//     }
// }
// -----------在這上面做資料處理回傳你想要的格式-----------------

// 關閉資料庫連線
// unset($pdo); 

// 轉換成 JSON 格式輸出 
echo json_encode($testResult);
?>
