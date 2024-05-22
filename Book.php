<?php
require_once('lib/db.php');

// Kết nối đến cơ sở dữ liệu và thực hiện truy vấn
$query = "SELECT b.*, m.Model, m.ModelBin, bt.Name AS BookTypeName, s.Name AS SizeName, p.Name AS PublisherName, cv.Name AS CovertypeName
FROM book b
JOIN model m ON b.Id = m.BookId
JOIN BookType bt ON b.BookTypeId = bt.Id
JOIN Size s ON b.SizeId = s.Id
JOIN Publisher p ON b.PublisherId = p.Id
JOIN covertype cv ON b.CoverTypeId = cv.Id
WHERE b.Id = 1;";
$parameters = []; // Các tham số truy vấn (nếu có)
$resultType = 2; // Loại kết quả truy vấn (2: Fetch All)

$book = DP::run_query($query, $parameters, $resultType);
if (count($book) > 0) {
    $NameBook = $book[0]['Name'];
    $model = $book[0]['Model'];
    $modelBin = $book[0]['ModelBin'];
    $comboBookId = $book[0]['ComboBookId'];
} else {
    echo "Không tìm thấy kết quả.";
    $model = null;
    $modelBin = null;
}
$queryImage = "SELECT * FROM `image` WHERE BookId=1;";
$lst_Image = DP::run_query($queryImage, $parameters, $resultType);


