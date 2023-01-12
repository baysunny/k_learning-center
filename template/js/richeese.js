$(document).ready(function(){

    $('.form-add-information').on('submit', function (e) {
        e.preventDefault();
        $("#add-information-submit-button").prop("disabled", true);
        $("#add-information-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_information.php',
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);

                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                    $("#add-information-submit-button").prop("disabled", false);
                    $("#add-information-submit-button").html('Tambah');
                }else{
                    $(".modal").modal("hide");

                    $("#successMessage").html("berhasil ditambah");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){
                        location.reload();
                    });
                }
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    $('#form-add-carousel').on('submit', function (e) {
        e.preventDefault();
        $("#add-carousel-submit-button").prop("disabled", true);
        $("#add-carousel-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_carousel.php',
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);

                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                    $("#add-carousel-submit-button").prop("disabled", false);
                    $("#add-carousel-submit-button").html('Tambah');
                }else{
                    $(".modal").modal("hide");

                    $("#successMessage").html("berhasil ditambah");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){
                        location.reload();
                    });

                }

            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    $('#form-add-video-carousel').on('submit', function (e) {
        e.preventDefault();
        $("#add-video-carousel-submit-button").prop("disabled", true);
        $("#add-video-carousel-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_carousel.php',
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);
                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                    $("#add-video-carousel-submit-button").prop("disabled", false);
                    $("#add-video-carousel-submit-button").html('Tambah');
                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("berhasil ditambah");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){
                        location.reload();
                    });
                }
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    // ====================== EVENT 

    $('.form-add-single-event').on('submit', function (e) {
        e.preventDefault();
        $("#add-single-event-submit-button").prop("disabled", true);
        $("#add-single-event-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_event.php',
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);
                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                    $("#add-single-event-submit-button").prop("disabled", false);
                    $("#add-single-event-submit-button").html("tambah");
                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("Berhasil ditambah");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){
                        location.reload();
                    });
                }
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    $(".form-delete-event").on("submit", function(e) {
        e.preventDefault();
        $("#delete-single-event-submit-button").prop("disabled", true);
        $("#delete-single-event-submit-button").html('menunggu <span class="spinner spinner-danger"></span>')
        var formData = new FormData(this);
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
                  $("#delete-single-event-submit-button").prop("disabled", false);
                  $("#delete-single-event-submit-button").html("tambah");
                }else{
                  $(".modal").modal("hide");
                  $("#successMessage").html("event berhasil dihapus");
                  $("#success").modal("show");
                  $("#success").on("hidden.bs.modal", function(){
                      location.reload();
                  });
                }
              },
              error: function(){
                alert("error in ajax form submission");
              }
          });
    });

    $('.form-edit-single-event').on('submit', function (e) {
        e.preventDefault();
        $("#edit-single-event-submit-button").prop("disabled", true);
        $("#edit-single-event-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_event.php',
            data: formData,
            success: function (data) {
              var result = $.parseJSON(data);

              if(result.info != "success"){
                $("#failedMessage").html(result.info);
                $("#failed").modal("show");

                $("#edit-single-event-submit-button").prop("disabled", false);
                $("#edit-single-event-submit-button").html("update");
              }else{
                $(".modal").modal("hide");

                $("#successMessage").html("Berhasil diupdate");
                $("#success").modal("show");
                $("#success").on("hidden.bs.modal", function(){
                    location.reload();
                });

              }

            },
            error: function(){
              alert("error in ajax form submission");
            }
        });
    });

    $('.form-add-new-user-to-event').on('submit', function (e) {
        e.preventDefault();
        $("#add-new-user-to-event-submit-button").prop("disabled", true);
        $("#add-new-user-to-event-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_event.php',
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);

                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                    $("#add-new-user-to-event-submit-button").prop("disabled", false);
                    $("#add-new-user-to-event-submit-button").html('Tambah');
                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("Berhasil ditambah");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){
                    location.reload();
                    });
                }
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    $('.form-add-new-user-from-excel-to-event').on('submit', function (e) {
        e.preventDefault();
        $("#add-user-from-excel-to-event-submit-button").prop("disabled", true);
        $("#add-user-from-excel-to-event-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_event.php',
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);

                if(result.info != "success" && result.info != "info"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                    $("#add-user-from-excel-to-event-submit-button").prop("disabled", false);
                    $("#add-user-from-excel-to-event-submit-button").html('Tambah');
                }else if(result.info == "info"){
                    $("#warningMessage").html(result.data);
                    $("#warning").modal("show");
                    $("#add-user-from-excel-to-event-submit-button").prop("disabled", false);
                    $("#add-user-from-excel-to-event-submit-button").html('Tambah');
                    
                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("Berhasil ditambah");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){
                        location.reload();
                    });
                }
              },
              error: function(){
                $("#add-user-from-excel-to-event-submit-button").prop("disabled", false);
                $("#add-user-from-excel-to-event-submit-button").html('Tambah');
                alert("error in ajax form submission");
              }
          });
    });

    $('.form-add-user-to-event').on('submit', function (e) {
        e.preventDefault();
        $("#add-user-to-event-submit-button").prop("disabled", true);
        $("#add-user-to-event-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_event.php',
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);

                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                    $("#add-user-to-event-submit-button").prop("disabled", false);
                    $("#add-user-to-event-submit-button").html('Tambah');
                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("Berhasil ditambah");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){
                        location.reload();
                });
                }
            },error: function(){
                alert("error in ajax form submission");
            }
        });
    });
    
    var myModal = $('#failed').on('shown', function () {
        alert("aaaaaaaaa");
        clearTimeout(myModal.data('hideInteval'))
        var id = setTimeout(function(){
            myModal.modal('hide');
        });
        myModal.data('hideInteval', id);
    })
    myModal;
    $('.form-sign-in-event').on('submit', function (e) {
        e.preventDefault();
        $("#sign-in-event-submit-button").prop("disabled", true);
        $("#sign-in-event-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_event.php',
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);
                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                    $("#failed").on("shown.bs.modal", function(){
                        setTimeout(function() {
                            $('#failed').modal('hide');
                        }, 1000);

                    });

                }else{
                    $("#successMessage").html("Berhasil absent <br> silahkan refresh halaman untuk melihat data terbaru");
                    $("#success").modal("show");
                    $("#success").on("shown.bs.modal", function(){
                        setTimeout(function() {
                            $('#success').modal('hide');
                        }, 1000);
                    });
                }
                $(".form-sign-in-event")[0].reset();
                $("#sign-in-event-submit-button").prop("disabled", false);
                $("#sign-in-event-submit-button").html("absent");
                
            },
            error: function(){
              alert("error in ajax form submission");
            }
        });
    });

    $("#form-edit-certificate-event").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#edit-certificate-event-submit-button").prop('disabled', true);
        $("#edit-certificate-event-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        $("#save-icon").removeClass("hidden");
        
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_event.php',
            data: formData,
            success: function (data) {

                var result = $.parseJSON(data);
                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("Berhasil diupdate");
                    $("#success").modal("show");
                }
                $("#edit-certificate-event-submit-button").prop('disabled', false);
                $("#edit-certificate-event-submit-button").html('save <span class="btn-label btn-label-right"> <span class="icon icon-save icon-lg icon-fw"></span><span class="spinner spinner-danger spinner-xs icon-fw hidden" id="save-icon"></span></span>');
                
                $("#save-icon").addClass("hidden");
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    $("#send-certificate-click-button").on("click", function(e) {
        e.preventDefault();
        $("#send-certificate-click-button").prop("disabled", true);
        $("#send-certificate-click-button").html("mengirim <span class=\'spinner spinner-danger\'></span>");
        var username = $("#username").text();
        var admin = $("#admin-username").text();
        var adminCode = $("#admin-code").text();
        var formData = new FormData();
        formData.append("sendCertificate", username);
        formData.append("sentBy", admin);
        formData.append("code-send", adminCode);
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
                    $("#send-certificate-click-button").prop("disabled", false);
                    $("#send-certificate-click-button").html("Kirim Sertifikat");
                }else{
                    $(".modal").modal("hide");
                    $("#send-certificate-click-button").prop("disabled", false);
                    $("#send-certificate-click-button").html("Kirim Sertifikat");
                    $("#successMessage").html("Berhasil dikirim");
                    $("#success").modal("show");
                }
            },error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    // ======================= CATEGORY
    $('#form-add-category').on('submit', function (e) {
        e.preventDefault();
        $("#add-category-submit-button").prop("disabled", true);
        $("#add-category-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_course.php',
            data: formData,
            success: function (data) {
              var result = $.parseJSON(data);

              if(result.info != "success"){
                $("#failedMessage").html(result.info);
                $("#failed").modal("show");
                $("#add-category-submit-button").prop("disabled", false);
                $("#add-category-submit-button").html('Tambah');
              }else{
                $(".modal").modal("hide");

                $("#successMessage").html("Bidang baru ditambahkan");
                $("#success").modal("show");
                $("#success").on("hidden.bs.modal", function(){
                    location.reload();
                });

              }

            },
            error: function(){
              alert("error in ajax form submission");
            }
        });
    });

    $('#form-add-focus-category').on('submit', function (e) {
        e.preventDefault();
        $("#add-focus-category-submit-button").prop("disabled", true);
        $("#add-focus-category-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_course.php',
            data: formData,
            success: function (data) {
              var result = $.parseJSON(data);

              if(result.info != "success"){
                $("#failedMessage").html(result.info);
                $("#failed").modal("show");
                $("#add-focus-category-submit-button").prop("disabled", false);
                $("#add-focus-category-submit-button").html('Tambah');

              }else{
                $(".modal").modal("hide");

                $("#successMessage").html("Bidang baru ditambahkan");
                $("#success").modal("show");
                $("#success").on("hidden.bs.modal", function(){
                    location.reload();
                });

              }

            },
            error: function(){
              alert("error in ajax form submission");
            }
        });
    });

    $('#form-add-mcourse').on('submit', function (e) {
        e.preventDefault();
        $("#add-mcourse-submit-button").prop("disabled", true);
        $("#add-mcourse-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_course.php',
            data: formData,
            success: function (data) {
              var result = $.parseJSON(data);

              if(result.info != "success"){
                $("#failedMessage").html(result.info);
                $("#failed").modal("show");
                $("#add-mcourse-submit-button").prop("disabled", false);
                $("#add-mcourse-submit-button").html('Tambah');
              }else{
                $(".modal").modal("hide");

                $("#successMessage").html("Materi baru ditambahkan");
                $("#success").modal("show");
                $("#success").on("hidden.bs.modal", function(){
                    location.reload();
                });

              }

            },
            error: function(){
              alert("error in ajax form submission");
            }
        });
    });

    $('#form-add-course').on('submit', function (e) {
        e.preventDefault();
        $("#add-course-submit-button").prop("disabled", true);
        $("#add-course-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_course.php',
            data: formData,
            success: function (data) {

              var result = $.parseJSON(data);

              if(result.info != "success"){
                $("#failedMessage").html(result.info);
                $("#failed").modal("show");
                $("#add-course-submit-button").prop("disabled", false);
                $("#add-course-submit-button").html('Tambah');
              }else{
                $(".modal").modal("hide");

                $("#successMessage").html("Kategori baru ditambahkan");
                $("#success").modal("show");
                $("#success").on("hidden.bs.modal", function(){
                    location.reload();
                });

              }

            },
            error: function(){
              alert("error in ajax form submission");
            }
        });
    });

    $('#form-add-subject').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#add-subject-submit-button").prop("disabled", true);
        $("#add-subject-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_course.php',
            data: formData,
            beforeSubmit: function(){
                $(".progress-bar").width("0%");
            },
            uploadProgress: function(event, position, total, percentageComplete){
                $(".progress-bar").animate({
                    width: percentageComplete + "%"
                },{
                    duration: 1000
                });
            },
            success: function (data) {
                var result = $.parseJSON(data);
                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                    $("#add-subject-submit-button").prop("disabled", false);
                    $("#add-subject-submit-button").html('Tambah');
                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("Materi baru ditambahkan");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){
                        location.reload();
                    });
                }
            },
            error: function(){
              alert("error in ajax form submission");
            }
        });
    });

    $('#addQuestion').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_course.php',
            data: formData,
            success: function (data) {
              var result = $.parseJSON(data);
              if(result.info != "success"){
                $("#failedMessage").html(result.info);
                $("#failed").modal("show");
              }else{
                $(".modal").modal("hide");
                $(".my-question").val("");
                $("#successMessage").html("Pertanyaan sudah dikirim, mohon tunggu jawaban dari admin");
                $("#success").modal("show");
              }
            },
            error: function(){
              alert("error in ajax form submission");
            }
        });
    });

    $('#form-add-mshelter').on('submit', function (e) {
        e.preventDefault();
        $("#add-mshelter-submit-button").prop("disabled", true);
        $("#add-mshelter-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_course.php',
            data: formData,
            success: function (data) {
              var result = $.parseJSON(data);
              if(result.info != "success"){
                $("#failedMessage").html(result.info);
                $("#failed").modal("show");
                $("#add-mshelter-submit-button").prop("disabled", false);
                $("#add-mshelter-submit-button").html('Tambah');
              }else{
                $(".modal").modal("hide");
                $("#successMessage").html("Fokus Jabatan Berhasil Ditambah");
                $("#success").modal("show");
                $("#success").on("hidden.bs.modal", function(){location.reload();});
              }
            },
            error: function(){
              alert("error in ajax form submission");
            }
        });
    });

    $('#form-add-shelter').on('submit', function (e) {
        e.preventDefault();
        $("#add-shelter-submit-button").prop("disabled", true);
        $("#add-shelter-submit-button").html('menunggu <span class="spinner spinner-danger"></span>');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_course.php',
            data: formData,
            success: function (data) {
              var result = $.parseJSON(data);
              if(result.info != "success"){
                $("#failedMessage").html(result.info);
                $("#failed").modal("show");
                $("#add-shelter-submit-button").prop("disabled", false);
                $("#add-shelter-submit-button").html('Tambah');
              }else{
                $(".modal").modal("hide");
                $("#successMessage").html("Jabatan / Kompetensi Berhasil Ditambah");
                $("#success").modal("show");
                $("#success").on("hidden.bs.modal", function(){location.reload();});
              }
            },
            error: function(){
              alert("error in ajax form submission");
            }
        });
    });

    $("#editMyAccount").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#editMe").prop('disabled', true);
        $("#editMe").html('menunggu <span class="spinner spinner-danger"></span>');

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
                    $("#successMessage").html("Berhasil di update");
                    $("#success").modal("show");
                    $("#success").on("hidden.bs.modal", function(){location.reload();});
                }
                $("#editMe").html("Save");
                $("#editMe").prop('disabled', false);

            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

    $("#lock-web").on("submit", function (e){
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_outside.php',
            data: formData,
            success: function (data) {
              var result = $.parseJSON(data);

              if(result.info != "success"){
                $("#failedMessage").html(result.info);
                $("#failed").modal("show");

              }else{

                $(".modal").modal("hide");

                $("#successMessage").html("website:" + result.status);
                $("#success").modal("show");

              }

            },
            error: function(){
              alert("error in ajax form submission");
            }
        });
    });

    $("#template-certificate-edit").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#editMe").prop('disabled', true);
        $("#save-icon").removeClass("hidden");

        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            url: '/handler/handler_ajax_certificate.php',
            data: formData,
            success: function (data) {

                var result = $.parseJSON(data);
                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                }else{
                    $(".modal").modal("hide");
                    $("#successMessage").html("Berhasil diupdate");
                    $("#success").modal("show");
                }
                $("#editMe").prop('disabled', false);
                $("#save-icon").addClass("hidden");
            },
            error: function(){
                alert("error in ajax form submission");
            }
        });
    });

});

