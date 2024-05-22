<?php
require_once('lib/db.php');

// Fetch the book details
$query = "SELECT `Id`, `Name` FROM `book` WHERE Id = 1;";
$parameters = [];
$resultType = 2;
$Book = DP::run_query($query, $parameters, $resultType);

if (is_array($Book) && count($Book) > 0) {
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

$Test1 = removeAccents($NameBook);

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

$modelName=sanitizeFilename($Test1);

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Upload File</title>
    <style>
        .container {
            width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
        }

        .input-group input[type="file"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
        }

        .progress {
            width: 100%;
            height: 20px;
            background-color: #ccc;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }

        .progress-bar {
            width: 0%;
            height: 100%;
            background-color: #4CAF50;
            transition: width 0.3s ease;
        }

        .button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .message {
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Upload File</h1>
        <form id="uploadForm" enctype="multipart/form-data">
            <div class="input-group">
                <label for="gltfFile">File GLTF:</label>
                <input type="file" id="gltfFile" name="gltfFile" accept=".gltf">
            </div>
            <div class="input-group">
                <label for="binFile">File BIN:</label>
                <input type="file" id="binFile" name="binFile" accept=".bin">
            </div>
            <div class="input-group">
                <label for="imageFiles">Hình ảnh:</label>
                <input type="file" id="imageFiles" name="imageFiles[]" multiple accept="image/*">
            </div>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <button type="submit" class="button" name="btn-upload">Upload</button>
        </form>
        <div class="message"></div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        

        $(document).ready(function() {
            $('#uploadForm').submit(function(e) {
                e.preventDefault(); // Ngăn chặn form submit mặc định
                
                var formData = new FormData($(this)[0]);
                var progressBar = $('.progress-bar');
                var progress = $('.progress');
                
                $.ajax({
                    url: 'upload.php', // Địa chỉ xử lý form PHP
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                progressBar.width(percentComplete + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(data) {
                        console.log('Upload thành công');
                        $('.message').html('Upload thành công');
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi khi tải lên: ', error);
                        $('.message').html('Lỗi khi tải lên: ' + error);
                    },
                    beforeSend: function() {
                        progress.show(); // Hiển thị thanh tiến trình trước khi tải lên
                    },
                    complete: function() {
                        progress.hide(); // Ẩn thanh tiến trình khi quá trình tải lên hoàn tất
                    }
                });
            });
        });
    </script>
</body>

</html>