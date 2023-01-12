$(document).ready(function(){

    // ============ Template_Edit_User_Admin() ============
    function createTableFollowedEventsGlobal(tableData) {
        if(tableData.length < 1){
            return "<h3>Kosong</h3>";
        }
        var result = "<table id=\'demo-datatables-scroller-2\' class=\'table table-striped table-nowrap dataTable\' cellspacing=\'0\' width=\'100%\'><thead><tr><th>Event</th><th>Status</th><th>Tgl Registrasi</th></tr></thead><tbody>";
        var n = 0;
        for(var i=0; i<tableData.length; i++) {
            n++;
            result += "<tr>";
            result += "<td class=\'text-left\'><a class=\'text-info\' href=\'/dashboard/event/event-detail.php?event="+tableData[i].eventID+"\'><span class=\'icon icon-calendar\'></span> "+tableData[i].eventName+"</a></td>";
            if(tableData[i].absentStatus == 0){
                result += '<td><span class="label label-outline-default" style="width:100px">Belum hadir</span></td>';
            }else if(tableData[i].absentStatus == 1){
                result += '<td><span class="label label-outline-success" style="width:100px">Hadir</span></td>';
            }else{
                result += '<td><span class="label label-outline-danger" style="width:100px">Tidak hadir</span></td>';
            }
            
            result += "<td class=\'text-left\'>"+tableData[i].dateCreated+"</td>";
            result += "</tr>";
        }
        result += "</tbody></table>";
        return result;
    }

    function createTableReadUserGlobal(tableData) {
        if(tableData.length < 1){
            return "<h3>Kosong</h3>";
        }
        var result = "<table id=\'demo-datatables-scroller-2\' class=\'table table-striped table-nowrap dataTable\' cellspacing=\'0\' width=\'100%\'><thead><tr><th>No</th><th>Judul</th><th>Waktu(detik)</th><th>Time</th></tr></thead><tbody>";
        var n = 0;
        for(var i=0; i<tableData.length; i++) {
            n++;
            result += "<tr>";
            result += "<td class=\'text-left\'>"+n+"</td>";
            result += "<td class=\'text-left\'><a href=\'/dashboard/materi/read.php?subject=" + tableData[i].subjectID +"\'><span class=\'icon icon-book\'></span>" + tableData[i].subjectName+ "</a></td>";
            result += "<td class=\'text-left\'>"+tableData[i].timeInSecond+"</td>";
            result += "<td class=\'text-left\'>"+tableData[i].timeRead+"</td>";
            result += "</tr>";
        }
        result += "</tbody></table>";
        return result;
    }

    function createGenderDropDown(gender){
        var result = "";
        if("Laki-Laki".localeCompare(gender) == 0 || "1".localeCompare(gender) == 0){
            result = "<option value=\'Laki-Laki\' selected>Laki-Laki</option><option value=\'Perempuan\'>Perempuan</option>";
        }else{
            result = "<option value=\'Laki-Laki\'>Laki-Laki</option><option value=\'Perempuan\' selected>Perempuan</option>";
        }return result;
    }

    function createMonthDropDown(month){
        var months = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];

        var result = "";
        var n;
        var mon;
        for(var i=0; i<months.length; i++){
            n = i + 1;
            
            if(n < 10){
                mon = "0" + n;
            }else{
                mon = n;
            }
            if(month == n){
                result += "<option value=" + mon + " selected>"+months[i]+"</option>";
            }else{
                result += "<option value=" + mon + ">"+months[i]+"</option>";
            }
        }return result;
    }

    // ====================== pop up Trigger ======================
    

    $("#profile-tab-edit-account").on("click", function(e){
        $(".profile-container").append("<strong><span class=\'loading spinner spinner-info spinner-lg\'></span></strong>");
        var userGlobalID = $("#user-globalID").text();
        var formData = new FormData();
        formData.append("userGlobalID", userGlobalID);
        formData.append("getProfile", "ahehe");
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
                    $("#edit-account-image").attr("src", "/dashboard/drive/drive-user/user-images/" + result.userData.image);
                    $(".edit-account-userID").val(result.userData.userID);
                    $("#edit-account-username").val(result.userData.username);
                    $("#edit-account-password").val(result.userData.password);
                    $("#edit-account-email").val(result.userData.email);
                    $("#edit-account-firstName").val(result.userData.firstName);
                    $("#edit-account-lastName").val(result.userData.lastName);
                    $("#edit-account-phone").val(result.userData.phone);
                    $("#edit-account-nip").val(result.userData.nip);
                    $("#edit-account-golongan").val(result.userData.golongan);
                    $("#edit-account-jabatan").val(result.userData.jabatan);
                    $("#edit-account-unitKerja").val(result.userData.unitKerja);
                    $("#edit-account-instansi").val(result.userData.instansi);
                    $("#edit-account-tempat").val(result.userData.tempat);
                    $("#edit-account-whatsapp").val(result.userData.whatsapp);
                    $("#edit-account-gender").append(createGenderDropDown(result.userData.gender));
                    $("#edit-account-birth-month").append(createMonthDropDown(result.userData.birthMonth));
                    $("#edit-account-birth-day").val(result.userData.birthDay);
                    $("#edit-account-birth-year").val(result.userData.birthYear);
                    $("#editAccount").modal("show");
                }
                $(".loading").hide();
            },error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    $("#profile-tab-events").on("click", function(e){
        $(".profile-container").append("<strong><span class=\'loading spinner spinner-info spinner-lg\'></span></strong>");
        var username = $("#username").text();
        var formData = new FormData();
        formData.append("getFollowedEvents", username);
        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            enctype: "multipart/form-data",
            url: "/handler/handler_ajax_event.php",
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);
                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                }else{
                   $("#profile-followed-events-table").html(createTableFollowedEventsGlobal(result.followedEvents));
                }
                $(".loading").hide();
            },error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    // pop up edit user
    $(".table-data-user").on("click", 'tbody tr td:first-child a', function(e){
        $(this).append(" <span class=\'loading spinner spinner-danger\'></span>");
        var userGlobalID = $(this).find("label").text();
        var formData = new FormData();
        formData.append("userGlobalID", userGlobalID);
        formData.append("getProfile", "ahehe");
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
                    $("#edit-account-image").attr("src", "/dashboard/drive/drive-user/user-images/" + result.userData.image);
                    $(".edit-account-userID").val(result.userData.userID);
                    $("#edit-account-username").val(result.userData.username);
                    $("#edit-account-password").val(result.userData.password);
                    $("#edit-account-email").val(result.userData.email);
                    $("#edit-account-firstName").val(result.userData.firstName);
                    $("#edit-account-lastName").val(result.userData.lastName);
                    $("#edit-account-phone").val(result.userData.phone);
                    $("#edit-account-nip").val(result.userData.nip);
                    $("#edit-account-golongan").val(result.userData.golongan);
                    $("#edit-account-jabatan").val(result.userData.jabatan);
                    $("#edit-account-unitKerja").val(result.userData.unitKerja);
                    $("#edit-account-instansi").val(result.userData.instansi);
                    $("#edit-account-tempat").val(result.userData.tempat);
                    $("#edit-account-whatsapp").val(result.userData.whatsapp);
                    $("#edit-account-gender").append(createGenderDropDown(result.userData.gender));
                    $("#edit-account-birth-month").append(createMonthDropDown(result.userData.birthMonth));
                    $("#edit-account-birth-day").val(result.userData.birthDay);
                    $("#edit-account-birth-year").val(result.userData.birthYear);
                    $("#editAccount").modal("show");
                }
                $(".loading").hide();
            },error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    // pop up delete user
    $(".table-data-user").on("click", 'tbody tr td:nth-child(2) a', function(e){
        $(this).append(" <span class=\'loading spinner spinner-danger\'></span>");
        var userGlobalID = $(this).find("label").text();
        var formData = new FormData();
        formData.append("userGlobalID", userGlobalID);
        formData.append("getProfile", "ahehe");
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
                    $(".delete-account-userID").val(result.userData.userID);
                    $("#delete-account-user-name").html("( " + result.userData.name + " )");
                    $("#deleteAccount").modal("show");
                }
                $(".loading").hide();
            },error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    // pop up profile user
    $(".table-data-user").on("click", 'tbody tr td:nth-child(3) a', function(e){
        $(this).append(" <span class=\'loading spinner spinner-danger\'></span>");
        var userGlobalID = $(this).find("label").text();
        var formData = new FormData();
        formData.append("userGlobalID", userGlobalID);
        formData.append("getProfile", "ahehe");
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
                    $("#profile-name").html(result.userData.name);
                    $("#profile-phone").html("<span class=\'icon icon-phone\'></span> " + result.userData.phone);
                    $("#profile-email").html("<span class=\'icon icon-envelope\'></span> " + result.userData.email);
                    $("#profile-birthDate").html("<span class=\'icon icon-calendar\'></span> " + result.userData.tempat + " " + result.userData.birthDate);
                    $("#profile-totalSubjectRead").html(result.readData.totalSubjectRead);
                    $("#profile-timeRead").html(result.readData.timeRead);
                    $("#profile-totalQuestion").html(result.readData.totalQuestion);
                    $("#profile-image").attr("src", "/dashboard/drive/drive-user/user-images/" + result.userData.image);
                    $("#profile-data-table").html(createTableReadUserGlobal(result.readData.readList));
                    $("#profile").modal("show");
                }
                $(".loading").hide();
            },error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    $(".crs-reply-question-pop-up").on("click", function(e){
        $(this).append(" <span class=\'loading spinner spinner-danger\'></span>");
        var questionID = $(this).find(".questionID").text();
        var formData = new FormData();
        formData.append("getQuestion", questionID);
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
                }else{
                    $("#reply-question-questionID").val(result.question.questionID);
                    $("#reply-question-username").val(result.question.username);
                    $("#reply-question-subjectName").val(result.question.subjectData.subjectName);
                    $("#reply-question-question").val(result.question.question);
                    $("#reply-question-email").val(result.question.userData.email);
                    
                    $("#reply-question-pop-up").modal("show");
                }
                $(".loading").hide();
            },error: function(){
                alert("error in ajax form submission");
            }
        });
    });



    $("#addUser").on("submit", function (e) {
        e.preventDefault();
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
                    $("#failedMessage").html("<h4>" + result.info + "</h4>");
                    $("#failed").modal("show");
                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("<h4><?php echo $title1; ?> baru ditambahkan</h4>");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){location.reload();});
                }
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    // ====================== Form Submit ======================

    $("#form-edit-account").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $("#submit-button-edit-account").prop("disabled", true);
        $("#submit-button-edit-account").html("menunggu <span class=\'spinner spinner-danger\'></span>");
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
                    $("#failedMessage").html("<h4>" + result.info + "</h4>");
                    $("#failed").modal("show");
                    $("#submit-button-edit-account").prop("disabled", false);
                    $("#submit-button-edit-account").html("Update");
                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("<h4>success</h4>");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){location.reload();});
                }
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    $("#form-edit-profile").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $("#submit-button-edit-profile").prop("disabled", true);
        $("#submit-button-edit-profile").html("menunggu <span class=\'spinner spinner-danger\'></span>");
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
                    $("#failedMessage").html("<h4>" + result.info + "</h4>");
                    $("#failed").modal("show");
                    $("#submit-button-edit-profile").prop("disabled", false);
                    $("#submit-button-edit-profile").html("Update");
                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("<h4>success</h4>");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){location.reload();});
                }
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    $("#form-edit-AccountProfile").on("submit", function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var userID = $("#user-ID").text();
        var userGlobalID = $("#user-globalID").text();
        formData.append("editAccountProfile", "");
        formData.append("userID", userID);
        formData.append("userGlobalID", userGlobalID);
        $("#submit-button-edit-AccountProfile").prop("disabled", true);
        $("#submit-button-edit-AccountProfile").html("menunggu <span class=\'spinner spinner-danger\'></span>");
        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            enctype: "multipart/form-data",
            url: "/handler/handler_ajax_user.php",
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);
                $("#submit-button-edit-AccountProfile").prop("disabled", false);
                $("#submit-button-edit-AccountProfile").html("Update");
                if(result.info != "success"){
                    $("#failedMessage").html("<h4>" + result.info + "</h4>");
                    $("#failed").modal("show");
                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("<h4>success</h4>");
                    $("#success").modal("show");
                }         
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    $("#form-delete-account").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $("#submit-button-delete-account").prop("disabled", true);
        $("#submit-button-delete-account").html("menunggu <span class=\'spinner spinner-danger\'></span>");
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
                    $("#failedMessage").html("<h4>" + result.info + "</h4>");
                    $("#failed").modal("show");
                    $("#submit-button-delete-account").prop("disabled", false);
                    $("#submit-button-delete-account").html("Hapus");
                }else{
                    $(".modal").modal("hide");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){location.reload();});
                }
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    $("#form-reply-question").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $("#submit-button-reply-question").prop("disabled", true);
        $("#submit-button-reply-question").html("menunggu <span class=\'spinner spinner-danger\'></span>");
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
                    $("#failedMessage").html("<h4>" + result.info + "</h4>");
                    $("#failed").modal("show");
                    $("#submit-button-reply-question").prop("disabled", false);
                    $("#submit-button-reply-question").html("Kirim");
                }else{
                    $(".modal").modal("hide");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){location.reload();});
                }
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    // send certificate 
    // dasboard
    $(".table-data-certificate").on("click", 'tbody tr td:nth-child(2) button', function(e){
        $(this).prop("disabled", true);
        $(this).append(" <span class=\'loading spinner spinner-danger\'></span>");
        var temp = this;
        var sentBy = $(this).find(".sentBy").text();
        var codeSend = $(this).find(".code-send").text();
        var username = $(this).find(".username").text();
        var lastText = "Tidak Tersedia";
        var formData = new FormData();
        formData.append("sendCertificate", "");
        formData.append("code-send", codeSend);
        formData.append("sentBy", sentBy);
        if(username != "None"){
            var lastText = "Kirim";
            formData.append("username", username);
        }
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
                    $(temp).prop("disabled", false);
                }else{
                    $(".modal").modal("hide");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){location.reload();});
                }       
                $(".loading").hide();
            },error: function(){
                alert("error in ajax form submission");
            }
        });
        
    });

    // profile
    $("#send-certificate-button").on("click", function(e){
        
        $(this).prop("disabled", true);
        var sentBy = $(this).find(".sentBy").text();
        var codeSend = $(this).find(".code-send").text();
        var username = $("#username").text();
        var formData = new FormData();
        $(this).text("Mengirim");
        $(this).append(" <span id=\'loading\' class=\'spinner spinner-danger\'></span>");
        formData.append("sendCertificate", "");
        formData.append("code-send", codeSend);
        formData.append("sentBy", sentBy);
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
                    $("#loading").hide();
                    $("#send-certificate-button").attr("disabled", false);
                    $("#send-certificate-button").text("Kirim Sertifikat");
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                }else{
                    $(".modal").modal("hide");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){location.reload();});
                }
                
                $(".loading").hide();
            },error: function(){
                alert("error in ajax form submission");
            }
        });
        
    });

    

    // ============ Template_Event_Detail_Admin() ============

    function createFileGlobalTable(tableData) {
        if(tableData.length < 1){
            return "<h3 class=\'text-center\'>Kosong</h3>";
        }
        var result = "<table id=\'demo-datatables-scroller-2\' class=\'table table-striped table-nowrap dataTable\' cellspacing=\'0\' width=\'100%\'><thead><tr><th>No</th><th>File</th><th>Tanggal Dikumpulkan</th><th>Download</th></tr></thead><tbody>";
        var n = 0;
        for(var i=0; i<tableData.length; i++) {
            n++;
            result += "<tr>";
            result += "<td class=\'text-left\'>"+n+"</td>";
            result += "<td class=\'text-left\'>"+tableData[i].title+"</td>";
            result += "<td class=\'text-left\'>"+tableData[i].dateCreated+"</td>";
            result += "<td class=\'text-left\'><a download href=\'/dashboard/drive/drive-event/event-file-global/" + tableData[i].fileFileName +"\'><span class=\'icon icon-download\'></span></a></td>";
            result += "</tr>";
        }
        result += "</tbody></table>";
        return result;
    }

    $(".event-file-global-button-trigger").on("click", function(e){

        $(this).append(" <span class=\'loading spinner spinner-danger\'></span>");
        var row = $(this).closest("tr");
        var registrationCode = row.find(".registrationCode").text();
        $("#user-name").html(row.find(".data-user-name").text());
        var formData = new FormData();
        formData.append("getFileGlobalList", registrationCode);
        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            enctype: "multipart/form-data",
            url: "/handler/handler_ajax_event.php",
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);
                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                }else{
                    $("#table-file-global").html(createFileGlobalTable(result.fileGlobalList));
                    $("#event-file-global-pop-up").modal("show");
                }
                $(".loading").hide();
            },error: function(){
                alert("error in ajax form submission");
            }
        });
    });

});