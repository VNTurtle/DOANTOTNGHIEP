<?php
    $productId = $_GET['idsp'];
    session_start();
    include "connect.php";
    include "binhluan.php";
    if (isset($_SESSION['Id']) && ($_SESSION['Id']) > 0) {
        if(isset($_POST['guibinhluan']) && ($_POST['guibinhluan'])){
            $iduser=$_SESSION['Id'];
            $idsp=$_POST['idsp'];
            $noidung=$_POST['noidung'];
            
            thembl($iduser,$idsp,$noidung);
        }
        $dsbl = showbl($productId);
?>

<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Comment</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>

    <body>
        <div class="customer-reviews row pb-4 py-4">
        <div class="col-lg-12 col-md-12 col-sm-12">
           
            <form action="comment.php" method="post">
                
                <div class="form-group">
                    <label for="formcontent">Nội dung: </label>
                    <textarea
                        required rows="8" id="formcontent" name="noidung" class="form-control"
                        placeholder="Viết bình luận..."
                    ></textarea>
                </div>
                <input type="hidden" name="idsp" value="<?= $productId ?>">
                <input class="btn btn-primary" type="submit" name="guibinhluan">
            </form>
            <hr>
            <?php
                if (!empty($dsbl)) {
                    foreach ($dsbl as $bl) {
                        echo $bl['UserId'].' - '.$bl['Content'] . "<br>";
                    }
                } else {
                    echo "Không có bình luận nào.";
                }
            ?>
            <hr>
        </div>
    </div>
        
    </body>

    </html>

<?php
} else {
    echo "<a href='Login.php' target='_parent'>Bạn vui lòng đăng nhập</a>";
}
?>