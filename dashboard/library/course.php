<?php

class MCourse{

    function getMCourseList(){
        $listMCourse = array();
        $sql = "SELECT * FROM crs_mcourse";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $mcourse = array();
            $mcourse["mcourseID"] = $row["mcourse_id"];
            $mcourse["mcourseName"] = $row["mcourse_name"];
            $mcourse["thumbnailText"] = $row["thumbnail_text"];
            $mcourse["thumbnailImage"] = $row["thumbnail_image"];
            $mcourse["createdBy"] = $row["created_by"];
            $mcourse["dateCreated"] = $row["date_created"];
            $mcourse["totalCourse"] = self::getTotalCourse($mcourse["mcourseID"]);
            array_push($listMCourse, $mcourse);
        }

        return $listMCourse;
    }

    function getMCourse($mcourseID){
        $sql = "SELECT * FROM crs_mcourse WHERE mcourse_id='$mcourseID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $mcourse = array();
        if($row = $result->fetch_assoc()){
            $mcourse["mcourseID"] = $row["mcourse_id"];
            $mcourse["mcourseName"] = $row["mcourse_name"];
            $mcourse["thumbnailText"] = $row["thumbnail_text"];
            $mcourse["thumbnailImage"] = $row["thumbnail_image"];
            $mcourse["createdBy"] = $row["created_by"];
            $mcourse["dateCreated"] = $row["date_created"];
            $mcourse["totalCourse"] = self::getTotalCourse($mcourse["mcourseID"]);
        }
        return $mcourse;
    }

    function addMCourse($mcourseName, $thumbnailImage, $createdBy){
        $empty = "empty";
        $sql = "INSERT INTO crs_mcourse (mcourse_name, thumbnail_text, thumbnail_image, created_by, status)
        VALUES('$mcourseName', '$empty', '$thumbnailImage', '$createdBy', 1)";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return 1;
        }return 0;
    }

    function deleteMCourse($mcourseID){
        $sql = "DELETE FROM crs_mcourse WHERE mcourse_id='$mcourseID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function updateMCourse($mcourseID, $mcourseName, $thumbnail){
        $sql = "UPDATE crs_mcourse SET mcourse_name='$mcourseName',
                                      thumbnail_image='$thumbnail'
                                      WHERE mcourse_id=$mcourseID";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return 1;
        }return 0;
    }

    function isExists($mcourseID){
        $sql = "SELECT mcourse_id FROM crs_mcourse WHERE mcourse_id='$mcourseID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            return true;
        }return false;
    }

    function getTotalCourse($mcourseID){
        $sql = "SELECT mcourse_id FROM crs_course WHERE mcourse_id='$mcourseID'";
        $result = Connection::connect()->query($sql);
        return $result->num_rows;
    }

    function getImageByID($mcourseID){
        $sql = "SELECT thumbnail_image FROM crs_mcourse WHERE mcourse_id='$mcourseID'";
        $result = Connection::connect()->query($sql);
        if($row = $result->fetch_assoc()){
            return $row["thumbnail_image"];
        }
        return 0;
    }
}

class Course{

    function getCourseList($mcourseID){

        $listCourse = array();
        $sql = "SELECT * FROM crs_course WHERE mcourse_id='$mcourseID'";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $course = array();
            $course["courseID"] = $row["course_id"];
            $course["courseName"] = $row["course_name"];
            $course["mcourseID"] = $row["mcourse_id"];
            $course["thumbnailImage"] = $row["thumbnail_image"];
            $course["thumbnailText"] = $row["thumbnail_text"];
            $course["createdBy"] = $row["created_by"];
            $course["dateCreated"] = $row["date_created"];
            $course["totalSubject"] = self::getTotalSubject($course["courseID"]);
            array_push($listCourse, $course);
        }

        return $listCourse;
    }

    function getMCourseID($courseID){
        $sql = "SELECT mcourse_id FROM crs_course WHERE course_id='$courseID'";
        $result = Connection::connect()->query($sql);
        if($row = $result->fetch_assoc()){
            return $row["mcourse_id"];
        }
        return 0;
    }

    function addCourse($mcourseID, $courseName, $thumbnail, $createdBy){
        $empty = "empty";
        $sql = "INSERT INTO crs_course (mcourse_id, course_name, thumbnail_text, thumbnail_image, created_by, status)
        VALUES($mcourseID, '$courseName', '$empty', '$thumbnail', '$createdBy', 1)";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return 1;
        }return 0;
    }

    function deleteCourse($courseID){
        $sql = "DELETE FROM crs_course WHERE course_id='$courseID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function updateCourse($courseID, $courseName, $thumbnail){
        $sql = "UPDATE crs_course SET course_name='$courseName',
                                      thumbnail_image='$thumbnail'
                                      WHERE course_id='$courseID'";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return 1;
        }return 0;
    }

    function getImageByID($courseID){
        $sql = "SELECT thumbnail_image FROM crs_course WHERE course_id='$courseID'";
        $result = Connection::connect()->query($sql);
        if($row = $result->fetch_assoc()){
            return $row["thumbnail_image"];
        }
        return 0;
    }

    function getCourse($courseID){
        $sql = "SELECT * FROM crs_course WHERE course_id='$courseID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $course = array();
        if($row = $result->fetch_assoc()){

            $course["courseID"] = $row["course_id"];
            $course["mcourseID"] = $row["mcourse_id"];
            $course["courseName"] = $row["course_name"];
            $course["thumbnailImage"] = $row["thumbnail_image"];
            $course["thumbnailText"] = $row["thumbnail_text"];
            $course["createdBy"] = $row["created_by"];
            $course["dateCreated"] = $row["date_created"];

        }return $course;
    }

    function getTotalSubject($courseID){
        $sql = "SELECT subject_id FROM crs_subject WHERE course_id='$courseID'";
        $result = Connection::connect()->query($sql);
        return $result->num_rows;
    }

    function isExists($courseID){
        $sql = "SELECT course_id FROM crs_course WHERE course_id='$courseID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            return true;
        }return false;
    }
}

class Subject{

    function getAllSubject(){
        $sql = "SELECT * FROM crs_subject";
        $result = Connection::connect()->query($sql);
        $allSubject = array();
        while($row = $result->fetch_assoc()){
            $subject = array();
            $subject["subjectID"] = $row["subject_id"];
            $subject["courseID"] = $row["course_id"];
            $subject["categoryID"] = $row["category_id"];
            $subject["shelterID"] = $row["shelter_id"];
            $subject["focusCategoryID"] = $row["focus_category_id"];
            $subject["subjectName"] = $row["subject_name"];
            $subject["point"] = $row["point"];
            $subject["thumbnailImage"] = $row["thumbnail_image"];
            $subject["thumbnailText"] = $row["thumbnail_text"];
            $subject["filename"] = $row["filename"];
            $subject["filenameOrigin"] = $row["filename_origin"];
            $subject["videoname"] = $row["videoname"];
            $subject["videonameOrigin"] = $row["videoname_origin"];
            array_push($allSubject, $subject);
        }

        return $allSubject;
    }

