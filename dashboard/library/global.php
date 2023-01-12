<?php

function request2($data, $url){
    $query = http_build_query($data);
    $contextData = array(
                        "method" => "POST",
                        "header" => "Connection: close\r\n".
                                    "Context-Length: ".strlen($query)."\r\n",
                        "content" => $query);
    $context = stream_context_create(array('http' => $contextData));
    $result = file_get_contents(
        $url,
        false,
        $context
    );
    return $result;
}

function formating_input_from_date_event($dateEvent){
    $tempDateEvent = explode(" ", $dateEvent);
    $theDate = explode("/", $tempDateEvent[0]);
    $theTime = $tempDateEvent[1];
    return sprintf("%s-%s-%s %s:00", $theDate[2], $theDate[1], $theDate[0], $theTime);
}

function formating_date_event_to_input($dateEvent){
    $tempDateEvent = explode(" ", $dateEvent);
    $theDate = explode("-", $tempDateEvent[0]);
    $theTime = $tempDateEvent[1];
    return sprintf("%s/%s/%s %s", $theDate[2], $theDate[1], $theDate[0], $theTime);
}

function time_formatting($second){
    $second = $second;
    $hours = floor($second / 3600);
    $mins = floor($second / 60 % 60);
    $secs = floor($second % 60);
    // if($hours == 20){
    //     $mins = 0;
    //     $secs = 0;
    // }
    return sprintf("%02d:%02d:%02d", $hours, $mins, $secs);
}

function date_formating($dateVar){
    $year = substr($dateVar, 0, 4);
    $month = substr($dateVar, 5, 2);
    $day = substr($dateVar, 8, 2);
    $month_string = int_to_month($month);

    return $day." ".substr($month_string,0, 3)." ".$year;
    // return $dateVar;
}

function time_formating2($date_time_){
    $hrs = substr($date_time_, 11, 2);
    $min = substr($date_time_, 14, 2);
    $sec = substr($date_time_, 17, 2);


    return $hrs.":".$min;
}

function time_formating3($date_time_){
    $hrs = substr($date_time_, 11, 2);
    $min = substr($date_time_, 14, 2);
    $sec = substr($date_time_, 17, 2);


    return $hrs.":".$min.":".$sec;
}

function get_date_of_datetime($date_time_){
    return substr($date_time_, 0, 10);
}

function request($data, $url){
    $curl = curl_init();
    $query = http_build_query($data);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    if(curl_errno($curl)){
        return curl_error($curl);
    }
    curl_close($curl);

    return $result;
}

function validateDate($date, $format = 'Y/m/d'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function pointSelectOption($currentSubjectPoint){
    $pointList = array(
        1=> "0menit",
        900 => "15menit",
        1800 => "30menit",
        2700 => "45menit"
    );
    foreach($pointList as $point=>$minute){
        if($point==$currentSubjectPoint){
            echo '<option value="'.$point.'" selected>'.$minute.'</option>';
        }else{
            echo '<option value="'.$point.'">'.$minute.'</option>';
        }
        
    }

}

function filterUrlString($text){
    $stringArray = explode(" ", $text);
    $result = "";
    foreach($stringArray as $string){
        if (filter_var($string, FILTER_VALIDATE_URL)) {
            $result .= '<br><a href="'.$string.'"><span class="icon icon-share text-warning"> kunjungi link</span></a>';
        }else{
            $result .= $string;
        }
        $result .= " ";
    }
    return $result;
    
}


// EVENT
function move_file_event($filename, $tempDir, $_type_){

    if($_type_ == "thumbnail"){
        $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-thumbnails/".$filename;
    }else if($_type_ == "file-global"){
        $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-file-global/".$filename;
    }else if($_type_ == "file"){
        $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-files/".$filename;
    }else if($_type_ == "video"){
        $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-videos/".$filename;
    }else if($_type_ == "event-certificate-background"){
        $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-certificate-background/".$filename;
    }else{
        return false;
    }

    return move_uploaded_file($tempDir, $baseDrive) ? true : false;
}

function delete_file_event($filename, $_type_){
    if(strlen($filename) == 0 || $filename == "default.jpg" || $filename == "test-image.png" || $filename == "test-image-2.png" || $filename == "0"){
        return true;
    }
    if($_type_ == "thumbnail"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-thumbnails/".$filename;
    }else if($_type_ == "file-global"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-file-global/".$filename;
    }else if($_type_ == "file"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-files/".$filename;
    }else if($_type_ == "video"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-videos/".$filename;
    }else if($_type_ == "event-certificate-background"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-certificate-background/".$filename;
    }else{
        return true;
    }

    if(unlink($file)){
        return true;
    }
    return false;
}


// USER
function move_file_user($filename, $tempDir, $_type_){
    if($_type_ == "image"){
        $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-user/user-images/".$filename;
    }else if($_type_ == "file"){
        $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-user/user-files/".$filename;
    }else if($_type_ == "video"){
        $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-user/user-videos/".$filename;
    }else{
        return false;
    }

    return move_uploaded_file($tempDir, $baseDrive) ? true : false;
}

function delete_file_user($filename, $_type_){
    if(strlen($filename) == 0 || $filename == "default.jpg" || $filename == "test-image.png" || $filename == "test-image-2.png" || $filename == "0"){
        return true;
    }
    if($_type_ == "image"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-user/user-images/".$filename;
    }else if($_type_ == "file-global"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-file-global/".$filename;
    }else if($_type_ == "file"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-files/".$filename;
    }else if($_type_ == "video"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-videos/".$filename;
    }else if($_type_ == "event-certificate-background"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-certificate-background/".$filename;
    }else{
        return true;
    }

    if(unlink($file)){
        return true;
    }else{
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/images/".$filename;
        if(unlink($file)){
            return true;
        }
    }
    return false;
}


function move_file_userimage($filename){
    $oldDirectory = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/images/".$filename;
    $newDirectory = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-user/user-images/".$filename;
    if(file_exists($oldDirectory)){
        rename($oldDirectory, $newDirectory);
    }
}

function delete_image_file($filename){
    if(strlen($filename) == 0 || $filename == "default.jpg" || $filename == "0"){
        return true;
    }
    $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/images/".$filename;
    if(!file_exists($file)){
        return true;
    }
    if(unlink($file)){
        return true;
    }
    return false;
}

function delete_subject_file($filename){
    if(strlen($filename) == 0 || $filename == "0"){
        return true;
    }
    $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/courses/".$filename;
    if(!file_exists($file)){
        return true;
    }
    if(unlink($file)){
        return true;
    }
    return false;
}

function email_validator($email){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return false;
    }return true;
}

