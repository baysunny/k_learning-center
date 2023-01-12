<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/course.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/certificate.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/email.php";



if (!get_magic_quotes_gpc()){
    
    if(isset($_POST["getProfile"])){
        $required_post = array("userGlobalID");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }
        $userGlobalID = $_POST["userGlobalID"];
        $userGlobalAddition = new UsersGlobalAddition();
        $userGlobalAddition = $userGlobalAddition->getUserByUserGlobalID($userGlobalID);
        if(!$userGlobalAddition){
            $data["info"] =  "ERROR 1";
            echo json_encode($data);
            die();
        }

        
        $data["userData"]["username"] = $userGlobalAddition["username"];
        $userGlobal = new User();
        $data["userData"] = $userGlobal->getUser($data["userData"]["username"]);
        if(!$userGlobal){
            $data["info"] =  "ERROR 1";
            echo json_encode($data);
            die();
        }
        $data["userData"] = array_merge($data["userData"], $userGlobalAddition);
        $clock = new Clock($data["userData"]["username"], 0, "");
        $qna = new Qna();

        $data["readData"]["readList"] = $clock->getReadList();
        $data["readData"]["totalSubjectRead"] = sizeof($data["readData"]["readList"]);
        $data["readData"]["timeInSecond"] = 0;
        for($j=0; $j<sizeof($data["readData"]["readList"]); $j++){
            $data["readData"]["readList"][$j] = array_merge(
                $data["readData"]["readList"][$j],
                Subject::getSubject($data["readData"]["readList"][$j]["subjectID"])
            );

            $data["readData"]["timeInSecond"] += $data["readData"]["readList"][$j]["timeInSecond"];
        }
        $data["readData"]["timeRead"] = time_formatting($data["readData"]["timeInSecond"]);
        $data["readData"]["totalQuestion"] = sizeof($qna->questionList($data["userData"]["username"]));


        $data["info"] = "success";
        echo json_encode($data);
        die();

    }else if(isset($_POST["getUsersGlobal"])){
        
        $usersGlobal = new UsersGlobalAddition();
        $usersGlobal = $usersGlobal->getUsers();
        $user = new User();
        for($i=0; $i<sizeof($usersGlobal); $i++){
            $usersGlobal[$i] = array_merge($usersGlobal[$i], $user->getUser($usersGlobal[$i]["username"]));
        }
        echo json_encode($usersGlobal);
        die();

    }else if(isset($_POST["getAccountSetting"])){
        $required_post = array("getAccountSetting");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }
        $username = $_POST["getAccountSetting"];
        $userGlobalAddition = new UsersGlobalAddition();
        $userGlobalAddition = $userGlobalAddition->getUser($username);
        if(!$userGlobalAddition){
            $data["info"] =  "ERROR 1";
            echo json_encode($data);
            die();
        }

        
        $data["userData"]["username"] = $userGlobalAddition["username"];
        $userGlobal = new User();
        $data["userData"] = $userGlobal->getUser($data["userData"]["username"]);
        if(!$userGlobal){
            $data["info"] =  "ERROR 1";
            echo json_encode($data);
            die();
        }
        $data["userData"] = array_merge($data["userData"], $userGlobalAddition);
        

        $data["info"] = "success";
        echo json_encode($data);
        die();
    
    }else if(isset($_POST["editAccount"])){
        $required_post = array("editedBy", "code-edit", "userID", "username", "password", "email", "firstName", "lastName", "gender", "phone");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $editedBy = $_POST["editedBy"];
        $adminCode = $_POST["code-edit"];
        $userID = $_POST["userID"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $gender = $_POST["gender"];
        $phone = $_POST["phone"];
        $u = new User();

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

        $userMainData = $u->getUserByID($userID);
        if(!$userMainData){
            $data["info"] = "User Not Found";
            echo json_encode($data);
            die();
        }

        if($username != $userMainData["username"]){
            if($u->getUser($username)){
                $data["info"] = "Username Already Used";
                echo json_encode($data);
                die();
            }
        }

        
        $oldProfileImage = $userMainData["image"];
        $profileImage = $oldProfileImage;
        $fileImageName = sprintf("%s-", $userMainData["username"]).date("y.m.d.h.i.s");
        if(isset($_FILES["profileImage"])){

            $profileImage = file_validator($_FILES["profileImage"], $fileImageName, array('jpg', 'jpeg', 'png'));
            if(!$profileImage){
                $profileImage = $oldProfileImage;
            }

            if($profileImage != $oldProfileImage){
                if(!move_file_user($profileImage, $_FILES["profileImage"]["tmp_name"], "image")){
                    $profileImage = $oldProfileImage;
                }else{
                    delete_file_user($oldProfileImage, "image");
                }
            }
        }

        if($u->updateUser($userID, $username, $password, $email, $firstName, $lastName, $gender, $profileImage, $phone)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }


        $data["info"] = "Failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["editProfile"])){
        $required_post = array("editedBy", "code-edit", "userID", "nip", "golongan", "jabatan", "unitKerja", "instansi", "tempat", "whatsapp", "birthDay", "birthMonth", "birthYear");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $editedBy = $_POST["editedBy"];
        $adminCode = $_POST["code-edit"];
        $userID = $_POST["userID"];
        $nip = $_POST["nip"];
        $golongan = $_POST["golongan"];
        $jabatan = $_POST["jabatan"];
        $unitKerja = $_POST["unitKerja"];
        $instansi = $_POST["instansi"];
        $tempat = $_POST["tempat"];
        $whatsapp = $_POST["whatsapp"];
        $birthDay = $_POST["birthDay"];
        $birthMonth = $_POST["birthMonth"];
        $birthYear = $_POST["birthYear"];
        $birthDate = "{$birthYear}/{$birthMonth}/{$birthDay}";

        if(!validateDate($birthDate)){
            $data["info"] = "Tanggal lahir tidak sesuai";
            echo json_encode($data);
            die();
        }

        $user = new User();
        $userGlobal = new UsersGlobalAddition();
        $userAdmin = $user->getUser($editedBy);
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

        $userMainData = $user->getUserByID($userID);
        if(!$userMainData){
            $data["info"] = "User Not Found";
            echo json_encode($data);
            die();
        }

        $userGlobalData = $userGlobal->getUser($userMainData["username"]);
        if(!$userGlobalData){
            $data["info"] = "User Not Found";
            echo json_encode($data);
            die();
        }

        if($nip != $userGlobalData["nip"]){
            if($userGlobal->getUserByNIP($nip)){
                $data["info"] = "NIP Already Used";
                echo json_encode($data);
                die();
            }            
        }


        if($userGlobal->updateUser($userGlobalData["userGlobalID"], $nip, $golongan, $jabatan, $unitKerja, $instansi, $whatsapp, $tempat, $birthDate)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }



        $data["info"] = "Failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["editAccountProfile"])){
        $required_post = array("userID", "username", "password", "email", "firstName", "lastName", "gender", "phone", "userGlobalID", "nip", "golongan", "jabatan", "unitKerja", "instansi", "tempat", "whatsapp", "birthDay", "birthMonth", "birthYear");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        // $editedBy = $_POST["editedBy"];
        // $adminCode = $_POST["code-edit"];
        $userID = $_POST["userID"];
        $userGlobalID = $_POST["userGlobalID"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $gender = $_POST["gender"];
        $phone = $_POST["phone"];
        $nip = $_POST["nip"];
        $golongan = $_POST["golongan"];
        $jabatan = $_POST["jabatan"];
        $unitKerja = $_POST["unitKerja"];
        $instansi = $_POST["instansi"];
        $tempat = $_POST["tempat"];
        $whatsapp = $_POST["whatsapp"];
        $birthDay = $_POST["birthDay"];
        $birthMonth = $_POST["birthMonth"];
        $birthYear = $_POST["birthYear"];
        $birthDate = "{$birthYear}/{$birthMonth}/{$birthDay}";

        if(!validateDate($birthDate)){
            $data["info"] = "Tanggal lahir tidak sesuai";
            echo json_encode($data);
            die();
        }

        $user = new User();
        $userGlobal = new UsersGlobalAddition();
        // $userAdmin = $user->getUser($editedBy);
        // if(!$userAdmin){
        //     $data["info"] = "Not Available";
        //     echo json_encode($data);
        //     die();
        // }

        // if($userAdmin["type"] != 1 || $userAdmin["status"] != 1 || $adminCode != "admin is cool you know"){
        //     $data["info"] = "User not allowed";
        //     echo json_encode($data);
        //     die();
        // }

        $userMainData = $user->getUserByID($userID);
        if(!$userMainData){
            $data["info"] = "User Not Found";
            echo json_encode($data);
            die();
        }

        $userGlobalData = $userGlobal->getUser($userMainData["username"]);
        if(!$userGlobalData){
            $data["info"] = "User Not Found";
            echo json_encode($data);
            die();
        }
        
        if($username != $userMainData["username"]){
            if($user->getUser($username)){
                $data["info"] = "Username Already Used";
                echo json_encode($data);
                die();
            }
        }
        
        if($nip != $userGlobalData["nip"]){
            if($userGlobal->getUserByNIP($nip)){
                $data["info"] = "NIP Already Used";
                echo json_encode($data);
                die();
            }            
        }


        $oldProfileImage = $userMainData["image"];
        $profileImage = $oldProfileImage;
        $fileImageName = sprintf("%s-", $userMainData["username"]).date("y.m.d.h.i.s");

        if(isset($_FILES["profileImage"])){

            $profileImage = file_validator($_FILES["profileImage"], $fileImageName, array('jpg', 'jpeg', 'png'));
            if(!$profileImage){
                $profileImage = $oldProfileImage;
            }

            if($profileImage != $oldProfileImage){
                if(!move_file_user($profileImage, $_FILES["profileImage"]["tmp_name"], "image")){
                    $profileImage = $oldProfileImage;
                }else{
                    delete_file_user($oldProfileImage, "image");
                }
            }
        }

        $updateAccount = false;
        $updateProfile = false;

        if($user->updateUser($userID, $username, $password, $email, $firstName, $lastName, $gender, $profileImage, $phone)){
            $updateAccount = true;
        }


        if($userGlobal->updateUser($userGlobalData["userGlobalID"], $nip, $golongan, $jabatan, $unitKerja, $instansi, $whatsapp, $tempat, $birthDate)){
            $updateProfile = true;
        }

        
        if($updateAccount && $updateProfile){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }


        $data["info"] = "Failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["deleteAccount"])){
        $required_post = array("deletedBy", "code-delete", "userID");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $deletedBy = $_POST["deletedBy"];
        $adminCode = $_POST["code-delete"];
        $userID = $_POST["userID"];
        
        $user = new User();
        $userGlobal = new UsersGlobalAddition();
        $userAdmin = $user->getUser($deletedBy);
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

        $userMainData = $user->getUserByID($userID);
        if(!$userMainData){
            $data["info"] = "User Not Found";
            echo json_encode($data);
            die();
        }

        if($user->deleteUser($userID)){
            $data["info"] = "success";
            echo json_encode($data);
            die();            
        }

        $data["info"] = "Failed!";
        echo json_encode($data);
        die();

    }else if(isset($_POST["self-global-editAccountProfile"])){
        $required_post = array("userID", "username", "password", "email", "firstName", "lastName", "gender", "phone", "nip", "golongan", "jabatan", "unitKerja", "instansi", "tempat", "whatsapp", "birthDay", "birthMonth", "birthYear");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }
        $userID = $_POST["userID"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $gender = $_POST["gender"];
        $phone = $_POST["phone"];
        $nip = $_POST["nip"];
        $golongan = $_POST["golongan"];
        $jabatan = $_POST["jabatan"];
        $unitKerja = $_POST["unitKerja"];
        $instansi = $_POST["instansi"];
        $tempat = $_POST["tempat"];
        $whatsapp = $_POST["whatsapp"];
        $birthDay = $_POST["birthDay"];
        $birthMonth = $_POST["birthMonth"];
        $birthYear = $_POST["birthYear"];
        $birthDate = "{$birthYear}/{$birthMonth}/{$birthDay}";

        if(!validateDate($birthDate)){
            $data["info"] = "Tanggal lahir tidak sesuai";
            echo json_encode($data);
            die();
        }

        $user = new User();
        $userMainData = $user->getUserByID($userID);
        if(!$userMainData){
            $data["info"] = "User Not Found";
            echo json_encode($data);
            die();
        }

        $userGlobal = new UsersGlobalAddition();
        $userGlobalData = $userGlobal->getUser($userMainData["username"]);
        if(!$userMainData){
            $data["info"] = "User Not Found";
            echo json_encode($data);
            die();
        }
        
        $oldProfileImage = $userMainData["image"];
        $profileImage = $oldProfileImage;
        $fileImageName = sprintf("%s-", $userMainData["username"]).date("y.m.d.h.i.s");
        if(isset($_FILES["profileImage"])){

            $profileImage = file_validator($_FILES["profileImage"], $fileImageName, array('jpg', 'jpeg', 'png'));
            if(!$profileImage){
                $profileImage = $oldProfileImage;
            }

            if($profileImage != $oldProfileImage){
                if(!move_file_user($profileImage, $_FILES["profileImage"]["tmp_name"], "image")){
                    $profileImage = $oldProfileImage;
                }else{
                    delete_file_user($oldProfileImage, "image");
                }
            }
        }

        if($user->updateUser($userID, $username, $password, $email, $firstName, $lastName, $gender, $profileImage, $phone)){
        
            if($userGlobal->updateUser($userGlobalData["userGlobalID"], $nip, $golongan, $jabatan, $unitKerja, $instansi, $whatsapp, $tempat, $birthDate)){
                $_SESSION['username'] = $username;
                $data["info"] = "success";
                echo json_encode($data);
                die();
            }
        }

        $data["info"] = "Failed";
        echo json_encode($data);
        die();

    }






    else if(isset($_POST["sign-up"])){

        $required_post = array("username", "password", "email", "firstName", "lastName", "hp", "gender", "type");
        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }


        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $hp = $_POST["hp"];
        $gender = $_POST["gender"];
        $type = $_POST["type"];
        $nip = "";
        $golongan = "";
        $jabatan = "";
        $unitKerja = "";
        $instansi = "";
        $birthDate = "0000-00-00";
        $tempat = "";
        $verificationCode = uniqid('', true);
        
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
        $u_t = new UserTemp();
        $u = new User();

        $user = $u->getUser($username);
        if($user){
            $data["info"] = "Username Already Used";
            echo json_encode($data);
            die();
        }
        $userTemp = $u_t->getUserTemp($username);
        if($userTemp){
            $data["info"] = "Username Already Used";
            echo json_encode($data);
            die();
        }

        if($type == 4){
            $required_post = array("nip", "golongan", "jabatan", "unitKerja", "instansi", "birthDay", "birthMonth", "birthYear", "tempat");

            if(!post_validator($_POST, $required_post)){
                $data["info"] = "data required error!";
                echo json_encode($data);
                die();
            }
            $nip = $_POST["nip"];
            $golongan = $_POST["golongan"];
            $jabatan = $_POST["jabatan"];
            $unitKerja = $_POST["unitKerja"];
            $instansi = $_POST["instansi"];
            $tempat = $_POST["tempat"];
            $birthDay = $_POST["birthDay"];
            if(strlen($birthDay) == 1){
                $birthDay = "0{$birthDay}";
            }
            $birthMonth = $_POST["birthMonth"];
            $birthYear = $_POST["birthYear"];
            $birthDate = "{$birthYear}-{$birthMonth}-{$birthDay}";

            $user = $u->getUserByNIP($nip);
            if($user){
                $data["info"] = "NIP Already Used";
                echo json_encode($data);
                die();
            }

            $userTemp = $u_t->getUserTempByNip($nip);
            if($userTemp){
                $data["info"] = "NIP Already Used";
                echo json_encode($data);
                die();
            }


        }

        if($type != 5 && $type != 4){
            $data["info"] = "Error ocured";
            echo json_encode($data);
            die();
        }

        Email::sendVerificationCode($email, $username, $verificationCode);
        $image = file_validator($_FILES["profileImage"], $username, array('jpg', 'jpeg', 'png'));
        if(!$image){
            $imageName = "default.jpg";
        }else{
            $imageName = $image;
            if(!move_file($imageName, $_FILES["profileImage"]["tmp_name"], "images")){
                $imageName = "default.jpg";
            }
        }


        if($u_t->signUp($username, $password, $email, $firstName, $lastName, $hp, $gender, $imageName, $type, $nip, $golongan, $jabatan, $unitKerja, $instansi, $birthDate, $tempat, $verificationCode)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        $data["info"] = "failed";
            echo json_encode($data);
            die();

    }else if(isset($_POST["activation"])){

        $required = array("username", "verificationCode");
        if(!post_validator($_POST, $required)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $username = $_POST["username"];
        $verificationCode = $_POST["verificationCode"];
        $u_t = new UserTemp();
        $u = new User();
        $u_g = new UsersGlobalAddition();

        $userTemp = $u_t->getUserTemp($username);
        if(!$userTemp){
            $data["info"] = "Verification Code Expired / Unrecognized";
            echo json_encode($data);
            die();
        }

        if($u->getUser($userTemp["username"])){
            $data["info"] = "User Already Registered";
            echo json_encode($data);
            die();
        }

        if($userTemp["verificationCode"] != $verificationCode){
            $data["info"] = "Verification Failed";
            echo json_encode($data);
            die();
        }

        if(!$u->insertUser($userTemp["username"], $userTemp["password"], $userTemp["email"], $userTemp["firstName"], $userTemp["lastName"], $userTemp["gender"], $userTemp["image"], $userTemp["type"], $userTemp["hp"])){
            $data["info"] = "Internal Error DB:Insert/:69:69-69";
            echo json_encode($data);
            die();
        }

        if($userTemp["type"] == 4){
            if(!$u_g->addUser($userTemp["nip"], $userTemp["username"], $userTemp["golongan"], $userTemp["jabatan"], $userTemp["unitKerja"], $userTemp["instansi"], "000000000", $userTemp["tempat"], $userTemp["birthDate"])){
                $data["info"] = $data["info"] = "Internal Error DB:Insert P2/:69:69-69";
                echo json_encode($data);
                die();
            }
            
        }
        $u_t->deleteUserTemp($userTemp["username"]);
        $data["info"] = "success";
        echo json_encode($data);
        die();

    }else if(isset($_POST["addUser"])){

        $required = array("username", "password", "email", "firstName", "lastName", "gender", "hp");
        
        $t = post_check($_POST, $required, 4);
        if($t != "success"){
            $data["info"] = $t;
            echo json_encode($data);
            die();
        }
        $user = new User();
        if(!$user->isSuperAdmin($_SESSION["username"])){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }

        if(!isset($_SESSION["super_admin_t_1"])){
            $data["info"] = "not allowed!~!~!~!";
            echo json_encode($data);
            die();
        }


        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $gender = $_POST["gender"];
        $hp = $_POST["hp"];

        if($user->isExists($username)){
            $data["info"] = "username already exists";
            echo json_encode($data);
            die();
        }

        $image = file_validator($_FILES["profileImage"], $username, array('jpg', 'jpeg', 'png'));
        if(!$image){
            $imageName = "default.jpg";
        }else{
            $imageName = $image;
            if(!move_file($imageName, $_FILES["profileImage"]["tmp_name"], "images")){
                $imageName = "default.jpg";
            }
        }


        if(strtolower($_POST["addUser"]) == "admin"){
            $response = $user->insertUser($username, $password, $email, $firstName, $lastName, $gender, $imageName, 2, $hp);
        }else if(strtolower($_POST["addUser"]) == "petugas"){
            $response = $user->insertUser($username, $password, $email, $firstName, $lastName, $gender, $imageName, 3, $hp);
        }else if(strtolower($_POST["addUser"]) == "umum"){
            $response = $user->insertUser($username, $password, $email, $firstName, $lastName, $gender, $imageName, 5, $hp);
        }else if(strtolower($_POST["addUser"]) == "pegawai"){
            $required = array("birthMonth", "birthDay", "birthYear", "nip", "golongan", "jabatan", "unitKerja", "instansi", "tempat");
            if(!post_validator($_POST, $required)){
                $data["info"] = "data required error!";
                echo json_encode($data);
                die();
            }
            $birthDay = $_POST["birthDay"];
            if(strlen($birthDay) == 1){
                $birthDay = "0{$birthDay}";
            }
            $birthMonth = $_POST["birthMonth"];
            $birthYear = $_POST["birthYear"];

            $nip = $_POST["nip"];
            $golongan = $_POST["golongan"];
            $jabatan = $_POST["jabatan"];
            $unitKerja = $_POST["unitKerja"];
            $instansi = $_POST["instansi"];
            $tempat = $_POST["tempat"];
            $birthDate = "{$birthYear}-{$birthMonth}-{$birthDay}";

            $response = $user->insertUser($username, $password, $email, $firstName, $lastName, $gender, $imageName, 4, $hp);
            if($response == 1){
                $response2 = $user->insertDataPegawai($username, $nip, $golongan, $jabatan, $unitKerja, $instansi, $birthDate, $tempat);
                if($response2 == 1){
                    $data["info"] = "success";
                    echo json_encode($data);
                    die();
                }
            }else{
                $data["info"] = "die yo!";
                echo json_encode($data);
                die();
            }

        }else{
            $data["info"] = "die";
            echo json_encode($data);
            die();
        }

        if($response == 1){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }else{
            $data["info"] = "failed insert to DB";
            echo json_encode($data);
            die();
        }

    }else if(isset($_POST["editUser"])){
        if(isset($_POST["editProfile"])){

            $required = array("username", "nip", "golongan", "jabatan", "unitKerja", "instansi", "tempat");

            if(!post_validator($_POST, $required)){
                $data["info"] = "data required error!";
                echo json_encode($data);
                die();
            }
            $birthDay = $_POST["birthDay"];
            if(strlen($birthDay) == 1){
                $birthDay = "0{$birthDay}";
            }

            $user = new User();

            $birthMonth = $_POST["birthMonth"];
            $birthYear = $_POST["birthYear"];
            if($_POST["editUser"] == "self"){
                $username = $_SESSION["username"];
            }else{
                $username = $_POST["username"];
            }


            $nip = $_POST["nip"];
            $golongan = $_POST["golongan"];
            $jabatan = $_POST["jabatan"];
            $unitKerja = $_POST["unitKerja"];
            $instansi = $_POST["instansi"];
            $tempat = $_POST["tempat"];
            $birthDate = "{$birthYear}-{$birthMonth}-{$birthDay}";
            if(!$user->isExists($username)){
                $data["info"] = "username does not exist!";
                echo json_encode($data);
                die();
            }

            if(!username_validator($username)){
                $data["info"] = "Format Username salah!";
                echo json_encode($data);
                die();
            }

            if($_POST["editProfile"] != "setting"){
                $update = $user->updateDataPegawai($username, $nip, $golongan, $jabatan, $unitKerja, $instansi, $birthDate, $tempat);
                if($update==1){
                    $data["info"] = "success";
                    echo json_encode($data);
                    die();
                }
                $data["info"] = "error update pegawai!";
                echo json_encode($data);
                die();
            }

        }
        $data = array();
        $required = array("username", "password", "email", "firstName", "lastName", "gender", "hp");
        $t = post_check($_POST, $required, 4);
        if($t != "success"){
            $data["info"] = $t;
            echo json_encode($data);
            die();
        }
        $userID = $_POST["userID"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $gender = $_POST["gender"];
        $hp = $_POST["hp"];
        $user = new User();
        $oldImage = $user->getImageByID($userID);

        if($user->isExists($username) && $username != $user->getUsernameByID($userID)){
            $data["info"] = "username already exists";
                echo json_encode($data);
                die();
        }

        $image = file_validator($_FILES["profileImage"], $username, array('jpg', 'jpeg', 'png'));
        if(!$image){
            $image = $oldImage;
        }

        if($image != $oldImage){
            if(!move_file($image, $_FILES["profileImage"]["tmp_name"], "images")){
                $image = $oldImage;
            }else{
                delete_image_file($oldImage);
            }
        }
        $success = $user->updateUser($userID, $username, $password, $email, $firstName, $lastName, $gender, $image, $hp);

        if($success == 1){
            if(isset($_POST["editProfile"])){
                $update = $user->updateDataPegawai($username, $nip, $golongan, $jabatan, $unitKerja, $instansi, $birthDate, $tempat);
                if($update != 1){
                    $data["info"] = "failed update profile";
                    echo json_encode($data);
                    die();
                }
            }
            if($_POST["editUser"] == "self"){
                $_SESSION['username'] = $username;
                $_SESSION['image'] = $image;
                $_SESSION["name"] = $_POST["firstName"]." ".$_POST["lastName"];

            }
            $data["info"] = "success";
            echo json_encode($data);
            die();

        }else{
            $data["info"] = "failed insert to DB";
            echo json_encode($data);
            die();
        }

    }else if(isset($_POST["deleteUser"])){
        $userID = $_POST["userID"];
        $request = new User();
        $imageName = $request->getImageByID($userID);
        if(!$request->isSuperAdmin($_SESSION["username"])){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }
        if(delete_image_file($imageName)){
            $success = $request->deleteUser($userID);
            if($success == 1){
                $data["info"] = "success";
                echo json_encode($data);
                die();
            }else{
                $data["info"] = "failed to delete row DB";
                echo json_encode($data);
                die();
            }
        }else{
            $data["info"] = "failed to delete old image";
            echo json_encode($data);
            die();
        }

    }else if(isset($_POST["getUser"], $_POST["username"])){
        $type = $_POST["getUser"];
        echo json_encode(User::getUser($_POST["username"]));

    }else if(isset($_POST["userList"])){
        $type = $_POST["userList"];
        if($type == 4){
            echo json_encode(User::getPegawaiList($type));
            die();
        }else{
            echo json_encode(User::getUserList($type));
            die();
        }

    }else if(isset($_POST["profile"])){
        $username = $_POST["profile"];
        $user = new User();
        if(!$user->isExists($username)){
            $data["info"] = "user not found!";
            echo json_encode($data);
            die();
        }
        $profile = $user->getUser($username);
            $qna = new Qna();
            $clock = new Clock($profile["username"], 0, "");
            $profile["readList"] = $clock->getReadList();
            $profile["totalSubjectRead"] = sizeof($profile["readList"]);
            $profile["timeInSecond"] = 0;
            for($j=0; $j<sizeof($profile["readList"]); $j++){
                $profile["readList"][$j] = array_merge(
                    $profile["readList"][$j],
                    Subject::getSubjectByID($profile["readList"][$j]["subjectID"])
                );

                $profile["timeInSecond"] += $profile["readList"][$j]["timeInSecond"];
            }
            $profile["timeRead"] = time_formatting($profile["timeInSecond"]);
            $profile["totalQuestion"] = sizeof($qna->questionList($username));

            // foreach ($profile[$i]["readList"] as $subject) {
            //     $profile[$i]["timeInSecond"] += $subject["timeInSecond"];
            // }
        echo json_encode($profile);
        die();

    }else if(isset($_POST["getUserGlobal"])){
        $username = $_POST["getUserGlobal"];
        $u = new User();
        $user = $u->getUserGlobal($username);

        $u_g = new UsersGlobalAddition();
        $userGlobal = $u_g->getUser($username);
        $birthDate = explode("-", $userGlobal["birthDate"]);
        $userGlobal["birthDay"] = $birthDate[2];
        $userGlobal["birthMonth"] = $birthDate[1];
        $userGlobal["birthYear"] = $birthDate[0];
        $user = array_merge($user, $userGlobal);
        echo json_encode($user);
        die();

    }else if(isset($_POST["list_profile_pg_4"])){
        $username = $_POST["list_profile_pg_4"];
        $user = new User();
        if(!$user->isSuperAdmin($username) && !$user->isAdmin($username)){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }
        $certificate = new Certificate();
        $profile = $user->getPegawaiList(4);
        for($i=0; $i<sizeof($profile); $i++){
            $clock = new Clock($profile[$i]["username"], 0, "");
            $qna = new Qna();
            $profile[$i]["readList"] = $clock->getReadList();
            $profile[$i]["totalSubjectRead"] = sizeof($profile[$i]["readList"]);
            $profile[$i]["timeInSecond"] = 0;
            for($j=0; $j<sizeof($profile[$i]["readList"]); $j++){
                $profile[$i]["readList"][$j] = array_merge(
                    $profile[$i]["readList"][$j],
                    Subject::getSubject($profile[$i]["readList"][$j]["subjectID"])
                );

                $profile[$i]["timeInSecond"] += $profile[$i]["readList"][$j]["timeInSecond"];
            }
            $profile[$i]["timeRead"] = time_formatting($profile[$i]["timeInSecond"]);
            $profile[$i]["totalQuestion"] = sizeof($qna->questionList($profile[$i]["username"]));

            if($certificate->isExists($profile[$i]["username"])){
                if((int)substr($profile[$i]["timeRead"], 0, 2) < 15){
                    $certificate->deleteCertificate($profile[$i]["username"]);
                    $profile[$i]["certificate"] = false;
                    $profile[$i]["totalSent"] = 0;
                }else{
                    $userCertificate = $certificate->getCertificateByUsername($profile[$i]["username"]);
                    $profile[$i]["certificate"] = true;
                    $profile[$i]["certificateID"] = $userCertificate["certificateID"];
                    $profile[$i]["code"] = $userCertificate["code"];
                    $profile[$i]["totalSent"] = $userCertificate["totalSent"];
                    $profile[$i]["certificateHistory"] = $certificate->getTransactionByUsername($profile[$i]["certificateID"]);
                }
            }else{
                if((int)substr($profile[$i]["timeRead"], 0, 2) > 14){
                    $certificate->insertSertificate($profile[$i]["username"]);
                    $profile[$i]["certificate"] = true;
                    $profile[$i]["totalSent"] = 0;
                    $profile[$i]["certificateHistory"] = $certificate->getTransactionByUsername($profile[$i]["username"]);
                }else{
                    $profile[$i]["certificate"] = false;
                    $profile[$i]["totalSent"] = 0;
                }

            }


            // foreach ($profile[$i]["readList"] as $subject) {
            //     $profile[$i]["timeInSecond"] += $subject["timeInSecond"];
            // }
        }
        $profileList = array();
        foreach ($profile as $p) {
            $profileList[$p["username"]] = $p;
        }

        echo json_encode($profileList);
        die();

    }else{
        $data["info"] = "Not Recognized";
        echo json_encode($data);
        die();
    }
}