    function getSubjectList($courseID){
        $listSubject = array();

        $sql = "SELECT users.image,
                       crs_subject.subject_id, crs_subject.subject_name, crs_subject.point, crs_subject.category_id, crs_subject.focus_category_id, crs_subject.thumbnail_image, crs_subject.thumbnail_text, crs_subject.shelter_id,
                       crs_subject.filename, crs_subject.filename_origin,
                       crs_subject.videoname, crs_subject.videoname_origin, crs_subject.uploaded_by,
                       crs_subject.date_created, crs_subject.course_id
                FROM users, crs_subject
                WHERE users.username = crs_subject.uploaded_by AND crs_subject.course_id='$courseID' AND crs_subject.status = 1 ORDER BY crs_subject.date_created DESC";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $subject = array();
            $subject["subjectID"] = $row["subject_id"];
            $subject["categoryID"] = $row["category_id"];
            $subject["shelterID"] = $row["shelter_id"];
            $subject["subjectName"] = $row["subject_name"];
            $subject["point"] = $row["point"];
            $subject["thumbnailImage"] = $row["thumbnail_image"];
            $subject["thumbnailText"] = $row["thumbnail_text"];
            $subject["filename"] = $row["filename"];
            $subject["filenameOrigin"] = $row["filename_origin"];
            $subject["videoname"] = $row["videoname"];
            $subject["videonameOrigin"] = $row["videoname_origin"];
            $subject["uploadedBy"] = $row["uploaded_by"];
            $subject["userImageUploader"] = $row["image"];
            $subject["dateCreated"] = $row["date_created"];
            $c = self::seenTotal($subject["subjectID"]);
            $subject["seenTotal"] = $c["seen"];
            $subject["timeTotal"] = $c["timeCounter"];
            $subject["timeRead"] = time_formatting($subject["timeTotal"]);
            $subject["totalDownloader"] = self::subjectXDownload($subject["subjectID"]);
            array_push($listSubject, $subject);

        }