function username_validator($username){
    if(!preg_match("/^[A-Za-z0-9]*$/", $username) || strlen($username) < 6){
        return false;
    }return true;
}

function admin_verification($username, $code){
    $user = new User();
    if(!$user->isUserExists($username)){
        return false;
    }
    if(!$user->isAdmin($username) && !$user->isSuperAdmin($username)){
        return false;
    }
    if($code != "admin is cool you know"){
        return false;
    }
    return true;

}

function image_validator($imageObject, $username){
    $imageName = $imageObject['image']['name'];
    $imageError = $imageObject['image']['error'];
    $imageTempDir = $imageObject['image']['tmp_name'];
    $temp = explode('.', $imageName);
    $imgExt = strtolower(end($temp));
    $allowedExtension = array('jpg', 'jpeg', 'png');
    if(!empty($imageName) && strlen($imageName) != 0){
        if (in_array($imgExt, $allowedExtension)) {
            if ($imageError === 0) {
                $imageName = $username . "-" . uniqid('', true) . "." . $imgExt;

                return $imageName;

            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    return false;
}

function file_validator($fileObject, $formatFileName, $allowedExtension){
    $filename = $fileObject['name'];
    $fileError = $fileObject['error'];
    $fileTempDir = $fileObject['tmp_name'];
    $temp = explode('.', $filename);
    $fileExtension = strtolower(end($temp));
    if(!empty($filename) && strlen($filename) != 0){
        if (in_array($fileExtension, $allowedExtension)) {
            if ($fileError === 0) {
                $filename = $formatFileName . "-" . uniqid('', true) . "." . $fileExtension;
                return $filename;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    return false;
}

function file_validator_ignore_ex($fileObject, $type){
    $filename = $fileObject['name'];
    $fileError = $fileObject['error'];
    $fileTempDir = $fileObject['tmp_name'];
    $temp = explode('.', $filename);
    $fileExtension = strtolower(end($temp));
    if(!empty($filename) && strlen($filename) != 0){
        if ($fileError === 0) {
            $filename = $type . "-" . uniqid('', true) . "." . $fileExtension;
            return $filename;
        }else{
            return false;
        }
    }
    return false;
}

function move_file($filename, $tempDir, $desDir){
    $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/".$desDir."/".$filename;
    return move_uploaded_file($tempDir, $baseDrive) ? true : false;
}

function move_file_message($filename, $tempDir, $desDir){
    $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-message/".$desDir."/".$filename;
    return move_uploaded_file($tempDir, $baseDrive) ? true : false;
}



function move_file_course($filename, $tempDir, $_type_){

    if($_type_ == "thumbnail"){
        $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-course/course-thumbnails/".$filename;
    }else if($_type_ == "file"){
        $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-course/course-files/".$filename;
    }else if($_type_ == "video"){
        $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-course/course-videos/".$filename;
    }else{
        return false;
    }

    return move_uploaded_file($tempDir, $baseDrive) ? true : false;
}

function move_file_carousel($filename, $tempDir, $_type_){

    if($_type_ == "image"){
        $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-carousel/carousel-images/".$filename;
    }else if($_type_ == "file"){
        $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-carousel/carousel-files/".$filename;
    }else if($_type_ == "video"){
        $baseDrive = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-carousel/carousel-videos/".$filename;
    }else{
        return false;
    }
    return move_uploaded_file($tempDir, $baseDrive) ? true : false;
}

function delete_file_carousel($filename, $_type_){
    if(strlen($filename) == 0 || $filename == "default.jpg" || $filename == "0"){
        return true;
    }
    if($_type_ == "image"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-carousel/carousel-images/".$filename;
    }else if($_type_ == "file"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-carousel/carousel-file/".$filename;
    }else if($_type_ == "video"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-carousel/carousel-video/".$filename;
    }else{
        return true;
    }

    if(!file_exists($file)){
        if($_type_ == "thumbnail"){
            delete_image_file($filename);
        }else if($_type_ == "file"){
            delete_subject_file($filename);
        }
        return true;
    }
    if(unlink($file)){
        return true;
    }
    return false;
}

function delete_file_course($filename, $_type_){
    if(strlen($filename) == 0 || $filename == "default.jpg" || $filename == "0"){
        return true;
    }
    if($_type_ == "thumbnail"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-course/course-thumbnail/".$filename;
    }else if($_type_ == "file"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-course/course-file/".$filename;
    }else if($_type_ == "video"){
        $file = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-course/course-video/".$filename;
    }else{
        return true;
    }

    if(!file_exists($file)){
        if($_type_ == "thumbnail"){
            delete_image_file($filename);
        }else if($_type_ == "file"){
            delete_subject_file($filename);
        }
        return true;
    }
    if(unlink($file)){
        return true;
    }
    return false;
}

function superNiceDateFormat($dateTime){
    return date_formating($dateTime)." / ".time_formating2($dateTime);
}

function post_validator($array_post, $required){
    foreach ($required as $field) {
        if(empty($array_post[$field]) || strlen($array_post[$field]) < 1){
            return false;
        }
    }return true;
}

function post_check($array_post, $required, $length_required){
    foreach ($required as $field) {
        if(empty($array_post[$field])){
            return "Field kosong !";
        }

        if($field == "username"){
            if(!username_validator($array_post[$field])){
                return "Masukan username dengan benar";
            }
        }else if($field == "email"){
            if(!email_validator($array_post[$field])){
                return "Masukan email dengan benar";
            }
        }else if($field == "firstName" || $field == "lastName"){
            if(strlen($array_post[$field]) < 4){
                return "Nama depan / Nama belakang minimal 4 characters";
            }
        }else if($field == "gender"){
            if($array_post[$field] != "Laki-Laki" && $array_post[$field] != "Perempuan"){
                return "Pilih jenis kelamin";
            }
        }else{
            if(strlen($array_post[$field]) < $length_required){
                return "Username / Password Minimal 6 characters";
            }
        }
    }
    return "success";
}


function is2min($date_before, $date_after){
    $date_start = date_create($date_before);
    $check_date = date_create($date_after);

    $date_end = $date_before;
    date_sub($date_end, date_interval_create_from_date_string("2 minutes"));

    if($check_date >= $date_start && $check_date <= $date_end) {
        return true;
    }return false;
}

function encrypt($string){
    $result = "";
    $key = "hello world 2020!";

    return password_hash($string, PASSWORD_DEFAULT);
}

function birthMonth(){
   return array("01" => "Januari",
                "02" => "Februari",
                "03" => "Maret",
                "04" => "April",
                "05" => "Mei",
                "06" => "Juni",
                "07" => "Juli",
                "08" => "Agustus",
                "09" => "September",
                "10" => "Oktober",
                "11" => "November",
                "12" => "Desember");
}

function int_to_month($month){
    $month_string = "";
    if($month == 1){
        $month_string = "Januari";
    }else if($month == 2){
        $month_string = "Februari";
    }else if($month == 3){
        $month_string = "Maret";
    }else if($month == 4){
        $month_string = "April";
    }else if($month == 5){
        $month_string = "Mei";
    }else if($month == 6){
        $month_string = "Juni";
    }else if($month == 7){
        $month_string = "Juli";
    }else if($month == 8){
        $month_string = "Agustus";
    }else if($month == 9){
        $month_string = "September";
    }else if($month == 10){
        $month_string = "Oktober";
    }else if($month == 11){
        $month_string = "November";
    }else if($month == 12){
        $month_string = "Desember";
    }
    return $month_string;
}

