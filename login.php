<?php
error_reporting(0);
session_start();
    if($_SESSION['name'] == null){
        header("Location: index.php");
    }
    else {
        if(isset($_SESSION['login_message'] )) {
            file_put_contents("logs/chat.log", $_SESSION['login_message'], FILE_APPEND | LOCK_EX);
            unset($_SESSION['login_message']);
        }
?>
<!DOCTYPE html>
<html lang="en" oncontextmenu="return false;" ondragstart="return false;">
<head>
    <meta charset="utf-8" />
    <title>SimplyChat</title>
    <link rel="icon" type="image/x-icon" href="https://img.icons8.com/color-glass/96/null/group-task.png">
    <meta name="description" content="SimplyChat" />
    <link rel="stylesheet" href="resources/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <noscript class="text-danger d-flex justify-content-center fs-3 mt-3">Please enable javascript :)</noscript>
    <div id="wrapper" class="mt-4 fs-5">
        <div id="menu">
            <p class="welcome fs-5 text-light">Welcome, <b class="fs-5 text-info"><?php echo $_SESSION['name']; ?></b>!</p>
            <p id="time_span" class="me-3"></p>
            <p class="logout"><a id="exit" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="fs-5">Exit Chat</a></p>
        </div>
        <div id="chatbox" class="scrollbar-colored">
            <?php
            if(file_exists("logs/chat.log") && filesize("logs/chat.log") > 0){
                $contents = file_get_contents("logs/chat.log");
                echo $contents;
            }
            if(filesize("logs/chat.log") > 10000000){
                $content = file_get_contents('logs/chat.log');
                $content = explode("\n", $content);
                array_splice($content, 0, 1000);
                $newcontent = implode("\n", $content);
                file_put_contents('logs/chat.log', $newcontent);
            }
            ?>
        </div>
        <form name="message" action="">
            <textarea autofocus name="usermsg" type="text" id="usermsg" class="ps-1 text-light" rows="2"></textarea>
            <input name="submitmsg" type="submit" id="submitmsg" value="Send" class="fs-5"/>
        </form>
        <form action="" id="upload" class="d-flex justify-content-end" method="post" enctype="multipart/form-data">
        <div class=" text-light text-center fst-italic p-1 pb-2">Print screen or paste image directly in chat OR</div>
            <div class="uploads">
                <div class="text-center p-1 pb-2 upload_text" style="position: absolute; outline: dotted 2px; width: 200px; heigth: 40px;">Drag &
                    drop your file <br> / Click to add</div>
                <input type="file" name="fileToUpload" id="fileToUpload" class="btn btn-primary fs-6"
                    style="width:200px; height:60px; opacity: 0;">
            </div>
            <input type="submit" value="Upload File" name="submit" class="btn btn-primary fs-6 p-3" id="submitfile">
        </form>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content exit-modal bg-dark text-light border-info border-5">
                <div class="modal-header border-info">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Exit</h1>
                    <button type="button" class="btn-close bg-info" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Exit the chat room?
                </div>
                <div class="modal-footer border-info">
                    <button type="button" class="btn btn-primary text-light fs-5" id="exit_yes"
                        data-bs-dismiss="modal">Yes</button>
                    <button type="button" class="btn btn-info text-light fs-5" data-bs-dismiss="modal" id="exit_no">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- clearConfirmModal -->
    <div class="modal fade" id="clearConfirmModal" tabindex="-1" aria-labelledby="clearConfirmModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content exit-modal bg-dark text-light border-info border-5">
                <div class="modal-header border-info">
                    <h1 class="modal-title fs-5" id="clearConfirmModalLabel">Delete discussion</h1>
                    <button type="button" class="btn-close bg-info" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Delete the entire discussion including files?<br>
                    <span class="text-warning fst-italic">(This cannot be undone.)</span>
                </div>
                <div class="modal-footer border-info">
                    <button type="button" class="btn btn-primary text-light fs-5" id="delete_yes"
                        data-bs-dismiss="modal">Yes</button>
                    <button type="button" class="btn btn-info text-light fs-5" data-bs-dismiss="modal" id="delete_no">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content help-modal bg-dark text-light border-info border-5">
                <div class="modal-header border-info">
                    <h1 class="modal-title fs-4" id="helpModalLabel">Command help</h1>
                    <button type="button" class="btn-close bg-info" data-bs-dismiss="modal" aria-label="Close" id="closemodal"></button>
                </div>
                <div class="modal-body">
                    <div class="card-header fs-5 mb-3">
                    Current commands:
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-light bg-transparent border-0"><span class="text-info">/*help</span> - displays this window.</li>
                    <li class="list-group-item text-light bg-transparent border-0"><span class="text-info">/*clear chat</span> - clears the chat leaving only pictures and links.</li>
                    <li class="list-group-item text-light bg-transparent border-0"><span class="text-info">/*clear everything</span> - clears the entire discussion including files.</li>
                    <li class="list-group-item text-light bg-transparent border-0"><span class="text-info">/*clear files</span> - clears all the uploaded files including screenshots.</li>
                    <li class="list-group-item text-light bg-transparent border-0"><span class="text-info">/*clear pics</span> - clears all the screenshots.</li>
                </ul>
                </div>
                <div class="modal-footer border-info">
                    <button type="button" class="btn btn-primary text-light fs-5 p-2 px-4" id="exit_ok" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script async type="text/javascript" src="resources/core.js"></script>
</body>
</html>
<?php
    }
?>