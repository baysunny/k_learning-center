<?php

session_start();

if($_SESSION["type"] > 2){
  header("Location: /dashboard/");
}else{

include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/gallery.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";

$page = "Gallery";

?>



    <link rel="stylesheet" href="/template_vendor/css/application.min.css">
    <link rel="stylesheet" href="/template_vendor/css/demo.min.css">
    <style>

        .resize-img{
            width: 250px;
            height: 200px;
            object-fit: cover;
        }

    </style>
    </head>
    <body class="layout layout-header-fixed layout-sidebar-fixed">

        <div class="layout-header">
            <?php nav_bar($page, "<span class='icon icon-image'></span>"); ?>
        </div>
        <div class="layout-main">
            <?php sidebar(); ?>
            <div class="layout-content">
                <div class="layout-content-body">
                  <div class="row gutter-xs">
                    <div class="col-xs-12">
                      <p><small>The first column shows individual constituent elements of a card, and the following columns show combinations in different ways.</small></p>
                    </div>
                  </div>

                  <div class="row gutter-xs">
                    <div class="col-xs-12">
                        <div class="card">
                                <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#addAlbum">
                                    <strong>Buat Album </strong>
                                </button>
                        </div>
                    </div>
                  </div>

                  <div class="row gutter-xs">
                    <div class="col-sm-12">
                        <div class="row gutter-xs">
                            <?php galleryList();
                            ?>

                        </div>
                    </div>

                  </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="addAlbum" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <form id="demo-uploader" action="//uploader.madebytilde.com/" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Buat album foto baru
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </h4>

                    </div>
                    <div class="modal-body">
                        <div class="signup">
                          <div class="signup-body">
                            <div class="signup-form">

                                <div class="row gutter-xs">
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label for="album">Nama Album</label>
                                      <input class="form-control" type="text" name="galleryName" spellcheck="false" autocomplete="off" data-msg-required="Masukan nama album." required>
                                    </div>
                                  </div>
                                </div>
                                <div class="row gutter-xs">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="file-upload-btn btn btn-primary">
                                            Upload files
                                                <input class="file-upload-input" type="file" name="files[]" accept='image/*' multiple="multiple">
                                            </label>
                                        </div>
                                        <div class="form-group">
                                          <ul class="file-list"></ul>
                                        </div>
                                    </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </div>
            </form>
          </div>
        </div>

    <script id="template-upload" type="text/x-tmpl">
      {% for (var i=0, file; file=o.files[i]; i++) { %}
      <li class="file template-upload fade">
        <div class="file-thumbnail">
          <div class="spinner spinner-default spinner-sm"></div>
        </div>
        <div class="file-info">
          <span class="file-ext">{%= file.ext %}</span>
          <span class="file-name">{%= file.name %}</span>
        </div>
      </li>
      {% } %}
    </script>
    <script id="template-download" type="text/x-tmpl">
      {% for (var i=0, file; file=o.files[i]; i++) { %}
      <li class="file template-download fade">
        <a class="file-link" href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}">
          {% if (file.thumbnailUrl) { %}
          <div class="file-thumbnail" style="background-image: url({%=file.thumbnailUrl%});"></div>
          {% } else { %}
          <div class="file-thumbnail {%=file.thumbnail%}"></div>
          {% } %}
          <div class="file-info">
          <span class="file-ext">{%=file.extension%}</span>
          <span class="file-name">{%=file.filename%}.</span>
          </div>
          </a>
        <button class="file-delete-btn delete" title="Delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}" type="button">
          <span class="icon icon-remove"></span>
          </button>
      </li>
      {% } %}
    </script>

    <script src="/template_vendor/js/vendor.min.js"></script>
    <script src="/template_vendor/js/elephant.min.js"></script>
    <script src="/template_vendor/js/application.min.js"></script>
    <script src="/template_vendor/js/demo.min.js"></script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-83990101-1', 'auto');
        ga('send', 'pageview');
    </script>

    </body>


<?php
}
include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
?>

