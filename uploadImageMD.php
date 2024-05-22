<?php
require_once('lib/db.php');

// Fetch the book details
$query = "SELECT `Id`, `Name` FROM `book` WHERE Id = 3;";
$parameters = [];
$resultType = 2;
$Book = DP::run_query($query, $parameters, $resultType);

if (count($Book) > 0) {
    $IdBook = $Book[0]['Id'];
    $NameBook = $Book[0]['Name'];
} else {
    echo "Không tìm thấy kết quả.";
    $IdBook = null;
    $NameBook = null;
}
function removeAccents($str) {
    $accentedChars = ['á', 'à', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'đ', 'é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'í', 'ì', 'ỉ', 'ĩ', 'ị', 'ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự', 'ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ', 'Đ', 'É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ', 'Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị', 'Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ', 'Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự', 'Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ'];
    $unaccentedChars = ['a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'd', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'y', 'y', 'y', 'y', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'D', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'Y', 'Y', 'Y', 'Y', 'Y'];

    // Đảm bảo cả hai mảng có cùng số phần tử
    if (count($accentedChars) == count($unaccentedChars)) {
        return $str; // Trả về chuỗi ban đầu nếu số lượng ký tự không khớp
    }
    return str_replace($accentedChars, $unaccentedChars, $str);
}
// Function to sanitize filenames
function sanitizeFilename($filename) {
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

$modelName=sanitizeFilename(removeAccents($NameBook));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gltfName = $_POST['gltfFile'];
    $gltfSQL=$modelName.'.gltf';
    $binSQL=$modelName.'.bin';
    $gltfPath='Model/'.$gltfName.'.gltf';
    $binPath ='Model/'.$gltfName.'.bin';
    $Image1=$_FILES['imageFile1']['name'];
    $Image2=$_FILES['imageFile2']['name'];
    $Image3=$_FILES['imageFile3']['name'];

    $uploadDir = "Model/" . $modelName . "/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    //Tạo file gltf cho model
    $newGltfPath = 'Model/' . $modelName. '/' .  $modelName .'.gltf';

    //Sao chép file bin cho model
    $newBinPath = 'Model/' . $modelName. '/' .  $modelName .'.bin';
    copy($binPath, $newBinPath);
    

    // Đọc nội dung của tệp gltf
    $gltfContent = file_get_contents($gltfPath);
    // Thực hiện thay thế các biến trong URI
    $modifiedGltfContent = strtr($gltfContent, [
        '$mainImage' => $Image1,
        '$coverImage' => $Image2,
        '$Image' => $Image3,
        '$binPath' => $binPath
    ]);
    
    // Ghi lại nội dung đã sửa đổi vào tệp GLTF mới
    file_put_contents($newGltfPath, $modifiedGltfContent);
    
    
    // Insert the model data into the database
    $query = "INSERT INTO `model` (`BookId`, `Model`, `ModelBin`, `Status`) VALUES ('$IdBook', '$gltfSQL', '$binSQL', true);";
    $parameters = [];
    $resultType = 1;
    DP::run_query($query, $parameters, $resultType);

    // Function to move uploaded files to the target directory
    function moveUploadedFile($file, $uploadDir) {
        $filename = basename($file['name']);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $targetFile = $uploadDir . $filename;

        // If the file already exists, rename the new file
        $i = 1;
        while (file_exists($targetFile)) {
            $newFilename = pathinfo($filename, PATHINFO_FILENAME) . '_' . $i . '.' . $extension;
            $targetFile = $uploadDir . $newFilename;
            $i++;
        }

        if ($file['error'] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                $size = $file['size'] / 1024;
                echo "File uploaded successfully: $targetFile (Size: $size KB)<br>";
            } else {
                echo "Error moving file: $filename<br>";
            }
        } else {
            echo "Error uploading file: $filename (Error code: " . $file['error'] . ")<br>";
        }
    }
    
    // Create upload directory based on the sanitized book name
    
    // Upload image file (assuming single file)
    if (isset($_FILES['imageFile1']) && $_FILES['imageFile1']['error'] === UPLOAD_ERR_OK) {
        $imageFile = [
            'name' => $_FILES['imageFile1']['name'],
            'type' => $_FILES['imageFile1']['type'],
            'tmp_name' => $_FILES['imageFile1']['tmp_name'],
            'error' => $_FILES['imageFile1']['error'],
            'size' => $_FILES['imageFile1']['size'],
        ];
        moveUploadedFile($imageFile, $uploadDir);
    } else {
        echo "Error uploading image file: " . $_FILES['imageFile']['name'] . " (Error code: " . $_FILES['imageFile']['error'] . ")<br>";
    }
    if (isset($_FILES['imageFile2']) && $_FILES['imageFile2']['error'] === UPLOAD_ERR_OK) {
        $imageFile = [
            'name' => $_FILES['imageFile2']['name'],
            'type' => $_FILES['imageFile2']['type'],
            'tmp_name' => $_FILES['imageFile2']['tmp_name'],
            'error' => $_FILES['imageFile2']['error'],
            'size' => $_FILES['imageFile2']['size'],
        ];
        moveUploadedFile($imageFile, $uploadDir);
    } else {
        echo "Error uploading image file: " . $_FILES['imageFile']['name'] . " (Error code: " . $_FILES['imageFile']['error'] . ")<br>";
    }
    if (isset($_FILES['imageFile3']) && $_FILES['imageFile3']['error'] === UPLOAD_ERR_OK) {
        $imageFile = [
            'name' => $_FILES['imageFile3']['name'],
            'type' => $_FILES['imageFile3']['type'],
            'tmp_name' => $_FILES['imageFile3']['tmp_name'],
            'error' => $_FILES['imageFile3']['error'],
            'size' => $_FILES['imageFile3']['size'],
        ];
        moveUploadedFile($imageFile, $uploadDir);
    } else {
        echo "Error uploading image file: " . $_FILES['imageFile']['name'] . " (Error code: " . $_FILES['imageFile']['error'] . ")<br>";
    }
}
?>
