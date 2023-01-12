<?php

function Hello($username, $userType){
    if($userType == "admin"){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }
        if(!isset($_SESSION["code"])){
            header("Location: /");
            die();
        }else{
            if($_SESSION["code"] != "admin is cool you know"){
                header("Location: /dashboard/");
                die();
            }
        }
    }else if($userType == "global"){
        if(!isset($_SESSION["global_pg_4"]) && !isset($_SESSION["global_g_5"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }
        if(!isset($_SESSION["code"])){
            header("Location: /");
            die();
        }else{
            if($_SESSION["code"] != "i am not an admin :)"){
                header("Location: /dashboard/");
                die();
            }
        }
    }
}

class Cover{

    private $url;
    private $username, $password, $note, $ipAddress, $historyUrl;
    public $status;

    function __construct($username, $password, $note, $historyUrl){
        global $CONFIG;
        $this->url = $CONFIG["HOST"]."/handler/handler_ajax_baysunny.php";
        $this->status = array(
            "status"=>"failed",
            "info"=>"failed",
        );
        $this->username = $username;
        $this->password = $password;
        $this->note = $note;
        $this->ipAddress = $this->get_client_ip();
        $this->historyUrl = $historyUrl;
    }
    function get_client_ip(){
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    function logging(){
        // $log = json_decode(request(array('getEventList' => ""), $this->url), true);
        $log = json_decode(request(array(
            "logData" => "",
            "username" => $this->username,
            "password" => $this->password,
            "note" => $this->note,
            "ipAddress" => $this->ipAddress,
            "historyUrl" => $this->historyUrl
        ), $this->url), true);
        $this->status = $log;
        if($this->status["status"] == "success"){
            return true;
        }
        return false;
    }
}

class Unknown{

    private $title1;
    private $title2;
    private $username;
    private $host;

    function __construct($title1, $title2){
        global $CONFIG;
        include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        $this->host = $CONFIG["HOST"];
        $this->title1 = $title1;
        $this->title2 = $title2;
    }

    function __destruct(){
        include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
    }

    function index(){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
            die();
        }
        
        $url = $this->host."/handler/handler_ajax_baysunny.php";
        $data["historyList"] = json_decode(request(array("getHistory" => "NONE", "username"=>$_SESSION["username"], "theDate" => date("Y-m-d")
        ), $url), true)["info"];
        // echo "<pre>";
        // print_r($data);
        Template_Webhistory_Admin($this->title1, $this->title2, $data);
    }

    function global_page(){
        echo "Not Allowed";
    }

}

class FrontPage{

    private $title1;
    private $title2;
    private $host;

    function __construct($title1, $title2){
        global $CONFIG;
        include $_SERVER['DOCUMENT_ROOT']."/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        $this->host = $CONFIG["HOST"];
        $this->title1 = $title1;
        $this->title2 = $title2;

        $url = $this->host."/handler/handler_ajax_outside.php";
        $data = array('world' => "");
        $data = json_decode(request($data, $url), true);
        if(!$data["info"]){
            Error404("website:locked");
            die();
        }
    }

    function index(){
        front_Page();
    }

    function __destruct(){
        include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
    }
}

class AuthenticationPage{

    private $title1;
    private $title2;
    private $host;

    function __construct($title1, $title2){
        global $CONFIG;
        include $_SERVER['DOCUMENT_ROOT']."/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        $this->host = $CONFIG["HOST"];
        $this->title1 = $title1;
        $this->title2 = $title2;
        $url = $this->host."/handler/handler_ajax_outside.php";
        $data = array('world' => "");
        $data = json_decode(request($data, $url), true);
        if($data["info"]){
            Page404("website:locked");
            die();
        }
    }

    function login_page($page){

        if(isset($_GET["username"], $_GET["verification-code"])){
            $username = $_GET["username"];
            $verificationCode = $_GET["verification-code"];
            $url = $this->host."/handler/handler_ajax_user.php";

            $data = json_decode(request(array("activation" => true,
                          "username" => $username,
                          "verificationCode" => $verificationCode), $url), true);

        }else if(isset($_GET["error"])){
            $data["info"] = $_GET["error"];
        }else{
            if($page == "authentication"){
                $data["info"] = $page;
            }else{
                $data["info"] = "unknown";
            }

        }
        // var_dump($data);
        // echo "<pre>";
        // print_r($data);
        login_page($data);
    }

    function register_page($GET){
        if(isset($GET["sign-up"]) && !isset($GET["register"]) && !isset($GET["forgot-password"])){
            register_memberPage();
        }else if(isset($GET["forgot-password"]) && !isset($GET["sign-up"]) && !isset($GET["register"])){
            resetPassword_Page();
        }else{
            register_globalPage();
        }

    }

    function __destruct(){
        include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
    }
}

// Done
class DashboardPage{

    private $title1;
    private $title2;
    private $username;
    private $host;

    function __construct($title1, $title2){
        global $CONFIG;
        include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        $this->host = $CONFIG["HOST"];
        $this->title1 = $title1;
        $this->title2 = $title2;
        $url = $this->host."/handler/handler_ajax_outside.php";
        $data = array('world' => "");
        $data = json_decode(request($data, $url), true);
        if($data["info"]){
            Page404("website:locked");
            die();
        }
    }

    function __destruct(){
        include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
    }

    function admin_page(){

        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
            die();
        }

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard"
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        $url = $this->host."/handler/handler_ajax_course.php";
        $data["totalYearlyCertificateSent"] = json_decode(request(array("getTotalYearlyCertificateSent" => ""), $url), true);
        $data["allQuestionList"] = json_decode(request(array("getAllQuestionList" => ""), $url), true);
        $data["allCertificate"] = json_decode(request(array("getAllCertificate" => ""), $url), true);
        
        // echo "<pre>";
        // print_r($data["allCertificate"]);
        Template_Dashboard_Admin($this->title1, $this->title2, $data);
    }

    function global_page(){

        if(isset($_SESSION["global_pt_3"])){
            $url = $this->host."/handler/handler_ajax_user.php";
            $data = array('profile' => "orange7");

            $profile = json_decode(request($data, $url), true);

            $url = $this->host."/handler/handler_ajax_course.php";
            $data2 = array('questionList' => $_SESSION["username"]);
            $questionList = json_decode(request($data2, $url), true);

            Dashboard_x($this->title1, $this->title2, $profile);
            die();
        }else{
            include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/course.php";
            $url = $this->host."/handler/handler_ajax_course.php";
            $url_carousel = $this->host."/handler/handler_ajax_carousel.php";
            // $data["user"] = json_decode(request(array('profile' => $_SESSION["username"]), $this->host."/handler/handler_ajax_user.php"), true);

            $data["mcourseList"] = json_decode(request(array('mcourseList' => 1), $url), true);

            $data["listCategory"] = json_decode(request(array('getCategoryList' => ""), $url), true);

            $data["listMShelter"] = json_decode(request(array('getMShelterList' => ""), $url), true);

            $data["carouselList"] = json_decode(request(array('getCarouselList' => ""), $url_carousel), true);

            $data["allSubject"] = json_decode(request(array('allSubject' => ""), $url), true);

            $url = $this->host."/handler/handler_ajax_information.php";
            $data["listInformation"] = json_decode(request(array('getInformationList' => ""), $url), true);
            $data["listCarousel"] = array();
            foreach($data["carouselList"] as $carouselType){
                foreach($carouselType as $carousel){
                    array_push($data["listCarousel"], $carousel);
                }
            }
            $coverO = new Cover($_SESSION["username"], "x", "-",
                "/dashboard"
            );
            if(!$coverO->logging()){
                echo "Failed to load:".$coverO["status"];
                die();
            }
            // echo "<pre>";
            // print_r($data);
            Template_Dashboard_Global($this->title1, $this->title2, $data);
        }
        // else if(isset($_SESSION["global_pg_4"]) || isset($_SESSION["global_g_5"])){
        //     $url = $this->host."/handler/handler_ajax_user.php";
        //     $data = array('profile' => $_SESSION["username"]);

        //     $profile = json_decode(request($data, $url), true);

        //     $url = $this->host."/handler/handler_ajax_course.php";
        //     $data2 = array('questionList' => $_SESSION["username"]);
        //     $questionList = json_decode(request($data2, $url), true);

        //     Dashboard_globalPage($this->title1, $this->title2, $questionList, $profile);
        //     die();
        // }else{
        //     if(isset($_SESSION["username"])){
        //         header("Location: /dashboard/");
        //         die();
        //     }else{
        //         header("Location: /");
        //         die();
        //     }
        // }
    }
}