        return $listSubject;
    }

    function getSubject($subjectID){
        $sql = "SELECT * FROM crs_subject WHERE subject_id='$subjectID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        if($row = $result->fetch_assoc()){
            $subject = array();
            $subject["subjectID"] = $row["subject_id"];
            $subject["courseID"] = $row["course_id"];
            $subject["categoryID"] = $row["category_id"];
            $subject["shelterID"] = $row["shelter_id"];
            $subject["focusCategoryID"] = $row["focus_category_id"];
            $subject["subjectName"] = $row["subject_name"];
            $subject["point"] = $row["point"];
            $subject["thumbnailImage"] = $row["thumbnail_image"];
            $subject["thumbnailText"] = $row["thumbnail_text"];
            $subject["filename"] = $row["filename"];
            $subject["filenameOrigin"] = $row["filename_origin"];
            $subject["videoname"] = $row["videoname"];
            $subject["videonameOrigin"] = $row["videoname_origin"];
        }
        $c = self::seenTotal($subject["subjectID"]);
        $subject["seenTotal"] = $c["seen"];
        $subject["totalDownloader"] = self::subjectXDownload($subject["subjectID"]);

        return $subject;
    }

    function getSubjectListByCategory($categoryID){
        $listSubject = array();

        $sqsl = "SELECT users.image, crs_course.course_name, crs_course.course_id,
                       crs_subject.subject_id, crs_subject.subject_name, crs_subject.category_id, crs_subject.focus_category_id, crs_subject.shelter_id,
                       crs_subject.thumbnail_image, crs_subject.thumbnail_text,
                       crs_subject.filename, crs_subject.filename_origin,
                       crs_subject.videoname, crs_subject.videoname_origin, crs_subject.uploaded_by,
                       crs_subject.date_created, crs_subject.course_id
                FROM users, crs_subject, crs_course
                WHERE users.username = crs_subject.uploaded_by
                 AND crs_subject.course_id = crs_course.course_id
                 AND crs_subject.category_id='$categoryID' AND crs_subject.status = 1 ORDER BY crs_subject.date_created DESC";
        $sql = "SELECT users.image, crs_course.course_name, crs_course.course_id,
                       crs_subject.subject_id, crs_subject.subject_name, crs_subject.point, crs_subject.category_id, crs_subject.shelter_id, crs_subject.thumbnail_image,
                       crs_subject.thumbnail_text,
                       crs_subject.filename, crs_subject.filename_origin,
                       crs_subject.videoname, crs_subject.videoname_origin, crs_subject.uploaded_by,
                       crs_subject.date_created, crs_subject.course_id
                FROM users, crs_subject, crs_course
                WHERE users.username = crs_subject.uploaded_by
                 AND crs_subject.course_id = crs_course.course_id
                 AND crs_subject.category_id='$categoryID' AND crs_subject.status = 1 ORDER BY crs_subject.date_created DESC";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $subject = array();
            $subject["subjectID"] = $row["subject_id"];
            $subject["categoryID"] = $row["category_id"];
            // $subject["focusCategoryID"] = $row["focus_category_id"];
            $subject["courseID"] = $row["course_id"];
            $subject["shelterID"] = $row["shelter_id"];
            $subject["courseName"] = $row["course_name"];
            $subject["subjectName"] = $row["subject_name"];
            $subject["point"] = $row["point"];
            $subject["thumbnailImage"] = $row["thumbnail_image"];
            $subject["thumbnailText"] = $row["thumbnail_text"];
            $subject["filename"] = $row["filename"];
            $subject["filenameOrigin"] = $row["filename_origin"];
            $subject["videoname"] = $row["videoname"];
            $subject["videonameOrigin"] = $row["videoname_origin"];
            $subject["uploadedBy"] = $row["uploaded_by"];
            $subject["userImageUploader"] = $row["image"];
            $subject["dateCreated"] = $row["date_created"];
            $c = self::seenTotal($subject["subjectID"]);
            $subject["seenTotal"] = $c["seen"];
            $subject["timeTotal"] = $c["timeCounter"];
            $subject["timeRead"] = time_formatting($subject["timeTotal"]);
            $subject["totalDownloader"] = self::subjectXDownload($subject["subjectID"]);
            array_push($listSubject, $subject);

        }
        return $listSubject;
        // $sql = "SELECT * FROM crs_subject WHERE category_id='$categoryID'";
        // $result = Connection::connect()->query($sql);
        // $listSubject = array();
        // while($row = $result->fetch_assoc()){
        //     $subject = array();
        //     $subject["subjectID"] = $row["subject_id"];
        //     $subject["courseID"] = $row["course_id"];
        //     $subject["subjectName"] = $row["subject_name"];
        //     $subject["thumbnailImage"] = $row["thumbnail_image"];
        //     $subject["thumbnailText"] = $row["thumbnail_text"];
        //     $subject["filename"] = $row["filename"];
        //     $subject["filenameOrigin"] = $row["filename_origin"];
        //     $subject["uploadedBy"] = $row["uploaded_by"];
        //     $subject["dateCreated"] = $row["date_created"];
        //     array_push($listSubject, $subject);
        // }
        // return $listSubject;
    }

    function getSubjectsListByShelter($shelterID){
        $listSubject = array();

        $sql = "SELECT users.image, crs_course.course_name, crs_course.course_id,
                       crs_subject.subject_id, crs_subject.subject_name, crs_subject.point, crs_subject.category_id, crs_subject.shelter_id, crs_subject.thumbnail_image,
                       crs_subject.thumbnail_text,
                       crs_subject.filename, crs_subject.filename_origin,
                       crs_subject.videoname, crs_subject.videoname_origin, crs_subject.uploaded_by,
                       crs_subject.date_created, crs_subject.course_id
                FROM users, crs_subject, crs_course
                WHERE users.username = crs_subject.uploaded_by
                 AND crs_subject.course_id = crs_course.course_id
                 AND crs_subject.shelter_id='$shelterID' AND crs_subject.status = 1 ORDER BY crs_subject.date_created DESC";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $subject = array();
            $subject["subjectID"] = $row["subject_id"];
            $subject["categoryID"] = $row["category_id"];
            // $subject["focusCategoryID"] = $row["focus_category_id"];
            $subject["courseID"] = $row["course_id"];
            $subject["shelterID"] = $row["shelter_id"];
            $subject["courseName"] = $row["course_name"];
            $subject["subjectName"] = $row["subject_name"];
            $subject["point"] = $row["point"];
            $subject["thumbnailImage"] = $row["thumbnail_image"];
            $subject["thumbnailText"] = $row["thumbnail_text"];
            $subject["filename"] = $row["filename"];
            $subject["filenameOrigin"] = $row["filename_origin"];
            $subject["videoname"] = $row["videoname"];
            $subject["videonameOrigin"] = $row["videoname_origin"];
            $subject["uploadedBy"] = $row["uploaded_by"];
            $subject["userImageUploader"] = $row["image"];
            $subject["dateCreated"] = $row["date_created"];
            $c = self::seenTotal($subject["subjectID"]);
            $subject["seenTotal"] = $c["seen"];
            $subject["timeTotal"] = $c["timeCounter"];
            $subject["timeRead"] = time_formatting($subject["timeTotal"]);
            $subject["totalDownloader"] = self::subjectXDownload($subject["subjectID"]);
            array_push($listSubject, $subject);

        }
        return $listSubject;
        // $sql = "SELECT * FROM crs_subject WHERE category_id='$categoryID'";
        // $result = Connection::connect()->query($sql);
        // $listSubject = array();
        // while($row = $result->fetch_assoc()){
        //     $subject = array();
        //     $subject["subjectID"] = $row["subject_id"];
        //     $subject["courseID"] = $row["course_id"];
        //     $subject["subjectName"] = $row["subject_name"];
        //     $subject["thumbnailImage"] = $row["thumbnail_image"];
        //     $subject["thumbnailText"] = $row["thumbnail_text"];
        //     $subject["filename"] = $row["filename"];
        //     $subject["filenameOrigin"] = $row["filename_origin"];
        //     $subject["uploadedBy"] = $row["uploaded_by"];
        //     $subject["dateCreated"] = $row["date_created"];
        //     array_push($listSubject, $subject);
        // }
        // return $listSubject;
    }

    function getSubjectListByFocusCategory($focusCategoryID){
        $listSubject = array();

        $sql = "SELECT users.image, crs_course.course_name, crs_course.course_id,
                       crs_subject.subject_id, crs_subject.subject_name, crs_subject.category_id, crs_subject.thumbnail_image,
                       crs_subject.thumbnail_text,
                       crs_subject.filename, crs_subject.filename_origin,
                       crs_subject.videoname, crs_subject.videoname_origin, crs_subject.uploaded_by,
                       crs_subject.date_created, crs_subject.course_id
                FROM users, crs_subject, crs_course
                WHERE users.username = crs_subject.uploaded_by
                 AND crs_subject.course_id = crs_course.course_id
                 AND crs_subject.focus_category_id='$focusCategoryID' AND crs_subject.status = 1 ORDER BY crs_subject.date_created DESC";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $subject = array();
            $subject["subjectID"] = $row["subject_id"];
            $category = Category::getCategory($row["category_id"]);
            $subject["categoryID"] = $row["category_id"];
            $subject["courseID"] = $row["course_id"];
            $subject["courseName"] = $row["course_name"];
            $subject["categoryName"] = $category["categoryID"] != 0 ? $category["categoryName"] : "";
            $subject["subjectName"] = $row["subject_name"];
            $subject["thumbnailImage"] = $row["thumbnail_image"];
            $subject["thumbnailText"] = $row["thumbnail_text"];
            $subject["filename"] = $row["filename"];
            $subject["filenameOrigin"] = $row["filename_origin"];
            $subject["videoname"] = $row["videoname"];
            $subject["videonameOrigin"] = $row["videoname_origin"];
            $subject["uploadedBy"] = $row["uploaded_by"];
            $subject["userImageUploader"] = $row["image"];
            $subject["dateCreated"] = $row["date_created"];
            $c = self::seenTotal($subject["subjectID"]);
            $subject["seenTotal"] = $c["seen"];
            $subject["timeTotal"] = $c["timeCounter"];
            $subject["timeRead"] = time_formatting($subject["timeTotal"]);
            $subject["totalDownloader"] = self::subjectXDownload($subject["subjectID"]);
            array_push($listSubject, $subject);

        }
        return $listSubject;
        // $sql = "SELECT * FROM crs_subject WHERE category_id='$categoryID'";
        // $result = Connection::connect()->query($sql);
        // $listSubject = array();
        // while($row = $result->fetch_assoc()){
        //     $subject = array();
        //     $subject["subjectID"] = $row["subject_id"];
        //     $subject["courseID"] = $row["course_id"];
        //     $subject["subjectName"] = $row["subject_name"];
        //     $subject["thumbnailImage"] = $row["thumbnail_image"];
        //     $subject["thumbnailText"] = $row["thumbnail_text"];
        //     $subject["filename"] = $row["filename"];
        //     $subject["filenameOrigin"] = $row["filename_origin"];
        //     $subject["uploadedBy"] = $row["uploaded_by"];
        //     $subject["dateCreated"] = $row["date_created"];
        //     array_push($listSubject, $subject);
        // }
        // return $listSubject;
    }

    function getSubjectListByShelter($shelterID){
        $listSubject = array();

        $sql = "SELECT users.image, crs_course.course_name, crs_course.course_id,
                       crs_subject.subject_id, crs_subject.subject_name, crs_subject.point, crs_subject.category_id, crs_subject.shelter_id, crs_subject.thumbnail_image,
                       crs_subject.thumbnail_text,
                       crs_subject.filename, crs_subject.filename_origin,
                       crs_subject.videoname, crs_subject.videoname_origin, crs_subject.uploaded_by,
                       crs_subject.date_created, crs_subject.course_id
                FROM users, crs_subject, crs_course
                WHERE users.username = crs_subject.uploaded_by
                 AND crs_subject.course_id = crs_course.course_id
                 AND crs_subject.shelter_id='$shelterID' AND crs_subject.status = 1 ORDER BY crs_subject.date_created DESC";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $subject = array();
            $subject["subjectID"] = $row["subject_id"];
            // $category = Category::getCategory($row["category_id"]);
            $subject["categoryID"] = $row["category_id"];
            $subject["shelterID"] = $row["shelter_id"];
            $subject["courseID"] = $row["course_id"];
            $subject["courseName"] = $row["course_name"];
            // $subject["categoryName"] = $category["categoryID"] != 0 ? $category["categoryName"] : "";
            $subject["subjectName"] = $row["subject_name"];
            $subject["point"] = $row["point"];
            $subject["thumbnailImage"] = $row["thumbnail_image"];
            $subject["thumbnailText"] = $row["thumbnail_text"];
            $subject["filename"] = $row["filename"];
            $subject["filenameOrigin"] = $row["filename_origin"];
            $subject["videoname"] = $row["videoname"];
            $subject["videonameOrigin"] = $row["videoname_origin"];
            $subject["uploadedBy"] = $row["uploaded_by"];
            $subject["userImageUploader"] = $row["image"];
            $subject["dateCreated"] = $row["date_created"];
            $c = self::seenTotal($subject["subjectID"]);
            $subject["seenTotal"] = $c["seen"];
            $subject["timeTotal"] = $c["timeCounter"];
            $subject["timeRead"] = time_formatting($subject["timeTotal"]);
            $subject["totalDownloader"] = self::subjectXDownload($subject["subjectID"]);
            array_push($listSubject, $subject);

        }
        return $listSubject;
        // $sql = "SELECT * FROM crs_subject WHERE category_id='$categoryID'";
        // $result = Connection::connect()->query($sql);
        // $listSubject = array();
        // while($row = $result->fetch_assoc()){
        //     $subject = array();
        //     $subject["subjectID"] = $row["subject_id"];
        //     $subject["courseID"] = $row["course_id"];
        //     $subject["subjectName"] = $row["subject_name"];
        //     $subject["thumbnailImage"] = $row["thumbnail_image"];
        //     $subject["thumbnailText"] = $row["thumbnail_text"];
        //     $subject["filename"] = $row["filename"];
        //     $subject["filenameOrigin"] = $row["filename_origin"];
        //     $subject["uploadedBy"] = $row["uploaded_by"];
        //     $subject["dateCreated"] = $row["date_created"];
        //     array_push($listSubject, $subject);
        // }
        // return $listSubject;
    }

    function getSubjectNameByID($subjectID){
        $sql = "SELECT subject_name FROM crs_subject WHERE subject_id='$subjectID'";
        $result = Connection::connect()->query($sql);
        if($row = $result->fetch_assoc()){
            return $row["subject_name"];
        }return "None";
    }

    function addSubject($subjectName, $point, $categoryID, $shelterID, $thumbnailImage, $thumbnailText, $filename, $filenameOrigin, $videoname, $videonameOrigin, $courseID, $uploadedBy){
        $sql = "INSERT INTO crs_subject (subject_name, point, category_id, shelter_id, thumbnail_image, thumbnail_text, filename, filename_origin, videoname, videoname_origin, course_id, uploaded_by, status)
        VALUES('$subjectName', '$point', '$categoryID', '$shelterID', '$thumbnailImage', '$thumbnailText', '$filename', '$filenameOrigin', '$videoname', '$videonameOrigin', $courseID, '$uploadedBy', 1)";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function deleteSubject($subjectID){
        $sql = "DELETE FROM crs_subject WHERE subject_id='$subjectID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function updateSubject($subjectID, $subjectName, $point, $categoryID, $shelterID, $focusCategoryID, $thumbnailImage, $thumbnailText, $filename, $filenameOrigin, $videoname, $videonameOrigin){
        $sql = "UPDATE crs_subject SET subject_name='$subjectName',
                                       point='$point',
                                       category_id='$categoryID',
                                       shelter_id='$shelterID',
                                       focus_category_id='$focusCategoryID',
                                       thumbnail_image='$thumbnailImage',
                                       thumbnail_text='$thumbnailText',
                                       filename='$filename',
                                       filename_origin='$filenameOrigin',
                                       videoname='$videoname',
                                       videoname_origin='$videonameOrigin'
                WHERE subject_id='$subjectID'";
        $result = Connection::connect()->query($sql);

        if($result){
            return 1;
        }
        return 0;
    }

    function getImageByID($subjectID){
        $sql = "SELECT thumbnail_image FROM crs_subject WHERE subject_id='$subjectID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            if($row = $result->fetch_assoc()){
                return $row["thumbnail_image"];
            }
        }return 0;
    }

    function getFileByID($subjectID){
        $sql = "SELECT filename FROM crs_subject WHERE subject_id='$subjectID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            if($row = $result->fetch_assoc()){
                return $row["filename"];
            }
        }return 0;
    }

    function getVideoByID($subjectID){
        $sql = "SELECT videoname FROM crs_subject WHERE subject_id='$subjectID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            if($row = $result->fetch_assoc()){
                return $row["videoname"];
            }
        }return 0;
    }

    function seenTotal($subjectID){
        $sql = "SELECT crs_read.time_read FROM crs_read, crs_subject WHERE crs_read.subject_id = crs_subject.subject_id AND crs_read.subject_id='$subjectID'";
        $result = Connection::connect()->query($sql);
        $temp = array("seen" => 0, "timeCounter" => 0);
        while($row = $result->fetch_assoc()){
            $temp["seen"] += 1;
            $temp["timeCounter"] += $row["time_read"];
        }
        return $temp;
    }

    function isExists($subjectID){
        $sql = "SELECT subject_id FROM crs_subject WHERE subject_id='$subjectID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            return true;
        }return false;
    }

    function subjectXDownload($subjectID){
        $sql = "SELECT username FROM crs_download WHERE subject_id='$subjectID'";
        $result = Connection::connect()->query($sql);
        $total_download = 0;
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $total_download += 1;
            }
        }return $total_download;;
    }

    function userXDownload($subjectID, $username){
        $sql = "SELECT download_id FROM crs_download WHERE subject_id='$subjectID' AND username='$username'";
        $result = Connection::connect()->query($sql);
        $total_download = 0;
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $total_download += 1;
            }
        }return $total_download;;
    }

    function download($subjectID, $username){
        $sql = "INSERT INTO crs_download (username, subject_id) VALUES('$username', '$subjectID')";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }
}

