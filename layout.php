<?php
session_start();
// Kiểm tra trạng thái đăng nhập

if (isset($_GET['logout'])) {
    // Xóa thông tin đăng nhập từ biến phiên
    unset($_SESSION['email']);

    // Hủy toàn bộ phiên
    session_destroy();

    // Chuyển hướng người dùng đến trang đăng nhập hoặc trang khác
    header("Location: index.php");
    exit;
}
$parameters = []; // Các tham số truy vấn (nếu có)
$resultType = 2; // Loại kết quả truy vấn (2: Fetch All)

$queryBookTypes = "SELECT Id, Name FROM `Type` ORDER BY Id ASC";

$bookTypeIds = DP::run_query($queryBookTypes, $parameters, $resultType);

$typedetailList = array();

foreach ($bookTypeIds as $bookType) {
    $typeId = $bookType['Id'];
    $queryTypeDetail = "SELECT * FROM typedetail WHERE TypeId = $typeId LIMIT 4";
    $typeDetails = DP::run_query($queryTypeDetail, $parameters, $resultType);

    // Hợp nhất kết quả truy vấn vào danh sách
    $typedetailList = array_merge($typedetailList, $typeDetails);
}
?>