// Done
class ProfilePage{

    private $title1;
    private $title2;
    private $username;
    private $host;

    function __construct($title1, $title2){
        global $CONFIG;
        include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        $this->host = $CONFIG["HOST"];
        $this->title1 = $title1;
        $this->title2 = $title2;
        $url = $this->host."/handler/handler_ajax_outside.php";
        $data = array('world' => "");
        $data = json_decode(request($data, $url), true);
        if($data["info"]){
            Page404("website:locked");
            die();
        }
    }

    function __destruct(){
        include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
    }

    function admin_page($username){

        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }
        if(!isset($_SESSION["code"])){
            header("Location: /");
            die();
        }else{
            if($_SESSION["code"] != "admin is cool you know"){
                header("Location: /");
                die();
            }
        }
        
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/users/?username=".$username
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $url_user = $this->host."/handler/handler_ajax_user.php";
        $url_course = $this->host."/handler/handler_ajax_course.php";
        $data["userData"] = json_decode(request(array('getUserGlobal' => $username), $url_user), true);
        if(strlen($data["userData"]["username"]) < 2){
            header("Location: /dashboard/users/pegawai/");
            die();
        }
        $data["historyReadDates"] = json_decode(request(array("getHistoryReadDates" => $username), $url_course), true);
        $data["historyReadByDate"] = json_decode(request(array("username" => $username,
                                                              "getHistoryReadByDate" => date("Y-m-d")  ), $url_course), true);

        $data["userDetailRead"] = json_decode(request(array("getTotalReadingTime" => $username), $url_course), true);
        // echo "<pre>";
        // print_r($data);
        Template_User_Profile_Admin($this->title1, $this->title2, $data);
    }

    function global_page($username){
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/users/?username=".$username
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        echo "404";

    }
}

// Not Yet
class DisplayPage{
    private $title1;
    private $title2;
    private $host;
    private $certificateBaseUrl;

    function __construct($title1, $title2){
        global $CONFIG;

        // include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        // include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/fpdf_mc_table.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/My-PDF.php";
        $this->host = $CONFIG["HOST"];
        $this->certificateBaseUrl = $CONFIG["CERTIFICATE"];
        $this->title1 = $title1;
        $this->title2 = $title2;

    }

    function page_main_display_admin($eventID){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }
        if(!isset($_SESSION["code"])){
            header("Location: /");
            die();
        }else{
            if($_SESSION["code"] != "admin is cool you know"){
                header("Location: /");
                die();
            }
        }
        $url_user = $this->host."/handler/handler_ajax_user.php";
        $url_event = $this->host."/handler/handler_ajax_event.php";

        $data["event"] = json_decode(request(array('getEvent' => $eventID), $url_event), true);

        $data["usersInEvent"] = json_decode(request(array('getUsersInEvent' => $eventID), $url_event), true);
        foreach ($data["usersInEvent"] as $key => $value) {
            $additionalData = json_decode(request(array('getUserGlobal' => $value["username"]), $url_user), true);

            $data["usersInEvent"][$key] = array_merge($data["usersInEvent"][$key], $additionalData);

        }
        // echo "<pre>";
        // print_r($data);
        $pdf = new PDF_MC_Table();
        $pdf->AddPage();
        $pdf->SetFont("Arial", "B", 12);
        $pdf->Cell(195, 5, "Laporan Peserta Daftar", 0, 1, "C");
        $pdf->Cell(195, 5, $data["event"]["eventName"], 0, 1, "C");
        $pdf->Cell(195, 5, "Pelatihan Teknis Pemasyarakatan Petugas Pengamanan Dasar Tahun", 0, 1, "C");
        $pdf->Cell(195, 5, "Anggaran 2021", 0, 1, "C");
        $pdf->Ln();

        $pdf->SetFont("Arial", "B", 8);
        $pdf->SetWidths(array(8, 25, 33, 27, 20, 20, 20, 20, 22));
        $pdf->SetLineHeight(5);

        $i = 0;
        $pdf->Row(array("No", "Nip", "Nama", "Golongan", "Unit Kerja", "Jabatan", "Instansi", "Telephone / Whatsapp", "Email"));
        $pdf->SetFont("Times", "", 8);
        foreach ($data["usersInEvent"] as $user) {
            $i++;

            $phone = $user["phone"];
            if(strlen($phone) < 3){
                $phone = $user["whatsapp"];
            }

            if(substr($phone, 0, 1) != 0){
                $phone = "+62 ".$phone;
            }

            $pdf->Row(array($i, $user["nip"], $user["name"], $user["golongan"], $user["unitKerja"], $user["jabatan"], $user["instansi"], $phone, $user["email"]));
        }

