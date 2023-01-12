<?php


class Event{

    function getEvent($eventID){
        $sql = "SELECT * FROM event WHERE event_id='$eventID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $event = array();
        if($row = $result->fetch_assoc()){
            $event["eventID"] = $row["event_id"];
            $event["eventName"] = $row["event_name"];
            $event["institution"] = $row["institution"];
            $event["thumbnailImage"] = $row["thumbnail_image"];
            $event["thumbnailText"] = $row["thumbnail_text"];
            $event["eventStart"] = $row["event_start"];
            $event["eventEnd"] = $row["event_end"];
            $event["eventStatus"] = $row["event_status"];
            $event["createdBy"] = $row["created_by"];
            $event["dateCreated"] = $row["date_created"];
        }

        return $event;
    }

    function getEventList(){
        $eventList = array();
        $sql = "SELECT * FROM event";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $event = array();
            $event["eventID"] = $row["event_id"];
            $event["eventName"] = $row["event_name"];
            $event["institution"] = $row["institution"];
            $event["thumbnailImage"] = $row["thumbnail_image"];
            $event["thumbnailText"] = $row["thumbnail_text"];
            $event["eventStart"] = $row["event_start"];
            $event["eventEnd"] = $row["event_end"];
            $event["eventStatus"] = $row["event_status"];
            $event["createdBy"] = $row["created_by"];
            $event["dateCreated"] = $row["date_created"];

            array_push($eventList, $event);
        }

        return $eventList;
    }

    function addEvent($eventName, $institution, $thumbnailImage, $thumbnailText, $eventStart, $eventEnd, $createdBy){
        $sql = "INSERT INTO event (event_name, institution, thumbnail_image, thumbnail_text, event_start, event_end, created_by) VALUES ('$eventName', '$institution', '$thumbnailImage', '$thumbnailText', '$eventStart', '$eventEnd', '$createdBy')";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;
    }

    function updateEvent($eventID, $eventName, $institution, $thumbnailImage, $thumbnailText, $eventStart, $eventEnd){
        $sql = "UPDATE event SET event_name='$eventName', institution='$institution',
                        thumbnail_image='$thumbnailImage', thumbnail_text='$thumbnailText',
                        event_start='$eventStart', event_end='$eventEnd' WHERE event_id='$eventID'";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;
    }

    function deleteEvent($eventID){
        $sql = "DELETE FROM event WHERE event_id='$eventID'";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;
    }

    function updateEventStatus($eventID, $eventStatus){
        $sql = "UPDATE event SET event_status='$eventStatus' WHERE event_id='$eventID'";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;
    }
}

class EventRegistration{

    function getUserInEvent($registrationCode){
        $sql = "SELECT * FROM event_registration WHERE registration_code='$registrationCode'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }

        $user = array();
        if($row = $result->fetch_assoc()){
            $user["eventRegistrationID"] = $row["event_registration_id"];
            $user["registrationCode"] = $row["registration_code"];
            $user["certificateCode"] = $row["certificate_code"];
            $user["nip"] = $row["nip"];
            $user["username"] = $row["username"];
            $user["eventID"] = $row["event_id"];
            $user["absentStatus"] = $row["absent_status"];
            $user["signInDate"] = $row["sign_in_date"];
            $user["dateCreated"] = $row["date_created"];
        }
        return $user;
    }

    function getUsersInEvent($eventID){
        $sql = "SELECT * FROM event_registration WHERE event_id='$eventID'";
        $result = Connection::connect()->query($sql);
        $userList = array();
        while($row = $result->fetch_assoc()){
            $user = array();
            $user["eventRegistrationID"] = $row["event_registration_id"];
            $user["registrationCode"] = $row["registration_code"];
            $user["certificateCode"] = $row["certificate_code"];
            $user["nip"] = $row["nip"];
            $user["username"] = $row["username"];
            $user["absentStatus"] = $row["absent_status"];
            $user["signInDate"] = $row["sign_in_date"];
            $user["dateCreated"] = $row["date_created"];
            array_push($userList, $user);
        }

        return $userList;
    }

