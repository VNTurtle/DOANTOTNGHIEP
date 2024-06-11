<?php
require_once('lib/db.php');
require_once('layout.php');
// Lấy giá trị Id từ tham số truyền vào
if (isset($_GET['id'])) {
    $bookId = $_GET['id'];
}


$query = "SELECT b.*, m.Model, m.ModelBin, bt.Name AS BookTypeName, s.Name AS SizeName, p.Name AS PublisherName, cv.Name AS CovertypeName
FROM book b
LEFT JOIN model m ON b.Id = m.BookId
JOIN Type bt ON b.TypeId = bt.Id
JOIN Size s ON b.SizeId = s.Id
JOIN Publisher p ON b.PublisherId = p.Id
JOIN covertype cv ON b.CoverTypeId = cv.Id
WHERE b.Id = $bookId;";
$parameters = []; // Các tham số truy vấn (nếu có)
$resultType = 2; // Loại kết quả truy vấn (2: Fetch All)

$book = DP::run_query($query, $parameters, $resultType);

if (count($book) > 0) {
    $NameBook = $book[0]['Name'];
    $model = $book[0]['Model'];
    $modelBin = $book[0]['ModelBin'];
    $comboBookId = $book[0]['ComboBookId'];
    $typeId = $book[0]['TypeId'];
} else {
    echo "Không tìm thấy kết quả.";
    $model = null;
    $modelBin = null;
}
$queryImage = "SELECT * FROM `image` WHERE BookId=$bookId;";
$lst_Image = DP::run_query($queryImage, $parameters, $resultType);

if ($comboBookId != null) {
    $querylstBook = "SELECT  b.* , i.Path
    FROM `book` b
    JOIN 
    `image` i ON b.Id = i.BookId
    WHERE 
    i.Id = (
        SELECT MIN(i2.Id)
        FROM `image` i2
        WHERE i2.BookId = b.Id
    )AND ComboBookId = $comboBookId;
    ";
    $lstBook = DP::run_query($querylstBook, $parameters, $resultType);
} else {
    $querylstBook = "SELECT b.* , i.Path
    FROM `book` b
    JOIN 
    `image` i ON b.Id = i.BookId
    WHERE 
    i.Id = (
        SELECT MIN(i2.Id)
        FROM `image` i2
        WHERE i2.BookId = b.Id
    )AND TypeId = $typeId;
    ";
    $lstBook = DP::run_query($querylstBook, $parameters, $resultType);
}


