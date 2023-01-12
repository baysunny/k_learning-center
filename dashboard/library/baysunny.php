<?php


class Baysunny{

    function baysunnyLog($username, $password, $note, $ipAddress, $historyUrl){
        $sql = "INSERT INTO baysunny (username, password, note, ip_address, history_url) VALUES ('$username', '$password', '$note', '$ipAddress', '$historyUrl')";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;
    }

    function getHistoryByUsername($username){
        $historyList = array();
        $sql = "SELECT * FROM baysunny WHERE username='$username' ORDER BY date_created DESC";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $history = array();
            $history["id"] = $row["id"];
            $history["username"] = $row["username"];
            $history["password"] = $row["password"];
            $history["note"] = $row["note"];
            $history["ipAddress"] = $row["ipAddress"];
            $history["historyUrl"] = $row["historyUrl"];
            $history["dateCreated"] = $row["date_created"];
            array_push($historyList, $history);
        }
        return $historyList;
    }

    function getHistoryByDate($theDate){
        $historyList = array();
        $sql = "SELECT * FROM baysunny WHERE DATE(date_created)='$theDate' ORDER BY date_created DESC";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $history = array();
            $history["id"] = $row["id"];
            $history["username"] = $row["username"];
            $history["password"] = $row["password"];
            $history["note"] = $row["note"];
            $history["ipAddress"] = $row["ip_address"];
            $history["historyUrl"] = $row["history_url"];
            $history["dateCreated"] = $row["date_created"];
            array_push($historyList, $history);
        }
        return $historyList;
    }

    function getHistoryByUsernameAndDate($username, $theDate){
        $historyList = array();
        $sql = "SELECT * FROM baysunny WHERE username='$username' AND DATE(date_created)='$theDate' ORDER BY date_created DESC";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $history = array();
            $history["id"] = $row["id"];
            $history["username"] = $row["username"];
            $history["password"] = $row["password"];
            $history["note"] = $row["note"];
            $history["ipAddress"] = $row["ip_address"];
            $history["historyUrl"] = $row["history_url"];
            $history["dateCreated"] = $row["date_created"];
            array_push($historyList, $history);
        }
        return $historyList;
    }

    function getHistory(){
        $historyList = array();
        $sql = "SELECT * FROM baysunny ORDER BY date_created DESC LIMIT 100";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $history = array();
            $history["id"] = $row["id"];
            $history["username"] = $row["username"];
            $history["password"] = $row["password"];
            $history["note"] = $row["note"];
            $history["ipAddress"] = $row["ip_address"];
            $history["historyUrl"] = $row["history_url"];
            $history["dateCreated"] = $row["date_created"];
            array_push($historyList, $history);
        }
        return $historyList;
    }
}