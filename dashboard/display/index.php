<?php


session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";

$page = new DisplayPage("Display", "");

if(!isset($_GET["eventID"])){
    header("Location: /");
    die();
}

if($_SESSION["code"] != "admin is cool you know"){
    // $page->page_main_setting_global();
    die();
}else{
    $page->page_main_display_admin($_GET["eventID"]);
    die();
}

