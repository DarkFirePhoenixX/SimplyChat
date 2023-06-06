// jQuery Document
$(document).ready(function () {
  var newscrollHeight = $("#chatbox")[0].scrollHeight - 20;
  $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
  window.addEventListener("focus", () => {
    $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
    $("#usermsg").focus();
  });

  window.onbeforeunload = function () {
    $.post("validation/post.php", { exit: "LypsZWF2ZQ==" });
  }
  
  $("#delete_yes").click(function(){
    $.post("validation/post.php", { text: "/*clear everything" });
    $("#usermsg").focus();
  });
  $("#delete_no").click(function () {
    $("#usermsg").focus();
  });

  $("#submitmsg").click(function () {
    var clientmsg = $("#usermsg").val();
    if (!$.trim(clientmsg).length > 0) {
      $("#usermsg").val('');
      return false;
    }
    else if (clientmsg.match(/^(http|ftp)s?:\/\/((?=.{3,253}$)(localhost|(([^ ]){1,63}\.[^ ]+)))$/)) {
      clientmsg = '<a href="' + clientmsg + '" style="text-decoration: none;" target="_blank">' + clientmsg + '</a>';
      $.post("validation/post.php", { text: clientmsg });
      $("#usermsg").val("");
      return false;
    }
    else if (clientmsg == "/*help") {
      $('#helpModal').modal('toggle')
      $("#usermsg").val("");
      return false;
    }
    else if (clientmsg == "/*clear everything") {
      $('#clearConfirmModal').modal('toggle')
      $("#usermsg").val("");
      return false;
    }
    else {
      $.post("validation/post.php", { text: clientmsg });
      $("#usermsg").val("");
      return false;
    }
  });

  $("#exit_ok").click(function () {
    $("#usermsg").focus();
  });

  document.getElementById("fileToUpload").addEventListener("change",function(){
  var filename = this.files[0].name;
  if(filename.length > 22){
    document.querySelector(".upload_text").innerHTML = 'Selected file: ' +'<br>' + filename.slice(0, (18-filename.length)) + '...';
  }
  else{
    document.querySelector(".upload_text").innerHTML = 'Selected file: ' +'<br>' + filename;
  }
  })

  // Submit form data via Ajax
  $("#upload").on('submit', function (e) {
    e.preventDefault();
    document.querySelector(".upload_text").innerHTML = 'Drag & drop your file' +'<br>' +'/ Click to add';
    $.ajax({
      type: 'POST',
      url: 'validation/upload.php',
      data: new FormData(this),
      dataType: 'json',
      contentType: false,
      cache: false,
      processData: false
    });
    document.getElementById("fileToUpload").files.length == 0;
  });

  function loadLog() {
    var oldscrollHeight = $("#chatbox")[0].scrollHeight - 200; //Scroll height before the request 
    $.ajax({
      url: "logs/chat.log",
      cache: false,
      success: function (html) {
        $("#chatbox").html(html); //Insert chat log into the #chatbox div 
        //Auto-scroll 
        var newscrollHeight = $("#chatbox")[0].scrollHeight - 200; //Scroll height after the request 
        if (newscrollHeight > oldscrollHeight) {
          $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div 
        }
      }
    });
  }
  setInterval(loadLog, 500);
  $("#exit_yes").click(function () {
    window.location = "index.php?logout=true";
  });
});
document.getElementById('usermsg').addEventListener("keydown", function (e) {
  if (e.key == 'Enter') {
    e.preventDefault();
    document.getElementById('submitmsg').click();
  }
});
document.getElementById('usermsg').addEventListener("keyup", function (e) {
  if (e.key == 'Enter') {
    document.getElementById('submitmsg').classList.add("exit_yes_hover");
    setTimeout(returnNormal,100);
    function returnNormal(){
      document.getElementById('submitmsg').classList.remove("exit_yes_hover");
    }
  }
});

// window.addEventListener('paste', ... or
document.getElementById('usermsg').onpaste = function (event) {
  var items = (event.clipboardData || event.originalEvent.clipboardData).items;
  // console.log(JSON.stringify(items)); // will give you the mime types
  for (index in items) {
    var item = items[index];
    if (item.kind === 'file') {
      var blob = item.getAsFile();
      var reader = new FileReader();
      reader.onload = function (event) {
        var dataURI = event.target.result;
        $.ajax({
          type: "POST",
          url: "validation/image.php",
          data: { hashString: dataURI },
        });
      }; // data url!
      reader.readAsDataURL(blob);
      // clearn clipboard data to avoid duplicates!
      navigator.clipboard.writeText('');
    }
  }
}