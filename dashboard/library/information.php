<?php


class Information{

    function addInformationList($informationTitle, $informationDescription, $informationUrl, $informationStatus, $createdBy){
        $sql = "INSERT INTO information (information_title, information_description, information_url, information_status, created_by) VALUES ('$informationTitle', '$informationDescription', '$informationUrl', '$informationStatus', '$createdBy')";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;
    }

    function editInformation($informationID, $informationTitle, $informationDescription, $informationUrl, $informationStatus){
        $sql = "UPDATE information SET information_title='$informationTitle', information_description='$informationDescription', information_url='$informationUrl', information_status='$informationStatus' WHERE information_id='$informationID'";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;
    }

    function deleteInformationList($informationID){
        $sql = "DELETE FROM information WHERE information_id='$informationID'";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;
    }

    function getInformationList(){
        $informationList = array();
        $sql = "SELECT * FROM information ORDER BY date_created DESC";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $information = array();
            $information["informationID"] = $row["information_id"];
            $information["informationTitle"] = $row["information_title"];
            $information["informationDescription"] = $row["information_description"];
            $information["informationUrl"] = $row["information_url"];
            $information["validDate"] = $row["valid_date"];
            $information["expirationDate"] = $row["expiration_date"];
            $information["informationStatus"] = $row["information_status"];
            $information["createdBy"] = $row["created_by"];
            $information["dateCreated"] = $row["date_created"];
            array_push($informationList, $information);
        }

        return $informationList;
    
    }
}