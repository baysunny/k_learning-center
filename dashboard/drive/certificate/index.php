<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";


if(isset($_GET["username"], $_GET["certificate-id"], $_GET['type'])){
    $page = new CourseCertificate($_GET["username"], $_GET["certificate-id"], $_GET["type"]);

    $page->index();
}else{
    echo "Not available";
}



