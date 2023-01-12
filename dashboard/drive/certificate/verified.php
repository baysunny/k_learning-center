<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";
include $_SERVER['DOCUMENT_ROOT']."/template/header.php";


if(isset($_GET["certificate-id"])){
    $certificateID = $_GET["certificate-id"];
    $x = new GenerateCertificate("None", $certificateID);
    if(substr($certificateID, strlen($certificateID) - 1, 1) == "/"){
        $certificateID = substr($certificateID, 0, strlen($certificateID) - 1);
    }
    if($_SESSION["type"] == 3){
        $x->verification_page();

    }else{
        echo "Not available";
    }

}else{
    echo "Not available";
}