$queryComboBook = "SELECT *
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
$ComboBook = DP::run_query($queryComboBook, $parameters, $resultType);
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
                            <i style="width: 25px; height: 25px; color: #000;" class="fa-solid fa-cart-shopping"></i>
                        </li>
                        <li class="header-account d-n">
                            <i style="width: 25px; height: 25px; color: #000;" class="fa-regular fa-user"></i>
                            <ul class="Show-account">
                                <li class="Login">Đăng nhập</li>
                                <li class="Register">Đăng ký</li>
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
                                    <li>
                                        <a href="">Register</a>
                                    </li>
                                    <li>
                                        <a href="">Login</a>
                                    </li>
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
                                <a href="" class="caret-down">Sản phẩm</a>
                                <div class="mega-content">
                                    <div class="row">
                                        <div class="col-lg-9 d-n">
                                            <ul class="level0">
                                                <li class="level1 item parent">
                                                    <a href="" class="hmega">Rau qua</a>
                                                    <ul class="level1">
                                                        <li class="level2">
                                                            <a href="">Rau la</a>
                                                        </li>
                                                        <li class="level2">
                                                            <a href="">Cu, qua</a>
                                                        </li>
                                                        <li class="level2">
                                                            <a href="">Nam cac loai</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="level1 item parent">
                                                    <a href="" class="hmega">Rau qua</a>
                                                    <ul class="level1">
                                                        <li class="level2">
                                                            <a href="">Rau la</a>
                                                        </li>
                                                        <li class="level2">
                                                            <a href="">Cu, qua</a>
                                                        </li>
                                                        <li class="level2">
                                                            <a href="">Nam cac loai</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="level1 item parent">
                                                    <a href="" class="hmega">Rau qua</a>
                                                    <ul class="level1">
                                                        <li class="level2">
                                                            <a href="">Rau la</a>
                                                        </li>
                                                        <li class="level2">
                                                            <a href="">Cu, qua</a>
                                                        </li>
                                                        <li class="level2">
                                                            <a href="">Nam cac loai</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="level1 item parent">
                                                    <a href="" class="hmega">Rau qua</a>
                                                    <ul class="level1">
                                                        <li class="level2">
                                                            <a href="">Rau la</a>
                                                        </li>
                                                        <li class="level2">
                                                            <a href="">Cu, qua</a>
                                                        </li>
                                                        <li class="level2">
                                                            <a href="">Nam cac loai</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="level1 item parent">
                                                    <a href="" class="hmega">Rau qua</a>
                                                    <ul class="level1">
                                                        <li class="level2">
                                                            <a href="">Rau la</a>
                                                        </li>
                                                        <li class="level2">
                                                            <a href="">Cu, qua</a>
                                                        </li>
                                                        <li class="level2">
                                                            <a href="">Nam cac loai</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="level1 item parent">
                                                    <a href="" class="hmega">Rau qua</a>
                                                    <ul class="level1">
                                                        <li class="level2">
                                                            <a href="">Rau la</a>
                                                        </li>
                                                        <li class="level2">
                                                            <a href="">Cu, qua</a>
                                                        </li>
                                                        <li class="level2">
                                                            <a href="">Nam cac loai</a>
                                                        </li>
                                                    </ul>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="col-lg-3"></div>
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
        <section class="bread-crumb" style="background: linear-gradient(0deg, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.3)), url('img/banner/bannerBk.jpg') no-repeat center;">
            <div class="container">
                <div class="title-bread-crumb">
                    <?php echo $book[0]['Name'] ?>
                </div>
                <ul class="breadcrumb">
                    <li class="home">
                        <a href="index.html">
                            <span>Trang chủ</span>
                        </a>
                        <span class="mr-lr">
                            &nbsp;
                            <i class="fa-solid fa-angle-right"></i>
                            &nbsp;
                        </span>
                    </li>
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

        <section class="product layout-product">
            <div class="container">

                <div class="details-product">
                    <div class="row">
                        <div class="product-detail-lef product-images col-3 col-md-6 col-lg-4">
                            <div class="product-image-block relative">
                                <div class="swiper-container gallery-top ">
                                    <div class="swiper-wrapper slider-for" style="justify-content: center;">
                                        <div class="swiper-slide swiper-slide-active" href="" style="width: 330px; justify-content: center;">
                                            <canvas id="3DCar" class="3DImage" height="400" width="400"></canvas>
                                        </div>
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
                                    <a href="" class="btn-favorite">
                                        <i style="width: 40px; height: 40px; color: red;" class="fa-regular fa-heart"></i>
                                    </a>
                                </div>
                                <div class="swiper-container gallery-thumb ">
                                    <div class="swiper-wrapper slider-nav">
                                        <div class="swiper-slide swiper-slide-visible">
                                            <img src="img/hinh-nen-lamborghini-aventador-1.jpg" alt="">
                                        </div>
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
                        <div class="col-12 col-md-6 col-lg-8">
                            <div class="details-pro">
                                <h1 class="title-product"><?php echo $book[0]['Name'] ?></h1>
                                <div class="inventory_quantity">
                                    <div class="thump-break">
                                        <span class="mb-break inventory">
                                            <span class="stock-brand-title">Tác giả:</span>
                                            <span class="a-stock"><?php echo $book[0]['Auther'] ?></span>
                                        </span>

                                        <div class="sku-product">
                                            <span class="stock-brand-title">Mã sản phẩm:</span>
                                            <span class="a-stock"><?php echo $book[0]['SKU'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                <form action="" method="get" class="form-inline">
                                    <div class="price-box clearFix">
                                        <span class="special-price">
                                            <span class="price product-price"><?php echo $book[0]['Price'] ?>$</span>
                                            <meta itemprop="price" content="12000">
                                            <meta itemprop="priceCurrency" content="VNĐ">
                                        </span>

                                    </div>
                                    <div class="form-product">
                                        <div class="clearFix form-group">
                                            <div class="flex-quantity">
                                                <div class="custom custom-btn-number">
                                                    <label for="" class="sl section">
                                                        Số lượng:
                                                    </label>
                                                    <div class="input-number-product">
                                                        <button class="btn-num num-1">-</button>
                                                        <input type="text" name="quantity" value="1" maxlength="3" class="form-control prd-quantity">
                                                        <button class="btn-num num-2">+</button>
                                                    </div>
                                                </div>
                                                <div class="btn-pay button-actions clearFix">
                                                    <button class="btn-action add-to-cart">
                                                        Thêm vào giỏ hàng
                                                    </button>
                                                    <button class="btn-action pay-now">
                                                        Mua ngay
                                                    </button>
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
                                                        <img src="images/img/6.png" alt="" class="comment-item-user-img">
                                                        <li><b>Sơn Hoàng</b></li>
                                                    </div>
                                                    <br>

                                                    <li>2020-08-17 20:40:18</li>
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
                                        <a class="title-name" href="">Cùng thể loại
                                            <img src="img/book-icon.png" alt="">
                                        </a>
                                        <span class=""></span>
                                    </h3>
                                </div>
                                <div class="product-flash-swiper swiper-container">
                                    <button class="btn-pre btn-pre-slider1"><i class='fa fa-angle-left' aria-hidden='true'></i></button>
                                    <div class="swiper-wrapper  slick-slider2">
                                        <?php
                                        foreach ($ComboBook as $key => $lst) {
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
                                    <button class="btn-next btn-next-slider1"><i class='fa fa-angle-right' aria-hidden='true'></i></button>
                                </div>
                            </div>
                            <div class="section-recenview-product productRelate">
                                <div class="group-title-index">
                                    <h3 class="title">
                                        <a class="title-name" href="">Sản phẩm đã xem
                                            <img src="img/book-icon.png" alt="">
                                        </a>
                                        <span class=""></span>
                                    </h3>
                                </div>
                                <div class="product-flash-swiper swiper-container">
                                    <button class="btn-pre btn-pre-slider2"><i class='fa fa-angle-left' aria-hidden='true'></i></button>
                                    <div class="swiper-wrapper  slick-slider3">
                                        <?php
                                        foreach ($ComboBook as $key => $lst) {
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
                            Chúng tôi hi vọng tất cả người tiêu dùng Việt nam sẽ được sử dụng những thụ phẩm rau củ quả tươi ngon, bổ dưỡng và an toàn nhất tại cửa hàng cung cấp thực phẩm rau củ sạch Dola Organic.
                        </div>
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
                        <ul class="social">
                            <li>
                                <a href="" title="zalo"><img style="width: 32px; height: 32px;" src="img/zalo.webp" alt=""></a>
                            </li>
                            <li>
                                <a href="" title="facebook"><img style="width: 32px; height: 32px;" src="img/facebook.webp" alt=""></a>
                            </li>
                            <li>
                                <a href="" title="youtube"><img style="width: 32px; height: 32px;" src="img/youtube.webp" alt=""></a>
                            </li>
                            <li>
                                <a href="" title="google"><img style="width: 32px; height: 32px;" src="img/google.webp" alt=""></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <h4 class="title-menu ">Instagram</h4>
                        <ul class="instagram">
                            <li>
                                <div class="thumb-img">
                                    <img src="img/HinhCute/h2.jfif" alt="">
                                </div>
                            </li>
                            <li>
                                <div class="thumb-img">
                                    <img src="img/HinhCute/h3.jfif" alt="">
                                </div>
                            </li>
                            <li>
                                <div class="thumb-img">
                                    <img src="img/HinhCute/h4.jfif" alt="">
                                </div>
                            </li>
                            <li>
                                <div class="thumb-img">
                                    <img src="img/HinhCute/h5.jfif" alt="">
                                </div>
                            </li>
                            <li>
                                <div class="thumb-img">
                                    <img src="img/HinhCute/h6.jfif" alt="">
                                </div>
                            </li>
                            <li>
                                <div class="thumb-img">
                                    <img src="img/HinhCute/Hinh-anh-cute.jpg" alt="">
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </footer>
    <script src="https://cdn.babylonjs.com/babylon.js"></script>
    <script src="https://preview.babylonjs.com/loaders/babylonjs.loaders.min.js"></script>
    <script src="vendor/jquery-3.6.0.min.js"></script>
    <script src="vendor/sclick/js/slick.min.js"></script>
    <script src="vendor/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script src="vendor/fontawesome/js/all.min.js"></script>
    <script src="js/layout.js"></script>
    <script src="js/product.js"></script>
    <script src="js/3D.js"></script>

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            var canvas = document.getElementById('3DCar');
            var engine = new BABYLON.Engine(canvas, true);

            // Tạo scene
            var scene = new BABYLON.Scene(engine);
            // Đặt màu nền
            scene.clearColor = new BABYLON.Color3(0, 0.749, 0.984);
            // Tạo camera
            var camera = new BABYLON.ArcRotateCamera('camera', Math.PI, Math.PI / 3, 10, BABYLON.Vector3.Zero(), scene);
            camera.attachControl(canvas, true);
            camera.wheelPrecision = 100; // Điều chỉnh tốc độ zoom

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
</body>

</html>