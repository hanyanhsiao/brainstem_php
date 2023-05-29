<?php
session_start();

include("Conn.php");

// =========請求==========
// 設定CORS標頭，允許 'content-type' 標頭欄位。
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");


// OPTIONS 请求方法是 CORS 的预检请求，用于确定是否可以发送实际的 POST 请求。在某些情况下，浏览器会发送 OPTIONS 请求以检查服务器是否允许跨域访问。
// 对于 OPTIONS 请求，只发送 CORS 头信息，不做其他处理
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}


// =========接收資料==========

// 確保 Content-Type 是 application/json
header('Content-Type: application/json');

// 解析 JSON 資料
$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'];
$name =  $data['name'];
$photo =  $data['photo'];



// =========資料庫比對==========

// 1 先透過php找$_SESSION看裡面有沒有這個EMAIL存在(看登入狀態)
$jsonArray = null;

if (isset($_SESSION['member_account']) ) {
    // echo '該 EMAIL 存在於 $_SESSION 中。';

    $jsonArray = array(
        'redirect' =>  $php_url.'/index.html'
    ); 

} else {
    // echo '該 EMAIL 不存在於 $_SESSION 中。';
    // 2 php把會員帳號撈出，看是否註冊過
    $sql = "SELECT * FROM MEMBER_DATA WHERE MEMBER_ACCOUNT = ? ";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1,$email);
    $statement->execute();
    $data = $statement->fetchAll();
    // 建立最終的 JSON 格式陣列

    if(count($data)>0){
        //已註冊過，導回首頁 

        $_SESSION["member_account"]= $email;
        $jsonArray = array(
            'redirect' =>  $php_url.'/index.html'
        );    
    }else{
        //首次註冊，導回首頁

        date_default_timezone_set("Asia/Taipei");
        $sql = "INSERT INTO MEMBER_DATA (MEMBERSHIP_NUMBER,NICKNAME,USERNAME,GENDER,MEMBER_ACCOUNT,MEMBER_PASSWORD,PHONE,MEMBER_PHOTO,BIRTHDAY,REGISTRATION_DATE,MEMBER_STATUS) 
        VALUES (? , ? , ? , ? , ? , ? ,? , ? , ? ,? , ? )";  
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, date("YmdHis"));
        $statement->bindValue(2, "");
        $statement->bindValue(3, $name);
        $statement->bindValue(4, "");
        $statement->bindValue(5, $email);
        $statement->bindValue(6, "");
        $statement->bindValue(7, "");
        $statement->bindValue(8, $photo);
        $statement->bindValue(9, date("Y-m-d"));
        $statement->bindValue(10, date("Y-m-d"));
        $statement->bindValue(11, 1);

        $statement->execute();
        $_SESSION["member_account"]= $email;

        $jsonArray = array(
            'redirect' =>  $php_url.'/index.html'
        );   
    }

        // 關閉資料庫連線
        unset($pdo);
}

// 輸出 JSON
echo json_encode($jsonArray);

?>
