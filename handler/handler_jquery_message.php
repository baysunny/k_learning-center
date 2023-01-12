<?php

@session_start();

include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/message.php";


if (!get_magic_quotes_gpc()){
    if(isset($_POST["loadmessage"])){
        $required_post = array("loadmessage", "n");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = $_POST["n"];
            echo json_encode($data);
            die();
        }
        $message = new Message();
        if($_SESSION["code"] == "admin is cool you know"){
            Layout_List_Message_Admin($message->getAllMessageLimit($_POST["n"]));
        }else if($_SESSION["code"] == "i am not an admin :)"){
            Layout_List_Message_Global($message->getAllMessageLimit($_POST["n"]));
        }

        die();


    }
}