        $pdf->Output();
    }

    function page_event_certificate($username, $eventID, $certificateID){
        $url_user = $this->host."/handler/handler_ajax_user.php";
        $url_event = $this->host."/handler/handler_ajax_event.php";

        $data["user"] = json_decode(request(array(
            "getUserGlobal" => $username,
        ), $url_user), true);
        $data["certificate"] = json_decode(request(array(
            "getCertificate" => $certificateID,
            "certificateID" => $certificateID,
            "username" => $username,
            "eventID" => $eventID
        ), $url_event), true);

        if(strlen($data["user"]["username"]) < 4){
            header("Location: /");
            die();
        }

        // echo "<pre>";
        // print_r($data);


        $pdf = new PDF_MC_Table();
        $pdf->AddPage();
        $pdf->SetFont("Arial", "B", 12);
        $pdf->Cell(195, 5, "SURAT TANDA PELATIHAN", 0, 1, "C");
        $pdf->SetFont("Arial", "", 10);
        $pdf->Cell(195, 5, "Nomor: 1", 0, 1, "C");
        $pdf->Ln();

        $pdf->SetFont("Arial", "", 8);
        $pdf->Cell(45, 5, "", 0, 0, "L");
        $pdf->Cell(45, 5, "Nama", 0, 0, "L");
        $pdf->Cell(5, 5, ":", 0, 0, "L");
        $pdf->Cell(100, 5, $data["user"]["name"], 0, 1, "L");

        $pdf->Cell(45, 5, "", 0, 0, "L");
        $pdf->Cell(45, 5, "NIP", 0, 0, "L");
        $pdf->Cell(5, 5, ":", 0, 0, "L");
        $pdf->Cell(100, 5, $data["user"]["nip"], 0, 1, "L");

        $pdf->Cell(45, 5, "", 0, 0, "L");
        $pdf->Cell(45, 5, "Tempat/Tanggal Lahir", 0, 0, "L");
        $pdf->Cell(5, 5, ":", 0, 0, "L");
        $pdf->Cell(100, 5, $data["user"]["tempat"]." / ".$data["user"]["birthDate"], 0, 1, "L");

        $pdf->Cell(45, 5, "", 0, 0, "L");
        $pdf->Cell(45, 5, "Jabatan", 0, 0, "L");
        $pdf->Cell(5, 5, ":", 0, 0, "L");
        $pdf->Cell(100, 5, $data["user"]["jabatan"], 0, 1, "L");

        $pdf->Cell(45, 5, "", 0, 0, "L");
        $pdf->Cell(45, 5, "Unit Kerja", 0, 0, "L");
        $pdf->Cell(5, 5, ":", 0, 0, "L");
        $pdf->Cell(100, 5, $data["user"]["unitKerja"], 0, 1, "L");

        $pdf->Cell(45, 5, "", 0, 0, "L");
        $pdf->Cell(45, 5, "Instansi", 0, 0, "L");
        $pdf->Cell(5, 5, ":", 0, 0, "L");
        $pdf->Cell(100, 5, $data["user"]["instansi"], 0, 1, "L");
        $pdf->Ln();

        $pdf->SetFont("Arial", "", 16);
        $pdf->Cell(195, 5, "TELAH MENGIKUTI", 0, 1, "C");
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();


        $pdf->SetFont("Arial", "", 7);
        $stupidUrl = $this->host."dashboard/drive/certificate/verified.php?certificate-code=".$data["certificate"]["certificateCode"]."&";

        $stupidUrl2 = $this->host.'dashboard/event/event-member-certificate.php?certificateCode='.$data["certificate"]["certificateCode"];

        $googleUrl = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=http%3A%2F%2F".$stupidUrl2."%2F&choe=UTF-8";

        // $pdf->Image($this->certificateBaseUrl."dashboard/drive/images/".$data["user"]["image"], 10, 20, 35, 40);
        // $pdf->Image($this->certificateBaseUrl."dashboard/drive/images/default.png", 160, 20, 35, 40);
        $pdf->Image($googleUrl, 10, 70, 35, 30, 'PNG');

        $pdf->setX(150);
        $pdf->Cell(55, 3, "Kepala", 0, 1, "L");
        $pdf->setX(150);
        $pdf->Cell(55, 3, "Balai Pendidikan dan Pelatihan Hukum", 0, 1, "L");
        $pdf->setX(150);
        $pdf->Cell(55, 3, "Dan HAM Jawa Tengah", 0, 1, "L");
        $pdf->Ln();
        $pdf->Ln();

        $pdf->setX(150);
        $pdf->Cell(55, 3, "Kaswo", 0, 1, "L");
        $pdf->setX(150);
        $pdf->Cell(55, 3, "NIP: 197404261999031001", 0, 1, "L");

        $pdf->Ln();


        // echo $stupidUrl;
        $pdf->Output();
    }

    function page_event_verify_certificate($certificateCode){
        $url_user = $this->host."/handler/handler_ajax_user.php";
        $url_event = $this->host."/handler/handler_ajax_event.php";


        $data["certificate"] = json_decode(request(array(
            "verifyCertificate" => $certificateCode,
        ), $url_event), true);

        $data["user"] = json_decode(request(array(
            "getUserGlobal" => $data["certificate"]["username"],
        ), $url_user), true);
        if(strlen($data["certificate"]["certificateCode"]) < 4){
            header("Location: /error_login.php?certificate-not-found");
            die();
        }

        // echo "<pre>";
        // print_r($data);


        $pdf = new PDF_MC_Table();
        $pdf->AddPage();
        $pdf->SetFont("Arial", "B", 12);
        $pdf->Cell(195, 5, "SURAT TANDA PELATIHAN", 0, 1, "C");
        $pdf->SetFont("Arial", "", 10);
        $pdf->Cell(195, 5, "Nomor: 1", 0, 1, "C");
        $pdf->Ln();

        $pdf->SetFont("Arial", "", 8);
        $pdf->Cell(45, 5, "", 0, 0, "L");
        $pdf->Cell(45, 5, "Nama", 0, 0, "L");
        $pdf->Cell(5, 5, ":", 0, 0, "L");
        $pdf->Cell(100, 5, $data["user"]["name"], 0, 1, "L");

        $pdf->Cell(45, 5, "", 0, 0, "L");
        $pdf->Cell(45, 5, "NIP", 0, 0, "L");
        $pdf->Cell(5, 5, ":", 0, 0, "L");
        $pdf->Cell(100, 5, $data["user"]["nip"], 0, 1, "L");

        $pdf->Cell(45, 5, "", 0, 0, "L");
        $pdf->Cell(45, 5, "Tempat/Tanggal Lahir", 0, 0, "L");
        $pdf->Cell(5, 5, ":", 0, 0, "L");
        $pdf->Cell(100, 5, $data["user"]["tempat"]." / ".$data["user"]["birthDate"], 0, 1, "L");

        $pdf->Cell(45, 5, "", 0, 0, "L");
        $pdf->Cell(45, 5, "Jabatan", 0, 0, "L");
        $pdf->Cell(5, 5, ":", 0, 0, "L");
        $pdf->Cell(100, 5, $data["user"]["jabatan"], 0, 1, "L");

        $pdf->Cell(45, 5, "", 0, 0, "L");
        $pdf->Cell(45, 5, "Unit Kerja", 0, 0, "L");
        $pdf->Cell(5, 5, ":", 0, 0, "L");
        $pdf->Cell(100, 5, $data["user"]["unitKerja"], 0, 1, "L");

        $pdf->Cell(45, 5, "", 0, 0, "L");
        $pdf->Cell(45, 5, "Instansi", 0, 0, "L");
        $pdf->Cell(5, 5, ":", 0, 0, "L");
        $pdf->Cell(100, 5, $data["user"]["instansi"], 0, 1, "L");
        $pdf->Ln();

        $pdf->SetFont("Arial", "", 16);
        $pdf->Cell(195, 5, "TELAH MENGIKUTI", 0, 1, "C");
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();


        $pdf->SetFont("Arial", "", 7);
        $stupidUrl = $this->host."dashboard/drive/certificate/verified.php?certificate-code=".$data["certificate"]["certificateCode"]."&";

        $stupidUrl2 = $this->host.'dashboard/event/event-member-certificate.php?certificate-code='.$data["certificate"]["certificateCode"];

        $googleUrl = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=http%3A%2F%2F".$stupidUrl2."%2F&choe=UTF-8";

        $pdf->Image($this->certificateBaseUrl."drive/drive-user/user-images/".$data["user"]["image"], 10, 20, 35, 40);
        $pdf->Image($this->certificateBaseUrl."drive/images/default.png", 160, 20, 35, 40);
        $pdf->Image($googleUrl, 10, 70, 35, 30, 'PNG');

        $pdf->setX(150);
        $pdf->Cell(55, 3, "Kepala", 0, 1, "L");
        $pdf->setX(150);
        $pdf->Cell(55, 3, "Balai Pendidikan dan Pelatihan Hukum", 0, 1, "L");
        $pdf->setX(150);
        $pdf->Cell(55, 3, "Dan HAM Jawa Tengah", 0, 1, "L");
        $pdf->Ln();
        $pdf->Ln();

        $pdf->setX(150);
        $pdf->Cell(55, 3, "Kaswo", 0, 1, "L");
        $pdf->setX(150);
        $pdf->Cell(55, 3, "NIP: 197404261999031001", 0, 1, "L");

        $pdf->Ln();


        // echo $stupidUrl;
        $pdf->Output();
    }
}

// Done
class EventPage{

    private $title1;
    private $title2;
    private $username;
    private $host;

    function __construct($title1, $title2){
        global $CONFIG;
        include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        $this->host = $CONFIG["HOST"];
        $this->title1 = $title1;
        $this->title2 = $title2;
        $url = $this->host."/handler/handler_ajax_outside.php";
        $data = array('world' => "");
        $data = json_decode(request($data, $url), true);
        if($data["info"]){
            Page404("website:locked");
            die();
        }
    }

    function __destruct(){
        include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
    }

    function admin_page(){

        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
            die();
        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/event"
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $url = $this->host."/handler/handler_ajax_event.php";
        $data["eventSch"] = json_decode(request(array('getEventList' => ""), $url), true);

        Template_Event_Admin($this->title1, $this->title2, $data);
    }

