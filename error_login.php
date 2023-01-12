<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";

$page = new AuthenticationPage("Dashboard", "");




if(isset($_GET["sign-up"]) || isset($_GET["register"]) || isset($_GET["forgot-password"])){
    $page->register_page($_GET);
}else{
    $page->login_page($_GET);
}
