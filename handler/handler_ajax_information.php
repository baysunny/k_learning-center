<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/information.php";
include $_SERVER['DOCUMENT_ROOT']."/config.php";

if (!get_magic_quotes_gpc()){
	if(isset($_POST["addInformation"])){
		$required_post = array("addedBy", "code-add", "informationTitle", "informationDescription", "informationUrl", "thumbnailText");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $addedBy = $_POST["addedBy"];
        $adminCode = $_POST["code-add"];
        $informationTitle = $_POST["informationTitle"];
        $informationDescription = $_POST["informationDescription"];
        $informationUrl = $_POST["informationUrl"];
        $thumbnailImage = "";
        $informationStatus = 1;
        $thumbnailText = $_POST["thumbnailText"];
       
        $u = new User();
        $information = new Information();
        $user = $u->getUser($addedBy);
        if(!$user){
            $data["info"] = "Not Available";
            echo json_encode($data);
            die();
        }

        if($user["type"] != 1 || $user["status"] != 1 || $adminCode != "admin is cool you know"){
            $data["info"] = "User not allowed";
            echo json_encode($data);
            die();
        }

        if($information->addInformationList($informationTitle, $informationDescription, $informationUrl, $informationStatus, $addedBy)){
            $data["info"] = "success";
            echo json_encode($data);
            die();

        }

     	$data["info"] = "failed";
        echo json_encode($data);
        die();


	}else if(isset($_POST["editInformation"])){
        $required_post = array("editedBy", "code-edit", "informationID", "informationTitle", "informationDescription", "informationUrl");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $editedBy = $_POST["editedBy"];
        $adminCode = $_POST["code-edit"];
        $informationID = $_POST["informationID"];
        $informationTitle = $_POST["informationTitle"];
        $informationDescription = $_POST["informationDescription"];
        $informationUrl = $_POST["informationUrl"];
        $informationStatus = 1;
       
        $u = new User();
        $information = new Information();
        $user = $u->getUser($editedBy);
        if(!$user){
            $data["info"] = "Not Available";
            echo json_encode($data);
            die();
        }

        if($user["type"] != 1 || $user["status"] != 1 || $adminCode != "admin is cool you know"){
            $data["info"] = "User not allowed";
            echo json_encode($data);
            die();
        }

        if($information->editInformation($informationID, $informationTitle, $informationDescription, $informationUrl, $informationStatus)){
            $data["info"] = "success";
            echo json_encode($data);
            die();

        }



        $data["info"] = "failed";
        echo json_encode($data);
        die();


    }else if(isset($_POST["deleteinformation"])){
        $required_post = array("deletedBy", "code-delete", "deleteinformation");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $deletedBy = $_POST["deletedBy"];
        $adminCode = $_POST["code-delete"];
        $informationID = $_POST["deleteinformation"];

        $u = new User();
        $information = new Information();
        $user = $u->getUser($deletedBy);
        if(!$user){
            $data["info"] = "Not Available";
            echo json_encode($data);
            die();
        }

        if($user["type"] != 1 || $user["status"] != 1 || $adminCode != "admin is cool you know"){
            $data["info"] = "User not allowed";
            echo json_encode($data);
            die();
        }

        if($information->deleteInformationList($informationID)){
            $data["info"] = "success";
            echo json_encode($data);
            die();

        }



        $data["info"] = "failed";
        echo json_encode($data);
        die();


    }else if(isset($_POST["getInformationList"])){

		$information = new Information();
		$informationList = $information->getInformationList();
		echo json_encode($informationList);
		die();
	}else{
		echo json_encode("ERROR");
		die();
	}
}


