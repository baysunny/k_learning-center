<?php
session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";

if(!isset($_GET["subject"])){
    header("Location: /dashboard");
}

$page = new CoursePage("Materi", $_GET["subject"]);


$_SESSION["type"] < 4 ? $page->page_read_admin($_GET["subject"]) : $page->page_read_global($_GET["subject"]);