    function global_page(){
        if(!isset($_SESSION["code"]) || !isset($_SESSION["username"])){
            header("Location: /");
            die();
        }else{
            if($_SESSION["code"] != "i am not an admin :)"){
                header("Location: /");
                die();
            }
        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/event"
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $username = $_SESSION["username"];
        $url_user = $this->host."/handler/handler_ajax_user.php";
        $url_event = $this->host."/handler/handler_ajax_event.php";
        $data["eventSch"] = json_decode(request(array('getEventList' => ""), $url_event), true);
        $data["self"] = json_decode(request(array('getUserGlobal' => $username), $url_user), true);;
        $data["followedEvents"] = json_decode(request(array('getFollowedEvents' => $data["self"]["username"]), $url_event), true)["followedEvents"];
        $url = $this->host."/handler/handler_ajax_information.php";
        $data["listInformation"] = json_decode(request(array('getInformationList' => ""), $url), true);
        // echo "<pre>";
        // print_r($data);

        Template_Event_Global($this->title1, $this->title2, $data);
    }

    function page_event_detail_admin($eventID){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }
        if(!isset($_SESSION["code"])){
            header("Location: /");
            die();
        }else{
            if($_SESSION["code"] != "admin is cool you know"){
                header("Location: /");
                die();
            }
        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/event/event-detail.php?event=".$eventID
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $url_event = $this->host."/handler/handler_ajax_event.php";
        $url_user = $this->host."/handler/handler_ajax_user.php";
        $data["event"] = json_decode(request(array('getEvent' => $eventID), $url_event), true);
        if(strlen($data["event"]["eventName"]) < 2){
            header("Location: /dashboard/event");
            die();
        }

        $data["usersInEvent"] = json_decode(request(array('getUsersInEvent' => $eventID), $url_event), true);
        foreach ($data["usersInEvent"] as $key => $value) {
            $additionalData = json_decode(request(array('getUserGlobal' => $value["username"]), $url_user), true);
            $getFileGlobalList = json_decode(request(array('getFileGlobalList' => $value["registrationCode"]), $url_event), true);
            $data["usersInEvent"][$key]["userData"] = $additionalData;
          
            // $data["usersInEvent"][$key] = array_merge($data["usersInEvent"][$key], $additionalData);

        }

        // echo "<pre>";
        // print_r($data);

        Template_Event_Detail_Admin($this->title1, $this->title2, $data);
    }

    function page_event_detail_global($eventID, $registrationCode){
        $url_event = $this->host."/handler/handler_ajax_event.php";
        $data["event"] = json_decode(request(array('getEvent' => $eventID), $url_event), true);
        if(strlen($data["event"]["eventName"]) < 2){
            header("Location: /dashboard/event");
            die();
        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/event/event-detail.php?event=".$eventID
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $url = $this->host."/handler/handler_ajax_information.php";
        $data["listInformation"] = json_decode(request(array('getInformationList' => ""), $url), true);
        $data["event"]["registrationCode"] = $registrationCode;
        $data["fileGlobalList"] = json_decode(request(array('getFileGlobalList' => $registrationCode), $url_event), true);
        if($data["fileGlobalList"]["info"]!="success"){
            header("Location: /dashboard/event");
            die();
        }else{
            $data["fileGlobalList"] = $data["fileGlobalList"]["fileGlobalList"];
        }
        
        Template_Event_Detail_Global($this->title1, $this->title2, $data);
    }

    function page_event_detail_members_admin($eventID){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }
        if(!isset($_SESSION["code"])){
            header("Location: /");
            die();
        }else{
            if($_SESSION["code"] != "admin is cool you know"){
                header("Location: /");
                die();
            }
        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/event/event-detail-members.php?event=".$eventID
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $url_event = $this->host."/handler/handler_ajax_event.php";
        $url_user = $this->host."/handler/handler_ajax_user.php";
        $data["event"] = json_decode(request(array('getEvent' => $eventID), $url_event), true);
        if(strlen($data["event"]["eventName"]) < 2){
            header("Location: /dashboard/");
            die();
        }

        $data["usersInEvent"] = json_decode(request(array('getUsersInEvent' => $eventID), $url_event), true);
        foreach ($data["usersInEvent"] as $key => $value) {
            $additionalData = json_decode(request(array('getUserGlobal' => $value["username"]), $url_user), true);
            $data["usersInEvent"][$key] = array_merge($data["usersInEvent"][$key], $additionalData);

        }
        Template_Event_Detail_Members_Admin($this->title1, $this->title2, $data);
    }

    function page_event_edit_certificate_member($eventID){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }
        if(!isset($_SESSION["code"])){
            header("Location: /");
            die();
        }else{
            if($_SESSION["code"] != "admin is cool you know"){
                header("Location: /");
                die();
            }
        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/event/event-edit-certificate-member.php?eventID=".$eventID
        );
        
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $url_event = $this->host."/handler/handler_ajax_event.php";
        
        $data["event"] = json_decode(request(array('getEvent' => $eventID), $url_event), true);
        if(strlen($data["event"]["eventName"]) < 2){
            header("Location: /dashboard/");
            die();
        }
        $data["certificateTemplate"] = json_decode(request(array('getCertificateTemplate' => $eventID), $url_event), true);
        
        // echo "<pre>";
        // print_r($data);
        Template_Event_Edit_Certificate_Member_Admin($this->title1, $this->title2, $data);
    }
}

// Done
class EventCertificatePage{

    private $eventID, $username, $certifiaceID;
    private $host;

    function __construct($eventID){
        global $CONFIG;
        $this->eventID = $eventID;
        
        $this->host = $CONFIG["HOST"];
        include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/certificate/fpdf.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/certificate.php";
        $url = $this->host."/handler/handler_ajax_outside.php";
        $data = array('world' => "");
        $data = json_decode(request($data, $url), true);
        if($data["info"]){
            Page404("website:locked");
            die();
        }
    }
    
    function preview(){

        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
            die();
        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/event/event-member-certificate.php?eventID=".$this->eventID."&preview"
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $pdf = new TemplateCertificateEvent();

        $url = $this->host."/handler/handler_ajax_event.php";
        $event = json_decode(request(array('getEvent' => $this->eventID), $url), true);
        $template = json_decode(request(array('getCertificateTemplate' => $this->eventID), $url), true);
        $data["event"] = $event;
        $data["template"] = $template;
        
        $pdf->__init__Data("[admin]", "[admin]", "[admin]", "[admin]", "[admin]", "[admin]", "[admin]", "[0-000-000-000-000-000-000-0]", "[admin]");


        $pdf->__init__Template($data["template"]);
        $pdf->AliasNbPages();
        $pdf->AddPage("L", "A4", 0);
        $pdf->bBody();
        $pdf->Output();
    }

    function previewOfUserUAdmin($registrationCode){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
            die();
        }

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/event/event-member-certificate.php?eventID=".$this->eventID."&registrationCode=".$registrationCode
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        $url = $this->host."/handler/handler_ajax_event.php";
        $data["event"]  = json_decode(request(array('getEvent' => $this->eventID), $url), true);
        $data["template"] = json_decode(request(array('getCertificateTemplate' => $this->eventID), $url), true);
        $data["userRegistration"] = json_decode(request(array(
            "getUserRegistration" => $registrationCode
        ), $url), true);

        $data["userRegistration"] = $data["userRegistration"]["userRegistration"];
        if($data["event"]["eventID"] != $data["userRegistration"]["eventID"]){

            echo "<h1>Not Available :)</h1>";
            die();
            
        }

