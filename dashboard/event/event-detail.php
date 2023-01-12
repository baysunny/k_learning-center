<?php


session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";

$page = new EventPage("Event", "");

if(isset($_GET["event"])){
    if($_SESSION["code"] == "admin is cool you know"){
        $page->page_event_detail_admin($_GET["event"]);
        die();
    }else{
        if(isset($_GET["registrationCode"])){
            $page->page_event_detail_global($_GET["event"], $_GET["registrationCode"]);
            die();
        }
        $page->global_page();die();
    }
}



