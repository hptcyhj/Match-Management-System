<?php
    session_start();

    if (isset($_SESSION['loggedin'])) {
        session_unset();
        session_destroy();
        setcookie('secret1', '', time() - 100, '/');
        setcookie('secret2', '', time() - 100, '/');
    }

    header('Location: index.php');
?>