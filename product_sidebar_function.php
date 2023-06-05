<?php
include("Conn.php");

$filterList_json = $_GET['filterlist'];
$filterList_json = json_decode($filterList_json, true);
$cateFilterList = implode(",",$filterList_json['cateFilterList']);
$saleFilterList = implode(",",$filterList_json['saleFilterList']);
$maxPrice = $filterList_json['maxPrice'];
$minPrice = $filterList_json['minPrice'];

echo $_GET['filterList'];
// 撰寫 SQL 查詢
$sql = 
"WITH GAME AS (
SELECT GD.*, 
    AC.ACTIVITY_ID, AC.DISCOUNT_PERCENTAGE,
    AC.ACTIVITY_BEGIN, AC.ACTIVITY_END,CATE.CATEGORY_ID,
    CATE.CATEGORY_NAME,GR.RATING_NAME
FROM GAME_DATA GD  
LEFT JOIN G_A_RELATION GA ON GD.GAME_ID = GA.GAME_ID
LEFT JOIN ACTIVITY AC ON GA.ACTIVITY_ID = AC.ACTIVITY_ID	
LEFT JOIN G_C_RELATION GC ON GD.GAME_ID = GC.GAME_ID
LEFT JOIN CATEGORY CATE ON GC.CATEGORY_ID = CATE.CATEGORY_ID
LEFT JOIN GAME_RATING GR ON GD.RATING_ID = GR.RATING_ID
) 
SELECT 
    GAME.GAME_ID,
    GAME.GAME_NAME,
    GAME.GAME_COVER,
    GAME.GAME_INTRO,
    GAME.ORIGINAL_PRICE,
    GAME.GAME_STATUS,
    GAME.RELEASE_DATE,
    GAME.PUBLISHER,
    GAME.DEVELOPER,
    GAME.RATING_NAME,
    GAME.SYSTEM_REQUIREMENT,
    GAME.TOTAL_COMMENTS,
    GAME.PURCHASED,
    ANY_VALUE(GAME.ACTIVITY_ID) as ACTIVITY_ID,
    ANY_VALUE(GAME.DISCOUNT_PERCENTAGE) as DISCOUNT_PERCENTAGE,
    ANY_VALUE(GAME.ACTIVITY_BEGIN) as ACTIVITY_BEGIN,
    ANY_VALUE(GAME.ACTIVITY_END) as ACTIVITY_END,
    ANY_VALUE(GROUP_CONCAT(GAME.CATEGORY_ID SEPARATOR ',')) as GAME_CATE_ID

from GAME 
LEFT JOIN G_A_RELATION GA ON GAME.GAME_ID = GA.GAME_ID
where GAME.ACTIVITY_ID in(?) and GAME.CATEGORY_ID in(?) and GAME.ORIGINAL_PRICE between ? and ?
GROUP BY GAME.GAME_ID;";

// 執行查詢
$statement = $pdo->prepare($sql);
$statement->bindValue(1, $saleFilterList);
$statement->bindValue(2, $cateFilterList);
$statement->bindValue(3, $minPrice);
$statement->bindValue(4, $maxPrice);
$statement->execute();



//抓出全部且依照順序封裝成一個二維陣列
$testResult  = $statement->fetchAll();

// 建立最終的 JSON 格式陣列
$jsonArray = array();

// -----------在這下面做資料處理回傳你想要的格式-----------------

// if (count($testResult) > 0) {
//     foreach($testResult as $index => $testRow){

//     }
// }
// -----------在這上面做資料處理回傳你想要的格式-----------------

// 關閉資料庫連線
// unset($pdo); 

// 轉換成 JSON 格式輸出 
echo json_encode($jsonArray);
?>