    function getUserInEventByNIP($eventID, $nip){
        $sql = "SELECT * FROM event_registration WHERE nip='$nip' AND event_id='$eventID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }

        $user = array();
        if($row = $result->fetch_assoc()){
            $user["eventRegistrationID"] = $row["event_registration_id"];
            $user["registrationCode"] = $row["registration_code"];
            $user["certificateCode"] = $row["certificate_code"];
            $user["nip"] = $row["nip"];
            $user["username"] = $row["username"];
            $user["eventID"] = $row["event_id"];
            $user["absentStatus"] = $row["absent_status"];
            $user["signInDate"] = $row["sign_in_date"];
            $user["dateCreated"] = $row["date_created"];
        }
        return $user;
    }

    function getFollowedEvents($username){
        $sql = "SELECT * FROM event_registration WHERE username='$username'";
        $result = Connection::connect()->query($sql);
        $userList = array();
        while($row = $result->fetch_assoc()){
            $user = array();
            $user["eventRegistrationID"] = $row["event_registration_id"];
            $user["registrationCode"] = $row["registration_code"];
            $user["certificateCode"] = $row["certificate_code"];
            $user["nip"] = $row["nip"];
            $user["username"] = $row["username"];
            $user["eventID"] = $row["event_id"];
            $user["absentStatus"] = $row["absent_status"];
            $user["signInDate"] = $row["sign_in_date"];
            $user["dateCreated"] = $row["date_created"];
            array_push($userList, $user);
        }

        return $userList;
    }

    function getFollowedEventsByNIP($nip){
        $sql = "SELECT * FROM event_registration WHERE nip='$nip'";
        $result = Connection::connect()->query($sql);
        $userList = array();
        while($row = $result->fetch_assoc()){
            $user = array();
            $user["eventRegistrationID"] = $row["event_registration_id"];
            $user["registrationCode"] = $row["registration_code"];
            $user["certificateCode"] = $row["certificate_code"];
            $user["nip"] = $row["nip"];
            $user["username"] = $row["username"];
            $user["eventID"] = $row["event_id"];
            $user["absentStatus"] = $row["absent_status"];
            $user["signInDate"] = $row["sign_in_date"];
            $user["dateCreated"] = $row["date_created"];
            array_push($userList, $user);
        }

        return $userList;
    }

    function updateAbsent($registrationCode, $absentStatus, $signInDate){
        $sql = "UPDATE event_registration SET sign_in_date='$signInDate', absent_status='$absentStatus' WHERE registration_code='$registrationCode'";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }return false;
    }

    function registerUserToEvent($registrationCode, $certificateCode, $username, $nip, $eventID){
        $sql = "INSERT INTO event_registration (registration_code, certificate_code, username, nip, event_id) VALUES('$registrationCode', '$certificateCode', '$username', '$nip', '$eventID')";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;
    }

    function deleteUserFromEvent($registrationCode, $eventID){
        $sql = "DELETE FROM event_registration WHERE registration_code='$registrationCode' AND event_id='$eventID'";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;
    }

    function generateCodeRegistration(){
        $characters = "0123456789";
        $charactersLength = strlen($characters);
        
        $code = '';
        for ($i = 0; $i < 20; $i++) {
            $code .= $characters[rand(0, $charactersLength - 1)];
            if($i % 3 == 0){
                $code .= "-";
            }
        }
    
        while (true){
            $sql = "SELECT * FROM event_registration WHERE registration_code='$code'";
            $result = Connection::connect()->query($sql);
            if($result->num_rows != 1){
                break;
            }
            for ($i = 0; $i < 20; $i++) {
                $code .= $characters[rand(0, $charactersLength - 1)];
                if($i % 3 == 0){
                    $code .= "-";
                }
            }
        }
        return $code;
    }

    function generateCodeCertificate($eventID){
        $characters = "0123456789";
        $charactersLength = strlen($characters);
        
        $code = '';
        for ($i = 0; $i < 19; $i++) {
            $code .= $characters[rand(0, $charactersLength - 1)];
            if($i % 3 == 0){
                $code .= "-";
            }
        }$code = $code.$eventID;
    
        while (true){
            $sql = "SELECT * FROM event_registration WHERE certificate_code='$code'";
            $result = Connection::connect()->query($sql);
            if($result->num_rows != 1){
                break;
            }
            for ($i = 0; $i < 19; $i++) {
                $code .= $characters[rand(0, $charactersLength - 1)];
                if($i % 3 == 0){
                    $code .= "-";
                }
            }$code = $code.$eventID;
        }
        return $code;
    }
}

