<?php


session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";

$page = new EventCertificatePage($_GET["eventID"]);

if(isset($_GET["eventID"])){
    
    if(isset($_GET["registrationCode"])){
        $page->previewOfUserUAdmin($_GET["registrationCode"]);
    }else if(isset($_GET["preview"])){
        $page->preview();
    }else{
        echo "Not available";
    }
}else{
    echo "Not available";
}

// $page = new DisplayPage("Event", "");

// if(isset($_GET["username"], $_GET["eventID"], $_GET["certificateID"])){
//     $page->page_event_certificate($_GET["username"], $_GET["eventID"], $_GET["certificateID"]);
//     die();
// }else if(isset($_GET["certificate-code"])){
//     $page->page_event_verify_certificate($_GET["certificate-code"]);
// }else{
//     header("Location: /dashboard/");
// }



