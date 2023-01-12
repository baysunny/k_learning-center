<?php

mb_internal_encoding("UTF-8");


if(isset($_POST['submit'])){

    require_once 'connection.inc.php';

    $username = mysqli_real_escape_string(Connection::connect(), $_POST['username']);
    $email = mysqli_real_escape_string(Connection::connect(), $_POST['email']);
    $password = mysqli_real_escape_string(Connection::connect(), $_POST['password']);
    $confirm_password = mysqli_real_escape_string(Connection::connect(), $_POST['confirm']);

    if(empty($username) || (mb_strlen($username) < 6) || empty($password) || (mb_strlen($password) < 6) || empty($email) || ($password != $confirm_password)){
            header("Location: ../page.php?register");
        exit();
    }else{
        if(!preg_match("/^[A-Za-z0-9]*$/", $username) || (!filter_var($email, FILTER_VALIDATE_EMAIL))){
            header("Location: ../page.php?register");
            exit();
        }else{
            $sql_username = "SELECT * FROM users WHERE username='$username'";
            $sql_email = "SELECT * FROM users WHERE username='$username'";

            $result_for_username = mysqli_query(Connection::connect(), $sql_username);
            $result_for_email = mysqli_query(Connection::connect(), $sql_email);

            $check_username = mysqli_num_rows($result_for_username);
            $check_email = mysqli_num_rows($result_for_email);
            $confirm_code=md5(uniqid(rand()));

            if($check_username == 1 || $check_email == 1){
                header("Location: ../page.php?register");
                exit();
            }else{
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $currentDate = date("Y-m-d H:i:s", STRTOTIME(date('h:i:sa')));
                $sql = "INSERT INTO users (username, email, password, image, last_activity) VALUES ('$username', '$email', '$hashed_password', 'default.jpg', '$currentDate')";
                $result = Connection::connect()->query($sql);
                if($result){
                    $get_id = "SELECT * FROM users WHERE username='$username'";
                    $result = mysqli_query(Connection::connect(), $get_id);
                    if($rows = mysqli_fetch_assoc($result)){
                        $user_id = $rows['id'];
                        $sql_general_insert = "INSERT INTO user_profile (username, liveIn, liveFrom, gender, relationship, birthday)
                                                                 VALUES ('$username', '0', '0', '0', '0', '0000-00-000')";
                        if(mysqli_query(Connection::connect(), $sql_general_insert)){
                            header("Location:/page.php?signup=successfully");
                            exit();
                        }else{
                            echo "error on inserting general info";
                        }
                    }else{
                        echo "error on fetching rows";
                    }
                }else{
                    echo Connection::connect()->error;
                }
            }
        }

    }

}else{
    header("Location: ../index.php?register=button");
}

?>
