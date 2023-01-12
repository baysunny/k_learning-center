<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";


if(isset($_GET["username"])){
    $page = new CourseCertificate($_GET["username"], "", "");

    $page->preview();
}else{
    echo "Not available";
}



