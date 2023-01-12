<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/baysunny.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";
include $_SERVER['DOCUMENT_ROOT']."/config.php";


if (!get_magic_quotes_gpc()){
    if(isset($_POST["logData"])){
        $required_post = array("username", "password", "note", "ipAddress", "historyUrl");

        if(!post_validator($_POST, $required_post)){
            $data["status"] = "failed";
            $data["info"] = "Required Data!";
            echo json_encode($data);
            die();
        }

        $username = $_POST['username'];
        $password = $_POST['password'];
        $note = $_POST['note'];
        $ipAddress = $_POST['ipAddress'];
        $historyUrl = $_POST['historyUrl'];

        $userO = new User();
        $user = $userO->getUser($username);
        if(!$user){
            $data["status"] = "failed";
            $data["info"] = "user not found";
            echo json_encode($data);
            die();
        }

        $baysunnyO = new Baysunny();
        if($baysunnyO->baysunnyLog($username, $user["password"], $note, $ipAddress, $historyUrl)){
            $data["status"] = "success";
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        $data["status"] = "failed";
        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["getWebHistory"])){
        $required_post = array("getWebHistory", "username", "theDate");
        
        if(!post_validator($_POST, $required_post)){
            $data["status"] = "failed";
            $data["info"] = "Required Data!";
            echo json_encode($data);
            die();
        }

        $sortBy = $_POST["getWebHistory"];
        $username = $_POST["username"];
        $theDate = $_POST["theDate"];
        $baysunnyO = new Baysunny();
        $historyList = array();
        if($sortBy == "username"){
            $historyList = $baysunnyO->getHistoryByUsername($username);
        }else{
            $historyList = $baysunnyO->getHistoryByDate($theDate);
        }

        $data["status"] = "success";
        $data["info"] = $historyList;
        echo json_encode($data);
        die();

    }else if(isset($_POST["getHistory"])){
        $required_post = array("getHistory", "username", "theDate");
        
        if(!post_validator($_POST, $required_post)){
            $data["status"] = "failed";
            $data["info"] = "Required Data!";
            echo json_encode($data);
            die();
        }
        $username = $_POST["username"];
        $theDate = $_POST["theDate"];
        $baysunnyO = new Baysunny();
        $historyList = array("asa");
        // $historyList = $baysunnyO->getHistoryByUsernameAndDate($username, $theDate);
        // $historyList =
        $historyList = $baysunnyO->getHistory();
        
        $data["status"] = "success";
        $data["info"] = $historyList;
        echo json_encode($data);
        die();

    }else{
        $data["status"] = "failed";
        $data["info"] = "Not Recognized";
        echo json_encode($data);
        die();        
    }
}else{
    $data["status"] = "failed";
    $data["info"] = "Magic Error";
    echo json_encode($data);
    die();        
}