class EventFileGlobal{
    
    function getFileGlobal($fileGlobalID){
        $sql = "SELECT * FROM event_file_global WHERE file_global_id='$fileGlobalID'";
        $result = Connection::connect()->query($sql);
        $file = array();
        if($row = $result->fetch_assoc()){
            $file["fileGlobalID"] = $row["file_global_id"];
            $file["registrationCode"] = $row["registration_code"];
            $file["title"] = $row["title"];
            $file["filename"] = $row["filename"];
            $file["fileFileName"] = $row["file_filename"];
            $file["dateCreated"] = $row["date_created"];
        }
        return $file;
    }

    function getFileGlobalList($registrationCode){
        $sql = "SELECT * FROM event_file_global WHERE registration_code='$registrationCode'";
        $result = Connection::connect()->query($sql);
        $fileGlobalList = array();
        while($row = $result->fetch_assoc()){
            $file = array();
            $file["fileGlobalID"] = $row["file_global_id"];
            $file["registrationCode"] = $row["registration_code"];
            $file["title"] = $row["title"];
            $file["filename"] = $row["filename"];
            $file["fileFileName"] = $row["file_filename"];
            $file["dateCreated"] = $row["date_created"];
            array_push($fileGlobalList, $file);
        }
        return $fileGlobalList;
    }
    
    function uploadFileGlobal($registrationCode, $title, $filename, $fileFileName){
        $sql = "INSERT INTO event_file_global (registration_code, title, filename, file_filename) VALUES('$registrationCode', '$title', '$filename', '$fileFileName')";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;  
    }

    function deleteFileGlobal($fileGlobalID, $registrationCode){
       $sql = "DELETE FROM event_file_global WHERE file_global_id='$fileGlobalID' AND registration_code='$registrationCode'";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;   
    }
}

class EventCertificateTemplate{

    function getTemplate($eventID){
        $sql = "SELECT * FROM event_certificate_template WHERE event_id='$eventID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $template = array();
        if($row = $result->fetch_assoc()){
            $template["backgroundImage"] = $row["background_image"];
            $template["certificateTemplateID"] = $row["certificate_template_id"];
            $template["x1"] = $row["x1"];
            $template["x2"] = $row["x2"];
            $template["x3"] = $row["x3"];
            $template["x4"] = $row["x4"];
            $template["x5"] = $row["x5"];
            $template["x6"] = $row["x6"];
            $template["x7"] = $row["x7"];
            $template["x8"] = $row["x8"];
            $template["x9"] = $row["x9"];
        }
        return $template;
    }

