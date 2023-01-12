<?php

session_start();

include  $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/carousel.php";




if (!get_magic_quotes_gpc()){
    if(isset($_POST["getCarouselList"])){
        $ca = new Carousel();
        $carouselList = $ca->getCarouselList();
        $gallery = array(
            "carouselImage"=>array(),
            "carouselVideo"=>array()
        );
        foreach($carouselList as $carousel){
            if(strlen($carousel["imageName"]) > 1){
                array_push($gallery["carouselImage"], $carousel);
            }else{
                array_push($gallery["carouselVideo"], $carousel);
            }
        }

        echo json_encode($gallery);
        die();

    }else if(isset($_POST["addCarousel"])){
        $required_post = array("addedBy", "code-add");

        if(!isset($_FILES["carouselImage"])){
            $data["info"] = "file required error!";
            echo json_encode($data);
            die();
        }

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $addedBy = $_POST["addedBy"];
        $adminCode = $_POST["code-add"];

        $u = new User();
        $ca = new Carousel();

        $user = $u->getUser($addedBy);
        if(!$user){
            $data["info"] = "Not Available";
            echo json_encode($data);
            die();
        }

        if($user["type"] != 1 && $user["type"] != 2 || $user["status"] != 1 || $adminCode != "admin is cool you know"){
            $data["info"] = "User not allowed";
            echo json_encode($data);
            die();
        }


        $formatFileName = date("y.m.d.h.i.s");
        $carouselImage = file_validator($_FILES["carouselImage"], $formatFileName, array('jpg', 'jpeg', 'png', 'gif'));
        if(!$carouselImage){
            $data["info"] = "Error image";
            echo json_encode($data);
            die();
        }
         
        if(!move_file_carousel($carouselImage, $_FILES["carouselImage"]["tmp_name"], "image")){
            $data["info"] = "failed to move file";
            echo json_encode($data);
            die();
        }

        if($ca->addCarousel($carouselImage, $_FILES["carouselImage"]["name"], "", "", "", $addedBy)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["addVideoCarousel"])){
        $required_post = array("addedBy", "code-add");

        if(!isset($_FILES["carouselVideo"])){
            $data["info"] = "file required error!";
            echo json_encode($data);
            die();
        }

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $addedBy = $_POST["addedBy"];
        $adminCode = $_POST["code-add"];

        $u = new User();
        $ca = new Carousel();

        $user = $u->getUser($addedBy);
        if(!$user){
            $data["info"] = "Not Available";
            echo json_encode($data);
            die();
        }

        if($user["type"] != 1 && $user["type"] != 2 || $user["status"] != 1 || $adminCode != "admin is cool you know"){
            $data["info"] = "User not allowed";
            echo json_encode($data);
            die();
        }


        $formatFileName = date("y.m.d.h.i.s");
        $carouselVideo = file_validator($_FILES["carouselVideo"], $formatFileName, array('3gp', 'wmv', 'mp4'));
        if(!$carouselVideo){
            $data["info"] = "Error Video";
            echo json_encode($data);
            die();
        }

        if(!move_file_carousel($carouselVideo, $_FILES["carouselVideo"]["tmp_name"], "video")){
            $data["info"] = "failed to move the file";
            echo json_encode($data);
            die();
        }

        if($ca->addCarousel("", "", $carouselVideo, $_FILES["carouselVideo"]["name"], "", $addedBy)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["deleteCarousel"])){
        $required_post = array("deletedBy", "code-delete", "carouselID");


        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $deletedBy = $_POST["deletedBy"];
        $adminCode = $_POST["code-delete"];
        $carouselID = $_POST["carouselID"];

        $u = new User();
        $ca = new Carousel();

        $user = $u->getUser($deletedBy);
        if(!$user){
            $data["info"] = "Not Available";
            echo json_encode($data);
            die();
        }

        if($user["type"] != 1 && $user["type"] != 2 || $user["status"] != 1 || $adminCode != "admin is cool you know"){
            $data["info"] = "User not allowed";
            echo json_encode($data);
            die();
        }

        $carousel = $ca->getCarousel($carouselID);
        if(!$carousel){
            $data["info"] = "carousel not found!";
            echo json_encode($data);
            die();
        }

        if($ca->deleteCarousel($carouselID)){
            $data["info"] = "success";
            delete_file_carousel($carousel["imageName"], "image");
            delete_file_carousel($carousel["videoName"], "video");
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else{
        $data["info"] = "<h1>failure</h1>";
        echo json_encode($data);
        die();
    }

}
