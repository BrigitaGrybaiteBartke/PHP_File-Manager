<?php
session_start();

$message = '';
$greeting = '';

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





    </div>

</body>

</html>