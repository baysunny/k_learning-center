<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";

$page = new DashboardPage("Dashboard", "");

$_SESSION["type"] < 3 ? $page->admin_page() : $page->global_page();