//  Hàm chuyển đổi Tên có dấu
function removeAccents($str)
{
    $accentedChars = ['á', 'à', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'đ', 'é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'í', 'ì', 'ỉ', 'ĩ', 'ị', 'ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự', 'ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ', 'Đ', 'É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ', 'Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị', 'Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ', 'Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự', 'Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ'];
    $unaccentedChars = ['a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'd', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'y', 'y', 'y', 'y', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'D', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'Y', 'Y', 'Y', 'Y', 'Y'];

    // Đảm bảo cả hai mảng có cùng số phần tử
    if (count($accentedChars) == count($unaccentedChars)) {
        return $str; // Trả về chuỗi ban đầu nếu số lượng ký tự không khớp
    }
    return str_replace($accentedChars, $unaccentedChars, $str);
}
// Function to sanitize filenames
function sanitizeFilename($filename)
{
    // Loại bỏ các ký tự đặc biệt
    $filename = preg_replace('/[^\pL\d.]+/u', '', $filename);
    // Loại bỏ các ký tự không hợp lệ
    $filename = preg_replace('/[^\x20-\x7E]/', '', $filename);
    // Chuyển đổi tiếng Việt có dấu thành tiếng Việt không dấu
    $filename = mb_convert_encoding($filename, 'ASCII', 'UTF-8');
    // Loại bỏ các ký tự đặc biệt còn lại
    $filename = preg_replace('/[^-\w.]+/', '', $filename);
    return $filename;
}

$modelName = sanitizeFilename(removeAccents($NameBook));


// Kiểm tra xem người dùng đã nhấp vào nút "Mua ngay" hay chưa
if (isset($_POST['checkout'])) {
    // Lấy thông tin sản phẩm từ form
    $quantity = $_POST['quantity'];
    $productId = $book[0]['Id']; // ID sản phẩm, bạn cần thay thế bằng ID thật của sản phẩm

    // Thêm sản phẩm vào giỏ hàng
    // Chuyển hướng người dùng đến trang thanh toán hoặc trang khác
    header("Location: checkout.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="icon" href="img/logo-web.jpg">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/product.css">
    <link rel="stylesheet" href="vendor/sclick/css/slick.min.css">
    <link rel="stylesheet" href="vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/all.min.css">
</head>

<body>
    <div class="opacity-menu" onclick="CLoseMenu()"></div>
    <header class="header">
        <div class="container">
            <div class="row row-header align-items-center">
                <div class="menu-bar " onclick="toggeleMenu()">
                    <i style="color: #fff; margin-top: 3px; margin-left: -2px;" class="fa-solid fa-bars"></i>
                </div>
                <div class="col-lg-3">
                    <a href="/" class="logo" title="Logo">
                        <img class="Logo-header" src="img/logo-bookstore.jpg" alt="Logo">
                    </a>
                </div>
                <div class="col-lg-4 search-header">
                    <div class="InputContainer">
                        <input placeholder="Search.." id="input" class="input" name="text" type="text">
                    </div>
                </div>
                <div class="col-lg-5 header-control">
                    <ul class="ul-control">
                        <div aria-label="Orange and tan hamster running in a metal wheel" role="img" class="wheel-and-hamster">
                            <div class="wheel"></div>
                            <div class="hamster">
                                <div class="hamster__body">
                                    <div class="hamster__head">
                                        <div class="hamster__ear"></div>
                                        <div class="hamster__eye"></div>
                                        <div class="hamster__nose"></div>
                                    </div>
                                    <div class="hamster__limb hamster__limb--fr"></div>
                                    <div class="hamster__limb hamster__limb--fl"></div>
                                    <div class="hamster__limb hamster__limb--br"></div>
                                    <div class="hamster__limb hamster__limb--bl"></div>
                                    <div class="hamster__tail"></div>
                                </div>
                            </div>
                            <div class="spoke"></div>
                        </div>
                        <li class="header-favourite d-n">
                            <i style="width: 25px; height: 25px;" class="fa-solid fa-heart"></i>
                        </li>
                        <li class="header-cart ">
                            <a href="cart.php">
                                <i style="width: 25px; height: 25px; color: #000;" class="fa-solid fa-cart-shopping"></i>
                            </a>
                            
                        </li>
                        <li class="header-account d-n">
                            <i style="width: 25px; height: 25px; color: #000;" class="fa-regular fa-user"></i>
                            <ul class="Show-account">
                            <?php
                                        if (isset($_SESSION['email'])) {
                                            // Người dùng đã đăng nhập
                                            echo '
                                            <li>
                                                <a href="">Cá nhân</a>
                                            </li>
                                            <li>
                                                <a href="invoice.php">Đơn hàng</a>
                                            </li>
                                            <li>
                                                <a href="">Register</a>
                                            </li>
                                            <li>
                                                <a href="?logout=true">Logout</a>
                                            </li>';
                                        } else {
                                            // Người dùng chưa đăng nhập
                                            echo '
                                            <li>
                                                <a href="Login.php">Login</a>
                                            </li>
                                            <li>
                                                <a href="Register.php">Register</a>
                                            </li>
                                            ';
                                        }
                                    ?>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="header-menu">
                <div class="header-menu-des">
                    <nav class="header-nav">
                        <ul class="item-big">
                            <li>
                                <a href="/" class="logo-sitenav d-block d-lg-none">
                                    <img src="img/logo-bookstore.jpg" width="172" height="50" alt="">
                                </a>
                            </li>
                            <li class="d-lg-none d-block account-mb">
                                <ul>
                                    <?php
                                        if (isset($_SESSION['user'])) {
                                            // Người dùng đã đăng nhập
                                            echo '
                                            <li>
                                                <a href="">Cá nhân</a>
                                            </li>
                                            <li>
                                                <a href="">Quản lý đơn hàng</a>
                                            </li>
                                            <li>
                                                <a href="">Register</a>
                                            </li>
                                            <li>
                                                <form method="post" action="">
                                                    <input type="submit" name="logout" value="Logout">
                                                </form>
                                            </li>';
                                        } else {
                                            // Người dùng chưa đăng nhập
                                            echo '
                                            <li>
                                                <a href="Login.php">Login</a>
                                            </li>
                                            <li>
                                                <a href="Register.php">Register</a>
                                            </li>
                                            ';
                                        }
                                    ?>
                                    
                                </ul>
                            </li>
                            <li class="d-block d-lg-none title-danhmuc">
                                <span>Menu chính</span>
                            </li>
                            <li class="nav-item">
                                <a href="/">
                                    <i class="fa-solid fa-house"></i>
                                    Trang chủ
                                </a>
                            </li>
                            <li class="nav-item has-mega">
                                <a href="lst-book.php" class="caret-down">Sản phẩm</a>
                                <div class="mega-content">
                                    <div class="lst-Type-main">
                                        <ul class="level0">
                                            <?php
                                            foreach ($bookTypeIds as $key => $lst_type) {

                                            ?>
                                                <li class="level1 item parent">
                                                    <a href="lst-book.php?lst-id=<?php echo $lst_type['Id']; ?>" class="hmega"><?php echo $lst_type['Name'] ?></a>
                                                    <ul class="level1">

                                                        <?php
                                                        foreach ($typedetailList as $key => $lst_typedetail) {
                                                            if ($lst_typedetail['TypeId'] == $lst_type['Id']) {
                                                        ?>
                                                                <li class="level2">
                                                                    <a href="lst-book.php?lst-id2=<?php echo $lst_typedetail['Id'] ?>"><?php echo $lst_typedetail['Name'] ?></a>
                                                                </li>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                        <li class="level2">
                                                            <a href="lst-book.php" style="color: #09bfff;">Xem thêm</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>

                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="">Hệ thống</a>
                            </li>
                            <li class="nav-item">
                                <a href="introduce.html">Giới thiệu</a>
                            </li>
                            <li class="nav-item">
                                <a href="contact.html">Liên Hệ</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <div class="bodywrap">
        <section class="bread-crumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li class="path home">
                        <a href="index.html">
                            <span>Trang chủ</span>
                        </a>
                        <span class="mr-lr">
                            &nbsp;
                            <i class="fa-solid fa-angle-right"></i>
                            &nbsp;
                        </span>
                    </li>
                    <li class="path BookType">
                        <a href="index.html">
                            <span><?php echo $book[0]['BookTypeName'] ?></span>
                        </a>
                        <span class="mr-lr">
                            &nbsp;
                            <i class="fa-solid fa-angle-right"></i>
                            &nbsp;
                        </span>
                    </li>
                    <!-- <li class="path BookType">
                        <a href="index.html">
                            <span><?php echo $book[0]['BookTypeName'] ?></span>
                        </a>
                        <span class="mr-lr">
                            &nbsp;
                            <i class="fa-solid fa-angle-right"></i>
                            &nbsp;
                        </span>
                    </li> -->
                    <li>
                        <strong>
                            <span>
                                <?php echo $book[0]['Name'] ?>
                            </span>
                        </strong>
                    </li>
                </ul>
            </div>
        </section>
        <div class="thongbao">
            Đã thêm vào giỏ hàng 
        </div>
        <section class="product layout-product">
            <div class="container">

                <div class="details-product">
                    <div class="row">
                        <div class="product-detail-lef product-images col-3 col-md-6 col-lg-4">
                            <div class="product-image-block relative">
                                <div class="swiper-container gallery-top ">
                                    <div class="swiper-wrapper slider-for" style="justify-content: center;">
                                        <?php
                                        if ($book[0]['Model'] != null) {
                                        ?>
                                            <div class="swiper-slide swiper-slide-active" href="" style="width: 330px; justify-content: center;">
                                                <canvas id="3D-Book" class="3DImage" height="400" width="400"></canvas>
                                            </div>
                                        <?php
                                        }

                                        ?>
                                        <?php
                                        foreach ($lst_Image as $key => $img) {
                                        ?>
                                            <a class="swiper-slide swiper-slide-active" href="" style="width: 330px; justify-content: center;">
                                                <img height="400" width="400" src="img/products/<?php echo $img['Path'] ?>" alt="">
                                            </a>
                                        <?php
                                        }
                                        ?>

                                    </div>

                                </div>
                                <div class="swiper-container gallery-thumb ">
                                    <div class="swiper-wrapper slider-nav">
                                        <?php
                                        if ($book[0]['Model'] != null) {
                                        ?>
                                            <div class="swiper-slide swiper-slide-visible">
                                                <img src="img/products/ThienSuNhaBenTap2_1.jpg" alt="">
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        foreach ($lst_Image as $key => $img) {
                                        ?>
                                            <div class="swiper-slide swiper-slide-visible">
                                                <img src="img/products/<?php echo $img['Path'] ?>" alt="">
                                            </div>
                                        <?php
                                        }
                                        ?>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-8 product-detail">
                            <div class="details-pro">
                                <h1 class="title-product"><?php echo $book[0]['Name'] ?></h1>
                                <div class="inventory_quantity">
                                    <div class="thump-break">
                                        <span class="mb-break inventory">
                                            <span class="stock-brand-title">Tác giả:</span>
                                            <span class="a-stock"><?php echo $book[0]['Author'] ?></span>
                                        </span>

                                        <div class="sku-product">
                                            <span class="stock-brand-title">Mã sản phẩm:</span>
                                            <span class="a-stock"><?php echo $book[0]['SKU'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                <form id="product-form" action="" method="post" class="form-inline" enctype="multipart/form-data">
                                    <div class="price-box clearFix">
                                        <span class="special-price">
                                            <input type="hidden" name="Id" value="<?php echo $book[0]['Id']; ?>">
                                            <span class="price product-price"><?php echo $book[0]['Price'] ?>$</span>
                                            <meta itemprop="price" content="12000">
                                            <meta itemprop="priceCurrency" content="VNĐ">
                                        </span>
                                    </div>
                                    <div class="form-product">
                                        <div class="clearFix form-group">
                                            <div class="flex-quantity">
                                                <div class="custom custom-btn-number">
                                                    <label for="" class="sl section">Số lượng:</label>
                                                    <div class="input-number-product">
                                                        <button class="btn-num num-1">-</button>
                                                        <input type="text" name="quantity" value="1" maxlength="3" class="form-control prd-quantity">
                                                        <button class="btn-num num-2">+</button>
                                                    </div>
                                                </div>
                                                <div class="btn-pay button-actions clearFix">
                                                    <button class="btn-action add-to-cart" type="submit" name="add-to-cart">Thêm vào giỏ hàng</button>
                                                    <button class="btn-action pay-now" type="submit" name="checkout">Mua ngay</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="col-12 product-detail-table">
                                    <div class="title">
                                        <span>Thông tin chi tiết </span>
                                    </div>
                                    <div class="content">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th style="color: #007bff;">Công ty phát hành</th>
                                                    <td><?php echo $book[0]['PublisherName'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="color: #007bff;">Ngày xuất bản</th>
                                                    <td><?php echo $book[0]['Date'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="color: #007bff;">Kích thước</th>
                                                    <td><?php echo $book[0]['SizeName'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="color: #007bff;">Loại bìa</th>
                                                    <td><?php echo $book[0]['CovertypeName'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="color: #007bff;">Số trang</th>
                                                    <td><?php echo $book[0]['NumberPage'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="color: #007bff;">SKU</th>
                                                    <td><?php echo $book[0]['SKU'] ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg12 col-xl-12">
                            <div class="product-tab e-tabs not-dqtab" id="tab-product">
                                <ul class="tabs tabs-title clearfix">
                                    <li class="tab-link active" id="tab-link-1">
                                        <h3>Mô tả sản phẩm</h3>
                                    </li>
                                    <li class="tab-link" id="tab-link-2">
                                        <h3>Đánh giá</h3>
                                    </li>
                                    <li class="tab-link" id="tab-link-3">
                                        <h3>Bình luận</h3>
                                    </li>
                                </ul>
                                <div class="tab-float">
                                    <div id="tab1" class="tab-content active">
                                        <div class="rte product_getcontent">
                                            <div class="ba-text-fpt">
                                                <p>
                                                    <?php echo $book[0]['Description'] ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab2" class="tab-content">
                                        <div class="rte product_getcontent">
                                            <div class="comment-item">
                                                <ul class=item-reviewer>
                                                    <div class="comment-item-user">
                                                        <img src="img/HinhCute/Admin.jpeg" alt="" class="comment-item-user-img">

                                                        <li><b>Nguyễn Nhung</b></li>
                                                    </div>

                                                    <br>
                                                    <li>2021-08-17 20:40:10</li>
                                                    <li>
                                                        <div class="product__panel-rate-wrap">
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <h4>Sách được bọc nilong kỹ càng, sạch, mới. Giao hàng nhanh. Nội dung chưa đọc nhưng
                                                            nhìn sơ có vẻ hấp dẫn và rất nhiều kiến thức bổ ích. Mình ở nước ngoài nhờ người mua
                                                            rồi gửi qua nên khâu đóng gói của người bán quan trọng lắm, giúp cho sách vận chuyển
                                                            đi xa cũng không bị hư tổn gì. Sẽ tiếp tục ủng hộ. Love book shop .From Hust with
                                                            LOve</h4>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="comment-item">
                                                <ul class=item-reviewer>
                                                    <div class="comment-item-user">
                                                        <img src="img/HinhCute/ahihi.png" alt="" class="comment-item-user-img">
                                                        <li><b>Tùng Lương</b></li>
                                                    </div>

                                                    <br>
                                                    <li>2021-02-17 12:20:10</li>
                                                    <li>
                                                        <div class="product__panel-rate-wrap">
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <h4>Sách được đóng rất cẩn thận, hộp ko bị móp méo gì cả .... , giao hàng chậm cả tuần,
                                                            Rõ trên app báo hàng đến kho rồi cả tuần k thấy đâu. shipper rất vui tính và thân
                                                            thiện . Còn ngoài ra thì sách rất đẹp nha mọi người. Giấy sáng và thơm. Từ bìa tới
                                                            màu sắc trong sách.Thấy mọi người bảo hay lắm nên mua về thử chứ mk chưa có đọc nên
                                                            chưa thể review về nội dung.</h4>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="comment-item">
                                                <ul class=item-reviewer>
                                                    <div class="comment-item-user">
                                                        <img src="img/HinhCute/h2.jfif" alt="" class="comment-item-user-img">
                                                        <li><b>Trung Trần</b></li>
                                                    </div>

                                                    <br>

                                                    <li>2020-12-27 10:48:20</li>
                                                    <li>
                                                        <div class="product__panel-rate-wrap">
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <h4>Love it! - Sách bìa cứng, in màu, giấy dày. - Giao hàng đúng hẹn, bao bì cẩn thận.
                                                            -mình đã tham gia 1 lớp nhưng chưa thông lắm nên mua về đọc lại.Giờ thì thông rồi
                                                            .Giá hơi chát nhưng phù hợp, hy vọng sẽ có giá tốt hơn vào kỳ tái bản kế tiếp! - Nội
                                                            dung hay, công phu, nhiều thuật ngữ nhưng viết dễ hiểu, hữu ích; có lẽ dịch cũng tốt
                                                            nữa! Tò mò quá nên mình mua thêm ebook tiếng Anh để đọc thêm nâng cao từ vựng. Quyển
                                                            này đọc nguyên gốc (tiếng Anh) trước chắc sẽ rất khó đọc. Bạn nào làm quản lý mua
                                                            đọc cũng hữu ích! Đáng đồng tiền bát gạo!</h4>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="comment-item">
                                                <ul class=item-reviewer>
                                                    <div class="comment-item-user">
                                                        <img src="img/HinhCute/h4.jfif" alt="" class="comment-item-user-img">
                                                        <li><b>Sơn Hoàng</b></li>
                                                    </div>
                                                    <br>

                                                    <li>2020-08-17 20:40:18</li>
                                                    <li>
                                                        <div class="product__panel-rate-wrap">
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                            <i class="fas fa-star product__panel-rate"></i>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <h4>sách được đóng trong hộp và có 1 lớp màng nilon bảo vệ. Gáy sách ko bị móp méo, chất
                                                            lượng giấy, màu sắc rất tuyệt. Nội dung cực kỳ hữu ích, rất dễ hiểu cho thể loại
                                                            sách thuần về lý thuyết tâm lý.Nội dung sách mới, lạ. Sách sử dụng rất nhiều thuật
                                                            ngữ khoa học, nên đòi hỏi người đọc kiên nhẫn và có hiểu biết nhất định. Cực kỳ hài
                                                            lòng và sẽ ủng hộ tiếp</h4>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="productRelate product-lq">
                                <div class="group-title-index">
                                    <h3 class="title">
                                        <a class="title-name" href=""><?php
                                                                        if ($comboBookId != null) {
                                                                            echo "Theo Combo";
                                                                        } else {
                                                                            echo "Cùng thể loại";
                                                                        } ?>
                                            <img src="img/book-icon.png" alt="">
                                        </a>
                                        <span class=""></span>
                                    </h3>
                                </div>
                                <div class="product-flash-swiper swiper-container">
                                    <button class="btn-pre btn-pre-slider1"><i class='fa fa-angle-left' aria-hidden='true'></i></button>
                                    <div class="swiper-wrapper  slick-slider2">
                                        <?php
                                        foreach ($lstBook as $key => $lst) {
                                        ?>
                                            <div class="swiper-slider">
                                                <div class="card">
                                                    <a class="card-img" href="Book.php?id=<?php echo $lst['Id'] ?>">
                                                        <img src="img/Products/<?php echo $lst['Path'] ?>" alt="">
                                                    </a>
                                                    <a class="card-info" href="Book.php?id=<?php echo $lst['Id'] ?>">
                                                        <p class="text-title" title="<?php echo $lst['Name'] ?>"><?php echo $lst['Name'] ?></p>
                                                    </a>
                                                    <div class="card-footer">
                                                        <span class="text-title">$<?php echo $lst['Price'] ?></span>
                                                        <div class="card-button">
                                                            <svg class="svg-icon" viewBox="0 0 20 20">
                                                                <path d="M17.72,5.011H8.026c-0.271,0-0.49,0.219-0.49,0.489c0,0.271,0.219,0.489,0.49,0.489h8.962l-1.979,4.773H6.763L4.935,5.343C4.926,5.316,4.897,5.309,4.884,5.286c-0.011-0.024,0-0.051-0.017-0.074C4.833,5.166,4.025,4.081,2.33,3.908C2.068,3.883,1.822,4.075,1.795,4.344C1.767,4.612,1.962,4.853,2.231,4.88c1.143,0.118,1.703,0.738,1.808,0.866l1.91,5.661c0.066,0.199,0.252,0.333,0.463,0.333h8.924c0.116,0,0.22-0.053,0.308-0.128c0.027-0.023,0.042-0.048,0.063-0.076c0.026-0.034,0.063-0.058,0.08-0.099l2.384-5.75c0.062-0.151,0.046-0.323-0.045-0.458C18.036,5.092,17.883,5.011,17.72,5.011z"></path>
                                                                <path d="M8.251,12.386c-1.023,0-1.856,0.834-1.856,1.856s0.833,1.853,1.856,1.853c1.021,0,1.853-0.83,1.853-1.853S9.273,12.386,8.251,12.386z M8.251,15.116c-0.484,0-0.877-0.393-0.877-0.874c0-0.484,0.394-0.878,0.877-0.878c0.482,0,0.875,0.394,0.875,0.878C9.126,14.724,8.733,15.116,8.251,15.116z"></path>
                                                                <path d="M13.972,12.386c-1.022,0-1.855,0.834-1.855,1.856s0.833,1.853,1.855,1.853s1.854-0.83,1.854-1.853S14.994,12.386,13.972,12.386z M13.972,15.116c-0.484,0-0.878-0.393-0.878-0.874c0-0.484,0.394-0.878,0.878-0.878c0.482,0,0.875,0.394,0.875,0.878C14.847,14.724,14.454,15.116,13.972,15.116z"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <button class="btn-next btn-next-slider1"><i class='fa fa-angle-right' aria-hidden='true'></i></button>
                                </div>
                            </div>
                            <div class="section-recenview-product productRelate">
                                <div class="group-title-index">
                                    <h3 class="title">
                                        <a class="title-name" href="">Cùng thể loại
                                            <img src="img/book-icon.png" alt="">
                                        </a>
                                        <span class=""></span>
                                    </h3>
                                </div>
                                <div class="product-flash-swiper swiper-container">
                                    <button class="btn-pre btn-pre-slider2"><i class='fa fa-angle-left' aria-hidden='true'></i></button>
                                    <div class="swiper-wrapper  slick-slider3">
                                        <?php
                                        foreach ($lstBook as $key => $lst) {
                                        ?>
                                            <div class="swiper-slider">
                                                <div class="card">
                                                    <div class="card-img"><img src="img/Products/<?php echo $lst['Path'] ?>" alt=""></div>
                                                    <div class="card-info">
                                                        <p class="text-title" title="<?php echo $lst['Name'] ?>"><?php echo $lst['Name'] ?></p>
                                                    </div>
                                                    <div class="card-footer">
                                                        <span class="text-title">$<?php echo $lst['Price'] ?></span>
                                                        <div class="card-button">
                                                            <svg class="svg-icon" viewBox="0 0 20 20">
                                                                <path d="M17.72,5.011H8.026c-0.271,0-0.49,0.219-0.49,0.489c0,0.271,0.219,0.489,0.49,0.489h8.962l-1.979,4.773H6.763L4.935,5.343C4.926,5.316,4.897,5.309,4.884,5.286c-0.011-0.024,0-0.051-0.017-0.074C4.833,5.166,4.025,4.081,2.33,3.908C2.068,3.883,1.822,4.075,1.795,4.344C1.767,4.612,1.962,4.853,2.231,4.88c1.143,0.118,1.703,0.738,1.808,0.866l1.91,5.661c0.066,0.199,0.252,0.333,0.463,0.333h8.924c0.116,0,0.22-0.053,0.308-0.128c0.027-0.023,0.042-0.048,0.063-0.076c0.026-0.034,0.063-0.058,0.08-0.099l2.384-5.75c0.062-0.151,0.046-0.323-0.045-0.458C18.036,5.092,17.883,5.011,17.72,5.011z"></path>
                                                                <path d="M8.251,12.386c-1.023,0-1.856,0.834-1.856,1.856s0.833,1.853,1.856,1.853c1.021,0,1.853-0.83,1.853-1.853S9.273,12.386,8.251,12.386z M8.251,15.116c-0.484,0-0.877-0.393-0.877-0.874c0-0.484,0.394-0.878,0.877-0.878c0.482,0,0.875,0.394,0.875,0.878C9.126,14.724,8.733,15.116,8.251,15.116z"></path>
                                                                <path d="M13.972,12.386c-1.022,0-1.855,0.834-1.855,1.856s0.833,1.853,1.855,1.853s1.854-0.83,1.854-1.853S14.994,12.386,13.972,12.386z M13.972,15.116c-0.484,0-0.878-0.393-0.878-0.874c0-0.484,0.394-0.878,0.878-0.878c0.482,0,0.875,0.394,0.875,0.878C14.847,14.724,14.454,15.116,13.972,15.116z"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <button class="btn-next btn-next-slider2"><i class='fa fa-angle-right' aria-hidden='true'></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="footer">
        <div class="mid-footer">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="" class="logo-ft" title="logo">
                            <img src="img/logo-bookstore.jpg" alt="Logo">
                        </a>
                        <div class="content-ft">
                            Edubook là cửa hàng luôn cung cấp cho các bạn tìm tòi tri thức, đam mê
                            đọc sách trên khắp cả nước.Chúng tôi sẽ liên tục cập
                            nhật những cuốn sách hay nhất, mới nhất, chất lượng nhất
                            giúp người đọc có những cuốn sách hay nhất để đọc!</div>
                        <h4 class="title-menu">Hình thức thanh toán</h4>
                        <ul class="Pay">
                            <li><img style="width: 50px; height: 30px;" src="img/payment_1.webp" alt=""></li>
                            <li><img style="width: 50px; height: 30px;" src="img/payment_2.webp" alt=""></li>
                            <li><img style="width: 50px; height: 30px;" src="img/payment_3.webp" alt=""></li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <h4 class="title-menu title-menu2">Chính sách</h4>
                        <ul class="list-menu">
                            <li>
                                <i class="fa-solid fa-user" style="color: #000; margin-left: 1px; margin-right: 9px;"></i>
                                <a href="">Chính sách thành viên</a>
                            </li>
                            <li>
                                <i class="fa-regular fa-credit-card" style="color: #000; margin-right: 6px;"></i>
                                <a href="">Chính sách thanh toán</a>
                            </li>
                            <li>
                                <i class="fa-solid fa-cart-shopping" style="color: #000; margin-right: 6px;"></i>
                                <a href="">Hưỡng dẫn mua hàng</a>
                            </li>
                            <li>
                                <i class="fa-solid fa-user-shield" style="color: #000; margin-left: 1px; margin-right: 3px;"></i>
                                <a href="">Bảo mật thông tin cá nhân</a>
                            </li>
                            <li>
                                <i class="fa-solid fa-gift" style="color: #000; margin-left: 2px; margin-right: 5px;"></i>
                                <a href="">Quà tặng chi ân</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <h4 class="title-menu title-menu2">Thông tin chung</h4>
                        <div class="group-address">
                            <ul>
                                <li>
                                    <b>Địa chỉ:</b>
                                    <span>77 Huỳnh Phúc Kháng, Quận 1, TP. HCM</span>
                                </li>
                                <li>
                                    <b>Điện thoại</b>
                                    <span>1900 6523</span>
                                </li>
                                <li>
                                    <b>Email:</b>
                                    <span>phamtrikhai2003@gmail.com</span>
                                </li>
                            </ul>
                        </div>
                        <h4 class="title-menu title-menu2">Liên hệ sàn</h4>
                        <div class="social">
                            <span>Social</span>
                            <a class="social-link" href="#">
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 461.001 461.001" xml:space="preserve" fill="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <g>
                                            <path style="fill:#F61C0D;" d="M365.257,67.393H95.744C42.866,67.393,0,110.259,0,163.137v134.728 c0,52.878,42.866,95.744,95.744,95.744h269.513c52.878,0,95.744-42.866,95.744-95.744V163.137 C461.001,110.259,418.135,67.393,365.257,67.393z M300.506,237.056l-126.06,60.123c-3.359,1.602-7.239-0.847-7.239-4.568V168.607 c0-3.774,3.982-6.22,7.348-4.514l126.06,63.881C304.363,229.873,304.298,235.248,300.506,237.056z"></path>
                                        </g>
                                    </g>
                                </svg>
                            </a>
                            <a class="social-link" href="#">
                                <svg fill="#000000" viewBox="0 0 512 512" id="icons" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M412.19,118.66a109.27,109.27,0,0,1-9.45-5.5,132.87,132.87,0,0,1-24.27-20.62c-18.1-20.71-24.86-41.72-27.35-56.43h.1C349.14,23.9,350,16,350.13,16H267.69V334.78c0,4.28,0,8.51-.18,12.69,0,.52-.05,1-.08,1.56,0,.23,0,.47-.05.71,0,.06,0,.12,0,.18a70,70,0,0,1-35.22,55.56,68.8,68.8,0,0,1-34.11,9c-38.41,0-69.54-31.32-69.54-70s31.13-70,69.54-70a68.9,68.9,0,0,1,21.41,3.39l.1-83.94a153.14,153.14,0,0,0-118,34.52,161.79,161.79,0,0,0-35.3,43.53c-3.48,6-16.61,30.11-18.2,69.24-1,22.21,5.67,45.22,8.85,54.73v.2c2,5.6,9.75,24.71,22.38,40.82A167.53,167.53,0,0,0,115,470.66v-.2l.2.2C155.11,497.78,199.36,496,199.36,496c7.66-.31,33.32,0,62.46-13.81,32.32-15.31,50.72-38.12,50.72-38.12a158.46,158.46,0,0,0,27.64-45.93c7.46-19.61,9.95-43.13,9.95-52.53V176.49c1,.6,14.32,9.41,14.32,9.41s19.19,12.3,49.13,20.31c21.48,5.7,50.42,6.9,50.42,6.9V131.27C453.86,132.37,433.27,129.17,412.19,118.66Z"></path>
                                    </g>
                                </svg>
                            </a>
                            <a class="social-link" href="#">
                                <svg viewBox="0 -28.5 256 256" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" fill="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <g>
                                            <path d="M216.856339,16.5966031 C200.285002,8.84328665 182.566144,3.2084988 164.041564,0 C161.766523,4.11318106 159.108624,9.64549908 157.276099,14.0464379 C137.583995,11.0849896 118.072967,11.0849896 98.7430163,14.0464379 C96.9108417,9.64549908 94.1925838,4.11318106 91.8971895,0 C73.3526068,3.2084988 55.6133949,8.86399117 39.0420583,16.6376612 C5.61752293,67.146514 -3.4433191,116.400813 1.08711069,164.955721 C23.2560196,181.510915 44.7403634,191.567697 65.8621325,198.148576 C71.0772151,190.971126 75.7283628,183.341335 79.7352139,175.300261 C72.104019,172.400575 64.7949724,168.822202 57.8887866,164.667963 C59.7209612,163.310589 61.5131304,161.891452 63.2445898,160.431257 C105.36741,180.133187 151.134928,180.133187 192.754523,160.431257 C194.506336,161.891452 196.298154,163.310589 198.110326,164.667963 C191.183787,168.842556 183.854737,172.420929 176.223542,175.320965 C180.230393,183.341335 184.861538,190.991831 190.096624,198.16893 C211.238746,191.588051 232.743023,181.531619 254.911949,164.955721 C260.227747,108.668201 245.831087,59.8662432 216.856339,16.5966031 Z M85.4738752,135.09489 C72.8290281,135.09489 62.4592217,123.290155 62.4592217,108.914901 C62.4592217,94.5396472 72.607595,82.7145587 85.4738752,82.7145587 C98.3405064,82.7145587 108.709962,94.5189427 108.488529,108.914901 C108.508531,123.290155 98.3405064,135.09489 85.4738752,135.09489 Z M170.525237,135.09489 C157.88039,135.09489 147.510584,123.290155 147.510584,108.914901 C147.510584,94.5396472 157.658606,82.7145587 170.525237,82.7145587 C183.391518,82.7145587 193.761324,94.5189427 193.539891,108.914901 C193.539891,123.290155 183.391518,135.09489 170.525237,135.09489 Z" fill="#5865F2" fill-rule="nonzero"> </path>
                                        </g>
                                    </g>
                                </svg>
                            </a>
                            <a class="social-link" href="#">
                                <svg fill="#000000" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" class="icon">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M488.1 414.7V303.4L300.9 428l83.6 55.8zm254.1 137.7v-79.8l-59.8 39.9zM512 64C264.6 64 64 264.6 64 512s200.6 448 448 448 448-200.6 448-448S759.4 64 512 64zm278 533c0 1.1-.1 2.1-.2 3.1 0 .4-.1.7-.2 1a14.16 14.16 0 0 1-.8 3.2c-.2.6-.4 1.2-.6 1.7-.2.4-.4.8-.5 1.2-.3.5-.5 1.1-.8 1.6-.2.4-.4.7-.7 1.1-.3.5-.7 1-1 1.5-.3.4-.5.7-.8 1-.4.4-.8.9-1.2 1.3-.3.3-.6.6-1 .9-.4.4-.9.8-1.4 1.1-.4.3-.7.6-1.1.8-.1.1-.3.2-.4.3L525.2 786c-4 2.7-8.6 4-13.2 4-4.7 0-9.3-1.4-13.3-4L244.6 616.9c-.1-.1-.3-.2-.4-.3l-1.1-.8c-.5-.4-.9-.7-1.3-1.1-.3-.3-.6-.6-1-.9-.4-.4-.8-.8-1.2-1.3a7 7 0 0 1-.8-1c-.4-.5-.7-1-1-1.5-.2-.4-.5-.7-.7-1.1-.3-.5-.6-1.1-.8-1.6-.2-.4-.4-.8-.5-1.2-.2-.6-.4-1.2-.6-1.7-.1-.4-.3-.8-.4-1.2-.2-.7-.3-1.3-.4-2-.1-.3-.1-.7-.2-1-.1-1-.2-2.1-.2-3.1V427.9c0-1 .1-2.1.2-3.1.1-.3.1-.7.2-1a14.16 14.16 0 0 1 .8-3.2c.2-.6.4-1.2.6-1.7.2-.4.4-.8.5-1.2.2-.5.5-1.1.8-1.6.2-.4.4-.7.7-1.1.6-.9 1.2-1.7 1.8-2.5.4-.4.8-.9 1.2-1.3.3-.3.6-.6 1-.9.4-.4.9-.8 1.3-1.1.4-.3.7-.6 1.1-.8.1-.1.3-.2.4-.3L498.7 239c8-5.3 18.5-5.3 26.5 0l254.1 169.1c.1.1.3.2.4.3l1.1.8 1.4 1.1c.3.3.6.6 1 .9.4.4.8.8 1.2 1.3.7.8 1.3 1.6 1.8 2.5.2.4.5.7.7 1.1.3.5.6 1 .8 1.6.2.4.4.8.5 1.2.2.6.4 1.2.6 1.7.1.4.3.8.4 1.2.2.7.3 1.3.4 2 .1.3.1.7.2 1 .1 1 .2 2.1.2 3.1V597zm-254.1 13.3v111.3L723.1 597l-83.6-55.8zM281.8 472.6v79.8l59.8-39.9zM512 456.1l-84.5 56.4 84.5 56.4 84.5-56.4zM723.1 428L535.9 303.4v111.3l103.6 69.1zM384.5 541.2L300.9 597l187.2 124.6V610.3l-103.6-69.1z"></path>
                                    </g>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div aria-label="Orange and tan hamster running in a metal wheel" role="img" class="wheel-and-hamster">
                            <div class="wheel"></div>
                            <div class="hamster">
                                <div class="hamster__body">
                                    <div class="hamster__head">
                                        <div class="hamster__ear"></div>
                                        <div class="hamster__eye"></div>
                                        <div class="hamster__nose"></div>
                                    </div>
                                    <div class="hamster__limb hamster__limb--fr"></div>
                                    <div class="hamster__limb hamster__limb--fl"></div>
                                    <div class="hamster__limb hamster__limb--br"></div>
                                    <div class="hamster__limb hamster__limb--bl"></div>
                                    <div class="hamster__tail"></div>
                                </div>
                            </div>
                            <div class="spoke"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </footer>
    <script src="https://cdn.babylonjs.com/babylon.js"></script>
    <script src="vendor/babylon/babylonjs.loaders.min.js"></script>
    <script src="vendor/jquery-3.6.0.min.js"></script>
    <script src="vendor/sclick/js/slick.min.js"></script>
    <script src="vendor/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script src="vendor/fontawesome/js/all.min.js"></script>
    <script src="js/layout.js"></script>
    <script src="js/product.js"></script>

    <script>
        document.getElementById('product-form').addEventListener('submit', function(event) {
            event.preventDefault();

            var form = event.target;
            var buttonClicked = event.submitter.name;
            
            

            if (buttonClicked === 'add-to-cart') {
                // Handle "Add to Cart" button click
                var formData = new FormData(form);

                // Send AJAX request to the PHP script
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add-to-cart.php', true);

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Successfully received response
                            document.querySelector('.thongbao').classList.add('show');
                            setTimeout(function() {
                                document.querySelector('.thongbao').classList.remove('show');
                            }, 2000);
                        } else {
                            // Error occurred
                            console.log('Error occurred:', xhr.status, xhr.statusText);
                        }
                    }
                };
                xhr.send(formData);
            } else if (buttonClicked === 'checkout') {
                // Handle "Checkout" button click
                // Implement corresponding actions
            }
        });
    </script>


    <?php
    if ($book[0]['Model'] != null) {
    ?>
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var canvas = document.getElementById('3D-Book');
                var engine = new BABYLON.Engine(canvas, true);

                // Tạo scene
                var scene = new BABYLON.Scene(engine);
                // Đặt màu nền
                scene.clearColor = new BABYLON.Color3(0, 0.749, 0.984);
                // Tạo camera
                var camera = new BABYLON.ArcRotateCamera('camera', Math.PI, Math.PI / 3, 10, BABYLON.Vector3.Zero(), scene);
                camera.attachControl(canvas, true);
                camera.wheelPrecision = 30; // Điều chỉnh tốc độ zoom

                // Thiết lập giá trị minZ và maxZ
                camera.minZ = 0.1; // Khoảng cách gần nhất mà camera có thể hiển thị
                camera.maxZ = 10000; // Khoảng cách xa nhất mà camera có thể hiển thị


                // Ngăn chặn hành động mặc định của trình duyệt khi cuộn trên thẻ canvas
                canvas.addEventListener('wheel', function(event) {
                    event.preventDefault();
                }, {
                    passive: false
                });

                // Tạo ánh sáng
                var light = new BABYLON.HemisphericLight('light', new BABYLON.Vector3(0, 1, 0), scene);

                // Tải mô hình từ Blender

                <?php
                $gltfFilePath = 'Model/' . $modelName . '/' . $model;
                $binFilePath = 'Model/' . $modelName . '/' . $modelBin;

                // Đọc nội dung của tệp gltf
                $gltfContent = file_get_contents($gltfFilePath);

                // Thay thế đường dẫn "uri":"$modelBin" bằng "uri":"Model/$modelBin"
                $modifiedGltfContent = preg_replace('/"uri"\s*:\s*"(?!Model\/)([^"]+)"/', '"uri":"Model/' . $modelName . '/$1"', $gltfContent);

                // Ghi lại nội dung đã sửa đổi vào tệp gltf gốc
                file_put_contents($gltfFilePath, $modifiedGltfContent);
                ?>

                var binName = '<?php echo $binFilePath; ?>';
                console.log(binName);
                var modelName = '<?php echo $gltfFilePath; ?>';
                console.log(modelName);
                // Tạo đường dẫn đầy đủ với 'Modun/' trước tên file
                BABYLON.SceneLoader.ImportMesh('', '', modelName, scene, function(meshes) {
                    // Meshes là một mảng chứa các mesh trong mô hình
                    // Bạn có thể thực hiện các thao tác khác trên các mesh ở đây

                    // Chỉnh kích thước mô hình
                    var model = meshes[0];
                    model.scaling = new BABYLON.Vector3(2.5, 2.5, 2.5);
                    model.position = new BABYLON.Vector3(0, -3, 0);
                    // Chạy vòng lặp chính
                    engine.runRenderLoop(function() {
                        scene.render();
                    });
                });

                // Xử lý sự kiện khi cửa sổ trình duyệt thay đổi kích thước
                window.addEventListener('resize', function() {
                    engine.resize();
                });
            });
        </script>
    <?php
    }
    ?>
</body>

</html>