<?php

include("Conn.php");
// =========請求==========
// 設定CORS標頭，允許 'content-type' 標頭欄位。

header("Access-Control-Allow-Credentials: true");
// 撰寫 SQL 查詢
$sql = 
"WITH GAME AS (
SELECT GD.*, 
    AC.ACTIVITY_ID, AC.DISCOUNT_PERCENTAGE,
    AC.ACTIVITY_BEGIN, AC.ACTIVITY_END,
    CATE.CATEGORY_NAME,GR.RATING_NAME
FROM GAME_DATA GD  
LEFT JOIN G_A_RELATION GA ON GD.GAME_ID = GA.GAME_ID
LEFT JOIN ACTIVITY AC ON GA.ACTIVITY_ID = AC.ACTIVITY_ID 	
LEFT JOIN G_C_RELATION GC ON GD.GAME_ID = GC.GAME_ID
LEFT JOIN CATEGORY CATE ON GC.CATEGORY_ID = CATE.CATEGORY_ID
LEFT JOIN GAME_RATING GR ON GD.RATING_ID = GR.RATING_ID

) 
SELECT DISTINCT 
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

    GROUP_CONCAT(GAME.CATEGORY_NAME SEPARATOR ',') AS GAME_CATE
from GAME 
LEFT JOIN G_A_RELATION GA ON GAME.GAME_ID = GA.GAME_ID";

$sql_where = " WHERE GAME.GAME_ID = ?";    
$sql_group_by = " GROUP BY GAME.GAME_ID;";
$sql_random = " GROUP BY GAME.GAME_ID ORDER BY RAND() limit 6;";
// $sql_activity = " WHERE GAME.ACTIVITY_ID =? GROUP BY GAME.GAME_ID;";
// $sql_cate = " WHERE GAME.ACTIVITY_ID =? GROUP BY GAME.GAME_ID;";

//判斷前面傳來的id是否為空，是的話就是列表頁，不拚where條件的部分，不是就拚where條件
if(isset($_GET['action'])){
    if($_GET["action"] == "recommend"){
        $sql = $sql.$sql_random;
        $statement = $pdo->prepare($sql);
        
    // }else if($_GET["action"] == "activity"){
    //     $sql = $sql.$sql_activity;
    //     $statement = $pdo->prepare($sql);
    //     $statement->bindValue(1,$_GET["id"]);
    
    }else{
        $sql =  $sql.$sql_where.$sql_group_by;
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1,$_GET["id"]);
    }
}else{
    $sql = $sql.$sql_group_by;
    $statement = $pdo->prepare($sql);
}



// 執行查詢

$statement->execute();

//抓出全部且依照順序封裝成一個二維陣列
$product_list  = $statement->fetchAll();
echo json_encode($product_list);


?>