class Course_Certificate{

    function getCourseCertificate($certificateCode){
        $sql = "SELECT * FROM cfc_certificate WHERE code='$certificateCode'";
        $result = Connection::connect()->query($sql);
        $certificate = array();
        if($result->num_rows != 1){
            return false;
        }
        if($row = $result->fetch_assoc()){
            $certificate["certificateID"] = $row["certificate_id"];
            $certificate["username"] = $row["username"];
            $certificate["dateCreated"] = $row["date_created"];
            $certificate["certificateCode"] = $row["code"];
        }
        return $certificate;;
    }
}

class Quest{

    function addQuest($question, $subjectID, $createdBy, $optionA, $optionB, $optionC, $optionD, $trueAnswer){
        $sql = "INSERT INTO crs_quest_question (question, subject_id, created_by, option_a, option_b, option_c, option_d, true_answer) VALUES('$question', '$subjectID', '$createdBy', '$optionA', '$optionB', '$optionC', '$optionD', '$trueAnswer')";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function deleteQuest($questionID){
        $sql = "DELETE FROM crs_quest_question WHERE q_question_id='$questionID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function generatePointID($username, $subjectID){
        $pointID = $formatFileName = date("y.m.d.h.i.s").uniqid('', true);
        $sql = "INSERT INTO crs_quest_try_point (q_point_id, username, subject_id, my_point)
        VALUES('$pointID', '$username', '$subjectID', 0)";
        $result = Connection::connect()->query($sql);
        if($result){
            return $pointID;
        }
        return "";

    }

    function addAnswer($questionId, $pointID, $answeredBy, $answer){
        $sql = "INSERT INTO crs_quest_answer (q_question_id, q_point_id, answered_by, answer) VALUES('$questionId', '$pointID', '$answeredBy', '$answer')";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function deleteAnswer($pointID){
        $sql = "DELETE FROM crs_quest_answer WHERE q_point_id='$pointID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function editQuest($questionID, $question, $optionA, $optionB, $optionC, $optionD, $trueAnswer){
        $sql = "UPDATE crs_quest_question SET question='$question',
                                              option_a='$optionA',
                                              option_b='$optionB',
                                              option_c='$optionC',
                                              option_d='$optionD',
                                              true_answer='$trueAnswer'
                WHERE q_question_id='$questionID'";
        $result = Connection::connect()->query($sql);

        if($result){
            return 1;
        }
        return 0;
    }

    function getQuestListBySubjectID($subjectID){
        $listQuest = array();
        $sql = "SELECT * FROM crs_quest_question WHERE subject_id='$subjectID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows < 1){
            return $listQuest;
        }

        while($row = $result->fetch_assoc()){
            $quest = array();
            $quest["questionID"] = $row["q_question_id"];
            $quest["question"] = $row["question"];
            $quest["optionA"] = $row["option_a"];
            $quest["optionB"] = $row["option_b"];
            $quest["optionC"] = $row["option_c"];
            $quest["optionD"] = $row["option_d"];
            $quest["optionList"] = array(
                "a" => $row["option_a"],
                "b" => $row["option_b"],
                "c" => $row["option_c"],
                "d" => $row["option_d"]
            );
            $quest["trueAnswer"] = $row["true_answer"];
            $quest["createdBy"] = $row["created_by"];
            $quest["dateCreated"] = $row["date_created"];
            $quest["subjectID"] = $row["subject_id"];


            array_push($listQuest, $quest);
        }

        return $listQuest;
    }

    function updatePoint($pointID, $username, $myPoint){
        $sql= "UPDATE crs_quest_try_point SET my_point='$myPoint' WHERE username='$username' AND q_point_id='$pointID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function getMyPoints($username, $subjectID){
        $sql = "SELECT * FROM crs_quest_try_point WHERE username='$username' AND subject_id='$subjectID'";
        $result = connection::connect()->query($sql);
        $myPoints = array();
        while($row = $result->fetch_assoc()){
            $myPoint = array();
            $myPoint["username"] = $row["username"];
            $myPoint["pointID"] = $row["q_point_id"];
            $myPoint["myPoint"] = $row["my_point"];
            $myPoint["dateCreated"] = $row["date_created"];
            array_push($myPoints, $myPoint);
        }
        return $myPoints;
    }
}

class Category{

