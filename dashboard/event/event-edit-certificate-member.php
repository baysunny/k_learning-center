<?php


session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";

$page = new EventPage("Event", "");

if(isset($_GET["eventID"])){
    if($_SESSION["code"] == "admin is cool you know"){
        $page->page_event_edit_certificate_member($_GET["eventID"]);
        die();
    }else{
        echo 'not available';
        // $page->page_event_detail_members_global();
        die();
    }
}



