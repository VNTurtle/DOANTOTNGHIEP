<?php
require_once('lib/db.php');
require_once('layout.php');
$lst_Id=null;
$lst_Id2=null;
if (isset($_GET['lst-id'])) {
    $lst_Id = $_GET['lst-id'];
}else if(isset($_GET['lst-id2'])){
    $lst_Id2 = $_GET['lst-id2'];
}

$parameters = []; // Các tham số truy vấn (nếu có)
$resultType = 2; // Loại kết quả truy vấn (2: Fetch All)

if($lst_Id ==null && $lst_Id2==null){
    $selectedValue = 24; 
    $query = "SELECT 
    b.Id AS BookId,
    b.Name AS BookName, 
    b.Price, 
    b.TypeId, 
    bt.Name AS BookTypeName,
    i.Path
    FROM 
    `book` b
    JOIN 
        `Type` bt ON b.TypeId = bt.Id
    LEFT JOIN 
        `image` i ON b.Id = i.BookId
    WHERE 
        i.Id = (
            SELECT MIN(i2.Id)
            FROM `image` i2
            WHERE i2.BookId = b.Id
        )
    LIMIT   $selectedValue";
    $lst_bv = DP::run_query($query, $parameters, $resultType);
}
else if($lst_Id!==null && $lst_Id2==null){
    $query = "SELECT 
	b.Id AS BookId,
    b.Name AS BookName, 
    b.Price, 
    b.TypeId, 
    bt.Name AS BookTypeName,
    i.Path
    FROM 
    `book` b
    JOIN 
        `Type` bt ON b.TypeId = bt.Id
    LEFT JOIN 
        `image` i ON b.Id = i.BookId
    WHERE 
        i.Id = (
            SELECT MIN(i2.Id)
            FROM `image` i2
            WHERE i2.BookId = b.Id  AND b.TypeId=$lst_Id
        )
   ";
    $lst_bv = DP::run_query($query, $parameters, $resultType);
}
else {
    $query = "SELECT 
	b.Id AS BookId,
    b.Name AS BookName, 
    b.Price, 
    b.TypeId, 
    t.Name AS BookTypeName,
    i.Path,
    bt.Id AS bttypeId
    FROM 
    `book` b
    JOIN 
        `Type` t ON b.TypeId = t.Id
    LEFT JOIN 
        `image` i ON b.Id = i.BookId
    LEFT JOIN 
        `booktype` bt  ON bt.Id = i.BookId
    WHERE 
        i.Id = (
            SELECT MIN(i2.Id)
            FROM `image` i2
            WHERE i2.BookId = b.Id  AND bt.TypeDetailId=$lst_Id2
        )
   ";
    $lst_bv = DP::run_query($query, $parameters, $resultType);
}



$queryBookTypes = "SELECT Id, Name FROM `Type` ORDER BY Id ASC";

$bookTypeIds = DP::run_query($queryBookTypes, $parameters, $resultType);

$queryLst_Types = "SELECT * FROM `typedetail` WHERE 1";
$Lst_Type = DP::run_query($queryLst_Types, $parameters, $resultType);

$queryCoverType = "SELECT * FROM `covertype` WHERE 1";
$Lst_CoverType = DP::run_query($queryCoverType, $parameters, $resultType);

$queryPublisher = "SELECT * FROM `publisher` WHERE 1";
$Lst_Publisher = DP::run_query($queryPublisher, $parameters, $resultType);

$typedetailList = array();

foreach ($bookTypeIds as $bookType) {
    $typeId = $bookType['Id'];

    $Top4queryTypeDetail = "SELECT * FROM typedetail WHERE TypeId = $typeId LIMIT 4";
    $Top4typeDetails = DP::run_query($Top4queryTypeDetail, $parameters, $resultType);

    // Hợp nhất kết quả truy vấn vào danh sách
    $typedetailList = array_merge($typedetailList, $Top4typeDetails);
}