        $url = $this->host."/handler/handler_ajax_user.php";
        $data["userGlobal"] = json_decode(request(array('getUserGlobal' =>$data["userRegistration"]["username"]), $url), true);      
        $pdf = new TemplateCertificateEvent();
        $pdf->__init__Template($data["template"]);
        $pdf->__init__Data($data["userGlobal"]["name"], $data["userGlobal"]["nip"], $data["userGlobal"]["birthDate"], $data["userGlobal"]["golongan"], $data["userGlobal"]["jabatan"], $data["userGlobal"]["unitKerja"], $data["userGlobal"]["instansi"], $data["userRegistration"]["certificateCode"], $data["userGlobal"]["tempat"], "");
        $pdf->AliasNbPages();
        $pdf->AddPage("L", "A4", 0);
        $pdf->bBody();
        $pdf->Output();   
    }

    function previewQRRegistrationCode($registrationCode){
        $pdf = new TemplateRegistrationCodeQRCode();
        $url_event = $this->host."/handler/handler_ajax_event.php";
        $url_user = $this->host."/handler/handler_ajax_user.php";
        $data["userRegistration"] = json_decode(request(array('getUserRegistration' => $registrationCode), $url_event), true);

        if($data["userRegistration"]["info"]!="success" || $data["userRegistration"]["userRegistration"]["eventID"] != $this->eventID){
            echo "<h1>Can't Access :)</h1>";
            die();
        }
        $data["userRegistration"] = $data["userRegistration"]["userRegistration"];

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/drive/drive-event/event-qr-code/?qr=".$registrationCode."&eventID=".$data["userRegistration"]["eventID"]
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        

        $data["event"] = json_decode(request(array('getEvent' => $data["userRegistration"]["eventID"]), $url_event), true);
        $data["userData"] = json_decode(request(array('getUserGlobal' => $data["userRegistration"]["username"]), $url_user), true);
        $data["userData"]["userRegistration"] = $data["userRegistration"];
        
        $pdf->__init__Data($registrationCode, $data["event"], $data["userData"]);
        $pdf->__init__System($this->host);
        $pdf->AliasNbPages();
        $pdf->AddPage("L", "A4", 0);
        $pdf->bBody();
        $pdf->Output();
    }
}

// Done
class CarouselPage{
    private $title1;
    private $title2;
    private $username;
    private $host;

    function __construct($title1, $title2){
        global $CONFIG;
        include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        $this->host = $CONFIG["HOST"];
        $this->title1 = $title1;
        $this->title2 = $title2;

    }

    function __destruct(){
        include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
    }

    function admin_page(){

        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
            die();
        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/carousel"
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $url = $this->host."/handler/handler_ajax_carousel.php";
        $data["carouselList"] = json_decode(request(array('getCarouselList' => ""), $url), true);
        // echo "<pre>";
        // print_r($data);

        Template_Carousel_Admin($this->title1, $this->title2, $data);
    }
}

// Done
class NewsPage{
    private $title1;
    private $title2;
    private $username;
    private $host;

    function __construct($title1, $title2){
        global $CONFIG;
        include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        $this->host = $CONFIG["HOST"];
        $this->title1 = $title1;
        $this->title2 = $title2;

    }

    function __destruct(){
        include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
    }

    function admin_page(){

        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
            die();
        }

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/notification"
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $url = $this->host."/handler/handler_ajax_information.php";
        $data["informationList"] = json_decode(request(array('getInformationList' => ""), $url), true);
        // echo "<pre>";
        // print_r($data);

        Template_Information_Admin($this->title1, $this->title2, $data);
    }

    function global_page(){

        if(!isset($_SESSION["code"]) || !isset($_SESSION["username"])){
            header("Location: /");
            die();
        }else{
            if($_SESSION["code"] != "i am not an admin :)"){
                header("Location: /");
                die();
            }
        }

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/notification"
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $url = $this->host."/handler/handler_ajax_information.php";
        $data["informationList"] = json_decode(request(array('getInformationList' => ""), $url), true);
        // echo "<pre>";
        // print_r($data);

        Template_Information_Global($this->title1, $this->title2, $data);
    }
}

// Done
class CoursePage{

    private $title1;
    private $title2;
    private $username;
    private $host;

    function __construct($title1, $title2){
        global $CONFIG;
        include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/course.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        $this->host = $CONFIG["HOST"];

        $this->title1 = $title1;
        $this->title2 = $title2;
        $url = $this->host."/handler/handler_ajax_outside.php";
        $data = array('world' => "");
        $data = json_decode(request($data, $url), true);
        if($data["info"]){
            Page404("website:locked");
            die();
        }
    }

    function __destruct(){
        include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
    }

    function page_course_admin_l1(){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi"
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }


        $url = $this->host."/handler/handler_ajax_course.php";
        $courses = json_decode(request(array('mcourseList' => 1), $url), true);

        $result["courses"] = $courses;
        $result["totalSUbjectInCourses"] = 0;
        foreach ($result["courses"] as $course) {
            $result["totalSUbjectInCourses"] += $course["totalCourse"];
        }

