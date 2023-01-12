<?php
session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";


$page = new CoursePage("Quest", "");
if (!isset($_GET{"subjectID"})){
    header("Location: /dashboard");
    die();
}

$_SESSION["type"] < 3 ? $page->page_category_admin($_GET["category"]) : $page->page_quest_global($_GET["subjectID"]);


