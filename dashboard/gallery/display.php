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

$page = "Album";
if(!isset($_GET['album'])){
    header("Location:/dashboard/gallery");
}
$album = $_GET['album'];

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
            <?php nav_bar($page, $album); ?>
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
                        <div class="">


                            <div class="card">

                                <a href="/dashboard/gallery" class="btn btn-link">
                                    <h5 class="text-success"><span class="icon icon-arrow-circle-left"></span> Gallery</h5>
                                </a>

                                <?php
                                $gallery = new Gallery();

                                $data = $gallery->getListGalleryName();
                                foreach ($data as $g) {
                                    if($g["galleryID"] === $album){
                                        echo '
                                        <a class="btn btn-link">
                                            <h4 class="text-info">'.$g["galleryName"].' <span class="badge badge-outline-info">'.$gallery->imageGCounter($g["galleryID"]).'</span></h4>
                                        </a>';
                                    }else{
                                        echo '
                                        <a href="/dashboard/gallery/display.php?album='.$g["galleryID"].'" class="btn btn-link">
                                            <h5 class="text-success">'.$g["galleryName"].' <span class="badge badge-outline-success">'.$gallery->imageGCounter($g["galleryID"]).'</span></h5>
                                        </a>';
                                    }
                                }

                                ?>
                            </div>
                        </div>
                    </div>

                  </div>

                  <div class="row gutter-xs">
                      <div class="col-sm-12">
                          <div class="row gutter-xs">

                              <?php imageList($album); ?>
                          </div>
                      </div>
                  </div>

              </div>
          </div>


        </div>
    </body>

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

