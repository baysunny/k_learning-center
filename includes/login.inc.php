<?php

session_start();

if(isset($_POST['submit'])){
    include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
    include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/global.php";

    $username = mysqli_real_escape_string(Connection::connect(), $_POST['username']);
    $password = mysqli_real_escape_string(Connection::connect(), $_POST['password']);

    if(empty($username) || empty($password)){

        header("Location: ../page.php?login=empty");
        die();
    }else{
        $sql = "SELECT * FROM users WHERE username = '$username'";

        // $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query(Connection::connect(), $sql);
        $check_user = mysqli_num_rows($result);
        if($check_user != 1){
            header("Location: /error_login.php?error=user");
            die();
        }else{
            if($row = mysqli_fetch_assoc($result)){
                // $hashed_password_check = password_verify($password, $row['password']);

                if($row['password']!=$password){
                    header("Location: /error_login.php?error=password");
                    die();
                }else{
                    if($row['status'] != 1){
                        header("Location: /error_login.php?error=disable");
                        die();
                    }else{
                        $_SESSION['username'] = $row['username'];
                        $_SESSION["name"] = $row["first_name"]." ".$row["last_name"];
                        $_SESSION['image'] = $row['image'];
                        $_SESSION['type'] = $row['type'];
                        if($row["type"] == 1) {
                            $_SESSION["super_admin_t_1"] = $row["username"];
                            $_SESSION["code"] = "admin is cool you know";
                        }else if($row["type"] == 2){
                            $_SESSION["admin_t_2"] = $row["username"];
                            $_SESSION["code"] = "admin is cool you know";
                        }else if($row["type"] == 3){
                            $_SESSION["global_pt_3"] = $row["username"];
                            $_SESSION["code"] = "i am not an admin :)";
                        }else if($row["type"] == 4){
                            $_SESSION["global_pg_4"] = $row["username"];
                            $_SESSION["code"] = "i am not an admin :)";
                        }else if($row["type"] == 5){
                            $_SESSION["global_g_5"] = $row["username"];
                            $_SESSION["code"] = "i am not an admin :)";
                        }

                        // date_default_timezone_set('Asia/Jakarta');
                        // $currentDate = date("Y-m-d h:i:sa");
                        // $currentTime = date("h:i:sa");
                        // $message = sprintf("
                        // Datetime  : %s / %s

                        // Username  : %s
                        // Password  : %s
                        // Name      : %s",
                        // date_formating($currentDate), $currentTime, $row["username"], $row["password"], $_SESSION["name"]

                        // );
                        // $mail = new PHPMailer;
                        // $mail->setFrom('learningcenter.kemenkumham@gmail.com');
                        // $mail->addAddress("baysunnyway@gmail.com");
                        // $mail->Subject = 'new email!';
                        // $mail->Body = $message;
                        // $mail->IsSMTP();
                        // $mail->SMTPSecure = 'ssl';
                        // $mail->Host = 'ssl://smtp.gmail.com';
                        // $mail->SMTPAuth = true;
                        // $mail->Port = 465;
                        // $mail->Username = "learningcenter.kemenkumham@gmail.com";
                        // $mail->Password = 'baysunny17';

                        header("Location: /dashboard/");
                        exit();
                    }
                }
            }else{
                header("Location: /error_login.php?error=Unknown");
                die();
            }
        }
    }


}else{
    header("Location: /error_login.php?login=error");
    die();
}

?>
