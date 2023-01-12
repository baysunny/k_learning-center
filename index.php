<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";

$page = new AuthenticationPage("Dashboard", "");




$page->login_page("front");
