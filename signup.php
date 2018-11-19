<?php
    include 'utils/db_util.php';
    include 'utils/validate_util.php';
    $existName = false;
    $existEmail = false;
    $existPhone = false;
    $isValid = false;
    $errorDB = false;

    session_start();
    if (isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit();
    }

    if (isset($_POST['submit']) and isset($_POST['username']) and isset($_POST['password']) and isset($_POST['email']) and isset($_POST['phone'])) {
        $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
        $password = md5($_POST['password']);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $phone = filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING);

        if (isExistUser($username)) {
            $existName = true;
        }
        elseif (isExistEmail($email)) {
            $existEmail = true;
        }
        elseif (isExistPhone($phone)) {
            $existPhone = true;
        }
        else {
            $infos = array('username' => $username, 'password' => $password, 'email' => $email, 'phone' => $phone);

            if (validateSignup($infos)) {
                $isValid = true;
                try {
                    signup($infos);
                }
                catch (Exception $e) {
                    $errorDB = true;
                }
            }
        }

        if (!$existName and !$existEmail and !$existPhone and $isValid and !$errorDB) {
            header('Location: index.php');
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
                <li><a href="index.php">Log In</a></li>
        </ul>
        </div>
    </nav>

    <div class="container">
        <div class="text-center">
            <h1>Register Your Account</h1>
            <p class="lead">Enjoy football game!</p>
        </div>

        <form class="col-md-4 col-md-offset-4" id="signup" action="signup.php" method="POST">
            <div class="form-group has-error text-center">
            <?php if ($existName) { ?>
                <p class="help-block">This username already exists!</p>
            <?php } ?>
            <?php if ($existEmail) { ?>
                <p class="help-block">This email has been used!</p>
            <?php } ?>
            <?php if ($existPhone) { ?>
                <p class="help-block">This phone number has been used!</p>
            <?php } ?>
            <?php if ($errorDB) { ?>
                <p class="help-block">Error Connecting DB! Try again!</p>
            <?php } ?>
            </div>
            <div class="form-group" id="div-username">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input class="form-control" type="text" name="username" placeholder="Username" required>
                </div>
                <span class="help-block hidden" id="username-invalid">Invalid username! (only letters and spaces)</span>
                <!-- <span class="help-block hidden" id="username-exist">This username has been used!</span> -->
            </div>
            <div class="form-group" id="div-password">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                </div>
                <span class="help-block hidden" id="password-invalid">Invalid password! (Min length is 6)</span>
            </div>
            <div class="form-group" id="div-email">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                    <input class="form-control" type="email" name="email" placeholder="Email Address" required>
                </div>
                <span class="help-block hidden" id="email-invalid">Invalid email!</span>
            </div>
            <div class="form-group" id="div-phone">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                    <input class="form-control" type="tel" name="phone" placeholder="Phone Number" required>
                </div>
                <span class="help-block hidden" id="phone-invalid">Invalid phone number!</span>
            </div>
            <div class="form-group">
                <input class="form-control btn btn-primary" type="submit" name="submit" value="Register">
            </div>
            <div class="form-group text-center">
                <a class="help-block" href="mailto:zy18738@nottingham.edu.cn">Want to be an admin?</a>
            </div>
        </form>
    </div>
</body>
<script type="text/javascript" src="utils/validate_util.js"></script>
<script type="text/javascript" src="js/signup.js"></script>
</html>