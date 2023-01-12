<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/event.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/email.php";
require_once $_SERVER['DOCUMENT_ROOT']."/dashboard/library/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include $_SERVER['DOCUMENT_ROOT']."/config.php";


if (!get_magic_quotes_gpc()){
    if(isset($_POST["getEvent"])){
        $eventID = $_POST["getEvent"];
        $e = new Event();
        $event = $e->getEvent($eventID);

        $currentDate = new DateTime(date("Y-m-d H:i:s"));
        $eventStart = new DateTime($event["eventStart"]);
        $eventEnd = new DateTime($event["eventEnd"]);
        $certificateEventTemplate = new EventCertificateTemplate();
        if($currentDate >= $eventStart && $currentDate <= $eventEnd) {
            $event["eventStatus"] = 1;
            $e->updateEventStatus($eventID, $event["eventStatus"]);
        }else if($currentDate < $eventStart){
            $event["eventStatus"] = 0;
            $e->updateEventStatus($eventID, $event["eventStatus"]);
        }else if($currentDate > $eventEnd){
            $event["eventStatus"] = 2;
            $e->updateEventStatus($eventID, $event["eventStatus"]);
        }

        if(!$certificateEventTemplate->getTemplate($event["eventID"])){
            $certificateEventTemplate->generateTemplate($event["eventID"], "default.jpg", "empty", "empty", "empty", "empty", "empty", "empty", "empty", "empty", "empty");
        }


        // if(var_dump($currentDate >= $eventStart && $currentDate <= $eventEnd)){
        //     $event["eventStatus"] = 1;
        //     $e->updateEventStatus($eventID, $event["eventStatus"]);
        // }else if(var_dump($currentDate < $eventStart)){
        //     $event["eventStatus"] = 0;
        //     $e->updateEventStatus($eventID, $event["eventStatus"]);
        // }else if(var_dump($currentDate > $eventEnd)){
        //     $event["eventStatus"] = 2;
        //     $e->updateEventStatus($eventID, $event["eventStatus"]);
        // }

        echo json_encode($event);
        die();

    }else if(isset($_POST["getEventList"])){
        // if(!isset($_SESSION["username"]) || !isset($_SESSION["code"])){
        //     $data["info"] = "Not logged in";
        //     echo json_encode($data);
        //     die();
        // }

        // $required_post = array("username");
        // if(!isset($_FILES["thumbnailImage"]) || !isset($_FILES["subjectFile"])){
        //     $data["info"] = "file required error!";
        //     echo json_encode($data);
        //     die();
        // }
        // if(!post_validator($_POST, $required_post)){
        //     $data["info"] = "data required error!";
        //     echo json_encode($data);
        //     die();
        // }
        // $uploadedBy = $_SESSION["username"];
        // $code = $_SESSION["code"];

        // if($uploadedBy != $_POST["username"] ){
        //     $data["info"] = "user not allowed to upload";
        //     echo json_encode($data);
        //     die();
        // }

        // $user = new User();
        // if(!$user->isUserExists($uploadedBy)){
        //     $data["info"] = "User doesn't exist";
        //     echo json_encode($data);
        //     die();
        // }
        // if(!$user->isAdmin($uploadedBy)){
        //     $data["info"] = "User is not admin";
        //     echo json_encode($data);
        //     die();
        // }
        // if($code != "admin is cool you know"){
        //     $data["info"] = "Code error";
        //     echo json_encode($data);
        //     die();
        // }

        $e = new Event();
        $allEvent = $e->getEventList();
        $data["allEvent"] = $allEvent;
        $data["onGoingEvent"] = array();
        $data["waitingEvent"] = array();
        $data["expiredEvent"] = array();
        $currentDate = date_create(date("Y-m-d H:i:s"));
        foreach ($allEvent as $event) {

            $eventStart = date_create($event["eventStart"]);
            $eventEnd = date_create($event["eventEnd"]);

            if($currentDate >= $eventStart && $currentDate <= $eventEnd) {
                array_push($data["onGoingEvent"], $event);
                $e->updateEventStatus($event["eventID"], 1);
            }else if($currentDate < $eventStart){
                array_push($data["waitingEvent"], $event);
                $e->updateEventStatus($event["eventID"], 0);
            }else if($currentDate > $eventEnd){
                array_push($data["expiredEvent"], $event);
                $e->updateEventStatus($event["eventID"], 2);
            }
        }

        echo json_encode($data);
        die();

    }else if(isset($_POST["getFollowedEvents"])){
        $e = new Event();
        $eventRegistration = new EventRegistration();
        $username = $_POST["getFollowedEvents"];
        $data["info"] = "failed";
        $followedEvents = $eventRegistration->getFollowedEvents($username);
        foreach ($followedEvents as $key => $value) {
            $event = $e->getEvent($value["eventID"]);
            $followedEvents[$key] = array_merge($followedEvents[$key], $event);
        }
        $data["info"] = "success";
        $data["followedEvents"] = $followedEvents;
        echo json_encode($data);
        die();

    }else if(isset($_POST["addEvent"])){
        $required_post = array("addedBy", "code-add", "eventName", "institution", "thumbnailText", "eventStart", "eventEnd");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $addedBy = $_POST["addedBy"];
        $adminCode = $_POST["code-add"];
        $eventName = $_POST["eventName"];
        $institution = $_POST["institution"];
        $thumbnailImage = "";
        $thumbnailText = $_POST["thumbnailText"];
        $eventStart = formating_input_from_date_event($_POST["eventStart"]);
        $eventEnd = formating_input_from_date_event($_POST["eventEnd"]);
        $thumbnailImageName = "event-0690-".date("y.m.d.h.i.s");

        $u = new User();
        $e = new Event();
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

        if(date_create($eventStart) > date_create($eventEnd)){
            $data["info"] = "You input the wrong order (event start > event end)";
            echo json_encode($data);
            die();
        }

        
        if(isset($_FILES["thumbnailImage"])){
            $thumbnailImage = file_validator($_FILES["thumbnailImage"], $thumbnailImageName, array('jpg', 'jpeg', 'png'));
            if(!$thumbnailImage){
                $thumbnailImage = "";
            }else{
                if(!move_file_event($thumbnailImage, $_FILES["thumbnailImage"]["tmp_name"], "thumbnail")){
                    $data["info"] = "failed to upload image";
                    echo json_encode($data);
                    die();
                }
            }
        }

        if($e->addEvent($eventName, $institution, $thumbnailImage, $thumbnailText, $eventStart, $eventEnd, $addedBy)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        delete_file_event($thumbnailImage, "thumbnail");

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["updateEvent"])){
        $required_post = array("updatedBy", "code-update", "eventID", "eventName", "institution", "thumbnailText", "eventStart", "eventEnd");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $updatedBy = $_POST["updatedBy"];
        $adminCode = $_POST["code-update"];
        $eventID = $_POST["eventID"];
        $eventName = $_POST["eventName"];
        $institution = $_POST["institution"];
        $thumbnailImage = "";
        $thumbnailText = $_POST["thumbnailText"];
        $eventStart = formating_input_from_date_event($_POST["eventStart"]);
        $eventEnd = formating_input_from_date_event($_POST["eventEnd"]);
        $thumbnailImageName = "event-".$eventID."-".date("y.m.d.h.i.s");

        $u = new User();
        $e = new Event();
        $user = $u->getUser($updatedBy);
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

        $oldEvent = $e->getEvent($eventID);
        if(!$oldEvent){
            $data["info"] = "Event not found";
            echo json_encode($data);
            die();
        }

        if(date_create($eventStart) > date_create($eventEnd)){
            $data["info"] = "You input the wrong order (event start > event end)";
            echo json_encode($data);
            die();
        }

        
        $oldThumbnailImage = $oldEvent["thumbnailImage"];

        if(isset($_FILES["thumbnailImage"])){
            $thumbnailImage = file_validator($_FILES["thumbnailImage"], $thumbnailImageName, array('jpg', 'jpeg', 'png'));
            if(!$thumbnailImage){
                $thumbnailImage = $oldThumbnailImage;
            }

            if($thumbnailImage != $oldThumbnailImage){
                if(!move_file_event($thumbnailImage, $_FILES["thumbnailImage"]["tmp_name"], "thumbnail")){
                    $thumbnailImage = $oldThumbnailImage;
                }else{
                    delete_file_event($oldThumbnailImage, "thumbnail");
                }
            }
        }


        if($e->updateEvent($eventID, $eventName, $institution, $thumbnailImage, $thumbnailText, $eventStart, $eventEnd)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        // $e_a = new EventAbsent();
        // $usersInEvent = $e_a->getUsersInEvent($eventID);

        // foreach ($usersInEvent as $user) {
        //     if($user["absentStatus"] == 2 && $event["eventStatus"] < 2 ){
        //         $e_a->updateAbsent($user["registrationID"], 0);
        //     }
        // }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["deleteEvent"])){
        $required_post = array("deletedBy", "code-delete", "eventID");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }


        $deletedBy = $_POST["deletedBy"];
        $adminCode = $_POST["code-delete"];
        $eventID = $_POST["eventID"];

        $u = new User();
        $e = new Event();
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

        $event = $e->getEvent($eventID);
        if(!$event){
            $data["info"] = "Event not found";
            echo json_encode($data);
            die();
        }

        if($e->deleteEvent($eventID)){
            delete_file_event($event["thumbnailImage"], "thumbnail");
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["signInEvent"])){
        $required_post = array("scanedBy", "code-update", "registrationCode");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $scanedBy = $_POST["scanedBy"];
        $adminCode = $_POST["code-update"];
        $registrationCode = $_POST["registrationCode"];
        $signInDate = date("Y-m-d H:i:s");

        $u = new User();
        $e = new Event();
        $eventRegistration = new EventRegistration();

        $user = $u->getUser($scanedBy);
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

        $userInEvent = $eventRegistration->getUserInEvent($registrationCode);
        if(!$userInEvent){
            $data["info"] = "- Registrasi ID tidak ditemukan <br>- Pegawai belum registrasi event";
            echo json_encode($data);
            die();
        }

        $event = $e->getEvent($userInEvent["eventID"]);

        if(!$userInEvent){
            $data["info"] = "Event tidak ada";
            echo json_encode($data);
            die();
        }

        if($userInEvent["signInDate"] != "0000-00-00 00:00:00"){
            $data["info"] = "Pegawai sudah melakukan absent";
            echo json_encode($data);
            die();
        }

        $safnkjwrnkjqebrkjqbr = new DateTime($signInDate);
        $eventEnd = new DateTime($event["eventEnd"]);

        if($safnkjwrnkjqebrkjqbr > $eventEnd){

            $update = $eventRegistration->updateAbsent($registrationCode, 2, "0000-00-00");
            if($update){
                $data["info"] = "Event sudah berakhir";
                echo json_encode($data);
                die();
            }
            $data["info"] = "Error:update absent";
            echo json_encode($data);
            die();
        }


        $signIn = $eventRegistration->updateAbsent($registrationCode, 1, $signInDate);
        if($signIn){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "absent failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["getUsersInEvent"])){
        $eventID = $_POST["getUsersInEvent"];
        $event = new Event();
        $eventRegistration = new EventRegistration();
        $event = $event->getEvent($eventID);
        $usersInEvent = $eventRegistration->getUsersInEvent($eventID);

        $temp = array();
        foreach ($usersInEvent as $user) {
            
            /** if the event is expired and user hasnt signin, user absent is 2
             * 0 waiting
             * 1 on going
             * 2 expired
            **/

            if($user["absentStatus"] != 1 && $event["eventStatus"] == 2){
                $eventRegistration->updateAbsent($user["registrationCode"], 2, "0000-00-00");
            }
            // $user["certificate"] = $e_c->getCertificate($user["username"], $event["eventID"], "");
            array_push($temp, $user);
        }


        echo json_encode($temp);
        die();

    }else if(isset($_POST["addNewUserToEvent"])){
        $required_post = array("addedBy", "code-add", "eventID", "username", "password", "email", "firstName", "lastName", "birthMonth", "birthDay", "birthYear", "phone", "gender", "nip", "golongan", "jabatan", "unitKerja", "instansi", "smWA");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        if(!isset($_FILES["image"])){
            $data["info"] = "file required error!";
            echo json_encode($data);
            die();
        }


        $addedBy = $_POST["addedBy"];
        $adminCode = $_POST["code-add"];
        $eventID = $_POST["eventID"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $tempat = $_POST["tempat"];
        $birthDay = strlen($_POST["birthDay"]) == 1 ? "0".$_POST["birthDay"] : $_POST["birthDay"];
        // $birthMonth = strlen($_POST["birthMonth"]) == 1 ? "0".$_POST["birthMonth"] : $_POST["birthMonth"];
        $birthMonth = $_POST["birthMonth"];
        $birthYear = $_POST["birthYear"];
        $phone = $_POST["phone"];
        $gender = $_POST["gender"];
        $nip = $_POST["nip"];
        $golongan = $_POST["golongan"];
        $jabatan = $_POST["jabatan"];
        $unitKerja = $_POST["unitKerja"];
        $instansi = $_POST["instansi"];
        $smWA = $_POST["smWA"];
        $image = $_FILES["image"];
        $birthDate = $birthYear."-".$birthMonth."-".$birthDay;
        $imageName = "user-0690-".date("y.m.d.h.i.s");

        $u = new User();
        $u_g = new UsersGlobalAddition();
        $e = new Event();
        $eventRegistration = new EventRegistration();
        
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

        if($u->getUser($username)){
            $data["info"] = "username already used";
            echo json_encode($data);
            die();
        }

        if($u_g->getUserByNIP($nip)){
            $data["info"] = "NIP account sudah terdaftar <br>silahkan daftar di ( Register B )";
            echo json_encode($data);
            die();
        }

        $event = $e->getEvent($eventID);
        if(!$event){
            $data["info"] = "Event not found";
            echo json_encode($data);
            die();
        }else{
            if($eventRegistration->getUserInEventByNIP($eventID, $nip)){
                $data["info"] = "NIP sudah terdaftar di Event";
                echo json_encode($data);
                die();
            }
        }

        if(!username_validator($username)){
            $data["info"] = "username in wrong format!";
            echo json_encode($data);
            die();
        }

        if(!email_validator($email)){
            $data["info"] = "email in wrong format!";
            echo json_encode($data);
            die();
        }

        $userImage = file_validator($_FILES["image"], $imageName, array('jpg', 'jpeg', 'png'));
        if(!$userImage){
            $userImage = "default.jpg";
        
        }else{
            if(!move_file_user($userImage, $_FILES["image"]["tmp_name"], "image")){

                $data["info"] = "failed to move files";
                echo json_encode($data);
                die();
            }
        }
        
        if($u->addUserGlobal($username, $password, $email, $firstName, $lastName, $phone, $gender, $userImage)){
            // success register new users
            if($u_g->addUser($nip, $username, $golongan, $jabatan, $unitKerja, $instansi, $smWA, $tempat, $birthDate)){
        
                $registrationCode = $eventRegistration->generateCodeRegistration();
                $certificateCode = $eventRegistration->generateCodeCertificate($eventID);
                if($eventRegistration->registerUserToEvent($registrationCode, $certificateCode, $username, $nip, $eventID)){
                    $data["info"] = "success";
                    echo json_encode($data);
                    die();
                }
                
            }
            $u->deleteUser($username);
        }



        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["addNewUserFromExcelToEvent"])){
        $required_post = array("addedBy", "code-add", "eventID");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        if(!isset($_FILES["dataExcel"])){
            $data["info"] = "file required error!";
            echo json_encode($data);
            die();
        }

        $addedBy = $_POST["addedBy"];
        $adminCode = $_POST["code-add"];
        $eventID = $_POST["eventID"];

        $allowedExtension = array("xlsx");
        $fileArray = explode(".", $_FILES['dataExcel']["name"]);
        $fileExtension = end($fileArray);
        if(!in_array($fileExtension, $allowedExtension)){
            $data["info"] = "file format is wrong!";
            echo json_encode($data);
            die();
        }
        $u = new User();
        $u_g = new UsersGlobalAddition();
        $e = new Event();
        $eventRegistration = new EventRegistration();
        
        $fileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($_FILES['dataExcel']["tmp_name"]);
        
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
        
        $spreadsheet = $reader->load($_FILES["dataExcel"]["tmp_name"]);
        $dataExcel = $spreadsheet->getActiveSheet()->toArray();
        $data = array("title"=>array(), "users"=>array());
        $users = array();
        $r = 0;
        $error = array();
        $totalData = 0;
        $newAcc = 0;
        $justJoinedEvent = 0;
        $failedToJoinEvent = 0;
        $failedToCreateNewAcc = 0;
        
        
        for($i=0 ; $i<sizeof($dataExcel) ; $i++){
            if($i==0){

            }else{
                $save = true;
                
                $c = 0;
                // $totalData++;
                // checking empty data
                $isRowEmpty = true;
                foreach($dataExcel[$i] as $column){
                    if($c == 0){
                        $c++;
                        continue;
                    }
                    // c < 13 to prevent the overchecking empty column
                    if(strlen($column) < 4 && $c < 13){
                        array_push($error, 
                            sprintf("id: %u | column: %u | row: %u", $dataExcel[$i][1], $c+1, $i+1)
                        );
                        $save = false;
                        $isRowEmpty = true;
                    }else{
                        $isRowEmpty = false;
                    }  
                    $c++;
                    
                }
                if(!$isRowEmpty){
                    $totalData++;
                }else{
                    continue;
                }
                $user = array(
                    "userMainData" => array(),
                    "userGlobalData" => array()
                );

                // checking birthDate format
                $birthDate = $dataExcel[$i][5];
                $birthDate = explode("/", $birthDate);
                if(sizeof($birthDate) < 3){
                    array_push($error, 
                        sprintf("id: %u | column: %u | row: %u", $dataExcel[$i][1], $c+1, $i+1)
                    );$save = false;
                }else{
                    
                    // if($i==1){
                    
                    // $data["info"] = $dataExcel[$i][5];
                    // echo json_encode($data);
                    // die();
                    // } 

                    // from excel format is day/month/year

                    $cellDateFormat = $spreadsheet->getActiveSheet()->getCell("F".$i)->getDataType();
                    if($cellDateFormat=="s" || $cellDateFormat=="n"){
                        $temp = $birthDate[1];
                        $birthDate[1] = $birthDate[0];
                        $birthDate[0] = $temp;
                    }
                    
                    // month
                    if(strlen($birthDate[1]) < 2){
                        $birthDate[1] = "0".$birthDate[1];
                    }
                    // day
                    if(strlen($birthDate[0]) < 2){
                        $birthDate[0] = "0".$birthDate[0];
                    }
                    
                    // to database format is year/month/day
                    $birthDate = sprintf("%s/%s/%s", $birthDate[2], $birthDate[1], $birthDate[0]);
                    // if($i==1){
                    //     $data["info"] = $birthDate;
                    //     echo json_encode($data);
                    //     die(); 
                    // }  
                    if(!validateDate($birthDate)){ 
                        array_push($error, 
                            sprintf("id: %u | column: %u | row: %u", $dataExcel[$i][1], $c+1, $i+1)
                        );$save = false;      
                    }
                }
                
                // $username = substr(str_replace(" ", "", $dataExcel[$i][2].$dataExcel[$i][3]), 0, 3).uniqid();
                $username = $dataExcel[$i][1];
                $password = $birthDate;
                if(!email_validator($dataExcel[$i][7])){
                    array_push($error, 
                        sprintf("id: %u | column: %u | row: %u", $dataExcel[$i][1], $c+1, $i+1)
                    );$save = false;
                }

                $user["userMainData"] = array(
                    "username" => $username,
                    "password" => $password,
                    "email" => $dataExcel[$i][7],
                    "firstName" => $dataExcel[$i][2],
                    "lastName"  => $dataExcel[$i][3],
                    "hp"        => $dataExcel[$i][12],
                    "gender"    => strtolower($dataExcel[$i][6]) == "perempuan" ? "Perempuan" : "Laki-Laki",
                    "image"     => "default.jpg",
                    "type"      => 4
                );
                $user["userGlobalData"] = array(
                    "nip" => $dataExcel[$i][1],
                    "username" => $username,
                    "golongan" => $dataExcel[$i][8],
                    "jabatan"  => $dataExcel[$i][9],
                    "unitKerja" => $dataExcel[$i][10],
                    "instansi" => $dataExcel[$i][11],
                    "whatsapp" => $dataExcel[$i][12],
                    "tempat" => $dataExcel[$i][4],
                    "birthDate" => $birthDate
                );
                array_push($users, $user);

                // prepare to save
                
                // check if event still exists
                if(!$e->getEvent($eventID)){
                    $x["info"] = "Event doesn't exist";
                    echo json_encode($x);
                    die();                        
                }

                if(!$save){
                    $failedToCreateNewAcc++;
                }else{
                    // check if nip already registered as an account

                    // if username hasnt registered in global DB
                    while(true){
                        if(!$u->getUser($user["userGlobalData"]["username"])){
                            break;
                        }
                        $generateUsername = substr(str_replace(" ", "", $dataExcel[$i][2].$dataExcel[$i][3]), 0, 3).uniqid();
                        $user["userGlobalData"]["username"] = $generateUsername;
                        $user["userMainData"]["username"] = $generateUsername;
                    }
                    $userGlobalDB = $u_g->getUserByNIP($user["userGlobalData"]["nip"]);

                    if(!$userGlobalDB){
                        if($u->addUserGlobal(
                            $user["userMainData"]["username"], 
                            $user["userMainData"]["password"], 
                            $user["userMainData"]["email"], 
                            $user["userMainData"]["firstName"], 
                            $user["userMainData"]["lastName"], 
                            $user["userMainData"]["hp"], 
                            $user["userMainData"]["gender"], "default.jpg")){
                            if($u_g->addUser(
                                $user["userGlobalData"]["nip"], 
                                $user["userGlobalData"]["username"], 
                                $user["userGlobalData"]["golongan"], 
                                $user["userGlobalData"]["jabatan"], 
                                $user["userGlobalData"]["unitKerja"], 
                                $user["userGlobalData"]["instansi"], 
                                $user["userGlobalData"]["whatsapp"], 
                                $user["userGlobalData"]["tempat"], 
                                $user["userGlobalData"]["birthDate"])){
                                $newAcc++;
                                // if account registration success -> register event
                                if(!$eventRegistration->getUserInEventByNIP($eventID, $user["userGlobalData"]["nip"])){
                                    $registrationCode = $eventRegistration->generateCodeRegistration();
                                    $certificateCode = $eventRegistration->generateCodeCertificate($eventID);
                                    if($eventRegistration->registerUserToEvent($registrationCode, $certificateCode, $user["userGlobalData"]["username"], $user["userGlobalData"]["nip"], $eventID)){
                                        $justJoinedEvent++;
                                    }else{
                                        $failedToJoinEvent++;
                                    }
                                }
                            }else{

                                // if registration to the global data failed -> delete data in main data
                                array_push($error, 
                                    sprintf("id: %u | column: %u | row: %u", $dataExcel[$i][1], $c+1, $i+1)
                                );
                                $failedToCreateNewAcc++;
                                
                            }
                        }else{
                            array_push($error, 
                                sprintf("id: %u | column: %u | row: %u", $dataExcel[$i][1], $c+1, $i+1)
                            );
                            $failedToCreateNewAcc++;

                        }
                    }else if($userGlobalDB){
                        if(!$eventRegistration->getUserInEventByNIP($eventID, $user["userGlobalData"]["nip"])){
                            $registrationCode = $eventRegistration->generateCodeRegistration();
                            $certificateCode = $eventRegistration->generateCodeCertificate($eventID);
                            if($eventRegistration->registerUserToEvent($registrationCode, $certificateCode, $userGlobalDB["username"], $user["userGlobalData"]["nip"], $eventID)){
                                $justJoinedEvent++;
                            }else{
                                $x["info"] = $user["userGlobalData"]["nip"];
                                echo json_encode($x);
                                die();
                            }
                        }
                        // check if the user is not admin then get user data then save it to the event
                    }else{
                        

                    }
                }
            }
        }

        
        $x["info"] = "info";
        $x["newAcc"] = $newAcc;
        $x["failedToCreateNewAcc"] = $failedToCreateNewAcc;
        $x["justJoinedEvent"] = $justJoinedEvent;
        $x["failedToJoinEvent"] = $failedToJoinEvent;
        $x["data"] = sprintf("total data: %u <br> new acc: %u <br> failedToCreateNewAcc: %u <br> SuccessJoinedEvent: %u <br> failedJoinEvent: %u <br>", $totalData, $newAcc, $failedToCreateNewAcc, $justJoinedEvent, $failedToJoinEvent);
        echo json_encode($x);
        die();
        
    }else if(isset($_POST["addUserToEvent"])){
        $required_post = array("addedBy", "code-add", "eventID", "nip");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $addedBy = $_POST["addedBy"];
        $adminCode = $_POST["code-add"];
        $eventID = $_POST["eventID"];
        $nip = $_POST["nip"];

        $user = new User();
        $u_g = new UsersGlobalAddition();
        $e = new Event();
        $eventRegistration = new EventRegistration();
        $user = $user->getUser($addedBy);
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

        $userGlobal = $u_g->getUserByNIP($nip);
        if(!$userGlobal){
            $data["info"] = "NIP belum pernah terdaftar sebagai account<br>silahkan daftar di ( Register A )";
            echo json_encode($data);
            die();
        }

        $event = $e->getEvent($eventID);
        if(!$event){
            $data["info"] = "Event not found";
            echo json_encode($data);
            die();
        }else{
            if($eventRegistration->getUserInEventByNIP($eventID, $nip)){
                $data["info"] = "NIP sudah terdaftar di Event";
                echo json_encode($data);
                die();
            }
        }


        $registrationCode = $eventRegistration->generateCodeRegistration();
        $certificateCode = $eventRegistration->generateCodeCertificate($eventID);
        if($eventRegistration->registerUserToEvent($registrationCode, $certificateCode, $userGlobal["username"], $userGlobal["nip"], $eventID)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["global-self-RegisterEvent"])){
        $required_post = array("joinedBy", "code-join", "username", "nip", "userID", "eventID");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $joinedBy = $_POST["joinedBy"];
        $globalCode = $_POST["code-join"];
        $username = $_POST["username"];
        $userID = $_POST["userID"];
        $nip = $_POST["nip"];
        $eventID = $_POST["eventID"];

        $u = new User();
        $u_g = new UsersGlobalAddition();
        $e = new Event();
        $eventRegistration = new EventRegistration();
        $user = $u->getUser($joinedBy);
        if(!$user){
            $data["info"] = "Not Available";
            echo json_encode($data);
            die();
        }

        if($user["type"] != 4 || $user["status"] != 1 || $globalCode != "i am not an admin :)"){
            $data["info"] = "User not allowed";
            echo json_encode($data);
            die();
        }

        $userGlobal = $u_g->getUserByNIP($nip);
        if(!$userGlobal){
            $data["info"] = "NIP Error:something went wrong";
            echo json_encode($data);
            die();
        }

        $event = $e->getEvent($eventID);
        if(!$event){
            $data["info"] = "Event not found";
            echo json_encode($data);
            die();
        }else{
            if($eventRegistration->getUserInEventByNIP($eventID, $nip)){
                $data["info"] = "NIP sudah terdaftar di Event";
                echo json_encode($data);
                die();
            }
        }


        $registrationCode = $eventRegistration->generateCodeRegistration();
        $certificateCode = $eventRegistration->generateCodeCertificate($eventID);
        if($eventRegistration->registerUserToEvent($registrationCode, $certificateCode, $userGlobal["username"], $userGlobal["nip"], $eventID)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["global-self-CancelEvent"])){
        $required_post = array("canceledBy", "code-cancel", "username", "nip", "userID", "eventID", "registrationCode");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $canceledBy = $_POST["canceledBy"];
        $globalCode = $_POST["code-cancel"];
        $username = $_POST["username"];
        $userID = $_POST["userID"];
        $nip = $_POST["nip"];
        $eventID = $_POST["eventID"];
        $registrationCode = $_POST["registrationCode"];
        

        $u = new User();
        $u_g = new UsersGlobalAddition();
        $e = new Event();
        $eventRegistration = new EventRegistration();
        $user = $u->getUser($canceledBy);
        if(!$user){
            $data["info"] = "Not Available";
            echo json_encode($data);
            die();
        }

        if($user["type"] != 4 || $user["status"] != 1 || $globalCode != "i am not an admin :)"){
            $data["info"] = "User not allowed";
            echo json_encode($data);
            die();
        }

        $userGlobal = $u_g->getUser($username);
        if(!$userGlobal){
            $data["info"] = "User tidak terdaftar sebagai account";
            echo json_encode($data);
            die();
        }

        $event = $e->getEvent($eventID);
        if(!$event){
            $data["info"] = "Event not found";
            echo json_encode($data);
            die();
        }

        if(!$eventRegistration->getUserInEvent($registrationCode)){
            $data["info"] = "User not found";
            echo json_encode($data);
            die();
        }

        if($eventRegistration->deleteUserFromEvent($registrationCode, $eventID)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["deleteUserFromEvent"])){
        $required_post = array("deletedBy", "code-delete", "eventID", "nip", "registrationCode");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $addedBy = $_POST["deletedBy"];
        $adminCode = $_POST["code-delete"];
        $eventID = $_POST["eventID"];
        $nip = $_POST["nip"];
        $registrationCode = $_POST["registrationCode"];

        $user = new User();
        $u_g = new UsersGlobalAddition();
        $e = new Event();
        $eventRegistration = new EventRegistration();
        $user = $user->getUser($addedBy);
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

        $userGlobal = $u_g->getUserByNIP($nip);
        if(!$userGlobal){
            $data["info"] = "NIP tidak terdaftar sebagai account";
            echo json_encode($data);
            die();
        }

        $event = $e->getEvent($eventID);
        if(!$event){
            $data["info"] = "Event not found";
            echo json_encode($data);
            die();
        }

        if(!$eventRegistration->getUserInEvent($registrationCode)){
            $data["info"] = "User not found";
            echo json_encode($data);
            die();
        }

        if($eventRegistration->deleteUserFromEvent($registrationCode, $eventID)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }


        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }

    else if(isset($_POST["getCertificateTemplate"])){
        $template = new EventCertificateTemplate();
        echo json_encode($template->getTemplate($_POST["getCertificateTemplate"]));
        die();

    }else if(isset($_POST["edit-certificate-event"])){

        $required = array("eventID", "certificateTemplateID", "x1", "x2", "x3", "x4", "x5", "x6", "x7", "x8", "x9");
        if(!post_validator($_POST, $required)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        if(!isset($_FILES["backgroundImage"])){
            $data["info"] = "file required error!";
            echo json_encode($data);
            die();
        }
        $eventID = $_POST["eventID"];
        $backgroundImage = "";
        $certificateEventTemplate = new EventCertificateTemplate();
        $certificateTemplate = $certificateEventTemplate->getTemplate($eventID);
        
        $oldBackgroundImage = $certificateTemplate["backgroundImage"];
        $backgroundImageName = "event-".$eventID."-".date("y.m.d.h.i.s");

        if(isset($_FILES["backgroundImage"])){
            $backgroundImage = file_validator($_FILES["backgroundImage"], $backgroundImageName, array('jpg', 'jpeg', 'png'));
            if(!$backgroundImage){
                $backgroundImage = $oldBackgroundImage;

            }

            if($backgroundImage != $oldBackgroundImage){
                if(!move_file_event($backgroundImage, $_FILES["backgroundImage"]["tmp_name"], "event-certificate-background")){
                    $backgroundImage = $oldBackgroundImage;
                }else{
                    delete_file_event($oldBackgroundImage, "event-certificate-background");
                }
            }

        }

        if($certificateEventTemplate->updateTemplate($eventID, $backgroundImage, $_POST["x1"], $_POST["x2"], $_POST["x3"], $_POST["x4"], $_POST["x5"], $_POST["x6"], $_POST["x7"], $_POST["x8"], $_POST["x9"])){
            $data["info"] = "success";
            echo json_encode($data);
            die();            
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["getUserRegistration"])){
        $registrationCode = $_POST["getUserRegistration"];
        $eventRegistration = new EventRegistration();

        $data["userRegistration"] = $eventRegistration->getUserInEvent($registrationCode);
        if(!$data["userRegistration"]){
            $data["info"] = "failed";
        }else{
            $data["info"] = "success";
        }
        

        echo json_encode($data);
        die();
    
    }else if(isset($_POST["sendCertificate"])){
        $required_post = array("sentBy", "code-send", "eventID", "nip", "username", "registrationCode");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error";
            echo json_encode($data);
            die();
        }
        
        $sentBy = $_POST["sentBy"];
        $adminCode = $_POST["code-send"];
        $eventID = $_POST["eventID"];
        $username = $_POST["username"];
        $registrationCode = $_POST["registrationCode"];
        $nip = $_POST["nip"];
        
        $user = new User();
        $event = new Event();
        $u_g = new UsersGlobalAddition();
        $eventRegistration = new EventRegistration();

        $event = $event->getEvent($eventID);
        if(!$event){
            $data["info"] = "Event Not Found";
            echo json_encode($data);
            die();
        }

        $userAdmin = $user->getUser($sentBy);
        if(!$userAdmin){
            $data["info"] = "Not Available";
            echo json_encode($data);
            die();
        }

        if($userAdmin["type"] != 1 || $userAdmin["status"] != 1 || $adminCode != "admin is cool you know"){
            $data["info"] = "User not allowed";
            echo json_encode($data);
            die();
        }

        $userGlobal = $user->getUser($username);
        if(!$userGlobal){
            $data["info"] = "user tidak ditemukan<br>silahkan daftar terlebih dahulu";
            echo json_encode($data);
            die();
        }

        $userGlobalAdditionalData = $u_g->getUserByNIP($nip);
        if(!$userGlobalAdditionalData){
            $data["info"] = "NIP belum pernah terdaftar sebagai account<br>silahkan daftar terlebih dahulu";
            echo json_encode($data);
            die();
        }

        $registrationData = $eventRegistration->getUserInEvent($registrationCode);
        if(!$registrationData){
            $data["info"] = "User not registered in event";
            echo json_encode($data);
            die();
        }

        if($userGlobal["username"] != $userGlobalAdditionalData["username"]){
            $data["info"] = "Error";
            echo json_encode($data);
            die();   
        }

        if($userGlobalAdditionalData["username"] != $registrationData["username"]){
            $data["info"] = "Error";
            echo json_encode($data);
            die();   
        }

        $certificate = new EventCertificate();
        $certificate->sendCertificate($userGlobal["email"], $eventID, $registrationData["registrationCode"]);

        $data["info"] = "success";
        echo json_encode($data);
        die();

    }else if(isset($_POST["scanCertificate"])){
        $required_post = array("scanBy", "code-scan", "certificateCode");
        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error";
            echo json_encode($data);
            die();
        }

        $scanBy = $_POST["scanBy"];
        $adminCode = $_POST["code-scan"];
        $certificateCode = $_POST["certificateCode"];
        $user = new User();
        $eventCertificate = new EventCertificate();
        $userAdmin = $user->getUser($scanBy);
        if(!$userAdmin){
            $data["info"] = "Not Available";
            echo json_encode($data);
            die();
        }

        if($userAdmin["type"] != 1 || $userAdmin["status"] != 1 || $adminCode != "admin is cool you know"){
            $data["info"] = "User not allowed";
            echo json_encode($data);
            die();
        }

        if($eventCertificate->getCertificate($certificateCode)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }


        
        $data["info"] = "Tidak ditemukan";
        echo json_encode($data);
        die();

    }
    

    else if(isset($_POST["getFileGlobalList"])){
        $registrationCode = $_POST["getFileGlobalList"];

        $eventRegistration = new EventRegistration();
        $eventFileGlobal = new EventFileGlobal();
        $registration = $eventRegistration->getUserInEvent($registrationCode);
        if(!$registration){
            $result["info"] = "User not recognized";
            echo json_encode($result);
            die();
        }


        $fileGlobalList = $eventFileGlobal->getFileGlobalList($registrationCode);
        $result["info"] = "success";
        $result["fileGlobalList"] = $fileGlobalList;
        echo json_encode($result);
        die();
    
    }else if(isset($_POST["uploadFileGlobal"])){
        $required_post = array("username", "eventID", "title", "registrationCode");
        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }
        $username = $_POST["username"];
        $eventID = $_POST["eventID"];
        $title = $_POST["title"];
        $registrationCode = $_POST["registrationCode"];
        $formatFileName = date("y.m.d.h.i.s");
        $userGlobalAddition = new UsersGlobalAddition();
        $eventRegistration = new EventRegistration();
        $eventFileGlobal = new EventFileGlobal();
        $userGlobal = $userGlobalAddition->getUser($username);
        if(!$userGlobal){
            $data["info"] = "Not Allowed";
            echo json_encode($data);
            die();
        }

        $eventRegistration = $eventRegistration->getUserInEvent($registrationCode);
        if(!$eventRegistration){
            $data["info"] = "User not Registered to Event";
            echo json_encode($data);
            die();
        }


        $fileFileName = file_validator($_FILES["fileGlobal"], $formatFileName, array("pdf", "mp4"));
        if(!$fileFileName){
            $data["info"] = "file error!";
            echo json_encode($data);
            die();
        }

        $filename = $_FILES["fileGlobal"]["name"];
        
        if(!move_file_event($fileFileName, $_FILES["fileGlobal"]["tmp_name"], "file-global")){
            $data["info"] = "directory error";
            echo json_encode($data);
            die();
        }
         
        
        if($eventFileGlobal->uploadFileGlobal($registrationCode, $title, $filename, $fileFileName)){
            $data["info"] = "success";
            echo json_encode($data);
            die();    
        }
        delete_file_event($fileFileName, "file-global");
        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["deleteFileGlobal"])){
        $required_post = array("username", "fileGlobalID", "eventID", "registrationCode");
        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $username = $_POST["username"];
        $fileGlobalID = $_POST["fileGlobalID"];
        $eventID = $_POST["eventID"];
        $registrationCode = $_POST["registrationCode"];       
        $userGlobalAddition = new UsersGlobalAddition();
        $eventRegistration = new EventRegistration();
        $eventFileGlobal = new EventFileGlobal();
        $userGlobal = $userGlobalAddition->getUser($username);
        if(!$userGlobal){
            $data["info"] = "Not Allowed";
            echo json_encode($data);
            die();
        }

        $eventRegistration = $eventRegistration->getUserInEvent($registrationCode);
        if(!$eventRegistration){
            $data["info"] = "User not Registered to Event";
            echo json_encode($data);
            die();
        }

        $fileGlobal = $eventFileGlobal->getFileGlobal($fileGlobalID);
        if(!$fileGlobal){
            $data["info"] = "File Not Found";
            echo json_encode($data);
            die();
        }


        if($eventFileGlobal->deleteFileGlobal($fileGlobalID, $registrationCode)){
            delete_file_event($fileGlobal["fileFileName"], "file-global");
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }


        
        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }







    else if(isset($_POST["scanCertificate"])){
        $certificateCode = $_POST["certificateCode"];
        $certificate = array();
        global $CONFIG;
        $e_c = new EventCertificate();
        $certificate = $e_c->getCertificate("", "", $certificateCode);
        if(!$certificate){
            $data["info"] = "Certificate Not Found";
            echo json_encode($data);
            die();
        }
        $data["info"] = "success";
        $data["cURL"] = "event/event-member-certificate.php?certificate-code=".$certificate["certificateCode"];
        echo json_encode($data);
        die();

    }else if(isset($_POST["verifyCertificate"])){
        $certificateCode = $_POST["verifyCertificate"];
        $certificate = array();

        $e_c = new EventCertificate();
        $certificate = $e_c->getCertificate("", "", $certificateCode);
        echo json_encode($certificate);
        die();

    }else if(isset($_POST["downloadExcelUsersEvent"])){
        $required_post = array("downloadedBy", "code-download", "eventID");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }


        $downloadedBy = $_POST["downloadedBy"];
        $adminCode = $_POST["code-download"];
        $eventID = $_POST["eventID"];
        $u = new User();
        $u_g = new UsersGlobalAddition();
        $e = new Event();
        $eventRegistration = new EventRegistration();
        $user = $u->getUser($downloadedBy);
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

        $event = $e->getEvent($eventID);
        if(!$event){
            $data["info"] = "Event not found";
            echo json_encode($data);
            die();
        }

        $usersInEvent = $eventRegistration->getUsersInEvent($eventID);

        $table = '
            <table id="demo-datatables-scroller-1" class="table table-striped   table-nowrap dataTable" cellspacing="0" width="100%">
                <thead class="text-info">
                    <tr>
                        <th colspan="9">Laporan Peserta Daftar</th>
                    </tr>
                    <tr>
                        <th colspan="9">'.$event["eventName"].'</th>
                    </tr>
                    <tr>
                        <th colspan="9">Pelatihan Teknis Pemasyarakatan Petugas Pengaman Dasar Tahun</th>
                    </tr>
                    <tr>
                        <th colspan="9">Anggaran 2021</th>
                    </tr>
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Golongan</th>
                        <th>Jabatan</th>
                        <th>Unit Kerja</th>
                        <th>Instansi</th>
                        <th>Telephone/whatsapp</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>';
            $i = 0;
            foreach ($usersInEvent as $user) {
                $i++;
                $userGlobal = $u->getUserGlobal($user["username"]);
                $userGlobalAddition = $u_g->getUser($user["username"]);
                $phone = $userGlobal["phone"];
                if(strlen($phone) < 3){
                    $phone = $userGlobalAddition["whatsapp"];
                }

                if(substr($phone, 0, 1) != 0){
                    $phone = "+62 ".$phone;
                }

                $table = $table.'
                    <tr>
                        <td>'.$i.'</td>
                        <td>'.$userGlobalAddition["nip"].'</td>
                        <td>'.$userGlobal["name"].'</td>
                        <td>'.$userGlobalAddition["golongan"].'</td>
                        <td>'.$userGlobalAddition["jabatan"].'</td>
                        <td>'.$userGlobalAddition["unitKerja"].'</td>
                        <td>'.$userGlobalAddition["instansi"].'</td>
                        <td>'.$phone.'</td>
                        <td>'.$userGlobal["email"].'</td>

                    </tr>';
            }
        $table = $table.'</tbody></table>';
        header("Content-Type: application/xls");
        header("Content-Disposition:attachment; filename=application.xls");

        echo $table;
        die();
    
    }else{
        $data["info"] = "Not Recognized";
        echo json_encode($data);
        die();
    }

}else{
    $data["info"] = "The Magic DOesn't work";
    echo json_encode($data);
    die();
}