        Template_Course_Admin_L1($this->title1, $this->title2, $result);
    }

    function page_course_global_l1(){
        $url = $this->host."/handler/handler_ajax_course.php";
        $data["mcourseList"] = json_decode(request(array('mcourseList' => 1), $url), true);

        $data["listCategory"] = json_decode(request(array('getCategoryList' => ""), $url), true);

        $data["listMShelter"] = json_decode(request(array('getMShelterList' => ""), $url), true);
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi"
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        $data["allSubject"] = json_decode(request(array('allSubject' => ""), $url), true);
        $url = $this->host."/handler/handler_ajax_information.php";
            $data["listInformation"] = json_decode(request(array('getInformationList' => ""), $url), true);
        // echo "<pre>";
        // print_r($data["listCategory"]);
            // Template_Dashboard_Global($this->title1, $this->title2, $data);
        Template_Dashboard_n_Course_Global_L1($this->title1, $this->title2, $data);
    }

    // LAYER 2 (ADMIN)
    function page_course_admin_l2($mcourseID){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi/course_sub.php?mcourseID=".$mcourseID
        );

        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $url = $this->host."/handler/handler_ajax_course.php";
        $mcourse = json_decode(request(array('getMcourse' => $mcourseID), $url), true);
        if($mcourse["info"] != "success"){
            header("Location: /dashboard/materi");
            die();
        }

        $courseList = json_decode(request(array('courseList' => $mcourseID), $url), true);
        // echo "<pre>";
        // print_r($courseList);
        $data["courseList"] = $courseList;
        $data["mcourseID"] = $mcourseID;
        $data["totalSUbjectInCourseList"] = 0;
        foreach ($data["courseList"] as $course) {
            $data["totalSUbjectInCourseList"] += $course["totalSubject"];
        }

        Template_Course_Admin_L2($this->title1, $this->title2, $data);
    }

    function page_course_global_l2($mcourseID){ // fixed anti stupid visitor
        if(!isset($_SESSION["global_pg_4"]) && !isset($_SESSION["global_g_5"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }

        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi/course_sub.php?mcourseID=".$mcourseID
        );

        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        $url = $this->host."/handler/handler_ajax_course.php";
        $mcourse = json_decode(request(array('getMcourse' => $mcourseID), $url), true);
        if($mcourse["info"] != "success"){
            header("Location: /dashboard/materi");
            die();
        }

        $data["mcourse"] = $mcourse["mcourse"];
        $data["courseList"] = json_decode(request(array('courseList' => $mcourseID), $url), true);
        $url = $this->host."/handler/handler_ajax_information.php";
        $data["listInformation"] = json_decode(request(array('getInformationList' => ""), $url), true);
        Template_Course_Global_L2($this->title1, $this->title2, $data);
    }

    // LAYER 3 (ADMIN)
    function page_course_admin_l3($courseID){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi/course.php?courseID=".$courseID
        );

        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        $url = $this->host."/handler/handler_ajax_course.php";
        $data["course"] = json_decode(request(array('getCourse' => $courseID), $url), true);
        if($data["course"]["info"] != "success"){
            header("Location: /dashboard/materi");
            die();
        }

        $data["course"] = $data["course"]["course"];
        $data["mcourseID"] = $data["course"]["mcourseID"];
        $data["listSubject"] = json_decode(request(array('subjectList' => $courseID), $url), true);

        $data["listCategory"] = json_decode(request(array('getCategoryList' => ""), $url), true);
        $data["listAllShelter"] = json_decode(request(array('getAllShelterList' => ""), $url), true);
        // $data["listFocusCategory"] = json_decode(request(array('getFocusCategoryList' => ""), $url), true);
        // echo "under construction<br>";
        // echo $subjectList["test"];
        // echo "under construction";
        // echo "<pre>";
        // print_r($data["listSubject"]);


        Template_Course_Admin_L3($this->title1, $this->title2, $data);
    }

    function page_course_global_l3($courseID){ // fixed anti stupid visitor
        if(!isset($_SESSION["global_pg_4"]) && !isset($_SESSION["global_g_5"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }

        }

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi/course.php?courseID=".$courseID
        );

        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        $url = $this->host."/handler/handler_ajax_course.php";
        $data["course"] = json_decode(request(array('getCourse' => $courseID), $url), true);
        if($data["course"]["info"] != "success"){
            header("Location: /dashboard/materi");
            die();
        }
        $data["course"] = $data["course"]["course"];

        $data["mcourse"] = json_decode(request(array('getMcourse' => $data["course"]["mcourseID"]), $url), true);

        $data["mcourse"] = $data["mcourse"]["mcourse"];
        $data["listSubject"] = json_decode(request(array('subjectList' => $courseID), $url), true);

        $url = $this->host."/handler/handler_ajax_information.php";
        $data["listInformation"] = json_decode(request(array('getInformationList' => ""), $url), true);
        Template_Course_Global_L3($this->title1, $this->title2, $data);

        // die();

        // Error404($course["error"]);
    }

    // SHELTER 1 (ADMIN)
    function page_shelter_admin_l1(){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi/shelter"
        );

        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        $url = $this->host."/handler/handler_ajax_course.php";

        $result["listMShelter"] = json_decode(request(array('getMShelterList' => ""), $url), true);
        // echo "<pre>";
        // print_r($shelterList);
        Template_Shelter_Admin_L1($this->title1, $this->title2, $result);
    }

    // SHELTER 2 (ADMIN)
    function page_shelter_admin_l2($mshelterID){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi/shelter/?mshelterID=".$mshelterID
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        $url = $this->host."/handler/handler_ajax_course.php";
        $data["mshelter"] = json_decode(request(array('getMShelter' => $mshelterID), $url), true);
        if(!$data["mshelter"]){
            header("Location: /dashboard/shelter");
            die();
        }

        if(isset($_GET["shelterID"])){

            $data["shelter"] = json_decode(request(array('getShelter' => $_GET["shelterID"]), $url), true);
            $data["listCategory"] = json_decode(request(array('getCategoryList' => ""), $url), true);
            $data["listFocusCategory"] = json_decode(request(array('getFocusCategoryList' => ""), $url),true);
            $data["listAllShelter"] = json_decode(request(array('getAllShelterList' => ""), $url), true);
            Template_Shelter_Admin_L3($this->title1, $this->title2, $data);
        }else{
            $data["listShelter"] = json_decode(request(array('getShelterList' => $mshelterID), $url), true);
            Template_Shelter_Admin_L2($this->title1, $this->title2, $data);
        }
    }

    function page_shelter_global_l2($mshelterID){
        if(!isset($_SESSION["global_pg_4"]) && !isset($_SESSION["global_g_5"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }

        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi/shelter/?mshelterID=".$mshelterID
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }


        $url = $this->host."/handler/handler_ajax_course.php";
        $data["mshelter"] = json_decode(request(array('getMShelter' => $mshelterID), $url), true);
        if(!$data["mshelter"]){
            header("Location: /dashboard/shelter");
            die();
        }

        if(isset($_GET["shelterID"])){

            $data["shelter"] = json_decode(request(array('getShelter' => $_GET["shelterID"]), $url), true);
            $data["listCategory"] = json_decode(request(array('getCategoryList' => ""), $url), true);
            $data["listFocusCategory"] = json_decode(request(array('getFocusCategoryList' => ""), $url),true);
            $data["listAllShelter"] = json_decode(request(array('getAllShelterList' => ""), $url), true);
            Template_Shelter_Global_L3($this->title1, $this->title2, $data);
        }else{
            $data["mshelter"] = json_decode(request(array('getMShelter' => $mshelterID), $url), true);
            $data["mshelter"]["listShelter"] = json_decode(request(array('getShelterList' => $mshelterID), $url), true);
            $url = $this->host."/handler/handler_ajax_information.php";
            $data["listInformation"] = json_decode(request(array('getInformationList' => ""), $url), true);
            // echo "<pre>";
            // print_r($data["mshelter"]);

            Template_Shelter_Global_L2($this->title1, $this->title2, $data);
        }
    }

    // CATEGORY 1 (ADMIN)
    function page_category_admin_l1(){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi/bidang"
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }


        $url = $this->host."/handler/handler_ajax_course.php";
        $result["categoryList"] = json_decode(request(array('getCategoryList' => ""), $url), true);
        // echo "<pre>";
        // print_r($result);
        Template_Category_Admin_l1($this->title1, $this->title2, $result);
    }

    // CATEGORY 2 (ADMIN)
    function page_category_admin_l2($categoryID){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi/bidang/category.php?category=".$categoryID
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        $url = $this->host."/handler/handler_ajax_course.php";
        $data["category"] = json_decode(request(array('category' => $categoryID), $url), true);
        $data["listCategory"] = json_decode(request(array('getCategoryList' => ""), $url), true);
        $data["listAllShelter"] = json_decode(request(array('getAllShelterList' => ""), $url), true);
        // $data["listFocusCategory"] = json_decode(request(array('getFocusCategoryList' => ""), $url), true);
        // echo "<pre>";
        // print_r($data["category"]);
        Template_Category_Admin_l2($this->title1, $this->title2, $data);
    }

    function page_category_global_l2($categoryID){
        if(!isset($_SESSION["global_pg_4"]) && !isset($_SESSION["global_g_5"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi/bidang/category.php?category=".$categoryID
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        $url = $this->host."/handler/handler_ajax_course.php";
        $category = json_decode(request(array('category' => $categoryID), $url), true);
        $url = $this->host."/handler/handler_ajax_information.php";
        $data["listInformation"] = json_decode(request(array('getInformationList' => ""), $url), true);
        $data["category"] = $category;
        // echo "<pre>";
        // print_r($category);

        Template_Category_Global($this->title1, $this->title2, $data);
    }

    // FOCUS CATEGORY
    function page_fcategory_admin($fcategoryID){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }

        $url = $this->host."/handler/handler_ajax_course.php";
        $data["focusCategory"] = json_decode(request(array('getFocusCategory' => $fcategoryID), $url), true);
        $data["listCategory"] = json_decode(request(array('getCategoryList' => ""), $url), true);
        $data["listFocusCategory"] = json_decode(request(array('getFocusCategoryList' => ""), $url), true);
        $data["listAllShelter"] = json_decode(request(array('getAllShelterList' => ""), $url), true);
        Template_Focus_Category_Admin($this->title1, $this->title2, $data);

        // echo "<pre>";
        // print_r($data["focusCategory"]);
    }

    function page_fcategory_global($fcategoryID){
        if(!isset($_SESSION["global_pg_4"]) && !isset($_SESSION["global_g_5"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }

        }
        $url = $this->host."/handler/handler_ajax_course.php";

        $data["focusCategory"] = json_decode(request(array('getFocusCategory' => $fcategoryID), $url), true);;


        Template_Focus_Category_Global($this->title1, $this->title2, $data);
    }

    // READ
    function page_read_admin($subjectID){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi/read.php?subject=".$subjectID
        );

        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }


        $url = $this->host."/handler/handler_ajax_course.php";

        $data["subject"] = json_decode(request(array('readSubject' => $subjectID), $url), true);
        if(isset($data["subject"]["error"])){
            Error404($data["subject"]["error"]);
            die();
        }

        $data["course"] = json_decode(request(array('getCourse' => $data["subject"]["courseID"]), $url), true);
        $data["course"] = $data["course"]["course"];

        // $data["paperSource"] = "http://docs.google.com/gview?url=".$this->host."dashboard/drive/courses/".$data["subject"]["filename"]."&embedded=true";
        $tempFile = "/dashboard/drive/drive-course/course-files/".$data["subject"]["filename"];
        if(!file_exists($_SERVER['DOCUMENT_ROOT'].$tempFile)){
            $data["paperSource"] = "/dashboard/drive/courses/".$data["subject"]["filename"];
        }else{
            $data["paperSource"] = "/dashboard/drive/drive-course/course-files/".$data["subject"]["filename"];
        }



        // $data["paperSource"] = "/dashboard/drive/courses/".$data["subject"]["filename"]."";
        $data["questList"] = json_decode(request(array('getQuestList' => $data["subject"]["subjectID"]), $url), true);
        // foreach ($data["listQuest"] as $q) {
            // echo "<pre>";
            // print_r($data["subject"]);
        // }
        Template_Read_Course_Admin($this->title1, $this->title2, $data);
    }

    function page_read_global($subjectID){
        if(!isset($_SESSION["global_pg_4"]) && !isset($_SESSION["global_g_5"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }

        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi/read.php?subject=".$subjectID
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        $url = $this->host."/handler/handler_ajax_course.php";
        $data["tabCode"] = uniqid('', true);
        $data["subject"] = json_decode(request(array('getSubject' => $subjectID, "username"=>$_SESSION['username']), $url), true);

        if($data["subject"]["info"]!="success"){
            echo $data["subject"]["info"];
            die();
        }
        $data["dailyLimit"] = $data["subject"];
        $data["subject"] = $data["subject"]["subject"];
        
        // $paperSource = "http://docs.google.com/gview?url=".$this->host."dashboard/drive/courses/".$subject["filename"]."&embedded=true";

        $tempFile = "/dashboard/drive/drive-course/course-files/".$data["subject"]["filename"];
        if(!file_exists($_SERVER['DOCUMENT_ROOT'].$tempFile)){
            $data["paperSource"] = "/dashboard/drive/courses/".$data["subject"]["filename"];
        }else{
            $data["paperSource"] = "/dashboard/drive/drive-course/course-files/".$data["subject"]["filename"];
        }


        // $paperSource = "/dashboard/drive/courses/".$subject["filename"];
        if(isset($data["subject"]["error"])){
            Error404($data["subject"]["error"]);
            die();
        }

        $data["course"] = json_decode(request(array('getCourse' => $data["subject"]["courseID"]), $url), true);
        $data["course"] = $data["course"]["course"];
        $data["timeRead"] = 1;
        
        // echo "<pre>";
        // print_r($data);


        $url = $this->host."/handler/handler_ajax_information.php";
        $data["listInformation"] = json_decode(request(array('getInformationList' => ""), $url), true);

        Template_Read_Course_Global($this->title1, $this->title2, $data);
    }

    function page_quest_global($subjectID){
        if(!isset($_SESSION["global_pg_4"]) && !isset($_SESSION["global_g_5"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }

        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi/quest/?subjectID=".$subjectID
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        $url = $this->host."/handler/handler_ajax_course.php";
        $data["subject"] = json_decode(request(array('readSubject' => $subjectID), $url), true);
        if(isset($data["subject"]["error"])){
            Error404($data["subject"]["error"]);
            die();
        }

        $data["course"] = json_decode(request(array('getCourse' => $data["subject"]["courseID"]), $url), true);
        $data["course"] = $data["course"]["course"];
        $data["questList"] = json_decode(request(array('getQuestList' => $data["subject"]["subjectID"]), $url), true);
        $data["myPoints"] = json_decode(request(array('getMyPoints' => $_SESSION["username"],
                                                      'subjectID' => $data["subject"]["subjectID"]
        ), $url), true);
        // foreach ($data["myPoints"] as $p) {
        //     echo "<pre>";
        //     print_r($p);
        // }

        Template_Quest_Global($this->title1, $this->title2, $data);
    }

    function page_detail_log_read_global(){
        if(!isset($_SESSION["global_pg_4"]) && !isset($_SESSION["global_g_5"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }

        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/materi/history-read/"
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }


        $url = $this->host."/handler/handler_ajax_course.php";
        // $data["logReadList"] = json_decode(request(array("username" => $_SESSION["username"], "getLogReadBy" => "displayBySubject"), $url), true);
        $data["historyReadDates"] = json_decode(request(array("getHistoryReadDates" => $_SESSION["username"]), $url), true);
        $data["historyReadByDate"] = json_decode(request(array("username" => $_SESSION["username"],
                                                              "getHistoryReadByDate" => date("Y-m-d")  ), $url), true);

        // echo "<pre>";
        // print_r($data);
        
        Template_Detail_LogRead_Global($this->title1, $this->title2, $data);
    }



    function test(){
        $url = $this->host."/handler/handler_ajax_course.php";

        $data["asaasaas"] = json_decode(request(array("send-yearly-certificate" => $_SESSION["username"],
                                                      "totalCertificateSend" => 2), $url), true);
        
        echo "<pre>";
        print_r($data);
    }
}

// Done
class Message{
    private $title1;
    private $title2;
    private $username;
    private $host;

    function __construct($title1, $title2){
        global $CONFIG;
        include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        $this->host = $CONFIG["HOST"];
        $this->username = "baysunny";
        $this->title1 = $title1;
        $this->title2 = $title2;
        $url = $this->host."/handler/handler_ajax_outside.php";
        $data = array('world' => "");
        $data = json_decode(request($data, $url), true);
        if($data["info"]){
            Page404("website:locked");
            die();
        }
    }

    function __destruct(){
        include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
    }

    function admin_page(){
        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }

        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/message"
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        $url = $this->host."/handler/handler_ajax_message.php";
        $data["listMessage"] = json_decode(request(array('get-all-message-limit' => 5), $url), true);
        $data["currentUser"] = $this->username;
        // foreach ($data["listMessage"] as $x) {
        //     echo "<pre>";
        //     print_r($x);
        // }
        Template_Message_Admin($this->title1, $this->title2, $data);

    }

    function global_page(){
        $url = $this->host."/handler/handler_ajax_message.php";
        $coverO = new Cover($_SESSION["username"], "x", "-",
            "/dashboard/message"
        );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }


        $data["listMessage"] = json_decode(request(array('get-all-message-limit' => 5), $url), true);
        $data["currentUser"] = $this->username;
        // foreach ($data["listMessage"] as $x) {
        //     echo "<pre>";
        //     print_r($x);
        // }
        Template_Message_Global($this->title1, $this->title2, $data);

    }
}

// Done
class EditUserPage{

    private $title1;
    private $title2;
    private $host;

    function __construct($title1, $title2){

        if(!isset($_SESSION["super_admin_t_1"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
        }
        global $CONFIG;
        $this->host = $CONFIG["HOST"];
        include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        $this->title1 = $title1;
        $this->title2 = $title2;
        $url = $this->host."/handler/handler_ajax_outside.php";
        $data = array('world' => "");
        $data = json_decode(request($data, $url), true);
        if($data["info"]){
            Page404("website:locked");
            die();
        }
    }

    function __destruct(){
        include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
    }

    function admin_page($type){
        $url = $this->host."/handler/handler_ajax_user.php";
        $data = json_decode(request(array('userList' => $type), $url), true);
        $data = array_slice($data, 0, 150);

        if($type == 4){
            $data = json_decode(request(array('getUsersGlobal' => $_SESSION["username"]), $url), true);
            // echo "<pre>";
            // print_r($data);
            $coverO = new Cover($_SESSION["username"], "x", "-",
                "/dashboard/users/pegawai"
            );
            if(!$coverO->logging()){
                echo "Failed to load:".$coverO["status"];
                die();
            }
            Template_Edit_User_Admin($this->title1, $this->title2, $data);
        }else{
            // echo "<pre>";
            // print_r($data);
            $coverO = new Cover($_SESSION["username"], "x", "-",
                "/dashboard/users/else"
            );
            if(!$coverO->logging()){
                echo "Failed to load:".$coverO["status"];
                die();
            }
            EditUser_page($this->title1, $this->title2, $data);
        }
        
    }

    function global_page(){
    }
}

// Done
class SettingPage{

    private $host;

    function __construct($title1, $title2){
        global $CONFIG;
        include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        $this->host = $CONFIG["HOST"];
        $this->title1 = $title1;
        $this->title2 = $title2;
        $url = $this->host."/handler/handler_ajax_outside.php";
        $data = array('world' => "");
        $data = json_decode(request($data, $url), true);
        if($data["info"]){
            Page404("website:locked");
            die();
        }
    }

    function __destruct(){
        include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
    }


    function global_page(){
        $coverO = new Cover($_SESSION["username"], "x", "-",
                "/dashboard/setting"
            );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $url = $this->host."/handler/handler_ajax_user.php";
        if($_SESSION["type"] == 4){
            $data = json_decode(request(array('getAccountSetting' => $_SESSION["username"]), $url), true);
            Template_Setting_Global($this->title1, $this->title2, $data);
        }else{
            $user = json_decode(request(array(
            'getUser' => $_SESSION["type"],
            'username' => $_SESSION["username"]), $url), true);
            Setting_globalPage($this->title1, $this->title2, $user);
        }
        
    }

    function admin_page(){
        $coverO = new Cover($_SESSION["username"], "x", "-",
                "/dashboard/setting"
            );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $url = $this->host."/handler/handler_ajax_user.php";
        $user = json_decode(request(array(
            'getUser' => $_SESSION["type"],
            'username' => $_SESSION["username"]), $url), true);
        Setting_adminPage($this->title1, $this->title2, $user);
    }

    function index(){
        $coverO = new Cover($_SESSION["username"], "x", "-",
                "/dashboard/setting"
            );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $url = $this->host."/handler/handler_ajax_user.php";
        $response = json_decode(request(array(
            'getUser' => $_SESSION["type"],
            'username' => $_SESSION["username"]), $url), true);
        if($_SESSION["type"] != 4){
            Setting_adminPage($this->title1, $this->title2, $response);
        }
    }
}

// Done
class CertificatePage{
    private $title1;
    private $title2;
    private $username;
    private $host;

    function __construct($title1, $title2){
        global $CONFIG;
        include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        $this->host = $CONFIG["HOST"];
        $this->title1 = $title1;
        $this->title2 = $title2;
        $url = $this->host."/handler/handler_ajax_outside.php";
        $data = array('world' => "");
        $data = json_decode(request($data, $url), true);
        if($data["info"]){
            Page404("website:locked");
            die();
        }
    }

    function __destruct(){
        include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
    }

    function admin_page(){

        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
            die();
        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
                "/dashboard/certificate/"
            );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $url = $this->host."/handler/handler_ajax_certificate.php";
        $data = array('template-certificate' => 1);
        $data = json_decode(request($data, $url), true);

        Page_Certificate_Admin($this->title1, $this->title2, $data);
    }
}

// Done
class CourseCertificate{

    private $username, $certifiaceID, $ctype;
    private $host;

    function __construct($username, $certificateCode, $ctype){
        global $CONFIG;
        $this->username = $username;
        $this->certificateCode = $certificateCode;
        $this->ctype = $ctype;
        $this->host = $CONFIG["HOST"];
        include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/certificate/fpdf.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/certificate.php";
        $url = $this->host."/handler/handler_ajax_outside.php";
        $data = array('world' => "");
        $data = json_decode(request($data, $url), true);
        if($data["info"]){
            Page404("website:locked");
            die();
        }
    }

    // global display
    function index(){
        $coverO = new Cover($this->username, "x", "-",
                "/dashboard/certificate/preview?global"
            );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }
        $pdf = new CTemplate();
        $url = $this->host."/handler/handler_ajax_course.php";
        $courseCertificate = json_decode(request(array(
            'getCourseCertificate' => "",
            'username' => $this->username,
            'certificateCode' => $this->certificateCode,
            'type' => $this->ctype
        ), $url), true);

        if($courseCertificate["info"]!="success"){
            echo "<h1>".$courseCertificate["info"]."</h1>";
            die();
        }

        $totalReadingTime = json_decode(request(array(
            'getTotalReadingTime' => $this->username
        ), $url), true);



        $url = $this->host."/handler/handler_ajax_certificate.php";
        $data = array('template-certificate' => 1);
        $textTemplate = json_decode(request($data, $url), true);
        $userData = $courseCertificate["certificate"];
        $userData["detailHistoryRead"] = array(
            "totalReadingTime" => $totalReadingTime["totalReadingTimeInFormat"],
            "certificateType" => $this->ctype
        );
        // echo "<pre>";
        // print_r($userData);
        
        $pdf->__init__Data($userData);

        $pdf->__init__Template($textTemplate);
        $pdf->AliasNbPages();
        $pdf->AddPage("L", "A4", 0);
        $pdf->bBody();
        $pdf->Output();
        
    }

    function preview(){

        if(!isset($_SESSION["super_admin_t_1"]) && !isset($_SESSION["admin_t_2"])){
            if(isset($_SESSION["username"])){
                header("Location: /dashboard/");
                die();
            }else{
                header("Location: /");
                die();
            }
            die();
        }
        $coverO = new Cover($_SESSION["username"], "x", "-",
                "/dashboard/certificate/preview-tC.php?username=baysunny"
            );
        if(!$coverO->logging()){
            echo "Failed to load:".$coverO["status"];
            die();
        }

        $pdf = new CTemplate();

        $url = $this->host."/handler/handler_ajax_certificate.php";
        $data = array('template-certificate' => 1);
        $textTemplate = json_decode(request($data, $url), true);

        $userData["name"] = "[admin]";
        $userData["nip"] = "[admin]";
        $userData["tempat"] = "[admin]";
        $userData["birthDate"] = "[admin]";
        $userData["golongan"] = "[admin]";
        $userData["jabatan"] = "[admin]";
        $userData["unitKerja"] = "[admin]";
        $userData["instansi"] = "[admin]";
        $userData["certificateCode"] = "[X-X-X-X]";
        $userData["detailHistoryRead"]["certificateType"] = "normal";

        $pdf->__init__Data($userData);


        $pdf->__init__Template($textTemplate);
        $pdf->AliasNbPages();
        $pdf->AddPage("L", "A4", 0);
        // $pdf->header11();
        $pdf->bBody();
        $pdf->Output();
    }

    function verification_page(){
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";

        $url = $this->host."/handler/handler_ajax_certificate.php";
        $certificate = json_decode(request(array(
            'check-certificate' => $this->certificateID), $url), true);
        $data["verification"] = $certificate["info"] == "success" ? true : false;
        if ($data["verification"]){
            $data["user"] = $certificate;
        }else{
            $data["user"] = $certificate["info"];
        }
        certificate_x("", "", $data);
    }
}

class Lock{
    function __construct($title1, $title2){
        global $CONFIG;
        // include $_SERVER['DOCUMENT_ROOT']."/header.php";
        include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";
        include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";
        $this->host = $CONFIG["HOST"];

    }

    function index(){
        lock_web();
    }
}
