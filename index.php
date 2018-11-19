<?php
    include 'utils/db_util.php';
    session_start();
    $invalid = false;

    if (isset($_SESSION['loggedin'])) {
        if ($_SESSION['usertype'] == 0) {
            header('Location: admin.php');
            exit();
        }
        else {
            header('Location: user.php');
            exit();
        }
    }
    elseif (isset($_COOKIE['secret1']) && isset($_COOKIE['secret2'])) {
        $username = base64_decode($_COOKIE['secret1']);
        $password = $_COOKIE['secret2'];
        if (authenticate($username, $password)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['usertype'] = getUserType($username);
            header('Location: index.php');
            exit();
        } 
    }
    elseif (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        if (!authenticate($username, $password)) {
            $invalid = true;
        }
        else {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['usertype'] = getUserType($username);

            if ($_POST['remember'] == 'true') {
                setcookie('secret1', base64_encode($username), time() + 86400, '/');
                setcookie('secret2', $password, time() + 86400, '/');
            }

            header('Location: index.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>UNNC Staff Football Organisation</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">USFO</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="signup.php">Sign Up</a></li>
        </ul>
        </div>
    </nav>

    <div class="container">
        <div class="text-center">
            <h1>Match Management System</h1>
            <p class="lead">Enjoy football game!</p>
        </div>

        <form class="col-md-4 col-md-offset-4" action="index.php" method="POST">
            <div class="form-group has-error text-center">
            <?php if ($invalid) { ?>
                <p class="help-block">Invalid login!</p>
            <?php } ?>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input class="form-control" type="text" name="username" placeholder="Username" required>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                </div>
            </div>
            <div class="form-group">
                <label><input id="remember" type="checkbox" name="remember" value="true"> Remember me (one day)</label>
            </div>
            <div class="form-group">
                <input class="form-control btn btn-primary" type="submit" name="submit" value="Login">
            </div>
        </form>
    </div>
</body>
</html>