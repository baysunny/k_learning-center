<?php


class Message{

    function getAllMessage(){
        $sql = "SELECT message_chat.message_id,
                       message_chat.username,
                       message_chat.message,
                       message_chat.message_image,
                       message_chat.image_origin_filename,
                       message_chat.message_file,
                       message_chat.file_origin_filename,
                       message_chat.date_created,
                       message_chat.status,
                       users.image

                        FROM message_chat, users WHERE message_chat.username=users.username";
        $listMessage = array();
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $message = array();
            $message["messageID"] = $row["message_id"];
            $message["username"] = $row["username"];
            $message["userImage"] = $row["image"];
            $message["message"] = $row["message"];
            $message["messageImage"] = $row["message_image"];
            $message["imageOriginFilename"] = $row["image_origin_filename"];
            $message["messageFile"] = $row["message_file"];
            $message["fileOriginFilename"] = $row["file_origin_filename"];
            $message["datetimeCreated"] = $row["date_created"];
            $message["timeCreated"] = time_formating2($row["date_created"]);
            $message["dateCreated"] = date_formating($row["date_created"]);
            $message["status"] = $row["status"];

            array_push($listMessage, $message);
        }

        return $listMessage;
    }

    function getAllMessageLimit($limit){
        $sql = "SELECT message_chat.message_id,
                       message_chat.username,
                       message_chat.message,
                       message_chat.message_image,
                       message_chat.image_origin_filename,
                       message_chat.message_file,
                       message_chat.file_origin_filename,
                       message_chat.date_created,
                       message_chat.status,
                       users.image, users.first_name, users.last_name
                       FROM message_chat, users WHERE message_chat.username=users.username
                       ORDER BY message_chat.date_created DESC LIMIT $limit
                       ";
        $listMessage = array();
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $message = array();
            $message["messageID"] = $row["message_id"];
            $message["username"] = $row["username"];
            $message["name"] = $row["first_name"]." ".$row["last_name"];
            $message["userImage"] = $row["image"];
            $message["message"] = $row["message"];
            $message["messageImage"] = $row["message_image"];
            $message["imageOriginFilename"] = $row["image_origin_filename"];
            $message["messageFile"] = $row["message_file"];
            $message["fileOriginFilename"] = $row["file_origin_filename"];
            $message["datetimeCreated"] = $row["date_created"];
            $message["timeCreated"] = time_formating2($row["date_created"]);
            $message["dateCreated"] = date_formating($row["date_created"]);
            $message["status"] = $row["status"];

            array_push($listMessage, $message);
        }

        return array_reverse($listMessage);
    }

    function sendMessage($username, $message, $messageImage, $imageOriginFilename, $messageFile, $fileOriginFilename, $status){
        $sql = "INSERT INTO message_chat (username, message, message_image,
                                          image_origin_filename, message_file, file_origin_filename, status)
        VALUES ('$username', '$message', '$messageImage', '$imageOriginFilename', '$messageFile', '$fileOriginFilename', '$status')";

        $result = Connection::connect()->query($sql);
        if($result == 1){
            return 1;
        }return 0;
    }

}
