<?php
    require_once('lib/db.php');

    $query = "SELECT * FROM `size` WHERE 1";
    $parameters = []; // Các tham số truy vấn (nếu có)
    $resultType = 2; // Loại kết quả truy vấn (2: Fetch All)

    $lst_bv = DP::run_query($query, $parameters, $resultType);

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
        #gltfFile{
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
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
                <label for="gltfFile">Chọn Size Book:</label>
                <select id="gltfFile" name="gltfFile">
                    <option value="13x18cm">13 x 18 cm</option>
                    <option value="file3.gltf">13 x 19 cm</option>
                    <option value="file1.gltf">11 x 17 cm</option>
                    
                </select>
            </div>
            <div class="input-group">
                <label for="imageFile1">Hình ảnh bìa:</label>
                <input type="file" id="imageFile1" name="imageFile1" accept="image/*">
            </div>
            <div class="input-group">
                <label for="imageFile2">Hình mặt sau:</label>
                <input type="file" id="imageFile2" name="imageFile2" accept="image/*">
            </div>
            <div class="input-group">
                <label for="imageFile3">Hình bên cạnh:</label>
                <input type="file" id="imageFile3" name="imageFile3" accept="image/*">
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
                    url: 'uploadImageMD.php', // Địa chỉ xử lý form PHP
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