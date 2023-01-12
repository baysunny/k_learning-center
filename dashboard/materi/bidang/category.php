<?php
session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";

if(isset($_GET["category"])){
    $page = new CoursePage("category", $_GET["category"]);
    $_SESSION["type"] < 3 ? $page->page_category_admin_l2($_GET["category"]) : $page->page_category_global_l2($_GET["category"]);
}else if(isset($_GET["fcategory"])){
    $page = new CoursePage("category", $_GET["fcategory"]);
    $_SESSION["type"] < 3 ? $page->page_fcategory_admin($_GET["fcategory"]) : $page->page_fcategory_global($_GET["fcategory"]);
}





