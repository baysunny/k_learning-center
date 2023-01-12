<?php
session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";

if(!isset($_GET["mcourseID"])){
    header("Location: /dashboard");
}


$page = new CoursePage("Kategori", 0, 0);



$_SESSION["type"] < 3 ? $page->page_course_admin_l2($_GET["mcourseID"]) : $page->page_course_global_l2($_GET["mcourseID"]);


