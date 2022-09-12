<?php
session_start();
umask(0000); // This will let the permissions be 0777

$message = '';
$greeting = '';

//login logic
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    if (isset($_POST['submit']) and !empty($_POST['username'] and !empty($_POST['password']))) {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
        if ($username == 'Bri' and $password == '111') {
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $_SESSION['logged'] = true;
        } else {
            $message = 'Incorrect username or password!';
        }
    } else {
        $message = 'Username or password values are empty!';
    }
}

if (!empty($_SESSION['username']) and !empty($_SESSION['password'])) {
    $greeting = "<h3 class='text-center'>Hello {$_SESSION['username']}!</h3>";
    $logOut = "Click here to <a href=\"index.php?action=logout\">logout</a>";
} else {
    $guest = 'Welcome Guest!';
}

if (isset($_GET['action']) == 'logout') {
    session_destroy();
};

// create new folder logic
if (isset($_POST['newFolder'])) {
    if (!empty($_POST['createNewFolder'])) {
        $newFolderName = $_POST['createNewFolder'];

        $dirCreate = './' . $newFolderName;

        if (isset($_GET['path'])) {
            $dirCreate =  $_GET['path'] . '/' . $newFolderName;
        }

        if (!is_dir($dirCreate)) {
            mkdir($dirCreate, 0777, true);
            $dirMessage =  '<p style="color: blue">A new folder created!</p>';
        } else {
            $dirMessage =  '<p style="color: red">A folder with the same name already exist!</p>';
        }
    }
}

// File size conversion function
function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}