    function addCategory($categoryName, $createdBy){
        $sql = "INSERT INTO crs_category (category_name, created_by) VALUES('$categoryName', '$createdBy')";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }

    function editCategory($categoryID, $categoryName, $showCategory){

        // $sql = "UPDATE crs_subject SET subject_name='$subjectName',
        //                                category_id='$categoryID',
        //                                thumbnail_image='$thumbnailImage',
        //                                thumbnail_text='$thumbnailText',
        //                                filename='$file',
        //                                filename_origin='$filename'
        //         WHERE subject_id='$subjectID'";
        // $result = Connection::connect()->query($sql);

        $sql = "UPDATE crs_category SET category_name='$categoryName',
                                        show_category='$showCategory'
                WHERE category_id='$categoryID'";
        $result = Connection::connect()->query($sql);

        if($result){
            return true;
        }
        return false;
    }

    function deleteCategory($categoryID){
        $sql = "DELETE FROM crs_category WHERE category_id='$categoryID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }

    function getCategoryList(){
        $sql = "SELECT * FROM crs_category";
        $result = Connection::connect()->query($sql);
        $listCategory = array();

        while($row = $result->fetch_assoc()){
            $category = array();
            $category["categoryID"] = $row["category_id"];
            $category["categoryName"] = $row["category_name"];
            $category["createdBy"] = $row["created_by"];
            $category["dateCreated"] = $row["date_created"];
            $category["showCategory"] = $row["show_category"];
            array_push($listCategory, $category);
        }
        return $listCategory;
    }

    function getSinCategoryByID($categoryID){
        $sql = "SELECT * FROM crs_category WHERE category_id='$categoryID'";
        $result = Connection::connect()->query($sql);

        if($row = $result->fetch_assoc()){
            $category = array();
            $category["categoryID"] = $row["category_id"];
            $category["categoryName"] = $row["category_name"];
            $category["createdBy"] = $row["created_by"];
            $category["dateCreated"] = $row["date_created"];
            $category["showCategory"] = $row["show_category"];
            $category["listSubject"] = Subject::getSubjectListByCategoryID($category["categoryID"]);

            // $category["listSubject"] = array();
            $category["totalSubject"] = sizeof($category["listSubject"]);
            return $category;
        }
        return array();
    }

    function getCategory($categoryID){
        $sql = "SELECT * FROM crs_category WHERE category_id='$categoryID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $category = array();
        if($row = $result->fetch_assoc()){
            $category["categoryID"] = $row["category_id"];
            $category["categoryName"] = $row["category_name"];
            $category["createdBy"] = $row["created_by"];
            $category["dateCreated"] = $row["date_created"];
            $category["showCategory"] = $row["show_category"];
        }
        return $category;
    }
}

class MShelter{

    function addMShelter($mshelterName, $createdBy){
        $sql = "INSERT INTO crs_mshelter (mshelter_name, created_by) VALUES('$mshelterName', '$createdBy')";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }

