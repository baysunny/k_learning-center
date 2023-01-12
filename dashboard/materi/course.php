<?php
session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";

if(!isset($_GET["courseID"])){
    header("Location: /dashboard");
}

$page = new CoursePage("Materi", $_GET["courseID"]);


$_SESSION["type"] < 3 ? $page->page_course_admin_l3($_GET["courseID"]) : $page->page_course_global_l3($_GET["courseID"]);

