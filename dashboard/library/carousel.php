<?php


class Carousel{

    function getCarousel($carouselID){
        $sql = "SELECT * FROM carousel_banner WHERE carousel_id='$carouselID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $carousel = array();
        while($row = $result->fetch_assoc()){
            $carousel["carouselID"] = $row["carousel_id"];
            $carousel["imageName"] = $row["image_name"];
            $carousel["imageFileName"] = $row["image_file_name"];
            $carousel["videoName"] = $row["video_name"];
            $carousel["videoFileName"] = $row["video_file_name"];
            $carousel["textText"] = $row["text_text"];
            $carousel["createdBy"] = $row["created_by"];
            $carousel["dateCreated"] = $row["date_created"];
        }

        return $carousel;
    }
    function getCarouselList(){
        $carouselList = array();
        $sql = "SELECT * FROM carousel_banner";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $carousel = array();
            $carousel["carouselID"] = $row["carousel_id"];
            $carousel["imageName"] = $row["image_name"];
            $carousel["imageFileName"] = $row["image_file_name"];
            $carousel["videoName"] = $row["video_name"];
            $carousel["videoFileName"] = $row["video_file_name"];
            $carousel["textText"] = $row["text_text"];
            $carousel["createdBy"] = $row["created_by"];
            $carousel["dateCreated"] = $row["date_created"];

            array_push($carouselList, $carousel);
        }

        return $carouselList;
    }

    function addCarousel($imageName, $imageFileName, $videoName, $videoFileName, $textText, $createdBy){
        $sql = "INSERT INTO carousel_banner (image_name, image_file_name, video_name, video_file_name, text_text, created_by) VALUES ('$imageName', '$imageFileName', '$videoName', '$videoFileName', '$textText', '$createdBy')";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }

    function deleteCarousel($carouselID){
        $sql = " DELETE FROM carousel_banner WHERE carousel_id='$carouselID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }
}
