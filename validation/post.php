<?php
error_reporting(0);
session_start();
date_default_timezone_set("Europe/Sofia");
if (isset($_SESSION['name'])) {
    $text = $_POST['text'];
    $exit = $_POST['exit'];
    if ($text == '/*clear chat') {
        $fc = file("../logs/chat.log");
        $f = fopen("../logs/chat.log", "w");
        foreach ($fc as $line) {
            if (strstr($line, "<a"))
                fputs($f, $line);
        }
        fclose($f);
        $text_message = "<div class='msgln text-warning'><span class='chat-time'>" . date("H:i") . "</span> <b class='user-name'>" . $_SESSION['name'] . "</b> " . "<span>has cleared the chat!</span>" . "<br></div>\r\n";
        file_put_contents("../logs/chat.log", $text_message, FILE_APPEND | LOCK_EX);

    } else if ($text == '/*clear everything') {
        file_put_contents("../logs/chat.log", '');
        array_map('unlink', array_filter((array) glob("../uploads/*")));
        $text_message = "<div class='msgln text-warning'><span class='chat-time'>" . date("H:i") . "</span> <b class='user-name'>" . $_SESSION['name'] . "</b> " . "<span>has deleted the entire discussion!</span>" . "<br></div>\r\n";
        file_put_contents("../logs/chat.log", $text_message, FILE_APPEND | LOCK_EX);
    } else if ($text == '/*clear files') {
        array_map('unlink', array_filter((array) glob("../uploads/*")));
        $text_message = "<div class='msgln text-warning'><span class='chat-time'>" . date("H:i") . "</span> <b class='user-name'>" . $_SESSION['name'] . "</b> " . "<span>has cleared all the uploaded files!</span>" . "<br></div>\r\n";
        file_put_contents("../logs/chat.log", $text_message, FILE_APPEND | LOCK_EX);
    } else if ($text == '/*clear pics') {
        array_map('unlink', array_filter((array) glob("../uploads/*.png")));
        $text_message = "<div class='msgln text-warning'><span class='chat-time'>" . date("H:i") . "</span> <b class='user-name'>" . $_SESSION['name'] . "</b> " . "<span>has cleared all the screenshots!</span>" . "<br></div>\r\n";
        file_put_contents("../logs/chat.log", $text_message, FILE_APPEND | LOCK_EX);
    } else if ($text == '/*clear pics') {
        array_map('unlink', array_filter((array) glob("../uploads/*")));
        $text_message = "<div class='msgln text-warning'><span class='chat-time'>" . date("H:i") . "</span> <b class='user-name'>" . $_SESSION['name'] . "</b> " . "<span>has cleared all the screenshots!</span>" . "<br></div>\r\n";
        file_put_contents("../logs/chat.log", $text_message, FILE_APPEND | LOCK_EX);
    }
    else if ($exit == 'LypsZWF2ZQ==') {
        $logout_message = "<div class='msgln'><span class='left-info'>User <b class='user-name-left'>" . $_SESSION['name'] . "</b> has left the chat session.</span><br></div>\r\n";
        file_put_contents("../logs/chat.log", $logout_message, FILE_APPEND | LOCK_EX);
        session_destroy();
    } else {
        $text_message = "<div class='msgln text-light'><span class='chat-time'>" . date("H:i") . "</span> <b class='user-name'>" . $_SESSION['name'] . "</b> " . strip_tags($text, '<a>') . "<br></div>\r\n";
        file_put_contents("../logs/chat.log", $text_message, FILE_APPEND | LOCK_EX);
    }
}
?>