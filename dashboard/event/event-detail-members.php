<?php


session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";

$page = new EventPage("Event", "");

if(isset($_GET["event"])){
    if($_SESSION["code"] == "admin is cool you know"){
        $page->page_event_detail_members_admin($_GET["event"]);
        die();
    }else{
        $page->page_event_detail_members_global();
        die();
    }
}



