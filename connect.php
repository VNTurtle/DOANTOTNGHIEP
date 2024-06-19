<?php 
    $server = 'localhost';
    $user ='root';
    $password='';
    $database ='db_book';

    $conn= new mysqli($server, $user, $password, $database);

    if($conn){
        mysqli_query($conn, "SET NAMES 'utf8'");
        // echo 'Đã kết nối thành công';
    }
    else{
        echo 'Kết nối thất bại';
    }

?>