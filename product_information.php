<?php

// 此檔為範例，請依據個人需求做更改

//資料庫連線(這行一定要寫!!!!!!!!!)
include("Conn.php");

// 撰寫 SQL 查詢
$sql = 
"WITH a AS (
	SELECT gd.*, 
    dc.activity_id, dc.discount_percentage,
	dc.discount_begin, dc.discount_end,
     cate.category_name 
	FROM `GAME_DATA` gd  
	LEFT JOIN discount dc ON gd.GAME_ID = dc.GAME_ID
	LEFT JOIN G_C_RELATION gc ON gd.game_id = gc.game_id
	LEFT JOIN category cate ON gc.category_id = cate.category_id
) 
SELECT DISTINCT a.game_id,
a.game_name,
a.game_cover,
a.game_intro,
a.original_price,
a.game_status,
a.release_date,
a.publisher,
a.developer,
a.rating_id,
a.system_requirement,
a.total_comments,
a.purchased,
ANY_VALUE(a.activity_id) as ACTIVITY_ID,
ANY_VALUE(a.discount_percentage) as DISCOUNT_PERCENTAGE,
ANY_VALUE(a.discount_begin) as DISCOUNT_BEGIN,
ANY_VALUE(a.discount_end) as DISCOUNT_END,

 GROUP_CONCAT(a.category_name SEPARATOR ',') AS game_cate
		from a 
        LEFT JOIN discount dc ON a.GAME_ID = dc.GAME_ID";

$sql_where = " where a.game_id = ?";    
$sql_group_by = " GROUP BY a.game_id;";
        



//判斷前面傳來的id是否為空，是的話就是列表頁，不拚where條件的部分，不是就拚where條件
 if(empty($_GET["id"])){
   $sql = $sql.$sql_group_by;
   $statement = $pdo->prepare($sql);
 }else{
   $sql =  $sql.$sql_where.$sql_group_by;
   $statement = $pdo->prepare($sql);
   $statement->bindValue(1,$_GET["id"]);
 }


// 執行查詢

$statement->execute();

//抓出全部且依照順序封裝成一個二維陣列
$product_list  = $statement->fetchAll();
echo json_encode($product_list)

// // -----------在這下面做資料處理回傳你想要的格式-----------------

// if (count($testResult) > 0) {
//     foreach($testResult as $index => $testRow){

//     }
// }
// // -----------在這上面做資料處理回傳你想要的格式-----------------

// 關閉資料庫連線
// unset($pdo); 

// // 轉換成 JSON 格式輸出 
// echo json_encode($jsonArray);
?>
