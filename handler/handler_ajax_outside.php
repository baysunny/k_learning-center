<?php

session_start();

include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/email.php";

if (!get_magic_quotes_gpc()){
    if(isset($_POST["sign-up"], $_POST["user"])){
        if(isset($_SESSION["username"])){
            $data["info"] = "Log out needed";
            echo json_encode($data);
            die();
        }
        $required = array(
            "username",
            "password",
            "email",
            "firstName",
            "lastName",
            "hp",
            "gender",
            "nip",
            "golongan",
            "jabatan",
            "unitKerja",
            "instansi",
            "tempat",
            "birthDay",
            "birthMonth",
            "birthYear"
        );

        $t = post_check($_POST, array_slice($required, 0, 7), 6);
        if($t != "success"){
            $data["info"] = $t;
            echo json_encode($data);
            die();
        }
        $auth = new Authentication();
        $code = $auth->getVerificationCodeByUsername($_POST["username"]);
        if(isset($_POST["resend-email"])){
            Email::sendVerificationCode($_POST["email"], $_POST["username"], $code);
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        if($auth->isExists($_POST["username"])){
            $data["info"] = "Username sudah digunakan";
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
        
        $image = file_validator($_FILES["profileImage"], $username, array('jpg', 'jpeg', 'png'));
        if(!$image){
            $imageName = "default.jpg";
        }else{
            $imageName = $image;
            if(!move_file($imageName, $_FILES["profileImage"]["tmp_name"], "images")){
                $imageName = "default.jpg";
            }
        }

        if($_POST["user"] == "pg4"){
            if(!post_validator($_POST, array_slice($required, 7))){
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
            $birthDate = "{$birthYear}-{$birthMonth}-{$birthDay}";

            $nip = $_POST["nip"];
            $golongan = $_POST["golongan"];
            $jabatan = $_POST["jabatan"];
            $unitKerja = $_POST["unitKerja"];
            $instansi = $_POST["instansi"];
            $tempat = $_POST["tempat"];
            $result = $auth->sign_up_4($username, $password, $email, $firstName, $lastName, $gender, $imageName, $hp, $nip, $golongan, $jabatan, $unitKerja, $instansi, $birthDate, $tempat);
            if($result == 1){
                $data["info"] = "success";
                $codeVerification = $auth->getVerificationCode();
                Email::sendVerificationCode($email, $username, $codeVerification);
                echo json_encode($data);
                die();
            }
            $data["info"] = $result;
            echo json_encode($data);
            die();
        }
        $result = $auth->sign_up_5($username, $password, $email, $firstName, $lastName, $gender, $imageName, $hp);
        if($result == 1){
            $codeVerification = $auth->getVerificationCode();
                Email::sendVerificationCode($email, $username, $codeVerification);
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        $data["info"] = "failed";
        echo json_encode($data);
        die();
    }else if(isset($_POST["activation"], $_POST["code"])){
        $user = new Authentication();
        if(!post_validator($_POST, array("activation", "code"))){
            $data["info"] = "Error Empty";
            echo json_encode($data);
            die();
        }

        if($user->activation($_POST["activation"], $_POST["code"]
                )){
            $userData = $user->getUser($_POST["activation"]);
            $insertGlobal = $user->insertUser(
                $userData["username"],
                $userData["password"],
                $userData["email"],
                $userData["firstName"],
                $userData["lastName"],
                $userData["gender"],
                $userData["image"],
                $userData["type"],
                $userData["hp"]
            );

            if($insertGlobal == 1){
                if($userData["type"] == 4){
                    $insertMember = $user->insertDataPegawai(
                        $userData["username"],
                        $userData["nip"],
                        $userData["golongan"],
                        $userData["jabatan"],
                        $userData["unitKerja"],
                        $userData["instansi"],
                        $userData["birthDate"],
                        $userData["tempat"]
                    );
                    if($insertMember != 1){
                        $data["info"] = "failed insert global data";
                        echo json_encode($data);
                        die();
                    }
                }
                $updateTemporaryData = $user->updateTempData($userData["username"]);
                if($updateTemporaryData == 1){
                    $data["info"] = "success";
                    echo json_encode($data);
                    die();
                }
                $data["info"] = "failed";
                echo json_encode($data);
                die();
            }

            $data["info"] = "failed insert main data";
            echo json_encode($data);
            die();
        }else{
            $data["info"] = "Not available";
            echo json_encode($data);
            die();
        }
        $data["info"] = "Fatal Error";
        echo json_encode($data);
        die();
    }else if(isset($_POST["resetPassword"])){
        $required = array("username");

        $t = post_check($_POST, $required, 6);
        if($t != "success"){
            $data["info"] = $t;
            echo json_encode($data);
            die();
        }

        $user = new Authentication();
        $data = $user->resetPassword($_POST["username"]);
        if(sizeof($data) == 0){
            $data["info"] = "user not found!";
            echo json_encode($data);
            die();
        }

        Email::sendmail(
            $data["email"],
            "ini passwordmu : ".$data["password"]
        );
        $data["info"] = "success";
        echo json_encode($data);
        die();
    }else if(isset($_POST["hello"])){
        $required = array("hello", "username", "password");
        if(!post_validator($_POST, array_slice($required, 7))){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }
        $user = new User();
        $username = $_POST["username"];
        $password = $_POST["password"];
        $auth = $user->lock_authentication($username, $password);
        if($auth != "failed"){
            $data["info"] = "success";
            $data["status"] = $auth."ed";
            echo json_encode($data);
            die();
        }
        $data["info"] = "authentication failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["world"])){
        $user = new User();
        $data["info"] = $user->LOCK();
        echo json_encode($data);
        die();

    }else{
        $data["info"] = "who are you?";
        echo json_encode($data);
        die();
    }
}else{
    $data["info"] = "who are you??";
    echo json_encode($data);
    die();
}