    function editMShelter($mshelterID, $mshelterName, $showMShelter){
        $sql = "UPDATE crs_mshelter SET mshelter_name='$mshelterName', show_mshelter='$showMShelter' WHERE mshelter_id='$mshelterID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }

    function deleteMShelter($mshelterID){
        $sql = "DELETE FROM crs_mshelter WHERE mshelter_id='$mshelterID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }


    function getMShelter($mshelterID){
        $sql = "SELECT * FROM crs_mshelter WHERE mshelter_id='$mshelterID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $mshelter = array();
        while($row = $result->fetch_assoc()){
            $mshelter["mshelterID"] = $row["mshelter_id"];
            $mshelter["mshelterName"] = $row["mshelter_name"];
            $mshelter["createdBy"] = $row["created_by"];
            $mshelter["dateCreated"] = $row["date_created"];
            $mshelter["showMShelter"] = $row["show_mshelter"];
        }
        return $mshelter;
    }

    function getMShelterList(){
        $sql = "SELECT * FROM crs_mshelter";
        $result = Connection::connect()->query($sql);
        $listMShelter = array();

        while($row = $result->fetch_assoc()){
            $mshelter = array();
            $mshelter["mshelterID"] = $row["mshelter_id"];
            $mshelter["mshelterName"] = $row["mshelter_name"];
            $mshelter["createdBy"] = $row["created_by"];
            $mshelter["dateCreated"] = $row["date_created"];
            $mshelter["showMShelter"] = $row["show_mshelter"];

            array_push($listMShelter, $mshelter);
        }
        return $listMShelter;
    }
}

class Shelter{

    function addShelter($shelterName, $mshelterID, $createdBy){
        $sql = "INSERT INTO crs_shelter (shelter_name, mshelter_id
        , created_by) VALUES('$shelterName', '$mshelterID', '$createdBy')";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }

    function editShelter($shelterID, $shelterName, $showShelter){
        $sql = "UPDATE crs_shelter SET shelter_name='$shelterName', show_shelter='$showShelter' WHERE shelter_id='$shelterID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }

    function deleteShelter($shelterID){
        $sql = "DELETE FROM crs_shelter WHERE shelter_id='$shelterID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }

    function getShelter($shelterID){
        $sql = "SELECT * FROM crs_shelter WHERE shelter_id='$shelterID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $shelter = array();
        while($row = $result->fetch_assoc()){
            $shelter["shelterID"] = $row["shelter_id"];
            $shelter["shelterName"] = $row["shelter_name"];
            $shelter["mshelterID"] = $row["mshelter_id"];
            $shelter["createdBy"] = $row["created_by"];
            $shelter["dateCreated"] = $row["date_created"];
            $shelter["showShelter"] = $row["show_shelter"];
        }
        return $shelter;
    }

    function getShelterList($mshelterID){
        $sql = "SELECT * FROM crs_shelter WHERE mshelter_id='$mshelterID'";
        $result = Connection::connect()->query($sql);
        $listShelter = array();

        while($row = $result->fetch_assoc()){
            $shelter = array();
            $shelter["shelterID"] = $row["shelter_id"];
            $shelter["mshelterID"] = $row["mshelter_id"];
            $shelter["shelterName"] = $row["shelter_name"];
            $shelter["createdBy"] = $row["created_by"];
            $shelter["dateCreated"] = $row["date_created"];
            $shelter["showShelter"] = $row["show_shelter"];

            array_push($listShelter, $shelter);
        }
        return $listShelter;
    }

    function getAllShelterList(){
        $sql = "SELECT * FROM crs_shelter";
        $result = Connection::connect()->query($sql);
        $listShelter = array();

        while($row = $result->fetch_assoc()){
            $shelter = array();
            $shelter["shelterID"] = $row["shelter_id"];
            $shelter["mshelterID"] = $row["mshelter_id"];
            $shelter["shelterName"] = $row["shelter_name"];
            $shelter["createdBy"] = $row["created_by"];
            $shelter["dateCreated"] = $row["date_created"];
            $shelter["showShelter"] = $row["show_shelter"];

            array_push($listShelter, $shelter);
        }
        return $listShelter;
    }
}

class FocusCategory{

    function getFocusCategory($focusCategoryID){
        $sql = "SELECT * FROM crs_focus_category WHERE focus_category_id='$focusCategoryID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $focusCategory = array();
        if($row = $result->fetch_assoc()){
            $focusCategory["focusCategoryID"] = $row["focus_category_id"];
            $focusCategory["focusCategoryName"] = $row["focus_category_name"];
            $focusCategory["createdBy"] = $row["created_by"];
            $focusCategory["dateCreated"] = $row["date_created"];
            $focusCategory["showFocusCategory"] = $row["show_focus_category"];
        }
        return $focusCategory;
    }

    function addFocusCategory($focusCategoryName, $createdBy){
        $sql = "INSERT INTO crs_focus_category (focus_category_name, created_by) VALUES('$focusCategoryName', '$createdBy')";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function editFocusCategory($focusCategoryID, $focusCategoryName, $showFocusCategory){

        $sql = "UPDATE crs_focus_category SET focus_category_name='$focusCategoryName',
                                        show_focus_category='$showFocusCategory'
                WHERE focus_category_id='$focusCategoryID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }


    function getFocusCategoryList(){
        $sql = "SELECT * FROM crs_focus_category";
        $result = Connection::connect()->query($sql);
        $listFocusCategory = array();

        while($row = $result->fetch_assoc()){
            $focusCategory = array();
            $focusCategory["focusCategoryID"] = $row["focus_category_id"];
            $focusCategory["focusCategoryName"] = $row["focus_category_name"];
            $focusCategory["createdBy"] = $row["created_by"];
            $focusCategory["dateCreated"] = $row["date_created"];
            $focusCategory["showFocusCategory"] = $row["show_focus_category"];

            array_push($listFocusCategory, $focusCategory);
        }
        return $listFocusCategory;
    }

    function deleteFocusCategory($focusCategoryID){
        $sql = "DELETE FROM crs_focus_category WHERE focus_category_id='$focusCategoryID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }
}

class LogRead{

    private $currentDate;
    private $currentTime;
    private $counter;
    private $tabCode;
    private $username;
    private $maxPointPerDay;
    private $subjectPoint;


    public $subjectIDtoRead;
    public $isNewRead;
    public $myTodaySubjectPoint;
    public $isTodayPointMax;

    public $whatTheHell;

    function __construct(){
        $this->maxPointPerDay = 5400;
        $this->isNewRead = false;
        $this->isTodayPointMax = false;
        $this->currentDate = date("Y-m-d");
        $this->currentTime = date("Y-m-d H:i:s");
    }

    function __init__($username, $subjectID, $subjectPoint, $tabCode){
        $this->username = $username;
        $this->subjectPoint = $subjectPoint;
        if($this->isTodayHitMaxPoint($this->username)){
            $this->isTodayPointMax = true;
        }
        $sql = "SELECT * FROM crs_history_read WHERE username='$username' AND DATE(date_created)='$this->currentDate' ORDER BY end_time DESC LIMIT 1";
        $result = Connection::connect()->query($sql);
        // user hasnt read today
        if($result->num_rows != 1){
            $this->subjectIDtoRead = $subjectID;
            $this->counter = 0;
            $this->myTodaySubjectPoint = 0;
            $this->tabCode = $tabCode;
            $this->isNewRead = true;
            $this->whatTheHell = "new";
        }else{
            if ($row = $result->fetch_assoc()){
                /**
                jika TIDAK ADA aktifitas selama lebih dari 3 detik pada materi yang terakhir dibaca
                (tidak ada aktivitas(memulai mambaca) / user membuka tab A)
                **/
                $lastReadTime = $row["end_time"];
                if(abs(STRTOTIME($this->currentTime) - STRTOTIME($lastReadTime)) > 3){
                    $this->subjectIDtoRead = $subjectID;

                    $sql2 = "SELECT counter FROM crs_history_read WHERE username='$this->username' AND DATE(date_created)='$this->currentDate' AND subject_id='$subjectID'";
                    $result2 = Connection::connect()->query($sql2);
                    // jika materi A belum pernah dibaca sama sekali, then start new read on materi A
                    // init counter 0 
                    if($result2->num_rows == 0){
                        $this->counter = 0;
                        $this->myTodaySubjectPoint = 0;
                        $this->isNewRead = true;
                        $this->whatTheHell = "start new";
                    }else{
                        // otherwise
                        if($row2 = $result2->fetch_assoc()){
                            $this->counter = $row2["counter"];
                            $this->myTodaySubjectPoint = $this->counter;
                        }
                    }
                }else{
                    /**
                    jika ADA aktifitas selama kurang dari 4 detik pada materi yang terakhir dibaca
                    (ada aktifitas(tab A terbuka) dan user membuka tab B) ignoring new tab counter
                    **/
                    if(strlen($row["tab_code"]) == 0){
                        $row["tab_code"] = $tabCode;
                    }
                    /**
                        materi A masuk DB dengan code yang sama dengan materi yang terakhir dibaca, 
                        kenapa sama? karena tab materi A masih terbuka,
                        materi B masuk DB dengan code baru, dan materi B tidak dibaca karena code tidak sama,
                        jika materi A di close, materi B code beda, selama lebih 3 detik, maka masuk ke if 

                    **/
                    if($tabCode == $row["tab_code"]){
                        $this->subjectIDtoRead = $row["subject_id"];
                        $this->counter = $row["counter"];
                        $this->myTodaySubjectPoint = $this->counter;
                    }
                }
            }
        }
    }

    function startNewReading($subjectID, $n, $startTime, $endTime, $tabCode){
        if($n != 1 || $this->isTodayPointMax){
            return false;
        }
        
        if($this->counter < $this->subjectPoint){
             $this->counter += $n;
        }else if ($this->counter > $this->subjectPoint){
            $this->counter = $this->subjectPoint;
        }

        $sql = "INSERT INTO crs_history_read (username, subject_id, tab_code, total_read, counter, start_time, end_time) VALUES 
        ('$this->username', '$subjectID', '$tabCode', 1, $this->counter, '$startTime', '$endTime')";

        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }return false;
    }

    function continueReading($subjectID, $n, $endTime, $tabCode){
        if($n != 1 || $this->isTodayPointMax){
            return false;
        }
        
        if($this->counter < $this->subjectPoint){
             $this->counter += $n;
        }else if ($this->counter > $this->subjectPoint){
            $this->counter = $this->subjectPoint;
        }
        
        $sql = "UPDATE crs_history_read SET total_read=1,
                                    counter=$this->counter,
                                    end_time='$endTime',
                                    tab_code='$tabCode'
                WHERE username='$this->username' AND subject_id='$subjectID' AND DATE(date_created)='$this->currentDate'";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }return false;
    }
   
    function getHistoryRead($username){
        $sql = "SELECT * FROM crs_history_read WHERE username='$username' ORDER BY date_created ASC";
        $result = Connection::connect()->query($sql);
        $logList = array();
        while($row = $result->fetch_assoc()){
            $log = array();
            $log["logReadID"] = $row["log_read_id"];
            $log["username"] = $row["username"];
            $log["subjectID"] = $row["subject_id"];
            $log["tabCode"] = $row["tab_code"];
            $log["startTime"] = $row["start_time"];
            $log["endTime"] = $row["end_time"];
            $log["totalRead"] = $row["total_read"];
            $log["counter"] = $row["counter"];
            $log["dateRead"] = date_format(date_create($row["date_created"]), "Y-m-d");
            $log["dateCreated"] = $row["date_created"];

            array_push($logList, $log);
        }
        return $logList;
    }

    function getOldHistoryRead($username){
        $sql = "SELECT * FROM crs_read WHERE username='$username'";
        $result = Connection::connect()->query($sql);
        $logList = array();
        while($row = $result->fetch_assoc()){
            $log = array();
            $log["logReadID"] = $row["read_id"];
            $log["username"] = $row["username"];
            $log["subjectID"] = $row["subject_id"];
            $log["tabCode"] = $row["tab_code"];
            $log["startTime"] = $row["start_read"];
            $log["endTime"] = $row["end_read"];
            $log["totalRead"] = $row["total_read"];
            $log["counter"] = $row["time_read"];
            $log["dateRead"] = date_format(date_create($row["start_read"]), "Y-m-d");
            $log["dateCreated"] = $row["start_read"];            
            array_push($logList, $log);
        }
        return $logList;
    }

    function getTotalPoint($username){
        $myHistoryRead = $this->getOldHistoryRead($username);
        $totalReadingTime = 0;
        foreach($myHistoryRead as $log){
            $totalReadingTime += $log["counter"];
        }
        $myHistoryRead = $this->getHistoryRead($username);
        foreach($myHistoryRead as $log){
            $totalReadingTime += $log["counter"];
        }
        return $totalReadingTime;
    }

    function isTodayHitMaxPoint($username){
        $sql = "SELECT * FROM crs_history_read WHERE username='$username' AND DATE(date_created)='$this->currentDate' ORDER BY date_created ASC";
        $result = Connection::connect()->query($sql);
        $myPoint = 0;
        
        while($row = $result->fetch_assoc()){
            $myPoint += $row["counter"];
        }

        if($myPoint >= $this->maxPointPerDay){
            return true;
        }return false;
        // return $myPoint >= $this->maxPointPerDay ? true : false;
    }
}

class CRSCertificate{

    function getCertificate($username){
        $sql = "SELECT * FROM crs_certificate WHERE username='$username'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $certificate = array();
        if($row = $result->fetch_assoc()){
            $certificate["certificateID"] = $row["certificate_id"];
            $certificate["username"] = $row["username"];
            $certificate["certificateCode"] = $row["certificate_code"];
            $certificate["dateCreated"] = $row["date_created"];
        }
        return $certificate;
    }

    function generateCertificate($username){
        $code = $this->generateCertificateCode();
        $sql = "INSERT INTO crs_certificate (username, certificate_code) VALUES('$username', '$code')";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }

    private function generateCertificateCode(){
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
            $sql = "SELECT * FROM crs_certificate WHERE registration_code='$code'";
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
}

class CRSCertificateSendHistory{

    function generateSendHistory($certificateID, $sentBy, $note){
        $sql = "INSERT INTO crs_certificate_send_history (certificate_id, sent_by, note) VALUES('$certificateID', '$sentBy', '$note')";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }

    function getSendHistory($certificateID){
        $sql = "SELECT * FROM crs_certificate_send_history WHERE certificate_id='$certificateID'";
        $result = Connection::connect()->query($sql);
        $listHistory = array();
        while($row = $result->fetch_assoc()){
            $sendHistory = array();
            $sendHistory["certificateID"] = $row["certificate_id"];
            $sendHistory["sentBy"] = $row["sent_by"];
            $sendHistory["note"] = $row["note"];
            $sendHistory["dateCreated"] = $row["date_created"];
            array_push($listHistory, $sendHistory);
        }return $listHistory;
    }

    function isYearlySent($certificateID, $year){
        $sql = "SELECT * FROM crs_certificate_send_history WHERE certificate_id='$certificateID' AND YEAR(date_created)='$year' AND note='YEARLY'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        return true;
    }

}

class Clock extends Subject{

    private $readSubjectList = array();
    private $currentTime;
    private $timeRead;
    private $tabCode;


    public $subjectIDtoRead;
    public $isNewRead;

    

    function __construct($username, $subjectID, $tabCode){
        $this->username = $username;
        $this->subjectID = $subjectID;

        $this->isNewRead = false;
        $this->currentTime = date("Y-m-d H:i:s", STRTOTIME(date('h:i:sa')));
        $sql = "SELECT * FROM crs_read WHERE username='$username' ORDER BY end_read DESC LIMIT 1";
        $result = Connection::connect()->query($sql);
        // querying if user is literally never read before then start new read
        if($result->num_rows != 1){
            $this->subjectIDtoRead = $subjectID;
            $this->timeRead = 0;
            $this->tabCode = $tabCode;
            $this->isNewRead = true;
        }else{
            if ($row = $result->fetch_assoc()){
                /**
                jika TIDAK ADA aktifitas selama lebih dari 3 detik pada materi yang terakhir dibaca
                (tidak ada aktivitas(memulai mambaca) / user membuka tab A)
                **/
                $lastReadTime = $row["end_read"];
                if(abs(STRTOTIME($this->currentTime) - STRTOTIME($lastReadTime)) > 3){
                    $this->subjectIDtoRead = $subjectID;

                    $sql2 = "SELECT time_read FROM crs_read WHERE username='$this->username' AND subject_id='$subjectID'";
                    $result2 = Connection::connect()->query($sql2);
                    // jika materi A belum pernah dibaca sama sekali, then start new read on materi A
                    // init timeRead 0 
                    if($result2->num_rows == 0){
                        $this->timeRead = 0;
                        $this->isNewRead = true;
                    }else{
                        // otherwise
                        if($row2 = $result2->fetch_assoc()){
                            $this->timeRead = $row2["time_read"];
                        }
                    }
                }else{
                    /**
                    jika ADA aktifitas selama kurang dari 4 detik pada materi yang terakhir dibaca
                    (ada aktifitas(tab A terbuka) dan user membuka tab B) ignoring new tab counter
                    **/
                    if(strlen($row["tab_code"]) == 0){
                        $row["tab_code"] = $tabCode;
                    }
                    /**
                        materi A masuk DB dengan code yang sama dengan materi yang terakhir dibaca, 
                        kenapa sama? karena tab materi A masih terbuka,
                        materi B masuk DB dengan code baru, dan materi B tidak dibaca karena code tidak sama,
                        jika materi A di close, materi B code beda, selama lebih 3 detik, maka masuk ke if 

                    **/
                    if($tabCode == $row["tab_code"]){
                        $this->subjectIDtoRead = $row["subject_id"];
                        $this->timeRead = $row["time_read"];
                    }
                }
            }
        }
    }

    function startNewReading($subjectID, $counter, $endTime, $tabCode){
        if($counter != 3){
            return 0;
        }
        $this->timeRead += $counter;
        $sql = "INSERT INTO crs_read
        (username, subject_id, total_read, time_read, end_read, tab_code) VALUES
        ('$this->username', '$subjectID', 1, $this->timeRead, '$endTime', '$tabCode')";

        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }return 0;
    }



    function continueReading($subjectID, $counter, $endTime, $tabCode){
        if($counter != 3){
            return 0;
        }
        $this->timeRead += $counter;
        $sql = "UPDATE crs_read SET total_read=1,
                                    time_read=$this->timeRead,
                                    end_read='$endTime',
                                    tab_code='$tabCode'
                WHERE username='$this->username' AND subject_id='$subjectID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }return 0;
    }

    function getReadList(){
        $sql = "SELECT * FROM crs_read WHERE username='$this->username'";
        $result = Connection::connect()->query($sql);
        $listSubject = array();
        while($row = $result->fetch_assoc()){
            $subject = array();
            $subject["readID"] = $row["read_id"];
            $subject["username"] = $row["username"];
            $subject["subjectID"] = $row["subject_id"];
            $subject["startRead"] = $row["start_read"];
            $subject["endRead"] = $row["end_read"];
            $subject["totalRead"] = $row["total_read"];
            $subject["timeInSecond"] = $row["time_read"];
            $hours = floor($row["time_read"] / 3600);
            $mins = floor($row["time_read"] / 60 % 60);
            $secs = floor($row["time_read"] % 60);

            $subject["timeRead"] = time_formatting($row["time_read"]);
            $subject["tabCode"] = $row["tab_code"];
            array_push($listSubject, $subject);
        }
        return $listSubject;
    }

    function getReadDetailBySubject($username, $subjectID){
        $sql = "SELECT * FROM crs_read WHERE username='$username' AND subject_id='$subjectID'";
        $result = Connection::connect()->query($sql);
        $listSubject = array();
        while($row = $result->fetch_assoc()){
            $subject = array();
            $subject["readID"] = $row["read_id"];
            $subject["username"] = $row["username"];
            $subject["subjectID"] = $row["subject_id"];
            $subject["startRead"] = $row["start_read"];
            $subject["endRead"] = $row["end_read"];
            $subject["totalRead"] = $row["total_read"];
            $subject["timeInSecond"] = $row["time_read"];
            $hours = floor($row["time_read"] / 3600);
            $mins = floor($row["time_read"] / 60 % 60);
            $secs = floor($row["time_read"] % 60);

            $subject["timeRead"] = time_formatting($row["time_read"]);
            $subject["tabCode"] = $row["tab_code"];
            array_push($listSubject, $subject);
        }
        return $listSubject;
    }
}

class QnA extends Subject{

    function getQuestion($questionID){
        $sql = "SELECT * FROM crs_qna_question WHERE question_id='$questionID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $question = array();
        if($row = $result->fetch_assoc()){
            $question["questionID"] = $row["question_id"];
            $question["username"] = $row["username"];
            $question["subjectID"] = $row["subject_id"];
            $question["question"] = $row["question"];
            $question["dateCreated"] = $row["date_created"];
            $question["status"] = $row["status"];
        }
        return $question;
    }

    function getAllQuestionList(){
        $sql = "SELECT * FROM crs_qna_question ORDER BY date_created DESC";
        $result = Connection::connect()->query($sql);
        $listQuestion = array();
        while($row = $result->fetch_assoc()){
            $question = array();
            $question["questionID"] = $row["question_id"];
            $question["username"] = $row["username"];
            $question["subjectID"] = $row["subject_id"];
            
            $question["question"] = $row["question"];
            $question["dateCreated"] = $row["date_created"];
            $question["status"] = $row["status"];
            array_push($listQuestion, $question);
        }
        return $listQuestion;
    }

    function replyQuestion($username, $questionID, $answer){
        $sql = "INSERT INTO crs_qna_answer (username, question_id, answer, status)
                VALUES ('$username', '$questionID', '$answer', 1)";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }


    function questionList($username){
        $sql = "SELECT * FROM crs_qna_question WHERE username='$username' ORDER BY date_created DESC";
        $result = Connection::connect()->query($sql);
        $listQuestion = array();
        while($row = $result->fetch_assoc()){
            $question = array();
            $question["questionID"] = $row["question_id"];
            $question["username"] = $row["username"];
            $question["subjectID"] = $row["subject_id"];
            $question["subjectName"] = self::getSubjectNameByID($question["subjectID"]);
            $question["answerList"] = self::getAnswerList($question["questionID"]);
            $question["question"] = $row["question"];
            $question["dateCreated"] = $row["date_created"];
            $question["status"] = $row["status"];
            array_push($listQuestion, $question);
        }
        return $listQuestion;
    }

    function getAnswerList($questionID){
        $sql = "SELECT * FROM crs_qna_answer WHERE question_id='$questionID' ORDER BY date_created DESC";
        $result = Connection::connect()->query($sql);
        $listAnswer = array();
        while($row = $result->fetch_assoc()){
            $answer = array();
            $answer["answerBy"] = $row["username"];
            $answer["answer"] = $row["answer"];
            $answer["dateAnswer"] = $row["date_created"];
            array_push($listAnswer, $answer);
        }
        return $listAnswer;
    }

    function addQuestion($username, $subjectID, $question){
        $sql = "INSERT INTO crs_qna_question (username, subject_id, question, status) VALUES('$username', '$subjectID', '$question', 1)";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function addAnswer($username, $questionID, $answer){
        $sql = "INSERT INTO crs_qna_answer (username, question_id, answer, status)
                VALUES ('$username', '$questionID', '$answer', 1)";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function questionExists($questionID){
        $sql = "SELECT question FROM crs_qna_question WHERE question_id='$questionID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            return true;
        }return false;
    }
}
