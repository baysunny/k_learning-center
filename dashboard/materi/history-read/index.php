<?php
session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";


$page = new CoursePage("log-read", 0, 0);

$_SESSION["type"] < 3 ? $page->page_category_admin_l1() : $page->page_detail_log_read_global();

