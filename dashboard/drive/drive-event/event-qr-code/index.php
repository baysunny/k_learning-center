<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";


if(isset($_GET["qr"], $_GET["eventID"])){
    $page = new EventCertificatePage($_GET["eventID"]);
    $page->previewQRRegistrationCode($_GET['qr']);
}else{
    echo "Not available";
}


