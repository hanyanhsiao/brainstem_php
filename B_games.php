<?php

include("Conn.php");

$sql = "SELECT * FROM GAME_DATA";

// 执行查询
$statement = $pdo->prepare($sql);
$statement->execute();

// 抓出全部且封装成一个二维数组
$games = $statement->fetchAll(PDO::FETCH_ASSOC);

// 关闭数据库连接
unset($pdo);

// 输出所有会员数据
echo json_encode($games);




?>