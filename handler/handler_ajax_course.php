<?php

session_start();


include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/course.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/email.php";


if (!get_magic_quotes_gpc()){
    if(isset($_POST["readSubject"])){

        $s = new Subject();
        $co = new Course();
        $q = new Quest();
        $c = new Category();
        $m_s = new MShelter();
        $sh = new Shelter();

        $subjectID = $_POST["readSubject"];
        $subject = $s->getSubject($subjectID);

        if($subject){
            $subject["listQuest"] = $q->getQuestListBySubjectID($subject["subjectID"]);
            $subject["detail-course"] = $co->getCourse($subject["courseID"]);
            $subject["detail-category"] = $c->getCategory($subject["categoryID"]);
            $subject["detail-shelter"] = $sh->getShelter($subject["shelterID"]);
            $subject["detail-mshelter"] = $m_s->getMShelter($subject["detail-shelter"]["mshelterID"]);
            echo json_encode($subject);
            die();
        }

    }else if(isset($_POST["getSubject"])){
        $required_post = array("username", "getSubject");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!!!";
            echo json_encode($data);
            die();
        }

        $subjectID = $_POST["getSubject"];
        $username = $_POST["username"];

        $subjectO = new Subject();
        $courseO = new Course();
        $categoryO = new Category();
        $mshelterO = new MShelter();
        $shelterO = new Shelter();
        $historyReadO = new LogRead();

        $subject = $subjectO->getSubject($subjectID);

        if(!$subject){
            $data["info"] = "Subject Not Found";
            echo json_encode($data);
            die();
        }
        $data["subject"] = $subject;
        $data["subject"]["detail-course"] = $courseO->getCourse($subject["courseID"]);
        $data["subject"]["detail-category"] = $categoryO->getCategory($subject["categoryID"]);
        $data["subject"]["detail-shelter"] = $shelterO->getShelter($subject["shelterID"]);
        $data["subject"]["detail-mshelter"] = $mshelterO->getMShelter($data["subject"]["detail-shelter"]["mshelterID"]);
        $subjectPointToday = 0;
        
        $newHistoryRead = $historyReadO->getHistoryRead($username);
        foreach($newHistoryRead as $log){
            if($log["dateRead"] == date("Y-m-d") && $log["subjectID"] == $subjectID){
                $subjectPointToday += $log["counter"];
            }
        }

        $oldHistoryRead = $historyReadO->getOldHistoryRead($username);
        foreach($oldHistoryRead as $log){
            if($log["dateRead"] == date("Y-m-d") && $log["subjectID"] == $subjectID){
                $subjectPointToday += $log["counter"];
            }
        }

        $LogReadO = new LogRead();
        if($LogReadO->isTodayHitMaxPoint($username)){
            $data["isTodayHitMaxPoint"] = "True";
        }else{
            $data["isTodayHitMaxPoint"] = "False";
        }
        // $data["isTodayHitMaxPoint"] = $LogReadO->isTodayHitMaxPoint($username);

        if($subjectPointToday < $subject["point"]){
            $data["isSubjectTodayHitMaxPoint"] = "False";
        }else{
            $data["isSubjectTodayHitMaxPoint"] = "True";
        }
        $data["info"] = "success";
        echo json_encode($data);
        die();

    }else if(isset($_POST["addSubject"])){ // fixed 99%
        if(!isset($_SESSION["username"]) || !isset($_SESSION["code"])){
            $data["info"] = "Not logged in";
            echo json_encode($data);
            die();
        }

        $required_post = array("username", "title", "point", "thumbnailText", "category", "shelterID", "courseID");
        if(!isset($_FILES["thumbnailImage"]) || !isset($_FILES["subjectFile"])){
            $data["info"] = "file required error!";
            echo json_encode($data);
            die();
        }
        
        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }
        $subjectName = $_POST["title"];
        $point = $_POST["point"];
        $subjectFile = "";
        $subjectVideo = "";
        $thumbnailImage = "";
        $thumbnailText = $_POST["thumbnailText"];
        $shelterID = $_POST["shelterID"];
        $courseID = $_POST["courseID"];
        $categoryID = $_POST["category"];
        $uploadedBy = $_SESSION["username"];
        $code = $_SESSION["code"];

        if($uploadedBy != $_POST["username"] ){
            $data["info"] = "user not allowed to upload";
            echo json_encode($data);
            die();
        }

        $user = new User();
        if(!$user->isUserExists($uploadedBy)){
            $data["info"] = "User doesn't exist";
            echo json_encode($data);
            die();
        }
        if(!$user->isAdmin($uploadedBy) && !$user->isSuperAdmin($uploadedBy)){
            $data["info"] = "Usecd /
            cdr is not admin";
            echo json_encode($data);
            die();
        }
        if($code != "admin is cool you know"){
            $data["info"] = "Code error";
            echo json_encode($data);
            die();
        }

        if(!Course::isExists($courseID)){
            $data["info"] = "Kategori tidak ada/belum dibuat!";
            echo json_encode($data);
            die();
        }

        if($point==1){
            $point=0;
        }

        if($point!=0 && $point!=900 && $point!=1800 && $point!=2700){
            $data["info"] = "Point error";
            echo json_encode($data);
            die();
        }


        $formatFileName = date("y.m.d.h.i.s");

        $thumbnailImage = file_validator($_FILES["thumbnailImage"], $formatFileName, array('jpg', 'jpeg', 'png'));
        if(!$thumbnailImage){
            $data["info"] = "image error!";
            echo json_encode($data);
            die();
        }
        $subjectFile = file_validator($_FILES["subjectFile"], $formatFileName, array("pdf"));
        if(!$subjectFile){
            $data["info"] = "file error!";
            echo json_encode($data);
            die();
        }


        if(!move_file_course($thumbnailImage, $_FILES["thumbnailImage"]["tmp_name"], "thumbnail") ||
            !move_file_course($subjectFile, $_FILES["subjectFile"]["tmp_name"], "file")

        ){
            delete_file_course($thumbnailImage);
            delete_file_course($subjectFile);
            $data["info"] = "failed to move files";
            echo json_encode($data);
            die();
        }

        if(isset($_FILES["subjectVideo"])){
            $subjectVideo = file_validator($_FILES["subjectVideo"], $formatFileName, array("mp4", "3gp", "mov", "m4v"));
            if(!$subjectVideo){
                $videonameOrigin = "";
            }else{
                if(!move_file_course($subjectVideo, $_FILES["subjectVideo"]["tmp_name"], "video")){
                    $data["info"] = "error uploading video";
                    delete_file_course($thumbnailImage, "thumbnail");
                    delete_file_course($subjectFile, "file");
                    echo json_encode($data);
                    die();
                }else{
                    $videonameOrigin = $_FILES["subjectVideo"]["name"];
                }
            }
        }else{
            $videonameOrigin = "";

        }

        $filenameOrigin = $_FILES["subjectFile"]["name"];
        if(Subject::addSubject($subjectName, $point, $categoryID, $shelterID, $thumbnailImage, $thumbnailText, $subjectFile, $filenameOrigin, $subjectVideo, $videonameOrigin, $courseID, $uploadedBy) == 1){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        $data["info"] = "error";
        echo json_encode($data);
        die();

    }else if(isset($_POST["deleteSubject"])){
        if(!isset($_SESSION["username"])){
            $data["info"] = "Not logged in";
            echo json_encode($data);
            die();
        }
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            $data["info"] = "code error! not allowed!";
            echo json_encode($data);
            die();
        }
        $deleteBy = $_SESSION["username"];
        $subjectID = $_POST["deleteSubject"];

        $user = new User();
        if(!$user->isAdmin($deleteBy) && !$user->isSuperAdmin($deleteBy) || !Subject::isExists($subjectID)){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }

        $request = new Subject();
        $thumbnailImage = $request->getImageByID($subjectID);
        $filePdf = $request->getFileByID($subjectID);
        if(delete_image_file($thumbnailImage) && delete_subject_file($filePdf)){
            $success = $request->deleteSubject($subjectID);
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

    }else if(isset($_POST["editSubject"])){
        $required_post = array("editedBy", "code-edit", "subjectName", "point", "thumbnailText", "categoryID", "shelterID", "subjectID", "courseID");


        if(!isset($_FILES["thumbnailImage"]) || !isset($_FILES["subjectFile"]) || !isset($_FILES["subjectVideo"])){
            $data["info"] = "file required error!";
            echo json_encode($data);
            die();
        }

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $editedBy = $_POST["editedBy"];
        $adminCode = $_POST["code-edit"];
        $subjectID = $_POST["subjectID"];
        $subjectName = $_POST["subjectName"];
        $point = $_POST["point"];
        $thumbnailText = $_POST["thumbnailText"];
        $categoryID = $_POST["categoryID"];
        $focusCategoryID = 0;
        $shelterID = $_POST["shelterID"];


        $u = new User();
        $s = new Subject();
        $c = new Course();

        $u = new User();
        $ca = new Category();
        $sh = new Shelter();

        $user = $u->getUser($editedBy);
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

        if($point==1){
            $point = 0;
        }

        if($point!=0 && $point!=900 && $point!=1800 && $point!=2700){
            $data["info"] = "Point error";
            echo json_encode($data);
            die();
        }

        $subject = $s->getSubject($subjectID);
        if(!$subject){
            $data["info"] = "subject not found!";
            echo json_encode($data);
            die();
        }

        if(!$ca->getCategory($categoryID)){
            $data["info"] = "category not found";
            echo json_encode($data);
            die();
        }

        if(!$sh->getShelter($shelterID)){
            $data["info"] = "shelter not found";
            echo json_encode($data);
            die();
        }


        // if ($_FILES["fileToUpload"]["size"] > 10000000) {
        //     echo "Sorry, your file is too large.";
        //     $uploadOk = 0;
        // }


        $oldThumbnailImage = $subject["thumbnailImage"];
        $oldFile = $subject["filename"];
        $oldVideo = $subject["videoname"];

        $formatFileName = date("y.m.d.h.i.s");
        $thumbnailImage = file_validator($_FILES["thumbnailImage"], $formatFileName, array('jpg', 'jpeg', 'png'));
        if(!$thumbnailImage){
            $thumbnailImage = $oldThumbnailImage;
        }
        $subjectFile = file_validator($_FILES["subjectFile"], $formatFileName, array("pdf"));
        if(!$subjectFile){
            $subjectFile = $oldFile;
        }
        $subjectVideo = file_validator($_FILES["subjectVideo"], $formatFileName, array("mp4", "3gp", "mov", "m4v"));
        if(!$subjectVideo){
            $subjectVideo = $oldVideo;
        }
        /*
            if image is new :
                -> save new image to server
                   1 -> if save is failed -> back to old image
                   0 -> if save is success -> delete old image
        */

        if($thumbnailImage != $oldThumbnailImage){
            if(!move_file_course($thumbnailImage, $_FILES["thumbnailImage"]["tmp_name"], "thumbnail")){
                $thumbnailImage = $oldThumbnailImage;
            }else{
                delete_file_course($oldThumbnailImage, "thumbnail");
            }
        }
        if($subjectFile != $oldFile){
            if(!move_file_course($subjectFile, $_FILES["subjectFile"]["tmp_name"], "file")){
                $subjectFile = $oldFile;
            }else{
                delete_file_course($oldFile, "file");
            }
        }
        if($subjectVideo != $oldVideo){
            if(!move_file_course($subjectVideo, $_FILES["subjectVideo"]["tmp_name"], "video")){
                $subjectVideo = $oldVideo;
            }else{
                delete_file_course($subjectVideo, "video");
            }
        }

        // $data["info"] = "success!";
        // echo json_encode($data);
        // die();
        $result = $s->updateSubject($subjectID, $subjectName, $point, $categoryID, $shelterID, $focusCategoryID,
            $thumbnailImage, $thumbnailText, $subjectFile, $_FILES["subjectFile"]["name"], $subjectVideo, $_FILES["subjectVideo"]["name"]);
        if($result){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        $data["info"] = "failed to update";
        echo json_encode($data);
        die();

    }else if(isset($_POST["addCourse"])){
        if(!isset($_SESSION["username"])){
            $data["info"] = "Not logged in";
            echo json_encode($data);
            die();
        }
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            $data["info"] = "code error! not allowed!";
            echo json_encode($data);
            die();
        }
        $required_post = array("courseName", "username", "mcourseID");
        $mcourse = new MCourse();
        $mcourseID = $_POST["mcourseID"];
        if(!$mcourse->isExists($mcourseID)){
            $data["info"] = "Materi tidak ada/belum dibuat!";
            echo json_encode($data);
            die();
        }

        if(!isset($_FILES["thumbnailImage"])){
            $data["info"] = "file required error!";
            echo json_encode($data);
            die();
        }
        $type = "course-";
        $thumbnailImage = file_validator($_FILES["thumbnailImage"], $type, array('jpg', 'jpeg', 'png'));
        if(!$thumbnailImage){
            $data["info"] = "image error!";
            echo json_encode($data);
            die();
        }

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }
        $createdBy = $_POST["username"] == $_SESSION["username"] ? $_SESSION["username"] : "";
        $user = new User();
        if(!$user->isAdmin($createdBy) && !$user->isSuperAdmin($createdBy)){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }


        $courseName = $_POST["courseName"];
        if(strlen($createdBy) == 0){
            $data["info"] = "illegal user!";
            echo json_encode($data);
            die();
        }

        if(!move_file($thumbnailImage, $_FILES["thumbnailImage"]["tmp_name"], "images")){
            $data["info"] = "failed to move files";
            echo json_encode($data);
            die();
        }


        $course = new Course();
        if($course->addCourse($mcourseID, $courseName, $thumbnailImage, $createdBy) == 1){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        delete_image_file($thumbnailImage);
        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["getCourse"])){
        $c = new Course();
        $courseID = $_POST["getCourse"];
        $course = $c->getCourse($courseID);
        if(!$course){
            $data["info"] = "failed";
            $data["course"] = array();
            echo json_encode($data);
            die();
        }

        $data["info"] = "success";
        $data["course"] = $course;
        echo json_encode($data);
        die();

    }else if(isset($_POST["editCourse"])){
        $required = array("courseName", "editBy", "editCourse");
        $courseName = $_POST["courseName"];
        $courseID = $_POST["editCourse"];
        $editBy = $_POST["editBy"];

        if(!post_validator($_POST, $required)){
            $data["info"] = "data required error";
            echo json_encode($data);
            die();
        }
        if(!isset($_FILES["thumbnailImage"])){
            $data["info"] = "file required error!";
            echo json_encode($data);
            die();
        }

        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            $data["info"] = "code error! not allowed!";
            echo json_encode($data);
            die();
        }
        $user = new User();
        $course = new Course();
        if(!$user->isAdmin($editBy) && !$user->isSuperAdmin($editBy) || !$course->isExists($courseID)){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }

        $type = "course-";

        $thumbnailImage = file_validator($_FILES["thumbnailImage"], $type, array('jpg', 'jpeg', 'png'));
        $oldThumbnailImage = $course->getImageByID($courseID);
        if(!$thumbnailImage){
            $thumbnailImage = $oldThumbnailImage;
        }
        if($thumbnailImage != $oldThumbnailImage){
            if(!move_file($thumbnailImage, $_FILES["thumbnailImage"]["tmp_name"], "images")){
                $thumbnailImage = $oldThumbnailImage;
            }else{
                delete_image_file($oldThumbnailImage);
            }
        }
        $result = $course->updateCourse($courseID, $courseName, $thumbnailImage);
        if($result == 1){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["deleteCourse"])){

        if(!post_validator($_POST, array("deleteBy", "deleteCourse"))){
            $data["info"] = "data required error";
            echo json_encode($data);
            die();
        }
        $courseID = $_POST["deleteCourse"];
        $deleteBy = $_POST["deleteBy"];
        $course = new Course();
        $user = new User();
        if(!isset($_SESSION["username"])){
            $data["info"] = "Not logged in";
            echo json_encode($data);
            die();
        }
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            $data["info"] = "code error! not allowed!";
            echo json_encode($data);
            die();
        }

        if(!$user->isAdmin($deleteBy) && !$user->isSuperAdmin($deleteBy) || !$course->isExists($courseID)){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }

        if($course->deleteCourse($courseID) == 1){
            $thumbnail = $course->getImageByID($courseID);
            delete_image_file($thumbnail);


            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["getMcourse"])){
        $m_c = new MCourse();
        $mcourseID = $_POST["getMcourse"];
        $mainCourse = $m_c->getMCourse($mcourseID);

        if(!$mainCourse){
            $data["info"] = "failed";
            $data["mcourse"] = array();
            echo json_encode($data);
            die();
        }

        $data["info"] = "success";
        $data["mcourse"] = $mainCourse;
        echo json_encode($data);
        die();

    }else if(isset($_POST["addMCourse"])){
        if(!isset($_SESSION["username"])){
            $data["info"] = "Not logged in";
            echo json_encode($data);
            die();
        }
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            $data["info"] = "code error! not allowed!";
            echo json_encode($data);
            die();
        }
        $required_post = array("mcourseName", "username");
        $mcourse = new MCourse();

        if(!isset($_FILES["thumbnailImage"])){
            $data["info"] = "file required error!";
            echo json_encode($data);
            die();
        }
        $type = "mcourse-";
        $thumbnailImage = file_validator($_FILES["thumbnailImage"], $type, array('jpg', 'jpeg', 'png'));
        if(!$thumbnailImage){
            $data["info"] = "image error!";
            echo json_encode($data);
            die();
        }

        if(!post_validator($_POST, $required_post)){
            $data["info"] = $_POST;
            echo json_encode($data);
            die();
        }
        $createdBy = $_POST["username"] == $_SESSION["username"] ? $_SESSION["username"] : "";
        $user = new User();
        if(!$user->isAdmin($createdBy) && !$user->isSuperAdmin($createdBy)){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }


        $mcourseName = $_POST["mcourseName"];
        if(strlen($createdBy) == 0){
            $data["info"] = "illegal user!";
            echo json_encode($data);
            die();
        }

        if(!move_file($thumbnailImage, $_FILES["thumbnailImage"]["tmp_name"], "images")){
            $data["info"] = "failed to move files";
            echo json_encode($data);
            die();
        }

        if($mcourse->addMCourse($mcourseName, $thumbnailImage, $createdBy) == 1){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        delete_image_file($thumbnailImage);
        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["editMCourse"])){
        $required = array("mcourseName", "editBy", "editMCourse");
        $mcourseName = $_POST["mcourseName"];
        $mcourseID = $_POST["editMCourse"];
        $editBy = $_POST["editBy"];

        if(!post_validator($_POST, $required)){
            $data["info"] = "data required error";
            echo json_encode($data);
            die();
        }
        if(!isset($_FILES["thumbnailImage"])){
            $data["info"] = "file required error!";
            echo json_encode($data);
            die();
        }

        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            $data["info"] = "code error! not allowed!";
            echo json_encode($data);
            die();
        }
        $user = new User();
        $mcourse = new MCourse();
        if(!$user->isAdmin($editBy) && !$user->isSuperAdmin($editBy) || !$mcourse->isExists($mcourseID)){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }

        $type = "mcourse-";

        $thumbnailImage = file_validator($_FILES["thumbnailImage"], $type, array('jpg', 'jpeg', 'png'));
        $oldThumbnailImage = $mcourse->getImageByID($mcourseID);
        if(!$thumbnailImage){
            $thumbnailImage = $oldThumbnailImage;
        }
        if($thumbnailImage != $oldThumbnailImage){
            if(!move_file($thumbnailImage, $_FILES["thumbnailImage"]["tmp_name"], "images")){
                $thumbnailImage = $oldThumbnailImage;
            }else{
                delete_image_file($oldThumbnailImage);
            }
        }
        $result = $mcourse->updateMCourse($mcourseID, $mcourseName, $thumbnailImage);
        // $result = 1;
        if($result == 1){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["deleteMCourse"])){

        if(!post_validator($_POST, array("deleteBy", "deleteMCourse"))){
            $data["info"] = "data required error";
            echo json_encode($data);
            die();
        }
        $mcourseID = $_POST["deleteMCourse"];
        $deleteBy = $_POST["deleteBy"];
        $mcourse = new MCourse();
        $user = new User();
        if(!isset($_SESSION["username"])){
            $data["info"] = "Not logged in";
            echo json_encode($data);
            die();
        }
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            $data["info"] = "code error! not allowed!";
            echo json_encode($data);
            die();
        }

        if(!$user->isAdmin($deleteBy) && !$user->isSuperAdmin($deleteBy) || !$mcourse->isExists($mcourseID)){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }

        if($mcourse->deleteMCourse($mcourseID) == 1){
            $thumbnail = $mcourse->getImageByID($mcourseID);
            delete_image_file($thumbnail);


            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["mcourseList"])){
        echo json_encode(MCourse::getMCourseList());
        die();

    }else if(isset($_POST["courseList"])){

        $c = new Course();
        $u = new User();
        $courseList = $c->getCourseList($_POST["courseList"]);
        for($i=0; $i<sizeof($courseList); $i++){
            $user = $u->getUser($courseList[$i]["createdBy"]);
            $courseList[$i]["userImage"] = $user["image"];
        }


        echo json_encode($courseList);
        die();

    }else if(isset($_POST["subjectList"])){
        $courseID = $_POST["subjectList"];
        $co = new Course();
        $s = new Subject();
        $q = new Quest();
        $c = new Category();
        $m_s = new MShelter();
        $sh = new Shelter();
        // $fc = new FocusCategory();
        $listSubject = $s->getSubjectList($courseID);
        $data = array();

        foreach ($listSubject as $subject) {
            $subject["listQuest"] = $q->getQuestListBySubjectID($subject["subjectID"]);
            $subject["detail-course"] = $co->getCourse($courseID);
            $subject["detail-category"] = $c->getCategory($subject["categoryID"]);
            // $subject["detail-mshelter"] = $m_s->getMShelter($subject["mshelterID"]);
            $subject["detail-shelter"] = $sh->getShelter($subject["shelterID"]);
            $subject["detail-mshelter"] = $m_s->getMShelter($subject["detail-shelter"]["mshelterID"]);
            // $subject["detail-focusCategory"] = $fc->getFocusCategory($subject["focusCategoryID"]);
            array_push($data, $subject);

        }

        echo json_encode($data);
        die();

    }else if(isset($_POST["allSubject"])){
        $s = new Subject();
        echo json_encode($s->getAllSubject());
        die();

    }else if(isset($_POST["addQQuest"])){
        if(!isset($_SESSION["username"])){
            $data["info"] = "Not logged in";
            echo json_encode($data);
            die();
        }


        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            $data["info"] = "code error! not allowed!";
            echo json_encode($data);
            die();
        }
        $user = new User();
        if(!$user->isAdmin($_SESSION["username"]) && !$user->isSuperAdmin($_SESSION["username"])){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }

        $required_post = array("question", "subjectID", "optionA", "optionB", "optionC", "optionD");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        if(!isset($_POST["option"])){
            $data["info"] = "choose the right!";
            echo json_encode($data);
            die();
        }

        $trueAnswer = "";
        $optionA = $_POST["optionA"];
        $optionB = $_POST["optionB"];
        $optionC = $_POST["optionC"];
        $optionD = $_POST["optionD"];
        if(in_array("a", $_POST["option"])){
            $trueAnswer = $_POST["optionA"];
        }else if(in_array("b", $_POST["option"])){
            $trueAnswer = $_POST["optionB"];
        }else if(in_array("c", $_POST["option"])){
            $trueAnswer = $_POST["optionC"];
        }else if(in_array("d", $_POST["option"])){
            $trueAnswer = $_POST["optionD"];
        }

        if(strlen($trueAnswer) < 1){
            $data["info"] = "error";
            echo json_encode($data);
            die();
        }

        $question = $_POST["question"];
        $subjectID = $_POST["subjectID"];
        $createdBy = $_SESSION["username"];

        if(!$user->isAdmin($createdBy) && !$user->isSuperAdmin($createdBy)){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }

        if(!Subject::isExists($subjectID)){
            $data["info"] = "subject doesn't exists";
            echo json_encode($data);
            die();
        }

        $quest = new Quest();

        if($quest->addQuest($question, $subjectID, $createdBy, $optionA, $optionB, $optionC, $optionD, $trueAnswer) == 1){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        $data["info"] = "fatal error";
        echo json_encode($data);
        die();

    }else if(isset($_POST["addQAnswer"])){
        if(!isset($_SESSION["username"])){
            $data["info"] = "Not logged in";
            echo json_encode($data);
            die();
        }

        if(!isset($_SESSION["global_pg_4"])){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }

        if(!isset($_SESSION["code"])){
            $data["info"] = "code error";
            echo json_encode($data);
            die();
        }
        $required = array("username", "subjectID");
        if(!post_validator($_POST, $required)){
            $data["info"] = "data required error";
            echo json_encode($data);
            die();
        }


        $username = $_POST["username"] == $_SESSION["username"] ? $_SESSION["username"] : "";
        if(strlen($username) == 0 || !User::isExists($username)){
            $data["info"] = "illegal | user doesn't exists";
            echo json_encode($data);
            die();
        }

        $subjectID = $_POST["subjectID"];
        $subject = new Subject();


        if(!$subject->isExists($subjectID)){
            $data["info"] = "subject doesn't exists";
            echo json_encode($data);
            die();
        }

        $data["info"] = "";
        $quest = new Quest();
        $questList = $quest->getQuestListBySubjectID($_POST["subjectID"]);
        $point = 0;
        $pointID = $quest->generatePointID($username, $subjectID);
        if(strlen($pointID) < 9){
            $data["info"] = "failed generating point ID";
            echo json_encode($data);
            die();
        }

        $userAnswers = array();
        foreach ($questList as $q) {
            $questionID = $q["questionID"];
            $formName = "option-".$questionID;

            if(isset($_POST[$formName])){

                $trueAnswer = $q["trueAnswer"];
                $userAnswer = $_POST[$formName];
                $userAnswers[$questionID] = $userAnswer;
                if($quest->addAnswer($questionID, $pointID, $username, $userAnswer)){
                    if($trueAnswer == $userAnswer){
                        $point ++;
                    }
                }else{
                    $data["info"] = "error answer";
                    $quest->deleteAnswer($pointID);
                    echo json_encode($data);
                    die();
                }

            }else{
                $data["info"] = "jawablah semua pertanyaan";
                $quest->deleteAnswer($pointID);

                echo json_encode($data);
                die();
            }
        }

        if($quest->updatePoint($pointID, $username, $point)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }



        $data["info"] = "fatal error";
        echo json_encode($data);
        die();

    }else if(isset($_POST["getMyPoints"])){
        $quest = new Quest();
        $username = $_POST["getMyPoints"];
        echo json_encode($quest->getMyPoints($username, $_POST["subjectID"]));
        die();

    }else if(isset($_POST["editQuest"])){
        if(!isset($_SESSION["username"])){
            $data["info"] = "Not logged in";
            echo json_encode($data);
            die();
        }

        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            $data["info"] = "code error! not allowed!";
            echo json_encode($data);
            die();
        }
        $user = new User();
        if(!$user->isAdmin($_SESSION["username"]) && !$user->isSuperAdmin($_SESSION["username"])){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }

        $required_post = array("questionID", "question", "subjectID", "optionA", "optionB", "optionC", "optionD");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        if(!isset($_POST["option"])){
            $data["info"] = "choose the right!";
            echo json_encode($data);
            die();
        }

        $trueAnswer = "";
        $optionA = $_POST["optionA"];
        $optionB = $_POST["optionB"];
        $optionC = $_POST["optionC"];
        $optionD = $_POST["optionD"];
        if(in_array("a", $_POST["option"])){
            $trueAnswer = $_POST["optionA"];
        }else if(in_array("b", $_POST["option"])){
            $trueAnswer = $_POST["optionB"];
        }else if(in_array("c", $_POST["option"])){
            $trueAnswer = $_POST["optionC"];
        }else if(in_array("d", $_POST["option"])){
            $trueAnswer = $_POST["optionD"];
        }

        if(strlen($trueAnswer) < 1){
            $data["info"] = "error";
            echo json_encode($data);
            die();
        }
        $questionID = $_POST["questionID"];
        $question = $_POST["question"];
        $subjectID = $_POST["subjectID"];
        $createdBy = $_SESSION["username"];

        if(!$user->isAdmin($createdBy) && !$user->isSuperAdmin($createdBy)){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }

        if(!Subject::isExists($subjectID)){
            $data["info"] = "subject doesn't exists";
            echo json_encode($data);
            die();
        }

        $quest = new Quest();
        if($quest->editQuest($questionID, $question, $optionA, $optionB, $optionC, $optionD, $trueAnswer) == 1){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        $data["info"] = "fatal error";
        echo json_encode($data);
        die();

    }else if(isset($_POST["deleteQuest"])){
        if(!isset($_SESSION["username"])){
            $data["info"] = "Not logged in";
            echo json_encode($data);
            die();
        }

        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            $data["info"] = "code error! not allowed!";
            echo json_encode($data);
            die();
        }
        $user = new User();
        if(!$user->isAdmin($_SESSION["username"]) && !$user->isSuperAdmin($_SESSION["username"])){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }

        $required_post = array("questionID");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }


        $questionID = $_POST["questionID"];
        $deletedBy = $_SESSION["username"];

        if(!$user->isAdmin($deletedBy) && !$user->isSuperAdmin($deletedBy)){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }

        $quest = new Quest();
        if($quest->deleteQuest($questionID) == 1){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

    }else if(isset($_POST["getQuestList"])){
        $quest = new Quest();
        echo json_encode($quest->getQuestListBySubjectID($_POST["getQuestList"]));
        die();

    }

    else if(isset($_POST["addFocusCategory"])){ // done fix
        $required_post = array("addedBy", "code-add", "focusCategoryName");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $addedBy = $_POST["addedBy"];
        $adminCode = $_POST["code-add"];
        $focusCategoryName = $_POST["focusCategoryName"];

        $u = new User();
        $f_c = new FocusCategory();
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

        if($f_c->addFocusCategory($focusCategoryName, $addedBy)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        // delete_file_event($thumbnailImage, "thumbnail");

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["deleteFocusCategory"])){ // done fix
        $required_post = array("deletedBy", "code-delete", "focusCategoryID");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $deletedBy = $_POST["deletedBy"];
        $adminCode = $_POST["code-delete"];
        $focusCategoryID = $_POST["focusCategoryID"];

        $u = new User();
        $f_c = new FocusCategory();
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

        if(!$f_c->getFocusCategory($focusCategoryID)){
            $data["info"] = "focus category not found";
            echo json_encode($data);
            die();
        }

        if($f_c->deleteFocusCategory($focusCategoryID)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }


        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["editFocusCategory"])){

        $required_post = array("editedBy", "code-edit", "focusCategoryID", "focusCategoryName");
        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $editedBy = $_POST["editedBy"];
        $adminCode = $_POST["code-edit"];
        $focusCategoryID = $_POST["focusCategoryID"];
        $focusCategoryName = $_POST["focusCategoryName"];
        $showFocusCategory = 0;
        $u = new User();
        $fc = new FocusCategory();
        $user = $u->getUser($editedBy);
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

        if(isset($_POST["showFocusCategory"])){
            $showFocusCategory = $_POST["showFocusCategory"];
            if($showFocusCategory != 1){
                $data["info"] = "u make a mistake!";
                echo json_encode($data);
                die();
            }
        }
        if(!$fc->getFocusCategory($focusCategoryID)){
            $data["info"] = "focus category not found";
            echo json_encode($data);
            die();
        }



        if($fc->editFocusCategory($focusCategoryID, $focusCategoryName, $showFocusCategory)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "100% failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["getFocusCategoryList"])){
        $f_c = new FocusCategory();
        $s = new Subject();
        $focusCategoryList = $f_c->getFocusCategoryList();
        foreach ($focusCategoryList as $key => $value) {
            $focusCategoryList[$key]["subjectList"] = $s->getSubjectListByFocusCategory($value["focusCategoryID"]);
        }


        echo json_encode($focusCategoryList);


        die();

    }else if(isset($_POST["getFocusCategory"])){
        $focusCategoryID = $_POST["getFocusCategory"];
        $co = new Course();
        $s = new Subject();
        $q = new Quest();
        $c = new Category();
        $fc = new FocusCategory();

        $focusCategory = $fc->getfocusCategory($focusCategoryID);
        $focusCategory["subjectList"] = $s->getSubjectListByFocusCategory($focusCategoryID);
        foreach ($focusCategory["subjectList"] as $key => $value) {
            $focusCategory["subjectList"][$key]["detail-course"] = $co->getCourse($value["courseID"]);
            $focusCategory["subjectList"][$key]["detail-category"] = $c->getCategory($value["categoryID"]);
            $focusCategory["subjectList"][$key]["detail-focusCategory"] = $focusCategory;
            $focusCategory["subjectList"][$key]["listQuest"] = $q->getQuestListBySubjectID($value["subjectID"]);
        }
        // $courseID = $_POST["subjectList"];
        // $subject = new Subject();
        // $course = new Course();
        // $data["listSubject"] = $subject->getSubjectList($courseID);
        // $data["mcourseID"] = $course->getCourse($courseID)["mcourseID"];

        echo json_encode($focusCategory);
        die();

    }


    else if(isset($_POST["addMShelter"])){ // done fix
        $required_post = array("addedBy", "code-add", "mshelterName");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $addedBy = $_POST["addedBy"];
        $adminCode = $_POST["code-add"];
        $mshelterName = $_POST["mshelterName"];

        $u = new User();
        $m_s = new MShelter();
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

        if($m_s->addMShelter($mshelterName, $addedBy)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["getMShelterList"])){
        $m_s = new MShelter();


        echo json_encode($m_s->getMShelterList());
        die();

    }else if(isset($_POST["getMShelter"])){
        $m_s = new MShelter();
        $mshelterID = $_POST["getMShelter"];

        echo json_encode($m_s->getMShelter($mshelterID));
        die();

    }else if(isset($_POST["deleteMShelter"])){

        $required_post = array("deletedBy", "code-delete", "mshelterID");
        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $deletedBy = $_POST["deletedBy"];
        $adminCode = $_POST["code-delete"];
        $mshelterID = $_POST["mshelterID"];

        $u = new User();
        $m_s = new MShelter();
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

        if(!$m_s->getMShelter($mshelterID)){
            $data["info"] = "mshelter not found";
            echo json_encode($data);
            die();
        }

        if($m_s->deleteMShelter($mshelterID)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["editMShelter"])){

        $required_post = array("editedBy", "code-edit", "mshelterID", "mshelterName");
        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $editedBy = $_POST["editedBy"];
        $adminCode = $_POST["code-edit"];
        $mshelterID = $_POST["mshelterID"];
        $mshelterName = $_POST["mshelterName"];
        $showMShelter = 0;
        $u = new User();
        $m_s = new MShelter();
        $user = $u->getUser($editedBy);
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

        if(isset($_POST["showMShelter"])){
            $showMShelter = $_POST["showMShelter"];
            if($showMShelter != 1){
                $data["info"] = "u make a mistake!";
                echo json_encode($data);
                die();
            }
        }

        if(!$m_s->getMShelter($mshelterID)){
            $data["info"] = "mshelter not found";
            echo json_encode($data);
            die();
        }

        if($m_s->editMShelter($mshelterID, $mshelterName, $showMShelter)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }


        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }


    else if(isset($_POST["addShelter"])){ // done fix
        $required_post = array("addedBy", "mshelterID", "code-add", "shelterName");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $addedBy = $_POST["addedBy"];
        $adminCode = $_POST["code-add"];
        $shelterName = $_POST["shelterName"];
        $mshelterID = $_POST["mshelterID"];

        $u = new User();
        $m_s = new MShelter();
        $sh = new Shelter();
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

        $mshelter = $m_s->getMShelter($mshelterID);
        if(!$mshelter){
            $data["info"] = "mshelter not found";
            echo json_encode($data);
            die();
        }


        if($sh->addShelter($shelterName, $mshelter["mshelterID"], $addedBy)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["getShelterList"])){
        $s = new Subject();
        $sh = new Shelter();

        $co = new Course();
        $q = new Quest();
        $c = new Category();
        $m_s = new MShelter();

        $mshelterID = $_POST["getShelterList"];
        $shelterList = $sh->getShelterList($mshelterID);
        foreach ($shelterList as $key => $value) {
            $shelterList[$key]["listSubject"] = $s->getSubjectListByShelter($value["shelterID"]);
            $data = array();
            foreach ($shelterList[$key]["listSubject"] as $subject) {
                $subject["listQuest"] = $q->getQuestListBySubjectID($subject["subjectID"]);
                $subject["detail-course"] = $co->getCourse($subject["courseID"]);
                $subject["detail-category"] = $c->getCategory($subject["categoryID"]);
                // $subject["detail-mshelter"] = $m_s->getMShelter($subject["mshelterID"]);
                $subject["detail-shelter"] = $sh->getShelter($subject["shelterID"]);
                $subject["detail-mshelter"] = $m_s->getMShelter($subject["detail-shelter"]["mshelterID"]);
                // $subject["detail-focusCategory"] = $fc->getFocusCategory($subject["focusCategoryID"]);
                array_push($data, $subject);
            }
            $shelterList[$key]["listSubject"] = $data;
        }
        echo json_encode($shelterList);
        die();

    }else if(isset($_POST["getAllShelterList"])){
        $sh = new Shelter();
        $s = new Subject();
        $shelterList = $sh->getAllShelterList();
        foreach ($shelterList as $key => $value) {
            $shelterList[$key]["subjectList"] = $s->getSubjectListByShelter($value["shelterID"]);
        }

        echo json_encode($shelterList);
        die();

    }else if(isset($_POST["getShelter"])){
        $shelterID = $_POST["getShelter"];
        $co = new Course();
        $s = new Subject();
        $q = new Quest();
        $c = new Category();
        $m_s = new MShelter();
        $sh = new Shelter();

        $shelter = $sh->getShelter($shelterID);
        $shelter["listSubject"] = $s->getSubjectListByShelter($shelterID);
        foreach ($shelter["listSubject"] as $key => $value) {
            $shelter["listSubject"][$key]["detail-course"] = $co->getCourse($value["courseID"]);
            $shelter["listSubject"][$key]["detail-category"] = $c->getCategory($value["categoryID"]);
            $shelter["listSubject"][$key]["detail-mshelter"] = $m_s->getMShelter($shelter["mshelterID"]);
            $shelter["listSubject"][$key]["detail-shelter"] = $shelter;
            $shelter["listSubject"][$key]["listQuest"] = $q->getQuestListBySubjectID($value["subjectID"]);
        }


        echo json_encode($shelter);
        die();

    }else if(isset($_POST["deleteShelter"])){

        $required_post = array("deletedBy", "code-delete", "shelterID");
        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $deletedBy = $_POST["deletedBy"];
        $adminCode = $_POST["code-delete"];
        $shelterID = $_POST["shelterID"];

        $u = new User();
        $sh = new Shelter();
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

        if(!$sh->getShelter($shelterID)){
            $data["info"] = "shelter not found";
            echo json_encode($data);
            die();
        }

        if($sh->deleteShelter($shelterID)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "failed!";
        echo json_encode($data);
        die();

    }else if(isset($_POST["editShelter"])){

        $required_post = array("editedBy", "code-edit", "shelterID", "mshelterID", "shelterName");
        // $data["info"] = "data !";
        // echo json_encode($data);
        // die();
        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $editedBy = $_POST["editedBy"];
        $adminCode = $_POST["code-edit"];
        $shelterID = $_POST["shelterID"];
        $mshelterID = $_POST["mshelterID"];
        $shelterName = $_POST["shelterName"];
        $showShelter = 0;
        $u = new User();
        $sh = new Shelter();
        $m_s = new MShelter();
        $user = $u->getUser($editedBy);
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

        if(isset($_POST["showShelter"])){
            $showShelter = $_POST["showShelter"];
            if($showShelter != 1){
                $data["info"] = "u make a mistake!";
                echo json_encode($data);
                die();
            }
        }

        if(!$m_s->getMShelter($mshelterID)){
            $data["info"] = "mshelter not found";
            echo json_encode($data);
            die();
        }

        if(!$sh->getShelter($shelterID)){
            $data["info"] = "shelter not found";
            echo json_encode($data);
            die();
        }
        // $data["info"] = $showShelter;
        //     echo json_encode($data);
        //     die();

        if($sh->editShelter($shelterID, $shelterName, $showShelter)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }


        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }


    else if(isset($_POST["addCategory"])){ // done fix
        $required_post = array("addedBy", "code-add", "categoryName");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!!!";
            echo json_encode($data);
            die();
        }

        $addedBy = $_POST["addedBy"];
        $adminCode = $_POST["code-add"];
        $categoryName = $_POST["categoryName"];

        $u = new User();
        $c = new category();
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

        if($c->addCategory($categoryName, $addedBy)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "100% failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["deleteCategory"])){ // done fix
        $required_post = array("deletedBy", "code-delete", "categoryID");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $deletedBy = $_POST["deletedBy"];
        $adminCode = $_POST["code-delete"];
        $categoryID = $_POST["categoryID"];

        $u = new User();
        $f_c = new Category();
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

        if($f_c->deleteCategory($categoryID)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }


        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["editCategory"])){ // done fix

        $required_post = array("editedBy", "code-edit", "categoryID", "categoryName");
        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $editedBy = $_POST["editedBy"];
        $adminCode = $_POST["code-edit"];
        $categoryID = $_POST["categoryID"];
        $categoryName = $_POST["categoryName"];
        $showCategory = 0;
        $u = new User();
        $c = new Category();
        $user = $u->getUser($editedBy);
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

        if(isset($_POST["showCategory"])){
            $showCategory = $_POST["showCategory"];
            if($showCategory != 1){
                $data["info"] = "u make a mistake!";
                echo json_encode($data);
                die();
            }
        }

        if(!$c->getCategory($categoryID)){
            $data["info"] = "category not found";
            echo json_encode($data);
            die();
        }

        if($c->editCategory($categoryID, $categoryName, $showCategory)){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }

        $data["info"] = "100% failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["getCategoryList"])){ // done fix
        $c = new Category();
        $s = new Subject();
        $categoryList = $c->getCategoryList();
        foreach ($categoryList as $key => $value) {
            $categoryList[$key]["subjectList"] = $s->getSubjectListByCategory($value["categoryID"]);
        }


        echo json_encode($categoryList);
        die();

    }else if(isset($_POST["category"])){
        $categoryID = $_POST["category"];
        $co = new Course();
        $s = new Subject();
        $q = new Quest();
        $c = new Category();
        $sh = new Shelter();
        $m_s = new MShelter();

        $category = $c->getCategory($categoryID);
        $category["listSubject"] = $s->getSubjectListByCategory($categoryID);
        foreach ($category["listSubject"] as $key => $value) {
            $category["listSubject"][$key]["detail-course"] = $co->getCourse($value["courseID"]);
            $category["listSubject"][$key]["detail-category"] = $category;
            $category["listSubject"][$key]["detail-shelter"] = $sh->getShelter($value["shelterID"]);
            $category["listSubject"][$key]["detail-mshelter"] = $m_s->getMShelter($category["listSubject"][$key]["detail-shelter"]["mshelterID"]);
            $category["listSubject"][$key]["listQuest"] = $q->getQuestListBySubjectID($value["subjectID"]);
        }
        // $courseID = $_POST["subjectList"];
        // $subject = new Subject();
        // $course = new Course();
        // $data["listSubject"] = $subject->getSubjectList($courseID);
        // $data["mcourseID"] = $course->getCourse($courseID)["mcourseID"];

        echo json_encode($category);
        die();

    }




    else if(isset($_POST["loggingReading"])){
        $required_post = array("loggingReading", "username", "subjectID", "tabCode", "increment");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $username = $_POST["username"];
        $subjectID = $_POST["subjectID"];
        $tabCode = $_POST["tabCode"];
        $increment = $_POST["increment"];

        $userGlobalO = new UsersGlobalAddition();
        $subjectO = new Subject();
        

        $userGlobal = $userGlobalO->getUser($username);
        
        if(!$userGlobal){
            $data["info"] = $username;
            echo json_encode($data);
            die();
        }

        $subject = $subjectO->getSubject($subjectID);
        if(!$subject){
            $data["info"] = "subject not found!";
            echo json_encode($data);
            die();
        }

        // $logRead = new LogRead($username, $subjectID, $subject["point"], $tabCode); 
        $currentDate = date("Y-m-d");
        $currentTime = date("Y-m-d H:i:s");

        $logRead = new LogRead();
        $logRead->__init__($username, $subjectID, $subject["point"], $tabCode);
        $subjectToRead = $logRead->subjectIDtoRead;
        $increment = $_POST["increment"];
        $result = true;
        $data["isTodayHitMaxPoint"] = "True";
        if(!$logRead->isTodayPointMax){
            $data["isTodayHitMaxPoint"] = "False";
            if($logRead->isNewRead){
                $result = $logRead->startNewReading($subjectToRead, $increment, $currentTime, $currentTime, $tabCode);
            }else{
                $result = $logRead->continueReading($subjectToRead, $increment, $currentTime, $tabCode);
            }
        }

        if($logRead->myTodaySubjectPoint < $subject["point"]){
            $data["isTodayHitMaxPoint"] = "False";
        }else{
            $data["isTodayHitMaxPoint"] = "True";
        }
        if(!$result){
            $data["info"] = "Error reading/Tutup page membaca yang lain";
            echo json_encode($data);
            die();
        }
        $data["info"] = "success";
        echo json_encode($data);
        die();
        
    }else if(isset($_POST["getHistoryReadDates"])){
        
        $username = $_POST['getHistoryReadDates'];
        
        $user = new User();
        $historyRead = new LogRead();
        $subject = new Subject();

        if(!$user->getUser($username)){
            $data["info"] = "Not Found";
            echo json_encode($data);
            die();
        }


        $myHistoryRead = $historyRead->getHistoryRead($username);
        $readDates = array("getOldHistory");
        foreach($myHistoryRead as $log){
            if(!in_array($log["dateRead"], $readDates)){
                array_push($readDates, $log["dateRead"]);
            }
        }
        $today = date("Y-m-d");
        if(!in_array($today, $readDates)){
            array_unshift($readDates, $today);
        }

        echo json_encode($readDates);
        die();
        
    }else if(isset($_POST["getHistoryReadBySubject"])){
        $required_post = array("username", "getHistoryReadBySubject");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!!!";
            echo json_encode($data);
            die();
        }
        $username = $_POST["username"];
        $subjectID = $_POST["getHistoryReadBySubject"];
        $subjectPointToday = 0;


        $historyReadO = new LogRead();
        $subjectO = new Subject();
        $subject = $subjectO->getSubject($subjectID);
        $newHistoryRead = $historyReadO->getHistoryRead($username);
        foreach($newHistoryRead as $log){
            if($log["dateRead"] == date("Y-m-d") && $log["subjectID"] == $subjectID){
                $subjectPointToday += $log["counter"];
            }
        }

        $oldHistoryRead = $historyReadO->getOldHistoryRead($username);
        foreach($oldHistoryRead as $log){
            if($log["dateRead"] == date("Y-m-d") && $log["subjectID"] == $subjectID){
                $subjectPointToday += $log["counter"];
            }
        }

        $LogReadO = new LogRead();
        if($LogReadO->isTodayHitMaxPoint($username)){
            $data["isTodayHitMaxPoint"] = "True";
        }else{
            $data["isTodayHitMaxPoint"] = "False";
        }
        // $data["isTodayHitMaxPoint"] = $LogReadO->isTodayHitMaxPoint($username);

        if($subjectPointToday < $subject["point"]){
            $data["isSubjectTodayHitMaxPoint"] = "False";
        }else{
            $data["isSubjectTodayHitMaxPoint"] = "True";
        }

        echo json_encode($data);
        die();
        
    }else if(isset($_POST["getHistoryReadByDate"])){
        $required_post = array("username", "getHistoryReadByDate");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!!!";
            echo json_encode($data);
            die();
        }

        $date = $_POST["getHistoryReadByDate"];
        $username = $_POST['username'];
        
        $user = new User();
        $historyRead = new LogRead();
        $subject = new Subject();

        if(!$user->getUser($username)){
            $data["info"] = "Not Found";
            echo json_encode($data);
            die();
        }

        if($date == "getOldHistory"){
            $myHistoryRead = $historyRead->getOldHistoryRead($username);
            $sortByDate = array();
            $myPointCurrentDay = 0;
            foreach($myHistoryRead as $log){    
                $log["subject"] = $subject->getSubject($log["subjectID"]);
                $log["subject"]["pointInFormat"] = time_formatting($log["subject"]["point"]);
                $log["myPoint"] = $log["counter"];
                $myPointCurrentDay += $log["counter"];
                $log["timeInFormat"] = time_formatting($log["counter"]);
                array_push($sortByDate, $log);
            }
        }else{
            $myHistoryRead = $historyRead->getHistoryRead($username);
            $sortByDate = array();
            $myPointCurrentDay = 0;
            foreach($myHistoryRead as $log){
                if($log["dateRead"] == $date){
                    $log["subject"] = $subject->getSubject($log["subjectID"]);
                    $log["subject"]["pointInFormat"] = time_formatting($log["subject"]["point"]);
                    $log["myPoint"] = $log["counter"];
                    $myPointCurrentDay += $log["counter"];
                    $log["timeInFormat"] = time_formatting($log["counter"]);
                    array_push($sortByDate, $log);
                }
            }
        }


        $data["info"] = "success";
        $data["myPoint"] = $myPointCurrentDay;
        $data["myPointInFormat"] = time_formatting($myPointCurrentDay);
        $data["history"] = $sortByDate;
        echo json_encode($data);
        die();
        
    }else if(isset($_POST["getTotalReadingTime"])){

        $username = $_POST["getTotalReadingTime"];
        $user = new User();
        $historyRead = new LogRead();
        $subject = new Subject();
        $data["totalReadingTime"] = 0;
        $data["allSubjectRead"] = array();
        if(!$user->getUser($username)){
            $data["info"] = "Not Found";
            echo json_encode($data);
            die();
        }

        $myHistoryRead = $historyRead->getOldHistoryRead($username);
        foreach($myHistoryRead as $log){
            if(!in_array($log["subjectID"], $data["allSubjectRead"])){
                array_push($data["allSubjectRead"], $log["subjectID"]);
            }
            $data["totalReadingTime"] += $log["counter"];
        }
        $myHistoryRead = $historyRead->getHistoryRead($username);
        foreach($myHistoryRead as $log){
            if(!in_array($log["subjectID"], $data["allSubjectRead"])){
                array_push($data["allSubjectRead"], $log["subjectID"]);
            }
            $data["totalReadingTime"] += $log["counter"];
        }
        $data["totalReadingTimeInFormat"] = time_formatting($data["totalReadingTime"]);
        $data["info"] = "success";
        echo json_encode($data);
        die();
        
    }else if(isset($_POST["getAllCertificate"])){
        
        $userO = new User();
        $historyReadO = new LogRead();
        $certificateO = new CRSCertificate();
        $certificateSendHistoryO = new CRSCertificateSendHistory();
        $data["users"] = array();
        $users = $userO->getUsersGlobal();
        foreach($users as $key => $value){
            $user = $users[$key];
            $user["totalReadingTime"] = 0;
            $certificate = $certificateO->getCertificate($user["username"]);
            if(!$certificate){
                $certificateO->generateCertificate($user["username"]);
                $certificate = $certificateO->getCertificate($user["username"]);
            }
            $user["certificate"] = $certificate;
            $user["certificate"]["sendHistory"] = $certificateSendHistoryO->getSendHistory($user["certificate"]["certificateID"]);
            $myHistoryRead = $historyReadO->getOldHistoryRead($user["username"]);
            foreach($myHistoryRead as $log){
                $user["totalReadingTime"] += $log["counter"];
            }
            $myHistoryRead = $historyReadO->getHistoryRead($user["username"]);
            foreach($myHistoryRead as $log){
                $user["totalReadingTime"] += $log["counter"];
            }
            array_push($data["users"], $user);
        }

        $data["info"] = "success";
        echo json_encode($data);
        die();
        


    }else if(isset($_POST["sendCertificate"])){
        $required_post = array("sentBy", "code-send", "username");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }
        
        $username = $_POST["username"];
        $sentBy = $_POST["sentBy"];
        $adminCode = $_POST["code-send"];

        $userO = new User();
        $logReadO = new LogRead();
        $certificateO = new CRSCertificate();
        $certificateSendHistoryO = new CRSCertificateSendHistory();

        $userAdmin = $userO->getUser($sentBy);
        if(!$userAdmin){
            $data["info"] = $_POST["sentBy"];
            echo json_encode($data);
            die();
        }

        if($userAdmin["type"] != 1 && $userAdmin["type"] != 2 || $userAdmin["status"] != 1 || $adminCode != "admin is cool you know"){
            $data["info"] = "User not allowed";
            echo json_encode($data);
            die();
        }

        $userGlobal = $userO->getUser($username);
        if(!$userGlobal){
            $data["info"] = "User not found";
            echo json_encode($data);
            die();
        }

        if($logReadO->getTotalPoint($username) < 72000){
            $data["info"] = "Waktu membaca tidak cukup";
            echo json_encode($data);
            die();
        }

        $certificate = $certificateO->getCertificate($userGlobal["username"]);
        if(!$certificate){
            $data["info"] = "Certificate not found";
            echo json_encode($data);
            die();
        }

        if($certificateSendHistoryO->generateSendHistory($certificate["certificateID"], $sentBy, "NORMAL")){
            Email::sendCertificate(
                $userGlobal["email"],
                $userGlobal["username"],
                $certificate["certificateCode"],
                "normal"
            );
            $data["info"] = "success";
            echo json_encode($data);
            die();   
        }


        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["send-yearly-certificate"])){
        
        $required_post = array("send-yearly-certificate", "totalCertificateSend");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!!!";
            echo json_encode($data);
            die();
        }

        $sentBy = $_POST["send-yearly-certificate"];
        $totalCertificateSend = $_POST["totalCertificateSend"];
        $currentYear = date("Y");
        $userO = new User();
        $historyReadO = new LogRead();
        $certificateO = new CRSCertificate();
        $certificateSendHistoryO = new CRSCertificateSendHistory();

        $usersGlobal = $userO->getUsersGlobal();
        $users = array();
        $counter = 0;
        foreach($usersGlobal as $userGlobal){
            if($counter==$totalCertificateSend){
                break;
            }
           

            $user["username"] = $userGlobal["username"];
            $user["email"] = $userGlobal["email"];
            $user["point"] = 0;
            $user["sent"] = false;
            // to reduce the stress AHAHAHAHAH
            $certificate = $certificateO->getCertificate($user["username"]);
            if(!$certificate){
                $certificateO->generateCertificate($user["username"]);
                $certificate = $certificateO->getCertificate($user["username"]);
            }
            if(!$certificateSendHistoryO->isYearlySent($certificate["certificateID"], $currentYear)){
                foreach($historyReadO->getOldHistoryRead($user["username"]) as $log){
                    $user["point"] += $log["counter"];
                }
                foreach($historyReadO->getHistoryRead($user["username"]) as $log){
                    $user["point"] += $log["counter"];
                }
                $user["pointInFormat"] = time_formatting($user["point"]);
                

                // send certificate;
                if($certificateSendHistoryO->generateSendHistory($certificate["certificateID"], $sentBy, "YEARLY")){
                    $user["sent"] = true;
                    Email::sendCertificate(
                        $user["email"],
                        $user["username"],
                        $certificate["certificateCode"],
                        "yearly"
                    );
                    $counter++;
                } 
            }else{
                $user["sent"] = true;
            }
            
            array_push($users, $user);
        }


        // $userAdmin = $user->getUser($_POST["username"]);
        //     Email::sendCertificate(
        //         $target["email"],
        //         $target["username"],
        //         $targetCertificate["code"]
        //     );
        //     $data["info"] = "success";
        //     echo json_encode($data);
        //     die();
        
        $data["info"] = "success";
        echo json_encode($data);
        die();

    }else if(isset($_POST["getTotalYearlyCertificateSent"])){
        $currentYear = date("Y");
        $userO = new User();
        $certificateO = new CRSCertificate();
        $certificateSendHistoryO = new CRSCertificateSendHistory();

        $usersGlobal = $userO->getUsersGlobal();
        $data["certificateSent"] = 0;
        $data["certificateNotSend"] = 0;
        foreach($usersGlobal as $userGlobal){
            
            $certificate = $certificateO->getCertificate($userGlobal["username"]);
            if(!$certificate){
                $certificateO->generateCertificate($userGlobal["username"]);
                $certificate = $certificateO->getCertificate($userGlobal["username"]);
            }
            if(!$certificateSendHistoryO->isYearlySent($certificate["certificateID"], $currentYear)){
                $data["certificateNotSend"] += 1;
            }else{
                $data["certificateSent"] += 1;
            }
        }
        $data["certificateTotal"] = $data["certificateSent"] + $data["certificateNotSend"];
        $data["percent"] = ($data["certificateSent"] / $data["certificateTotal"]) * 100;
        $data["info"] = "success";
        echo json_encode($data);
        die();

    }else if(isset($_POST["getCourseCertificate"])){
        $required = array("username", "certificateCode", "type");
        if(!post_validator($_POST, $required)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }
        $username = $_POST["username"];
        $certificateCode = $_POST["certificateCode"];
        $ctype = $_POST["type"];
        $userGlobal = new UsersGlobalAddition();
        $userGlobal = $userGlobal->getUser($username);
        if(!$userGlobal){
            $data["info"] = "user not found";
            echo json_encode($data);
            die();
        }

        $user = new User();
        $user = $user->getUserGlobal($username);
        if(!$user){
            $data["info"] = "user not found";
            echo json_encode($data);
            die();
        }

        $courseCertificateO = new CRSCertificate();
        $courseHistorySendCertificateO = new CRSCertificateSendHistory();
        $certificate = $courseCertificateO->getCertificate($username);
        
        if(!$certificate){
            $data["info"] = "certificate not found";
            echo json_encode($data);
            die();
        }

        $sendHistory = $courseHistorySendCertificateO->getSendHistory($certificate["certificateID"]);

        if($certificate["certificateCode"] != $certificateCode){
            $data["info"] = "error :) ";
            echo json_encode($data);
            die();
        }

        if($certificate["username"] != $userGlobal["username"]){
            $data["info"] = "error :) ";
            echo json_encode($data);
            die();
        }

        // $find = false;
        if($ctype=="yearly"){
            foreach($sendHistory as $history){
                if(strtolower($history["note"]) == "yearly" && substr($history["dateCreated"], 0, 4) == date("Y")){
                    $userGlobal = array_merge($user, $userGlobal);
                    $certificate = array_merge($userGlobal, $certificate);
                    $data["info"] = "success";
                    $data["certificate"] = $certificate;
                    echo json_encode($data);
                    die();
                }
            }
        }else if($ctype=="normal"){
            $userGlobal = array_merge($user, $userGlobal);
            $certificate = array_merge($userGlobal, $certificate);
            $data["info"] = "success";
            $data["certificate"] = $certificate;
            echo json_encode($data);
            die();
        }
        $data["info"] = "failed";
        $data["certificate"] = array();
        echo json_encode($data);
        die();

    }

    else if(isset($_POST["getQuestion"])){
        $questionID = $_POST["getQuestion"];
        $questionO = new QnA();
        $userO = new user();
        $subjectO = new Subject();
        $question = $questionO->getQuestion($questionID);
        if(!$question){
            $data["info"] = "Question Not Found!";
            echo json_encode($data);
            die();    
        }

        $question["userData"] = $userO->getUser($question["username"]);
        $question["subjectData"] = $subjectO->getSubject($question["subjectID"]);


        $data["info"] = "success";
        $data["question"] = $question;
        echo json_encode($data);
        die();

    }else if(isset($_POST["getAllQuestionList"])){
        $userO = new User();        
        $qnaO = new QnA();
        $subjectO = new Subject();
        $allQuestionList = $qnaO->getAllQuestionList();
        foreach($allQuestionList as $key => $value){
            $allQuestionList[$key]["userData"] = $userO->getUser($allQuestionList[$key]["username"]);
            $allQuestionList[$key]["subjectData"] = $subjectO->getSubject($allQuestionList[$key]["subjectID"]);
            $allQuestionList[$key]["answerList"] = $qnaO->getAnswerList($allQuestionList[$key]["questionID"]);
        }

        echo json_encode($allQuestionList);
        die();

    }else if(isset($_POST["replyQuestion"])){
        $required_post = array("answeredBy", "code-answer", "subjectName", "questionID", "question", "email", "answer");

        if(!post_validator($_POST, $required_post)){
            $data["info"] = "data required error!";
            echo json_encode($data);
            die();
        }

        $answeredBy = $_POST["answeredBy"];
        $adminCode = $_POST["code-answer"];
        $questionID = $_POST["questionID"];
        $subjectName = $_POST["subjectName"];
        $question = $_POST["question"];
        $email = $_POST["email"];
        $answer = $_POST["answer"];

        $userO = new User();
        $subjectO = new Subject();
        $qnaO = new QnA();
        
        $user = $userO->getUser($answeredBy);
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

        $question = $qnaO->getQuestion($questionID);
        if(!$question){
            $data["info"] = "question doesn't exist";
            echo json_encode($data);
            die();
        }

        $subject = $subjectO->getSubject($question["subjectID"]);
        if(!$subject){
            $data["info"] = "subject doesn't exist";
            echo json_encode($data);
            die();
        }

        if($qnaO->replyQuestion($answeredBy, $question["questionID"], $answer)){
            Email::qnaMailAnswer($_POST["email"], $_POST["question"], $_POST["answer"]);
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
           
        $data["info"] = "Failed";
        echo json_encode($data);
        die();

    }



    else if(isset($_POST["counter"])){
        $username = $_SESSION["username"];
        $subjectID = $_POST["counter"];
        $tabCode = $_POST["tabCode"];

        $type = $_SESSION["type"];

        if($type < 4){
            $data["info"] = "not allowed";
            echo json_encode($data);
            die();
        }
        $clock = new Clock($username, $subjectID, $tabCode);
        if(!$clock->isExists($subjectID)){
            $data["info"] = "subject doesn't exists";
            echo json_encode($data);
            die();
        }
        $subjectToRead = $clock->subjectIDtoRead;
        $increment = $_POST["increment"];

        if($clock->isNewRead){
            $result = $clock->startNewReading($subjectToRead, $increment, date("Y-m-d H:i:s"), $tabCode);
        }else{
            $result = $clock->continueReading($subjectToRead, $increment, date("Y-m-d H:i:s"), $tabCode);
        }
        if(!$result){
            $data["info"] = "unknown user";
            echo json_encode($data);
            die();
        }else{
            // $data["info"] = $result;
            $data["info"] = "success";
            $data["subjectID"] = $clock->subjectIDtoRead;
            // echo json_encode($clock->getReadList());
            echo json_encode($data);
            die();
        }

    }else if(isset($_POST["readList"])){

        $username = $_POST["username"];
        $subjectID = $_POST["readList"];
        $codePage = $_POST["tabCode"];

        $type = $_POST["type"];
        if($type != 4){
            die();
        }
        $clock = new Clock($username, $subjectID, $codePage);
        $result = $clock->getReadList();

        echo json_encode($result);
        die();

    }else if(isset($_POST["download"])){
        if(!isset($_SESSION["username"])){
            $data["info"] = "Not logged in";
            echo json_encode($data);
            die();
        }
        $downloader = $_SESSION["username"];
        $type = $_SESSION["type"];
        if($type > 5){
            $data["info"] = "unknown user";
            echo json_encode($data);
            die();
        }
        $result = Subject::download($_POST["download"], $downloader);
        if($result == 1){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        $data["info"] = "failed";
        echo json_encode($data);
        die();

    }else if(isset($_POST["questionList"])){
        $user = new User();

        $username = $_POST["questionList"];
        $qna = new QnA();
        $listQuestion = $qna->questionList($username);
        for($i=0; $i<sizeof($listQuestion); $i++){
            $userData = $user->getUser($listQuestion[$i]["username"]);
            unset($userData["dateCreated"]);
            $listQuestion[$i] = array_merge($listQuestion[$i], $userData);

        }

        echo json_encode($listQuestion);
        die();

    }else if(isset($_POST["addQuestion"])){
        if(!isset($_SESSION["username"])){
            $data["info"] = "Not logged in";
            echo json_encode($data);
            die();
        }

        if(!isset($_SESSION["global_pg_4"])){
            $data["info"] = "not allowed!";
            echo json_encode($data);
            die();
        }
        $required = array("username", "subjectID", "question");
        if(!post_validator($_POST, $required)){
            $data["info"] = "data required error";
            echo json_encode($data);
            die();
        }
        $qna = new QnA();
        $username = $_POST["username"] == $_SESSION["username"] ? $_SESSION["username"] : "";
        if(strlen($username) == 0 || !User::isExists($username)){
            $data["info"] = "illegal | user doesn't exists";
            echo json_encode($data);
            die();
        }

        $subjectID = $_POST["subjectID"];
        $question = $_POST["question"];
        if(!$qna->isExists($subjectID)){
            $data["info"] = "subject doesn't exists";
            echo json_encode($data);
            die();
        }

        if(strlen($question) < 3){
            $data["info"] = "Question error!";
            echo json_encode($data);
            die();
        }

        $result = $qna->addQuestion($username, $subjectID, $question);
        if($result == 1){
            $data["info"] = "success";
            echo json_encode($data);
            die();
        }
        $data["info"] = "Fatal Error";
        echo json_encode($data);
        die();

    }else{
        $data["info"] = "Not Recognized";
        echo json_encode($data);
        die();

    }

}else{
    $data["info"] = "Not Recognized";
    echo json_encode($data);
    die();
}



?>

