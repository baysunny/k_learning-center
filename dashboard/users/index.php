<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";


if(!isset($_GET["username"])){
    header("Location: /dashboard");
}

$page = new ProfilePage("User", "profile");
$username = $_GET["username"];


$_SESSION["type"] < 2 ? $page->admin_page($username) : $page->global_page();


?>
