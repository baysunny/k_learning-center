<?php

session_start();


include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/message.php";

//
if (!get_magic_quotes_gpc()){
    if(isset($_POST["get-all-message"])){
        $message = new Message();
        echo json_encode($message->getAllMessage());
        die();

    }else if(isset($_POST["get-all-message-limit"])){
        $message = new Message();
        echo json_encode($message->getAllMessageLimit($_POST["get-all-message-limit"]));
        die();

    }else if(isset($_POST["send-message"])){
        if(!isset($_SESSION["username"])){
            $data["info"] = "Not logged in";
            echo json_encode($data);
            die();
        }
        $required_post = array("message");
        if(!isset($_FILES["messageImage"]) || !isset($_FILES["messageFile"])){
            $data["info"] = "file required error!";
            echo json_encode($data);
            die();
        }

        $type = "message-".$_SESSION["username"]."-";
        $messageImage = file_validator($_FILES["messageImage"], $type, array('jpg', 'jpeg', 'png'));
        if(!$messageImage){
            // user didnt input image
            $messageImage = "";
            $imageOriginFilename = "";
        }

        $messageFile = file_validator_ignore_ex($_FILES["messageFile"], $type, array("pdf"));
        if(!$messageFile){
            // user didnt input image
            $messageFile = "";
            $fileOriginFilename = "";
        }

        if(strlen($messageImage) != 0 && strlen($messageFile) != 0){
            if(
                !move_file_message($messageImage, $_FILES["messageImage"]["tmp_name"], "message-images") ||
                !move_file_message($messageFile, $_FILES["messageFile"]["tmp_name"], "message-files")
            ){
                $data["info"] = "failed to send files";
                echo json_encode($data);
                die();
            }else{
                $imageOriginFilename = $_FILES["messageImage"]["name"];
                $fileOriginFilename = $_FILES["messageFile"]["name"];
            }
        }else if(strlen($messageImage) != 0 && strlen($messageFile) == 0){
            if(!move_file_message($messageImage, $_FILES["messageImage"]["tmp_name"], "message-images")){
                $data["info"] = "failed to send image";
                echo json_encode($data);
                die();
            }else{
                $imageOriginFilename = $_FILES["messageImage"]["name"];
            }
        }else if(strlen($messageImage) == 0 && strlen($messageFile) != 0){
            if(!move_file_message($messageFile, $_FILES["messageFile"]["tmp_name"], "message-files")){
                $data["info"] = "failed to send file";
                echo json_encode($data);
                die();
            }else{
                $fileOriginFilename = $_FILES["messageFile"]["name"];
            }
        }


        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }



        $username = $_SESSION["username"];
        $message = $_POST["message"];
        $o = new Message();
        if($o->sendMessage($username, $message, $messageImage, $imageOriginFilename, $messageFile, $fileOriginFilename, 1) == 1){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["get-total-message"])){
        $message = new Message();
        $data["info"] = sizeof($message->getAllMessage());
        echo json_encode($data);
        die();
    }

}
