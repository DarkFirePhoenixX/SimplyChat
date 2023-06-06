<?php
error_reporting(0);
session_start();
date_default_timezone_set("Europe/Sofia");
if (isset($_SESSION['name'])) {
    $image = $_POST['hashString'];

    $data = $image;

    $data = str_replace('data:image/png;base64,', '', $data);

    $data = str_replace(' ', '+', $data);

    $data = base64_decode($data);

    $file = '../uploads/' . rand() . '.png';

    $success = file_put_contents($file, $data);

    $num = count(glob("../uploads/" . "*.png"));

    $text_message = "<div class='msgln text-light'><span class='chat-time'>" . date("H:i") . "</span> <b class='user-name'>" . $_SESSION['name'] . "</b><a style='text-decoration: none;' target='_blank' href=SimplyChat/" . $file . ">screenshot(" . $num . ")</a>" . "<br></div>\r\n";
    file_put_contents("../logs/chat.log", $text_message, FILE_APPEND | LOCK_EX);

    $compress = imagecreatefrompng($file);

    imagejpeg($compress, $file, 75);
}
?>