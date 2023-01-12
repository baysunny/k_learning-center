<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";

$page = new Unknown("Unknown", "");

$_SESSION["type"] < 3 ? $page->index() : $page->global_page();
