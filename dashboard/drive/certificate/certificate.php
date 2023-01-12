<?php

include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/certificate/fpdf.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/certificate.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";

if (!get_magic_quotes_gpc()){

    if($_POST["username"], $_POST["code"]){
        $username = $_POST["username"];
        $user = new User();
        if(!$user->isExists($username)){
            $data["info"] = "user not found!";
            die();
        }




    }
}
