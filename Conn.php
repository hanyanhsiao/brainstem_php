<?php
// ==========勿動勿動勿動勿動勿動勿動勿動勿動===========
  
    // //MySQL相關資訊
    $db_host = "127.0.0.1";
    $db_user = "root";
    $db_pass = "password";
    $db_select = "brainstem";

    // 將程式上傳到TiBaMe伺服器時，記得將PHP中資料庫連線改成以下設定..
    // $db_host = "127.0.0.1";
    // $db_user = "tibamefe_since2021";
    // $db_pass = "vwRBSb.j&K#E";
    // $db_select = "tibamefe_thd101g2";

    //建立資料庫連線物件
    $dsn = "mysql:host=".$db_host.";dbname=".$db_select.";charset=utf8";

    //建立PDO物件，並放入指定的相關資料
    $pdo = new PDO($dsn, $db_user, $db_pass);
    
    // ======================================================================
    
    // 設定CORS標頭
    header("Access-Control-Allow-Origin: http://localhost:3000");
    // header("Access-Control-Allow-Origin: https://tibamef2e.com");

    //共用伺服器參數
    $php_url = "http://localhost:3000/";
    // $php_url = "https://tibamef2e.com/thd101/g2/dist/";

    // ==========勿動勿動勿動勿動勿動勿動勿動勿動===========
 ?>