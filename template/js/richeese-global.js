$(document).ready(function(){

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    if(dd<10) {
        dd="0"+dd
    }

    if(mm<10) {
        mm="0"+mm
    }

    today = mm+"/"+dd+"/"+yyyy;
    if($("#realtimeTime").length){
        var myVar=setInterval(function(){myTimer()},1000);
    }


    function myTimer() {
        var d = new Date();
        document.getElementById("realtimeTime").innerHTML = d.toLocaleTimeString();
    }

    $('#pdf-file').change(function() {
        var file = this.files[0];
        if(typeof(file) !== "undefined"){
            var extension = file.name.split('.').pop().toLowerCase();
            if(extension == "pdf"){
                $("#button-switch-pdf").removeClass("btn-outline-success").addClass("btn-success");
                var filename;
                if(file.name.length > 16){
                    filename = file.name.slice(0, 16) + "...";
                }else{
                    filename = file.name;
                }
                $("#file-message").html(filename);
            }else{
                $("#button-switch-pdf").removeClass("btn-success").addClass("btn-outline-success");
                $("#file-message").html("No file chosen");
                alert("pdf file!");
            }
        }else{
            $("#button-switch-pdf").removeClass("btn-success").addClass("btn-outline-success");
            $("#file-message").html("No file chosen");
        }
    });

    $('#xls-file').change(function() {
        var file = this.files[0];
        if(typeof(file) !== "undefined"){
            var extension = file.name.split('.').pop().toLowerCase();
            if(extension == "xlsx"){
                $("#button-switch-xls").removeClass("btn-outline-success").addClass("btn-success");
                var filename;
                if(file.name.length > 16){
                    filename = file.name.slice(0, 16) + "...";
                }else{
                    filename = file.name;
                }
                $("#file-message").html(filename);
            }else{
                $("#button-switch-xls").removeClass("btn-success").addClass("btn-outline-success");
                $("#file-message").html("No file chosen");
                alert("xls file!");
            }
        }else{
            $("#button-switch-pdf").removeClass("btn-success").addClass("btn-outline-success");
            $("#file-message").html("No file chosen");
        }
    });

    $("#vid-file").change(function() {
        var file = this.files[0];
        if(typeof(file) !== "undefined"){

            var extension = file.name.split('.').pop().toLowerCase();

            if(extension == "mp4" || extension == "3gp" || extension == "mov" || extension == "m4v"){

                $("#button-switch-vid").removeClass("btn-outline-success").addClass("btn-success");
                var filename;
                if(file.name.length > 16){
                    filename = file.name.slice(0, 16) + "...";
                }else{
                    filename = file.name;
                }
                $("#video-message").html(filename);
            }else{
                $("#button-switch-vid").removeClass("btn-success").addClass("btn-outline-success");
                $("#video-message").html("No file chosen");
            }
        }else{

            $("#button-switch-vid").removeClass("btn-success").addClass("btn-outline-success");
            $("#video-message").html("No file chosen");
        }
    });

    $('#file').change(function() {
        var file = this.files[0];
        if(typeof(file) !== "undefined"){
            var extension = file.name.split('.').pop().toLowerCase();
            if(jQuery.inArray(extension, ['png', 'jpg', 'jpeg']) === -1){
                $("#button-switch-image").removeClass("btn-primary").addClass("btn-outline-primary");
            }else{
                $("#button-switch-image").removeClass("btn-outline-primary").addClass("btn-primary");
                var filename;
                if(file.name.length > 16){
                    filename = file.name.slice(0, 16) + "...";
                }else{
                    filename = file.name;
                }
                $("#image-message").html(filename);
            }
        }else{
            $("#button-switch-image").removeClass("btn-primary").addClass("btn-outline-primary");
        }
    });

    $('#input-messageImage').change(function() {
        var file = this.files[0];
        if(typeof(file) !== "undefined"){
            var extension = file.name.split('.').pop().toLowerCase();
            if(jQuery.inArray(extension, ['png', 'jpg', 'jpeg']) === -1){
                $("#input-messageImage-icon").removeClass("text-warning");
            }else{
                $("#input-messageImage-icon").addClass("text-warning");
            }
        }else{
            $("#input-messageImage-icon").removeClass("text-warning");
        }
    });

    $('#input-messageFile').change(function() {
        var file = this.files[0];
        if(typeof(file) !== "undefined"){
            var extension = file.name.split('.').pop().toLowerCase();
            if(jQuery.inArray(extension, ['png', 'jpg', 'jpeg']) === -1){
                $("#input-messageFile-icon").removeClass("text-success");
            }else{
                $("#input-messageFile-icon").addClass("text-success");
            }
        }else{
            $("#input-messageFile-icon").removeClass("text-success");
        }
    });

    
    $("#form-self-edit-account").on("submit", function (e) {
        e.preventDefault();
        $("#submit-button-self-edit-account").prop('disabled', true);
        $("#submit-button-self-edit-account").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        
        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            enctype: "multipart/form-data",
            url: "/handler/handler_ajax_user.php",
            data: formData,
            success: function (data) {

                var result = $.parseJSON(data);
                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");

                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("Berhasil");
                    $("#success").modal("show");

                    $("#success").on("hidden.bs.modal", function(){
                        location.reload();
                    });
                }
                $("#submit-button-self-edit-account").html("Save");
                $("#submit-button-self-edit-account").prop('disabled', false);
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    $("#form-sign-up").on("submit", function (e) {
        e.preventDefault();
        $("#sign-up-submit-button").prop('disabled', true);
        $("#sign-up-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        // var button = $("#sign-up-submit");
        // if(button.text() == "Kirim ulang"){
        //     formData.append("resend-email", "hiya hiya hiya!")
        // }

        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            enctype: "multipart/form-data",
            url: "/handler/handler_ajax_user.php",
            data: formData,
            success: function (data) {

                var result = $.parseJSON(data);
                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");

                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("Berhasil silahkan cek email untuk verifikasi akun (code verifikasi valid dalam 60 menit)");
                    $("#success").modal("show");

                    $("#success").on("hidden.bs.modal", function(){
                        location.reload();
                    });
                }
                $("#sign-up-submit-button").html("Sign up");
                $("#sign-up-submit-button").prop('disabled', false);
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });


    $("#reset-password").on("submit", function (e) {
        e.preventDefault();
        $("#reset-submit").prop('disabled', true);
        $("#reset-submit").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            enctype: "multipart/form-data",
            url: "/handler/handler_ajax_outside.php",
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);
                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("Silahkan cek email");
                    $("#success").modal("show");
                }
                $("#reset-submit").html("Kirim Ulang");
                $("#reset-submit").prop('disabled', false);
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });


    function createHistoryReadSortByDate(tableData) {
        if(tableData.length < 1){
            return "<h3>Tidak ada record</h3>";
        }
        var subjectPoint = {
            0 : "",
            900 : "<span class=\'icon icon-star icon-md\'></span>",
            1800 : "<span class=\'icon icon-star icon-md'\></span><span class=\'icon icon-star icon-md'\></span>",
            2700 : "<span class=\'icon icon-star icon-md'\></span><span class=\'icon icon-star icon-md'\></span><span class=\'icon icon-star icon-md'\></span>"
        };
        var result = "<table id=\'demo-datatables-scroller-2\' class=\'table table-striped table-nowrap dataTable\' cellspacing=\'0\' width=\'100%\'><thead><tr><th>No</th><th>Judul</th><th>Point</th><th>Max Waktu Perhari</th><th>Waktu Membaca</th></tr></thead><tbody>";
        var n = 0;
        for(var key in tableData){
            n++;
            result += "<tr>";
            result += "<td class=\'text-left\'>"+n+"</td>";
            result += "<td class=\'text-left\'><a href=\'/dashboard/materi/read.php?subject=" + tableData[key].subjectID +"\'><span class=\'icon icon-book\'></span> " + tableData[key].subject.subjectName+ "</a></td>";
            result += "<td class=\'text-left text-warning\'>"+subjectPoint[tableData[key].subject.point]+"</td>";
            result += "<td class=\'text-left\'>"+tableData[key].subject.pointInFormat+"</td>";
            
            result += "<td class=\'text-left\'>"+tableData[key].timeInFormat+"</td>";
            result += "</tr>";
        }
        
        result += "</tbody></table>";
        return result;
    }


    $("#sortByDate").on("change", function(){
        $("#table-log-read").html("<div class=\'row gutter-xs\'><span class=\'loading spinner spinner-danger\'></span></div>");
        $("#todayRecord").hide();
        var sortByDate = $("#sortByDate");
        sortByDate.css("color", "#990000");
        sortByDate.css("font-weight", "bold");
        var username = $("#username").text();
        var formData = new FormData();
        formData.append("getHistoryReadByDate", sortByDate.val());
        formData.append("username", username);
        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            enctype: "multipart/form-data",
            url: "/handler/handler_ajax_course.php",
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);
                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                    $("#table-log-read").html("");
                }else{
                    $("#myPointCurrentDay").html("Waktu Total : " + result.myPointInFormat)
                    $("#table-log-read").html(createHistoryReadSortByDate(result.history));
                    
                }
            },error: function(){
                alert("error in ajax form submission");
            }
        }); 
    });

});

