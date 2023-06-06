<?php
error_reporting(0);
session_start();
$filetypeinfo = mime_content_type($_FILES['fileToUpload']['tmp_name']);
$imageinfo = getimagesize($_FILES['fileToUpload']['tmp_name']);
$target_dir = "../uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
// $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($target_file)) {
  $uploadOk = 0;
}

// Check if there is a file
if ($_FILES["fileToUpload"]["size"] < 0) {
  //   echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 10000000) {
  //   echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if ($imageinfo['mime'] != 'image/png' && $imageinfo['mime'] != 'image/jpeg' && $filetypeinfo != 'application/zip' && $filetypeinfo != 'application/x-rar' && $imageinfo['mime']!= 'image/jpg' && $filetypeinfo != 'text/plain') {
  //   echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  // if everything is ok, try to upload file
}
else {
  if($imageinfo['mime'] == 'image/png' || $imageinfo['mime'] == 'image/jpeg' || $imageinfo['mime'] == 'image/jpg'){
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      if (isset($_SESSION['name'])) {
        $text_message = "<div class='msgln text-light'><span class='chat-time'>" . date("H:i") . "</span> <b class='user-name'>" . $_SESSION['name'] . "</b> " . "<a style='text-decoration: none;' target='_blank' href='uploads/{$_FILES['fileToUpload']['name']}'/>{$_FILES['fileToUpload']['name']}</a>" . "<br></div>\r\n";
        file_put_contents("../logs/chat.log", $text_message, FILE_APPEND | LOCK_EX);
        if($imageinfo['mime'] == 'image/jpeg' || $imageinfo['mime'] == 'image/jpg'){
          $compressjpeg = imagecreatefromjpeg($target_file);
          imagejpeg($compressjpeg, $target_file, 75);
        }
        else if($imageinfo['mime'] == 'image/png'){
          $compresspng = imagecreatefrompng($target_file);
          imagejpeg($compresspng, $target_file, 75);
        }
      }
    }
  }
  else{
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      if (isset($_SESSION['name'])) {
        $text_message = "<div class='msgln text-light'><span class='chat-time'>" . date("H:i") . "</span> <b class='user-name'>" . $_SESSION['name'] . "</b> " . "<a style='text-decoration: none;' href='uploads/{$_FILES['fileToUpload']['name']}'/>{$_FILES['fileToUpload']['name']}</a>" . "<br></div>\r\n";
        file_put_contents("../logs/chat.log", $text_message, FILE_APPEND | LOCK_EX);
      }
    }
  }
  
}
?>