<?php
require_once('lib/db.php');
session_start();

$parameters = []; // Các tham số truy vấn (nếu có)
$resultType = 2; // Loại kết quả truy vấn (2: Fetch All)

$bookId = $_POST['Id'];
$userId = $_SESSION['Id'];
if($userId == null){
    header("Location: Login.php");
    exit;
}
echo $bookId;
echo $userId;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    // Lấy thông tin sản phẩm từ form
    $quantity = $_POST['quantity'];
    echo $quantity;
    if ($bookId !== null) {
        $userId = $_SESSION['Id'];

        // Thêm sản phẩm vào giỏ hàng
        $queryAddtoCart = "INSERT INTO `cart`(`UserId`, `BookId`, `Quantity`, `Status`) VALUES ('$userId','$bookId','$quantity','1')";
        DP::run_query($queryAddtoCart, $parameters, $resultType);

        // Chuyển hướng người dùng đến trang giỏ hàng hoặc trang khác
        exit;
    } else {
        echo "Không tìm thấy ID sách.";
    }
}
?>