<?php
error_reporting(0);
session_set_cookie_params(0, "/", "", true, true);
session_start();
if (isset($_GET['logout'])) {
    header('Content-Type: text/html; charset=utf-8');
    $logout_message = "<div class='msgln'><span class='left-info'>User <b class='user-name-left'>" . $_SESSION['name'] . "</b> has left the chat session.</span><br></div>\r\n";
    file_put_contents("logs/chat.log", $logout_message, FILE_APPEND | LOCK_EX);
    session_unset();
    session_destroy();
    header("Location: index.php");
}
if (isset($_POST['enter'])) {
    if (preg_match('/^[\p{L}]+$/u', $_POST['name'])) {
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name'])) . "_" . rand(1000, 10000);
    } else if ($_POST['name'] == "") {
        echo '<div class="error text-center fs-5 position-absolute top-50 start-50 translate-middle">Please enter a name!</div>';
    } else {
        echo '<div class="error text-center fs-5 position-absolute top-50 start-50 translate-middle">Your name should consist only letters and no spaces!</div>';
    }
}
function loginForm()
{
    echo
        '<!DOCTYPE html>
<html lang="en" oncontextmenu="return false;">
<head>
    <meta charset="utf-8" />
    <title>SimplyChat</title>
    <link rel="icon" type="image/x-icon" href="https://img.icons8.com/color-glass/96/null/group-task.png">
    <meta name="description" content="SimplyChat" />
    <link rel="stylesheet" href="resources/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <div id="loginform" class="mt-5 text-centered text-light">
        <h1>Welcome to <b class="fs-1 logotext">SimplyChat!</b><img src="https://img.icons8.com/color-glass/96/null/group-task.png" width="65px" class="mb-3 logo"></h1>
            <p>Please enter your name to continue!</p>
            <form action="index.php" method="post"> 
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="text-light border-info"/> 
            <input type="submit" name="enter" id="enter" value="Enter" class="fs-5"/> 
            </form> 
            </div></body></html>';
}
?>
<?php
if (!isset($_SESSION['name'])) {
    loginForm();
} else {
    $_SESSION['login_message'] = "<div class='msgln'><span class='left-info'>User <b class='user-name-left'>" . $_SESSION['name'] . "</b> has joined the chat session.</span><br></div>\r\n";
    header("Location: login.php");
}
?>