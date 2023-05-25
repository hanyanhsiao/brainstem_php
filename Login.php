<?php
       include("Conn.php");

       //---------------------------------------------------
  
       //對應到input的name
       $acc = $_POST["account"]; 
       $pas = $_POST["password"];

       //建立SQL語法
       // $sql = "SELECT * FROM member WHERE Account = '$acc' and PWD ='$pas'";
       $sql = "SELECT * FROM member WHERE Account = :account and PWD = :pwd ";
       // $sql = "SELECT * FROM member WHERE Account = ? and PWD = ? ";

       //執行並查詢，會回傳查詢結果的物件，必須使用fetch、fetchAll...等方式取得資料
       // $statement = $pdo->query($sql);
       $statement = $pdo->prepare($sql);

       // bindParam() - 自定義名稱表示
       //透過bindParam()過濾(自定義字元,變數)
       //下方bindParam()第二個參數只能是變數
       $statement->bindParam(":account",$acc);
       $statement->bindParam(":pwd",$pas);

       // bindParam() - 問號表示
       // $statement->bindParam(1,$acc);
       // $statement->bindParam(2,$pas);

       // ===========================================

       //下方bindValue()第二個參數，可以是變數，也可以是值
       $statement->bindValue(":account",$acc);
       $statement->bindValue(":pwd",$pas);

       // bindValue() - 問號表示
       // $statement->bindValue(1,$acc);
       // $statement->bindValue(2,$pas);

       $statement->execute();
       
       //==================================================
       //抓出全部且依照順序封裝成一個二維陣列
       $data = $statement->fetchAll();
       // print_r( $data);

        if(count($data)>0){
       //  echo "登入成功!";
       //  echo "<br>";
        //    將二維陣列取出顯示其值
       //  foreach($data as $index => $row){
       //  echo $row["Account"];   //欄位名稱
       //  echo " / ";
       //  echo $row["PWD"];    //欄位名稱
       //  echo " / ";
       //  echo $row["CreateDate"];    //欄位名稱
       // echo "<br>";
       //   }
         session_start();
         $_SESSION["memberID"]=$acc;      
         header('Location: Welcome.php');
  
       }else{
              echo "登入失敗!";
       }
    
?>