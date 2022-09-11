<?php
session_start();

$message = '';
$greeting = '';

//login logic
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    if (isset($_POST['submit']) and !empty($_POST['username'] and !empty($_POST['password']))) {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
        if ($username == 'bri' and $password == '111') {
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
    $greeting = "<h3>Hello {$_SESSION['username']}!</h3>";
    echo "click here to <a href=\"index.php?action=logout\">logout</a>";
} else {
    echo 'Welcome Guest!<br>';
}

if (isset($_GET['action']) == 'logout') {
    session_destroy();
};

// create new folder logic
if (isset($_POST['newFolder'])) {
    if (!empty($_POST['createNewFolder'])) {
        $newFolderName = $_POST['createNewFolder'];
        $dirCreate = './' . $newFolderName;
        if (!is_dir($dirCreate)) {
            mkdir($dirCreate, 0777, true);
            $dirMessage =  '<p style="color: blue">A new file created!</p>';
        } else {
            $dirMessage =  '<p style="color: red">A file with the same name already exist!</p>';
        }
    }
}

// upload folder logic
if (isset($_POST['upload'])) {
    $allowed_ext = ['png', 'jpg', 'jpeg', 'gif', 'svg'];
    var_dump($allowed_ext);
    if (!empty($_FILES['upload']['name'])) {
        $file_name = $_FILES['upload']['name'];
        $file_size = $_FILES['upload']['size'];
        $file_tmp = $_FILES['upload']['tmp_name'];
        $target_dir = "./${file_name}";
        var_dump($target_dir);

        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));

        if (in_array($file_ext, $allowed_ext)) {
            if ($file_size <= 1000000) {
                $move = move_uploaded_file($file_tmp, $target_dir);
                var_dump($move);
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


<?php
// testing
// echo '<pre>';
// var_dump($_FILES['upload'])


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

    <style>
        .width {
            width: 350px;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 3px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #a59b9b;
        }
    </style>

</head>

<body>
    <div class="container">
        <!-- login form -->
        <!-- logout form -->
        <div>
            <!-- click here to <a href="index.php?action=logout">logout</a> -->

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
                                    <input type="text" name="username" placeholder="username = bri" class="form-control">
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

        <!-- create new folder -->
        <div>
            <div class="d-flex flex-column align-items-start width">
                <div class="text-center mt-5 mb-1">
                    <h5>Create new folder</h5>
                </div>
                <div class="form-control">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <div class="d-flex flex-column">
                            <div>
                                <label for="newFolder" class="form-label">Folder name: </label>
                                <input type="text" name="createNewFolder" placeholder="Create new folder" class="form-control">
                            </div>
                            <div class="mt-2 align-self-end">
                                <input type="submit" name="newFolder" value="Create new folder" class="btn btn-primary">
                            </div>

                        </div>
                    </form>
                </div>
                <div class="mt-1">
                    <p><?php echo $dirMessage ?? null ?></p>
                </div>
            </div>
        </div>

        <!-- upload folder -->
        <div class="form-control">
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                <div class="d-flex flex-column">
                    <div>
                        <label for="upload">Select file to upload</label>
                        <input type="file" name="upload">
                    </div>
                    <div class="mt-2 align-self-end">
                        <input type="submit" name="upload" value="Upload file">
                    </div>
                </div>
            </form>
            <div class="mt-3">
                <?php echo $uplMessage ?? null ?>
            </div>
            <div>
                <span>Image name: <?php echo $_FILES['upload']['name'] ?? 'No image selected!' ?></span><br>
                <span>Image type: <?php echo $_FILES['upload']['type'] ?? 'No image selected!' ?></span><br>
                <span>Image size: <?php echo $_FILES['upload']['size'] ?? 'No image selected!' ?></span><br>
            </div>
        </div>

        <!-- table -->
        <div>
            <div>
                <h3>File manager</h3>
            </div>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
                <!-- table logic -->
                <?php
                $current = './' . $_GET['path'];
                $list = array_diff(scandir($current), array('.', '..'));
                foreach ($list as $listItem) {
                    $isFolder = is_dir($current . '/' . $listItem);
                    if ($isFolder) {
                        print("<tr><td>" . "<a href='?path=" . $_GET['path'] . "/" . $listItem . "'>" . $listItem . "</a></td>");
                        print('<td>Directory</td>');
                        print('<td></td>');
                    }
                    if (is_file($listItem)) {
                        print("<tr><td>" . "<a href='?path=" . $_GET['path'] . "/" . $listItem . "'>" .  $listItem . "</a></td>");
                        print('<td>File</td>');
                        print("<td>" . "<a href='delete.php?del=$listItem'>Delete</a>" . " " . "<a href='download.php?link=$listItem'> Download </a>" . "</td></tr>");
                    }
                }
                print('</tbody></table>')
                ?>


        </div>






    </div>

</body>

</html>