<?php

include("Conn.php");

if (isset($_GET['id'])) {
    // 获取URL中的游戏ID参数
    $gameid = $_GET['id'];

    // 撰写 SQL 查询，根据游戏ID筛选数据
    $sql = "SELECT gd.GAME_ID, gi.IMG_PATH, gi.IMG_DESCRIPTION
    FROM GAME_DATA gd
    JOIN GAME_IMG gi ON gd.GAME_ID = gi.GAME_ID
    WHERE gd.GAME_ID = :gameid;";


    // 执行查询
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':gameid', $gameid);
    $statement->execute();

    // 获取单个游戏的详细信息
    $game = $statement->fetchAll(PDO::FETCH_ASSOC);
    // $systemRequirement = $game['SYSTEM_REQUIREMENT'];
    // echo $systemRequirement;

    // 关闭数据库连接
    unset($pdo);

    // 输出游戏详细信息
    echo json_encode($game);
} else {
    echo "缺少游戏ID参数";
}
?>