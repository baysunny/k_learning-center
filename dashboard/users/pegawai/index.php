<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";


$page = new EditUserPage("Pegawai", "user");


$_SESSION["type"] < 2 ? $page->admin_page(4) : $page->global_page();


?>
