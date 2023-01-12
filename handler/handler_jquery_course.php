<?php

@session_start();


include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/course.php";


if(isset($_POST["totalDownloader"])){
    echo Subject::subjectXDownload($_POST["totalDownloader"]);
}else{
    echo "Error";
}
