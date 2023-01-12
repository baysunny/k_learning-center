<?php
session_start();

include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/template/templates.php";


$page = new CoursePage("shelter", 0, 0);

if(isset($_GET["mshelterID"])){
    $_SESSION["type"] < 3 ? $page->page_shelter_admin_l2($_GET["mshelterID"]) : $page->page_shelter_global_l2($_GET["mshelterID"]);
}else{
    $_SESSION["type"] < 3 ? $page->page_shelter_admin_l1() : $page->page_shelter_global_l1();
}
