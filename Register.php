<?php
    require_once('lib/db.php');
    $parameters = []; // Các tham số truy vấn (nếu có)
    $resultType = 2; // Loại kết quả truy vấn (2: Fetch All)

$firstname = "";
$lastname = "";
$email = "";
$password="";
$confirm_password="";
$notemail=""; 
$notpassword="";
if(isset($_POST['register'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['cfpassword'];


    $sql = "SELECT id FROM account WHERE email = ?";
    $parameters = [$email];
     $checkEmail = DP::run_query($sql, $parameters, $resultType);
     if (!empty($checkEmail)) {
        $notemail="Email already exists";
    }
    if ($password !== $confirm_password) {
        $notpassword="Passwords do not match";
    }
    if (empty($notemail) && empty($notpassword)) {
        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Tạo fullname từ firstname và lastname
        $fullname = $firstname . ' ' . $lastname;

        // Chèn người dùng mới vào cơ sở dữ liệu
        $queryISAccount = "INSERT INTO account (FirstName, lastname, email, password, fullname, roleId) VALUES (?, ?, ?, ?, ?, ?)";
        $parameters = [$firstname, $lastname, $email, $hashed_password, $fullname, 2];
        $ISAccount = DP::run_query($queryISAccount, $parameters, $resultType);

        if ($ISAccount !== false) {
            header("Location: Login.php");
            exit;
        } else {
            echo "Thông tin đăng ký không đúng";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" href="img/logo-web.jpg">
    <link rel="stylesheet" href="vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/Register.css">
</head>

<body>
    <div class="container">
        <form class="form" method="POST" enctype="multipart/form-data">
            <p class="title">Register </p>
            <p class="message">Signup now and get full access to our app. </p>
            <div class="flex">
                <label>
                    <input required="" name="firstname" placeholder="" type="text" class="input" value="<?php echo htmlspecialchars($firstname); ?>">
                    <span>Firstname</span>
                </label>

                <label>
                    <input required="" name="lastname" placeholder="" type="text" class="input" value="<?php echo htmlspecialchars($lastname); ?>">
                    <span>Lastname</span>
                </label>
            </div>

            <label>
                <input required="" name="email" placeholder="" type="email" class="input" value="<?php echo htmlspecialchars($email); ?>">
                <span>Email</span>
                <div class="checkemail"><?php echo $notemail ?></div>
            </label>

            <label>
                <input required="" name="password" placeholder="" type="password" class="input" value="<?php echo htmlspecialchars($password); ?>">
                <span>Password</span>
            </label>
            <label>
                <input required="" name="cfpassword" placeholder="" type="password" class="input" value="<?php echo htmlspecialchars($confirm_password); ?>">
                <span>Confirm password</span>
                <div class="checkpassword"><?php echo $notpassword ?></div>
            </label>
            <button class="submit" name="register">Submit</button>
            <p class="signin">Already have an acount ? <a href="Login.php">Signin</a> </p>
        </form>
    </div>

    <script src="vendor/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
</body>

</html>