// upload file logic
if (isset($_POST['upload'])) {
    $allowed_ext = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'pdf', 'zip', 'txt', 'odt'];
    if (!empty($_FILES['upload']['name'])) {
        $file_name = $_FILES['upload']['name'];
        $file_type = $_FILES['upload']['type'];
        $file_size = $_FILES['upload']['size'];
        $file_tmp = $_FILES['upload']['tmp_name'];
        $target_dir = './' . $file_name;

        if (isset($_GET['path'])) {
            $target_dir = $_GET['path'] . '/' . $file_name;
        }

        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));

        if (in_array($file_ext, $allowed_ext)) {
            if ($file_size <= 2097152) {
                $move = move_uploaded_file($file_tmp, $target_dir);
                $message = '<p style="color: green">File uploaded</p>';
            } else {
                $message = '<p style="color: red">File is too large</p>';
            }
        } else {
            $message = '<p style="color: red">Invalid file type</p>';
        }
    } else {
        $message = '<p style="color: red">Please choose a file</p>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Bootstrap  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">



    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Maven+Pro:wght@400;600;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Maven Pro', sans-serif;
        }

        .width {
            width: 350px;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="mt-2"><?php echo $guest ?? null ?></div>
        <div class="mt-2"><?php echo $logOut ?? null ?></div>

        <!-- login form -->
        <!-- logout form -->
        <div>
            <?php isset($_SESSION['logged']) == true ? print($greeting) : null ?>
            <div <?php isset($_SESSION['logged']) == true ? print('style="display: none"') : print('style="display: block"') ?>>
                <div class="d-flex flex-column align-items-center">
                    <div class="text-center mt-5 mb-4">
                        <h2>Welcome!</h2>
                        <h5>Please fill in the login form!</h5>
                    </div>
                    <div class="width form-control">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                            <div class="d-flex flex-column">

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username: </label>
                                    <input type="text" name="username" placeholder="username = Bri" class="form-control">
                                </div>
                                <div>
                                    <label for="password" class="form-label">Password: </label>
                                    <input type="password" name="password" placeholder="password = 111" class="form-control">
                                </div>
                                <div class="mt-2 align-self-end">
                                    <input type="submit" value="Submit" name="submit" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="mt-3">
                        <p style="color: red"><?php echo $message ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div <?php isset($_SESSION['logged']) == false ? print('style="display: none"') : print('style="display: block"') ?>>
            
            <div class="d-flex justify-content-evenly align-items-end">
                <!-- create new folder -->
                <div class="d-flex flex-column align-items-start width">
                    <div class="text-center mt-5 mb-1">
                        <h5>Create new folder</h5>
                    </div>
                    <div class="form-control">

                        <form action="<?php echo $_SERVER["PHP_SELF"] . '?' . http_build_query($_GET); ?>" method="POST">
                            <div class="d-flex flex-column">
                                <div>
                                    <label for="newFolder" class="form-label">Folder name: </label>
                                    <input type="text" name="createNewFolder" placeholder="Create new folder" class="form-control">
                                </div>
                                <div class="mt-2 align-self-end">
                                    <input type="submit" name="newFolder" value="Create new folder" class="btn btn-success">
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="mt-1">
                        <p><?php echo $dirMessage ?? null ?></p>
                    </div>
                </div>

                <!-- upload file -->
                <div class="form-control width">
                    <form action="<?php echo $_SERVER["PHP_SELF"] . '?' . http_build_query($_GET); ?>" method="POST" enctype="multipart/form-data">
                        <div class="d-flex flex-column">
                            <div>
                                <label for="upload" class="form-label">Select file to upload</label>
                                <input type="file" name="upload" class="form-control">
                            </div>
                            <div class="mt-2 align-self-end">
                                <input type="submit" name="upload" value="Upload file" class="btn btn-success">
                            </div>
                        </div>
                    </form>
                    <div class="mt-3">
                        <?php echo $uplMessage ?? null ?>
                    </div>
                    <div>
                        <span>File name: <?php echo $file_name ?? 'No file selected!' ?></span><br>
                        <span>File type: <?php echo $file_type ?? 'No file selected!' ?></span><br>
                        <span>File size: <?php empty($file_size) ? print('0 bytes') : print(formatSizeUnits($file_size)) ?></span><br>
                    </div>
                </div>
            </div>
            <!-- table -->
            <div>
                <div>
                    <h3 class="mt-5 mb-3">File Browser</h3>
                </div>
                <table class="table table-hover table-bordered">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                    <!-- table logic -->
                    <?php
                    $current = '.';

                    if (isset($_GET['path'])) {
                        $current = $_GET['path'];
                    }

                    $list = array_diff(scandir($current), array('.', '..'));

                    foreach ($list as $listItem) {
                        $folderPath = $current . '/' . $listItem;
                        $isFolder = is_dir($folderPath);

                        if ($isFolder) {
                            print("<tr><td>" . "<a href='?path=" . $folderPath . "'>" . $listItem . "</a></td>");
                            print('<td>Directory</td>');
                            print('<td></td></tr>');
                        }

                        $pathToFile = $current . '/' . $listItem;
                        if (is_file($pathToFile)) {
                            print("<tr><td>" . "<a href='?path=" . $pathToFile . "'>" .  $listItem . "</a></td>");
                            print('<td>File</td>');

                            $_GET["del"] = $current . '/' . $listItem;
                            $_GET['path'] = $current . '/' . $listItem;
                            print("<td>" . "<a class='btn btn-outline-danger' href='delete.php?" . http_build_query($_GET) . "'>Delete</a>" . " " . "<a class='btn btn-outline-success' href='download.php?" . http_build_query($_GET) . "'> Download </a>" . "</td></tr>");
                        }
                    }
                    
                    ?>
                </table>

                <!-- Homepage / Back buttons -->
                <div>
                    <a href="<?php print('./') ?>" class='btn btn-light'>Home page</a>
                    <a href="<?php $q_string = explode('/', rtrim($_SERVER['QUERY_STRING'], '/'));
                                array_pop($q_string);
                                count($q_string) == 0
                                    ? print('./')
                                    : print('?' . implode('/', $q_string) . '/');
                                ?>" class='btn btn-light'>Back</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>