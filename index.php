<?php
require_once('lib/db.php');

// Kết nối đến cơ sở dữ liệu và thực hiện truy vấn
$query = "SELECT 
b.Name AS BookName, 
b.Price, 
b.BookTypeId, 
bt.Name AS BookTypeName,
i.Path
FROM 
`book` b
JOIN 
`booktype` bt ON b.BookTypeId = bt.Id
LEFT JOIN 
`image` i ON b.Id = i.BookId
WHERE 
i.Id = (
    SELECT MIN(i2.Id)
    FROM `image` i2
    WHERE i2.BookId = b.Id
);
";
$parameters = []; // Các tham số truy vấn (nếu có)
$resultType = 2; // Loại kết quả truy vấn (2: Fetch All)

$lst_bv = DP::run_query($query, $parameters, $resultType);

$queryBookTypes = "SELECT Id, Name FROM `booktype` ORDER BY Id ASC";

$bookTypeIds = DP::run_query($queryBookTypes, $parameters, $resultType);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="icon" href="img/logo-web.jpg">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="vendor/sclick/css/slick.min.css">
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
        <section class="sliderShow container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div id="carouselExample" class="carousel slide" style="margin: 0 0px 25px 12px; ">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="img/banner/363104_06.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="img/hinh-nen-lamborghini-aventador-1.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="img/banner/363107_05.jpg" class="d-block w-100" alt="...">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bestselle">
                        <img class="d-block w-100" src="img/banner/363104_06.jpg" alt="">
                    </div>
                    <div class="bestselle">
                        <img class="d-block w-100" src="img/banner/363107_05.jpg" alt="">
                    </div>
                </div>
            </div>


        </section>


        <section class="section-danhmuc">
            <div class="container">
                <div class="group-title-index">
                    <h3 class="title">
                        <a class="title-name" href="">Sách bán chạy
                            <img src="img/book-icon.png" alt="">
                        </a>
                    </h3>
                </div>
                <div class="product-flash-swiper swiper-container">
                    <button class="btn-pre btn-pre-slider1"><i class='fa fa-angle-left' aria-hidden='true'></i></button>
                    <div class="swiper-wrapper  slick-slider">
                        <?php

                        foreach ($lst_bv as $key => $bv) {
                            if ($bv['BookTypeId'] == 7) {
                        ?>
                                <div class="swiper-slider">
                                    <div class="card">
                                        <div class="card-img"><img src="img/products/<?php echo $bv['Path'] ?>" alt=""></div>
                                        <div class="card-info">
                                            <p class="text-title"><?php echo $bv['BookName']; ?> </p>

                                        </div>
                                        <div class="card-footer">
                                            <span class="text-title">$<?php echo $bv['Price'] ?></span>
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
                        }
                        ?>
                    </div>
                    <button class="btn-next btn-next-slider1"><i class='fa fa-angle-right' aria-hidden='true'></i></button>
                </div>
            </div>
        </section>
        <?php
        foreach ($bookTypeIds as $key => $BookType) {
        ?>
            <section class="section-1-banner">
                <div class="container">
                    <a class="image-effect" href="">
                        <img width="1920" height="500" src="img/banner/muonkiepnhansinh_resize_920x420.jpg" alt="">
                    </a>
                </div>
            </section>
            <section class="section-product section-product1">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-xl-3 col-sm-5 d-none d-sm-block">
                            <!-- <canvas id="<?php echo $BookType['Name'] ?>" class="3D-booktype" height="400" width="330"></canvas> -->
                        </div>
                        <div class="col-lg-8 col-xl-9 col-sm-7">
                            <div class="group-title-index">
                                <h3 class="title">
                                    <a class="title-name" href=""><?php echo $BookType['Name']  ?>
                                        <img src="img/book-icon.png" alt="">
                                    </a>
                                    <span class=""></span>
                                </h3>
                            </div>
                            <div class="product-flash-swiper swiper-container">
                                <button class="btn-pre btn-pre-slider2"><i class='fa fa-angle-left' aria-hidden='true'></i></button>
                                <div class="swiper-wrapper  slick-slider2">
                                    <?php
                                    foreach ($lst_bv as $key => $bv) {
                                        if ($bv['BookTypeId'] == $BookType['Id']) {
                                    ?>
                                            <div class="swiper-slider">
                                                <div class="card">
                                                    <div class="card-img"><img src="img/Products/<?php echo $bv['Path'] ?>" alt=""></div>
                                                    <div class="card-info">
                                                        <p class="text-title"><?php echo $bv['BookName'] ?></p>
                                                    </div>
                                                    <div class="card-footer">
                                                        <span class="text-title">$<?php echo $bv['Price'] ?></span>
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
                                    }
                                    ?>
                                </div>
                                <button class="btn-next btn-next-slider2"><i class='fa fa-angle-right' aria-hidden='true'></i></button>
                            </div>
                            <div class="see-more">
                                <a href="" title="xem tất cả">Xem tất cả</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php
        }
        ?>
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

    <script src="vendor/babylon/babylon.js"></script>
    <script src="vendor/babylon/babylonjs.loaders.min.js"></script>
    <script src="vendor/jquery-3.6.0.min.js"></script>
    <script src="vendor/sclick/js/slick.min.js"></script>
    <script src="vendor/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script src="vendor/fontawesome/js/all.min.js"></script>
    <script src="js/layout.js"></script>
    <script src="js/index.js"></script>
    <?php
    foreach ($bookTypeIds as $key => $BookType) {
    ?>
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var canvas = document.getElementById('<?php echo $BookType['Name'] ?>');
                var engine = new BABYLON.Engine(canvas, true);

                // Tạo scene
                var scene = new BABYLON.Scene(engine);

                // Đặt màu nền
                scene.clearColor = new BABYLON.Color3(0, 0.749, 0.984);

                // Tạo camera
                var camera = new BABYLON.ArcRotateCamera('camera', Math.PI, Math.PI / 3, 10, BABYLON.Vector3.Zero(), scene);
                camera.attachControl(canvas, true);
                camera.wheelPrecision = 1; // Điều chỉnh tốc độ zoom

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
                BABYLON.SceneLoader.ImportMesh('', '', 'Model/demoCar.gltf', scene, function(meshes) {
                    // Meshes là một mảng chứa các mesh trong mô hình
                    // Bạn có thể thực hiện các thao tác khác trên các mesh ở đây

                    // Chỉnh kích thước mô hình
                    var model = meshes[0];
                    model.scaling = new BABYLON.Vector3(0.03, 0.03, 0.03);
                    model.position = new BABYLON.Vector3(0, -1, 0);
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