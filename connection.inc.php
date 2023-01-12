<?php

date_default_timezone_set("Asia/Jakarta");

class Connection{

//    private static $server = "ftp.a111609863.com";
//    private static $username = "acom";
//    private static $password = "Gmg1o19j8U";
//    private static $dbname = "acom_facebook";

    private static $server = "localhost";
    private static $username = "root";
    private static $password = "B4d1kL4t";
    private static $dbname = "kemenkum_learningcenter";


    public static function connect(){
        return mysqli_connect(self::$server, self::$username, self::$password, self::$dbname);
    }

    public static function logout(){
        session_start();
        session_unset();
        unset($_SESSION);
        session_destroy();
        header("Location: /authentication");
        exit;
    }

}
