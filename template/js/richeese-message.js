$(document).ready(function(){
    // var out = document.getElementById('layout-message');
    // out.scrollTop = out.scrollHeight;
    var currentUser = $("#currentUser").val();


    var div = document.getElementById("scrolling");
    div.scrollTop = div.scrollHeight - div.clientHeight;


    // var x = 1;
    var messageShown = 5;


    setInterval(function(){
        // var isScrolledToBottom = out.scrollHeight - out.clientHeight <= out.scrollTop + 1;
        // console.log(out.scrollHeight - out.clientHeight,  out.scrollTop + 1);
        // var newElement = document.createElement('div');
        // out.appendChild(newElement);
        // if(isScrolledToBottom)
        //     out.scrollTop = out.scrollHeight - out.clientHeight;


            reloadMessage();

    //         x += 1;


    }, 1000);

    function reloadMessage(){
        // console.log(x);
        $('#layout-message').load("/handler/handler_jquery_message.php",
            {
                code:currentUser,
                loadmessage:"random stuffs",
                n:messageShown
            }
        );
    }

    $("#load-more-message").on("click", function (e) {
        e.preventDefault();
        messageShown += 5;
        console.log(messageShown);
        reloadMessage();
        var formData = new FormData();
        formData.append("get-total-message", "");
        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            enctype: "multipart/form-data",
            url: "/handler/handler_ajax_message.php",
            data: formData,
            success: function (data) {
                if(messageShown >= $.parseJSON(data).info){
                    $("#divider-button-load").hide();
                }

            },
            error: function(){
              alert("error in ajax form submission");
            }
        });
    });

    $("#send-message").on("submit", function (e) {
        e.preventDefault();
        $("#button-send").prop("disabled", true);
        $("#button-send").html('mengirim <span class="spinner spinner-danger"></span>');
        messageShown ++;
        var formData = new FormData(this);
        formData.append("send-message", "");
        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            enctype: "multipart/form-data",
            url: "/handler/handler_ajax_message.php",
            data: formData,
            success: function (data) {
              var result = $.parseJSON(data);
              if(result.info != "success"){
                $("#failedMessage").html(result.info);
                $("#failed").modal("show");
              }else{
                $("#send-message")[0].reset();
                $("#input-messageImage-icon").removeClass("text-warning");
                $("#input-messageFile-icon").removeClass("text-success");
              }
            },
            error: function(){
              alert("error in ajax form submission");
            }
        });
        $("#button-send").prop("disabled", false);
        $("#button-send").html('Send <span class="icon icon-arrow-circle-right"></span>');
    });

    
});