// Giá trị đã chọn (có thể là giá trị được lấy từ người dùng)



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Product</title>
    <link rel="icon" href="img/logo-web.jpg">
    <link rel="stylesheet" href="vendor/sclick/css/slick.min.css">
    <link rel="stylesheet" href="vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/lst-book.css">
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
                                Danh sách sản phẩm
                            </span>
                        </strong>
                    </li>
                </ul>
            </div>
        </section>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-3 col-sm-0 col-0">
                    <div class="left-category">
                        <div class="product-category">
                            <div class="product-category-title">
                                Danh mục sản phẩm
                            </div>
                            <ul class="product-category-list">
                                <?php
                                foreach ($bookTypeIds as $key => $lst_type) {
                                ?>
                                    <li class="product-category-item">
                                        <a class="nav-link" href="lst-book.php?lst-id=<?php echo $lst_type['Id']; ?>""><?php echo $lst_type['Name'] ?></a>
                                        <i id="category-code-<?php echo $lst_type['Id'] ?>" class="open-menu category-code icon-menu" onclick="ShowMenu(this)" data-target="Menu-detail-<?php echo $lst_type['Id'] ?>"></i>
                                        <ul class="menu-down" id="Menu-detail-<?php echo $lst_type['Id'] ?>">
                                            <?php
                                            foreach ($Lst_Type as $key => $lst_typedetail) {
                                                if ($lst_typedetail['TypeId'] == $lst_type["Id"]) {
                                            ?>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="lst-book.php?lst-id2=<?php echo $lst_typedetail['Id'] ?>"><?php echo $lst_typedetail['Name'] ?></a>
                                                    </li>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="aside-item">
                            <div class="aside-heading">
                                Chọn mức giá
                            </div>
                            <div class="aside-content">
                                <ul>
                                    <li class="aside-content-item">
                                        <input class="aside-item-input" type="checkbox">
                                        <label class="aside-item-name" for="">Dưới 10.000đ</label>
                                    </li>
                                    <li class="aside-content-item">
                                        <input class="aside-item-input" type="checkbox">
                                        <label class="aside-item-name" for="">Từ 10.000đ - 50.000đ</label>
                                    </li>
                                    <li class="aside-content-item">
                                        <input class="aside-item-input" type="checkbox">
                                        <label class="aside-item-name" for="">Từ 50.000đ - 100.000đ</label>
                                    </li>
                                    <li class="aside-content-item">
                                        <input class="aside-item-input" type="checkbox">
                                        <label class="aside-item-name" for="">Từ 200.000đ - 300.000đ</label>
                                    </li>
                                    <li class="aside-content-item">
                                        <input class="aside-item-input" type="checkbox">
                                        <label class="aside-item-name" for="">Từ 300.000đ - 500.000đ</label>
                                    </li>
                                    <li class="aside-content-item">
                                        <input class="aside-item-input" type="checkbox">
                                        <label class="aside-item-name" for=""> Trên 1 triệu</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="aside-item">
                            <div class="aside-heading">
                                Loại bìa
                            </div>
                            <div class="aside-content">
                                <ul>
                                    <?php
                                    foreach ($Lst_CoverType as $key => $lst_cv) {
                                    ?>
                                        <li class="aside-content-item">
                                            <input class="aside-item-input" type="checkbox">
                                            <label class="aside-item-name" for=""><?php echo $lst_cv['Name'] ?></label>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <div class="aside-item">
                            <div class="aside-heading">
                                Nhà xuất bản
                            </div>
                            <div class="aside-content">
                                <ul>
                                    <?php
                                    foreach ($Lst_Publisher as $key => $lst_pl) {
                                    ?>
                                        <li class="aside-content-item">
                                            <input class="aside-item-input" type="checkbox">
                                            <label class="aside-item-name" for=""><?php echo $lst_pl['Name'] ?></label>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-9 col-sm-12 col-12">
                    <div class="sort-product">
                        <h3 class="sort-heading">
                            <i class="fa-solid fa-arrow-down-z-a"></i>
                            Xếp theo
                        </h3>
                        <div class="d-flex">
                            <select class="sort-arrange" name="select-item" id="sort-arr">
                                <option class="sort-item" value="1">Bán chạy tuần</option>
                                <option class="sort-item" value="2">Bán chạy tháng</option>
                                <option class="sort-item" value="3">Bán chạy năm</option>
                                <option class="sort-item" value="4">Mới nhất</option>
                            </select>
                            
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        foreach ($lst_bv as $key => $bv) {
                        ?>
                            <div class="product__panel-item col-lg-3 col-md-4 col-sm-6">
                                <div class="product__panel-item-wrap">
                                    <div class="product__panel-img-wrap">
                                        <img src="img/Products/<?php echo $bv['Path'] ?>" alt="" class="product__panel-img">
                                    </div>
                                    <div class="product__panel-heading">
                                        <a href="product.html" class="product__panel-link"><?php echo $bv['BookName'] ?></a>
                                    </div>
                                    <div class="product__panel-rate-wrap">
                                        <i class="fas fa-star product__panel-rate"></i>
                                        <i class="fas fa-star product__panel-rate"></i>
                                        <i class="fas fa-star product__panel-rate"></i>
                                        <i class="fas fa-star product__panel-rate"></i>
                                        <i class="fas fa-star product__panel-rate"></i>
                                    </div>

                                    <div class="product__panel-price">
                                        <span class="product__panel-price-current">
                                            <?php echo $bv['Price'] ?>0 đ
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <nav class="page-book" aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
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

    <script src="vendor/jquery-3.6.0.min.js"></script>
    <script src="vendor/sclick/js/slick.min.js"></script>
    <script src="vendor/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script src="vendor/fontawesome/js/all.min.js"></script>
    <script src="js/layout.js"></script>
    <script src="js/lst-book.js"></script>
    <script>
        document.getElementById("sort-arr2").addEventListener("change", function() {
            // Lấy giá trị đã chọn
            var selectedValue = this.value;
            console.log(selectedValue);

            // Gửi giá trị selectedValue đến cùng một file bằng AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", 'selectBook.php', true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText); // Hiển thị phản hồi từ file PHP
                }
            };
            xhr.send("selectedValue=" + selectedValue);
        });
    </script>

</body>

</html>