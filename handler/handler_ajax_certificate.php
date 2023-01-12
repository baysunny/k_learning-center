<?php


session_start();

include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/certificate/fpdf.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/certificate.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/email.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/course.php";

if (!get_magic_quotes_gpc()){
    if(isset($_POST["check-certificate"])){
        $certificateCode = $_POST["check-certificate"];
        $user = new User();
        $certificate = new Certificate();
        if(!$certificate->isExistsByCode($certificateCode)){
            $data["info"] = "Tidakkkkk";
            echo json_encode($data);
            die();
        }
        $myCertificate = $certificate->getCertificateByCode($certificateCode);
        $username = $myCertificate["username"];
        $profile = $user->getUser($username);
        $qna = new QnA();
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
        $profile["certificateCode"] = $certificateCode;
        $profile["birthDate"] = date_formating($profile["birthDate"]);
        $profile["timeRead"] = time_formatting($profile["timeInSecond"]);
        $profile["totalQuestion"] = sizeof($qna->questionList($username));
        $profile["info"] = "success";
        echo json_encode($profile);
        die();

    }else if(isset($_POST["display-certificate"])){
        $required = array("username", "code");
        if(!post_validator($_POST, $required)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }
        $username = $_POST["username"];
        $code = $_POST["code"];
        $user = new User();
        $certificate = new Certificate();
        if(!$user->isExists($username)){
            $data["info"] = "user not found!";
            echo json_encode($data);
            die();
        }

        if(!$certificate->isExists($username)){
            $data["info"] = "Certificate doesn't exists!";
            echo json_encode($data);
            die();
        }

        $myCertificate = $certificate->getCertificate($username, $code);
        $myCertificate = array_merge($user->getUser($username), $myCertificate);
        if(strlen($myCertificate["username"]) == 0){
            $data["info"] = "data error";
            echo json_encode($data);
            die();
        }

        echo json_encode($myCertificate);
        die();

    }else if(isset($_POST["get-certificate"])){
        $required = array("username", "code");
        if(!post_validator($_POST, $required)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }
        $username = $_POST["username"];
        $code = $_POST["code"];
        $user = new User();
        $certificate = new Certificate();
        if(!$user->isExists($username)){
            $data["info"] = "user not found!";
            echo json_encode($data);
            die();
        }

        if(!$certificate->isExists($username)){
            $data["info"] = "Certificate doesn't exists!";
            echo json_encode($data);
            die();
        }

        $myCertificate = $certificate->getCertificate($username, $code);
        $myCertificate = array_merge($user->getUser($username), $myCertificate);
        if(strlen($myCertificate["username"]) == 0){
            $data["info"] = "data error";
            echo json_encode($data);
            die();
        }

        echo json_encode($myCertificate);
        die();

    }else if(isset($_POST["gen-certificate"])){

    }else if(isset($_POST["send-certificate"])){
        $required = array("username", "sentBy");
        if(!post_validator($_POST, $required)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        if($_SESSION["username"] != $_POST["sentBy"]){
            $data["info"] = "admin data required error!";
            echo json_encode($data);
            die();
        }
        $user = new User();
        $username = $_POST["username"];
        $certificate = new Certificate();
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            $data["info"] = "code error! not allowed!";
            echo json_encode($data);
            die();
        }
        if(!$certificate->isExists($username)){
            $data["info"] = "Certificate doesn't exists!";
            echo json_encode($data);
            die();
        }
        $target = $user->getUser($_POST["username"]);
        $targetCertificate = $certificate->getCertificateByUsername($username);

        $sentBy = $_POST["sentBy"];
        $result = $certificate->insertTransaction($targetCertificate["certificateID"], $sentBy);

        if($result == 1){
            Email::sendCertificate(
                $target["email"],
                $target["username"],
                $targetCertificate["code"]
            );
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["template-certificate"])){
        $template = new TextCertificate();
        echo json_encode($template->getText());
        die();

    }else if(isset($_POST["template-certificate-edit"])){
        $required = array("trashID", "x1", "x2", "x3", "x4", "x5", "x6", "x7", "x8", "x9");
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

        $text = new TextCertificate();
        $imageName = "userE-0690-".date("y.m.d.h.i.s");
        $oldImage = $text->getText($_POST["trashID"])["backgroundImage"];
        $backgroundImage = file_validator($_FILES["backgroundImage"], $imageName, array('jpg', 'jpeg', 'png'));
        if(!$backgroundImage){
            $backgroundImage = $oldImage;
        }

        if($backgroundImage != $oldImage){
            if(!move_file($backgroundImage, $_FILES["backgroundImage"]["tmp_name"], "images")){
                $backgroundImage = $oldImage;
            }
        }


        if($text->editText($_POST["x1"], $_POST["x2"], $_POST["x3"], $_POST["x4"], $_POST["x5"], $_POST["x6"], $_POST["x7"], $_POST["x8"], $_POST["x9"], $backgroundImage)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();
    }else{
        $data["info"] = "certificate not recognized";
        echo json_encode($data);
        die();
    }
}else{
    $data["info"] = "hu ar yu?2";
    echo json_encode($data);
    die();
}