    function updateTemplate($eventID, $backgroundImage, $x1, $x2, $x3, $x4, $x5, $x6, $x7, $x8, $x9){
        $sql = "UPDATE event_certificate_template SET
                x1='$x1', x2='$x2', x3='$x3', x4='$x4', x5='$x5', x6='$x6', x7='$x7', x8='$x8', x9='$x9', background_image='$backgroundImage' 
                WHERE event_id='$eventID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }

    function generateTemplate($eventID, $backgroundImage, $x1, $x2, $x3, $x4, $x5, $x6, $x7, $x8, $x9){
        $sql = "INSERT INTO  event_certificate_template (event_id, background_image, x1, x2, x3, x4, x5, x6, x7, x8, x9) VALUES
                ('$eventID', '$backgroundImage', '$x1', '$x2', '$x3', '$x4', '$x5', '$x6', '$x7', '$x8', '$x9')";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }
}

class EventCertificate{

    function sendCertificate($email, $eventID, $code){
        include $_SERVER['DOCUMENT_ROOT']."/config.php";

        $url = $CONFIG["HOST"].sprintf("dashboard/event/event-member-certificate.php?eventID=%s&registrationCode=%s",
                        $eventID, $code);

        $message = sprintf("
            Link sertifikat :

            %s", $url);
        $subject = "Sertifikat Diterima";

        $mail = new PHPMailer;
        $mail->setFrom('learningcenter.kemenkumham@gmail.com');
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->IsSMTP();
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'ssl://smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->Username = "learningcenter.kemenkumham@gmail.com";
        $mail->Password = 'baysunny17';
        $mail->send();

        // mail($email, $subject, $message, "From: learningcenter.kemenkumham@gmail.com");
    }

    function getCertificate($certificateCode){
        $sql = "SELECT * FROM event_registration WHERE certificate_code='$certificateCode'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }

        $user = array();
        if($row = $result->fetch_assoc()){
            $user["eventRegistrationID"] = $row["event_registration_id"];
            $user["registrationCode"] = $row["registration_code"];
            $user["certificateCode"] = $row["certificate_code"];
            $user["nip"] = $row["nip"];
            $user["username"] = $row["username"];
            $user["eventID"] = $row["event_id"];
            $user["absentStatus"] = $row["absent_status"];
            $user["signInDate"] = $row["sign_in_date"];
            $user["dateCreated"] = $row["date_created"];
        }
        return $user;
    }
}

class EventCertificate__{

    function getCertificate($username, $eventID){
        $sql = "SELECT * FROM event_certificate WHERE username='$username' AND event_id='$eventID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }

        $certificate = array();
        if($row = $result->fetch_assoc()){
            $certificate["certificateID"] = $row["certificate_id"];
            $certificate["certificateCode"] = $row["certificate_code"];
            $certificate["username"] = $row["username"];
            $certificate["eventID"] = $row["event_id"];
            $certificate["dateCreated"] = $row["date_created"];
        }
        return $certificate;
    }

    function getCertificate____x($username, $eventID, $certificateCode){
        $sql = "SELECT * FROM event_certificate WHERE username='$username' AND event_id='$eventID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            $sql = "SELECT * FROM event_certificate WHERE certificate_code='$certificateCode'";
            $result = Connection::connect()->query($sql);
            if($result->num_rows != 1){
                return false;
            }
        }

        $certificate = array();
        if($row = $result->fetch_assoc()){
            $certificate["certificateID"] = $row["certificate_id"];
            $certificate["certificateCode"] = $row["certificate_code"];
            $certificate["username"] = $row["username"];
            $certificate["eventID"] = $row["event_id"];
            $certificate["dateCreated"] = $row["date_created"];
        }
        return $certificate;
    }

    function generateCertificate($username, $eventID, $certificateCode){
        $sql = "INSERT INTO event_certificate (certificate_code, username, event_id) VALUES('$certificateCode', '$username', '$eventID')";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }

    function generateCode($eventID){
        $characters = "0123456789";
        $charactersLength = strlen($characters);
        
        $code = '';
        for ($i = 0; $i < 19; $i++) {
            $code .= $characters[rand(0, $charactersLength - 1)];
            if($i % 3 == 0){
                $code .= "-";
            }
        }$code = $code.$eventID;
    
        while (true){
            $sql = "SELECT * FROM event_certificate WHERE certificate_code='$code'";
            $result = Connection::connect()->query($sql);
            if($result->num_rows != 1){
                break;
            }
            for ($i = 0; $i < 20; $i++) {
                $code .= $characters[rand(0, $charactersLength - 1)];
                if($i % 3 == 0){
                    $code .= "-";
                }
            }$code = $code.$eventID;
        }
        return $code;
    }
}
