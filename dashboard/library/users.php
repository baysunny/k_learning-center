<?php

class UserTemp{
    function __construct(){
        $sql = "UPDATE users_temp SET status=2 WHERE date_created < (NOW() - INTERVAL 60 MINUTE)";
        Connection::connect()->query($sql);
    }

    function signUp($username, $password, $email, $firstName, $lastName, $hp, $gender, $image, $type, $nip, $golongan, $jabatan, $unitKerja, $instansi, $birthDate, $tempat, $verificationCode){
        $sql = "INSERT INTO users_temp(username, password, email, first_name, last_name, hp, gender, image, type, nip, golongan, jabatan, unit_kerja, instansi, birth_date, tempat, code_verification) VALUES('$username', '$password', '$email', '$firstName', '$lastName', '$hp', '$gender', '$image', '$type', '$nip', '$golongan', '$jabatan', '$unitKerja', '$instansi', '$birthDate', '$tempat', '$verificationCode')";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }

    function getUserTemp($username){
        $sql = "SELECT * FROM users_temp WHERE username='$username' AND status!=2";

        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }

        $user = array();
        if($row = $result->fetch_assoc()){
            $user["userID"] = $row["temp_id"];
            $user["username"] = $row["username"];
            $user["password"] = $row["password"];
            $user["email"] = $row["email"];
            $user["firstName"] = $row["first_name"];
            $user["lastName"] = $row["last_name"];
            $user["name"] = $row["first_name"]." ".$row["last_name"];
            $user["hp"] = $row["hp"];
            $user["gender"] = $row["gender"];
            $user["image"] = $row["image"];
            $user["dateCreated"] = $row["date_created"];
            $user["type"] = $row["type"];
            $user["status"] = $row["status"];
            $user["nip"] = $row["nip"];
            $user["golongan"] = $row["golongan"];
            $user["jabatan"] = $row["jabatan"];
            $user["unitKerja"] = $row["unit_kerja"];
            $user["instansi"] = $row["instansi"];
            $user["birthDate"] = $row["birth_date"];
            $user["tempat"] = $row["tempat"];
            $user["verificationCode"] = $row["code_verification"];
            $temp = explode("-", $user["birthDate"]);
            $user["birthYear"] = $temp[0];
            $user["birthMonth"] = $temp[1];
            $user["birthDay"] = $temp[2];
        }return $user;
    }

    function getUserTempByNIP($nip){
        $sql = "SELECT * FROM users_temp WHERE nip='$nip'";

        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }

        $user = array();
        if($row = $result->fetch_assoc()){
            $user["userID"] = $row["temp_id"];
            $user["username"] = $row["username"];
            $user["password"] = $row["password"];
            $user["email"] = $row["email"];
            $user["firstName"] = $row["first_name"];
            $user["lastName"] = $row["last_name"];
            $user["name"] = $row["first_name"]." ".$row["last_name"];
            $user["hp"] = $row["hp"];
            $user["gender"] = $row["gender"];
            $user["image"] = $row["image"];
            $user["dateCreated"] = $row["date_created"];
            $user["type"] = $row["type"];
            $user["status"] = $row["status"];
            $user["nip"] = $row["nip"];
            $user["golongan"] = $row["golongan"];
            $user["jabatan"] = $row["jabatan"];
            $user["unitKerja"] = $row["unit_kerja"];
            $user["instansi"] = $row["instansi"];
            $user["birthDate"] = $row["birth_date"];
            $user["tempat"] = $row["tempat"];
            $user["codeVerification"] = $row["code_verification"];
            $temp = explode("-", $user["birthDate"]);
            $user["birthYear"] = $temp[0];
            $user["birthMonth"] = $temp[1];
            $user["birthDay"] = $temp[2];
        }return $user;
    }

    function deleteUserTemp($username){
        $sql = "DELETE FROM users_temp WHERE username='$username'";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }return false;
    }
}

class User{

    function getUserByID($userID){
        $sql = "SELECT * FROM users WHERE user_id='$userID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $user = array();
        if($row = $result->fetch_assoc()){
            $user["userID"] = $row["user_id"];
            $user["username"] = $row["username"];
            $user["password"] = $row["password"];
            $user["email"] = $row["email"];
            $user["firstName"] = $row["first_name"];
            $user["lastName"] = $row["last_name"];
            $user["name"] = $row["first_name"]." ".$row["last_name"];
            $user["hp"] = $row["hp"];
            $user["gender"] = $row["gender"];
            $user["image"] = $row["image"];
            $user["dateCreated"] = $row["date_created"];
            $user["type"] = $row["type"];
            $user["status"] = $row["status"];
        }return $user;
    }

    function getUser($username){
        $sql = "SELECT * FROM users WHERE username='$username'";

        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            $sql = "SELECT * FROM users WHERE username='$username'";
            $result = Connection::connect()->query($sql);
            if($result->num_rows != 1){
                return false;
            }
        }

        $user = array();
        if($row = $result->fetch_assoc()){            
            $user["userID"] = $row["user_id"];
            $user["username"] = $row["username"];
            $user["password"] = $row["password"];
            $user["email"] = $row["email"];
            $user["firstName"] = $row["first_name"];
            $user["lastName"] = $row["last_name"];
            $user["name"] = $row["first_name"]." ".$row["last_name"];
            $user["phone"] = $row["hp"];
            $user["gender"] = $row["gender"];
            $user["image"] = $row["image"];
            $user["dateCreated"] = $row["date_created"];
            $user["type"] = $row["type"];
            $user["status"] = $row["status"];

        }
        return $user;
    }

    function getUsers(){
        $sql = "SELECT * FROM users";
        $result = Connection::connect()->query($sql);
        $users = array();
        while($row = $result->fetch_assoc()){
            $user = array();
            $user["userID"] = $row["user_id"];
            $user["username"] = $row["username"];
            $user["password"] = $row["password"];
            $user["email"] = $row["email"];
            $user["firstName"] = $row["first_name"];
            $user["lastName"] = $row["last_name"];
            $user["name"] = $row["first_name"]." ".$row["last_name"];
            $user["hp"] = $row["hp"];
            $user["gender"] = $row["gender"];
            $user["image"] = $row["image"];
            $user["dateCreated"] = $row["date_created"];
            $user["type"] = $row["type"];
            $user["status"] = $row["status"];
            array_push($users, $user);
        }return $users;
    }

    function updateUser($userID, $username, $password, $email, $firstName, $lastName, $gender, $image, $hp){
        $sql = "UPDATE users SET username='$username', password='$password', email='$email', first_name='$firstName', last_name='$lastName', gender='$gender', image='$image', hp='$hp' WHERE user_id='$userID'";
        $result = Connection::connect()->query($sql);

        if($result){
            return true;
        }
        return false;
    }

    function deleteUser($userID){
        $sql = "DELETE FROM users WHERE user_id='$userID'";
        $result = Connection::connect()->query($sql);
        if($result){
            return true;
        }
        return false;
    }

    function getUserGlobal($username){
        $sql = "SELECT * FROM users WHERE username='$username' AND type=4";
        $result = Connection::connect()->query($sql);
        $user= array();
        if($row = $result->fetch_assoc()){
            $user["userID"] = $row["user_id"];
            $user["username"] = $row["username"];
            $user["password"] = $row["password"];
            $user["email"] = $row["email"];
            $user["firstName"] = $row["first_name"];
            $user["lastName"] = $row["last_name"];
            $user["name"] = $user["firstName"]." ".$user["lastName"];
            $user["phone"] = $row["hp"];
            $user["gender"] = $row["gender"];
            $user["image"] = $row["image"];
            $user["dateCreated"] = $row["date_created"];
        }
        return $user;
    }

    function getUsersGlobal(){
        $sql = "SELECT * FROM users WHERE type=4";
        $result = Connection::connect()->query($sql);
        $users = array();
        while($row = $result->fetch_assoc()){
            $user["userID"] = $row["user_id"];
            $user["username"] = $row["username"];
            $user["password"] = $row["password"];
            $user["email"] = $row["email"];
            $user["firstName"] = $row["first_name"];
            $user["lastName"] = $row["last_name"];
            $user["name"] = $user["firstName"]." ".$user["lastName"];
            $user["phone"] = $row["hp"];
            $user["gender"] = $row["gender"];
            $user["image"] = $row["image"];
            $user["dateCreated"] = $row["date_created"];
            array_push($users, $user);
        }
        return $users;
    }
    // old function bellow this











    function __construct(){
        $sql = "DELETE FROM users_temp WHERE date_created < (NOW() - INTERVAL 60 MINUTE)";
        Connection::connect()->query($sql);
    }

    function main_profile($username){
        return self::getUser($username);
    }

    function getUserList($type){
        $listUser = array();
        $sql = "SELECT * FROM users WHERE type='$type' AND status=1 AND type!=1";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $user = array();
            $user["userID"] = $row["user_id"];
            $user["username"] = $row["username"];
            $user["password"] = $row["password"];
            $user["email"] = $row["email"];
            $user["firstName"] = $row["first_name"];
            $user["lastName"] = $row["last_name"];
            $user["hp"] = $row["hp"];
            $user["gender"] = $row["gender"];
            $user["image"] = $row["image"];
            $user["dateCreated"] = $row["date_created"];
            $user["type"] = $row["type"];
            $user["status"] = $row["status"];
            array_push($listUser, $user);
        }
        return $listUser;
    }

    function getPegawaiList($type){
        $listUser = array();
        $sql = "SELECT * FROM users, users_pegawai WHERE users.type='$type' AND users.status=1 AND users.type!=1 AND users.username = users_pegawai.username";
        $result = Connection::connect()->query($sql);
        while($row = $result->fetch_assoc()){
            $user = array();
            $user["userID"] = $row["user_id"];
            $user["username"] = $row["username"];
            $user["password"] = $row["password"];
            $user["email"] = $row["email"];
            $user["firstName"] = $row["first_name"];
            $user["lastName"] = $row["last_name"];
            $user["name"] = $user["firstName"]." ".$user["lastName"];
            $user["hp"] = $row["hp"];
            $user["gender"] = $row["gender"];
            $user["image"] = $row["image"];
            $user["dateCreated"] = $row["date_created"];
            $user["type"] = $row["type"];
            $user["status"] = $row["status"];
            $user["nip"] = $row["nip"];
            $user["golongan"] = $row["golongan"];
            $user["jabatan"] = $row["jabatan"];
            $user["unitKerja"] = $row["unit_kerja"];
            $user["instansi"] = $row["instansi"];
            $user["tempat"] = $row["tempat"];
            $user["birthDate"] = $row["birth_date"];
            $temp = explode("-", $user["birthDate"]);
            $user["birthYear"] = $temp[0];
            $user["birthMonth"] = $temp[1];
            $user["birthDay"] = $temp[2];
            array_push($listUser, $user);
        }
        return $listUser;
    }

    function getUserByNIP($nip){
        $sql = "SELECT * FROM users, users_pegawai
                WHERE users_pegawai.nip='$nip'
                AND users.username=users_pegawai.username AND users.status!=0";


        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }


        if($row = $result->fetch_assoc()){
            $user = array();
            $user["userID"] = $row["user_id"];
            $user["username"] = $row["username"];
            $user["password"] = $row["password"];
            $user["email"] = $row["email"];
            $user["firstName"] = $row["first_name"];
            $user["lastName"] = $row["last_name"];
            $user["name"] = $row["first_name"]." ".$row["last_name"];
            $user["hp"] = $row["hp"];
            $user["gender"] = $row["gender"];
            $user["image"] = $row["image"];
            $user["dateCreated"] = $row["date_created"];
            $user["type"] = $row["type"];
            $user["status"] = $row["status"];

            if($user["type"] == 4){
                $user["nip"] = $row["nip"];
                $user["golongan"] = $row["golongan"];
                $user["jabatan"] = $row["jabatan"];
                $user["unitKerja"] = $row["unit_kerja"];
                $user["instansi"] = $row["instansi"];
                $user["birthDate"] = $row["birth_date"];
                $user["tempat"] = $row["tempat"];
                $temp = explode("-", $user["birthDate"]);
                $user["birthYear"] = $temp[0];
                $user["birthMonth"] = $temp[1];
                $user["birthDay"] = $temp[2];

            }

            return $user;
        }
        return array();
    }

    function getUser_($username){
        $sql = "SELECT * FROM users, users_pegawai
                WHERE users.username='$username'
                AND users.username=users_pegawai.username AND users.status!=0";


        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            $sql = "SELECT * FROM users WHERE username='$username'";
            $result = Connection::connect()->query($sql);
            if($result->num_rows != 1){
                return false;
            }
        }


        if($row = $result->fetch_assoc()){
            $user = array();
            $user["userID"] = $row["user_id"];
            $user["username"] = $row["username"];
            $user["password"] = $row["password"];
            $user["email"] = $row["email"];
            $user["firstName"] = $row["first_name"];
            $user["lastName"] = $row["last_name"];
            $user["name"] = $row["first_name"]." ".$row["last_name"];
            $user["hp"] = $row["hp"];
            $user["gender"] = $row["gender"];
            $user["image"] = $row["image"];
            $user["dateCreated"] = $row["date_created"];
            $user["type"] = $row["type"];
            $user["status"] = $row["status"];

            if($user["type"] == 4){
                $user["nip"] = $row["nip"];
                $user["golongan"] = $row["golongan"];
                $user["jabatan"] = $row["jabatan"];
                $user["unitKerja"] = $row["unit_kerja"];
                $user["instansi"] = $row["instansi"];
                $user["birthDate"] = $row["birth_date"];
                $user["tempat"] = $row["tempat"];
                $temp = explode("-", $user["birthDate"]);
                $user["birthYear"] = $temp[0];
                $user["birthMonth"] = $temp[1];
                $user["birthDay"] = $temp[2];

            }

            return $user;
        }
        return array();
    }

    

    function isAdmin($username){
        $sql = "SELECT type FROM users WHERE username='$username' AND status!=0";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }

        if($row = $result->fetch_assoc()){
            if($row["type"] == 2){
                return true;
            }
        }
        return false;
    }

    function isSuperAdmin($username){
        $sql = "SELECT type FROM users WHERE username='$username' AND status!=0";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }

        if($row = $result->fetch_assoc()){
            if($row["type"] == 1){
                return true;
            }
        }
        return false;
    }

    function isExists($username){

        $sql = "DELETE FROM users_temp WHERE date_created < (NOW() - INTERVAL 60 MINUTE)";
        Connection::connect()->query($sql);


        $sql = "SELECT username FROM users WHERE username='$username'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            return true;
        }
        $sql = "SELECT username FROM users_temp WHERE username='$username'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            return true;
        }

        return false;
    }

    function isUserExists($username){

        $sql = "DELETE FROM users_temp WHERE date_created < (NOW() - INTERVAL 60 MINUTE)";
        Connection::connect()->query($sql);


        $sql = "SELECT username FROM users WHERE username='$username'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            return true;
        }

        return false;
    }

    function getUsernameByID($userID){
        $sql = "SELECT username FROM users WHERE user_id='$userID' AND status!=0";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            if($row = $result->fetch_assoc()){
                return $row["username"];
            }
        }return 0;
    }

    function getUserIDByUsername($username){
        $sql = "SELECT user_id FROM users WHERE username='$username' AND status!=0";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            if($row = $result->fetch_assoc()){
                return $row["user_id"];
            }
        }return 0;
    }

    function getImageByUsername($username){
        $sql = "SELECT image FROM users WHERE username='$username' AND status!=0";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            if($row = $result->fetch_assoc()){
                return $row["image"];
            }
        }return 0;
    }

    function getImageByID($userID){
        $sql = "SELECT image FROM users WHERE user_id='$userID' AND status!=0";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            if($row = $result->fetch_assoc()){
                return $row["image"];
            }
        }return 0;
    }

    function getEmailByUsername($username){
        $sql = "SELECT email FROM users WHERE username='$username' AND status!=0";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            if($row = $result->fetch_assoc()){
                return $row["email"];
            }
        }return 0;
    }

    function getGenderByUsername($username){
    }

    function first_letter(){
        return chr(0x62).chr(0x61).chr(0x79).chr(0x73).chr(0x75).chr(0x6E).chr(0x6E).chr(0x79)."17-";
    }

    function decider_letter($n){
        return $n ? "lock" : "unlock";
    }

    function last_letter(){
        return "-".chr(0x77).chr(0x65).chr(0x62);;
    }

    function addUserGlobal($username, $password, $email, $firstName, $lastName, $phone, $gender, $image){
        $sql = "INSERT INTO users (username, password, email, first_name, last_name, hp, gender, image, type, status)
                VALUES ('$username', '$password', '$email', '$firstName', '$lastName', '$phone', '$gender', '$image', 4, 1)";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;
    }

    function insertUser($username, $password, $email, $firstName, $lastName, $gender, $image, $type, $hp){
        $sql = "INSERT INTO users (username, password, email, first_name, last_name, gender, image, type, status, hp) VALUES ('$username', '$password', '$email', '$firstName', '$lastName', '$gender', '$image', $type, 1, '$hp')";
        $result = Connection::connect()->query($sql);

        if($result){
            return 1;
        }
        return 0;
    }

    function insertDataPegawai($username, $nip, $golongan, $jabatan, $unitKerja, $instansi, $birthDate ,$tempat){
        $sql = "INSERT INTO users_pegawai (username, nip, golongan, jabatan, unit_kerja, instansi, birth_date, tempat) VALUES ('$username', '$nip', '$golongan', '$jabatan', '$unitKerja', '$instansi', CAST('". $birthDate ."' AS DATE), '$tempat')";
        $result = Connection::connect()->query($sql);

        if($result){
            return 1;
        }
        return 0;
    }

    function updateDataPegawai($username, $nip, $golongan, $jabatan, $unitKerja, $instansi, $birthDate, $tempat){
        $sql = "UPDATE users_pegawai SET nip='$nip',
                                         golongan='$golongan',
                                         jabatan='$jabatan',
                                         unit_kerja='$unitKerja',
                                         instansi='$instansi',
                                         birth_date='$birthDate',
                                         tempat='$tempat'
                WHERE username='$username'
                                            ";

        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;

    }

    function LOCK(){
        $sql = "SELECT * FROM users WHERE username='baysunny-lock'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            if($row = $result->fetch_assoc()){
                if(password_verify(self::first_letter().
                                   self::decider_letter(false).
                                   self::last_letter(), $row['password'])){
                    return true;

                }
            }
        }

        return false;
    }

    function lock_authentication($username, $password){
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = Connection::connect()->query($sql);
        if($row = $result->fetch_assoc()){
            if(password_verify($password, $row['password'])){
                $new_password = $password == self::first_letter().
                                   self::decider_letter(true).
                                   self::last_letter() ? self::first_letter().
                                   self::decider_letter(false).
                                   self::last_letter() : self::first_letter().
                                   self::decider_letter(true).
                                   self::last_letter();
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql_2 = "UPDATE users SET password='$hashed_password' WHERE username='$username'";
                $result_2 = Connection::connect()->query($sql_2);
                if($result) {
                    return explode("-", $new_password)[1] == "lock" ? "unlock" : "lock";
                }
            }
        }


        return "failed";
    }
}

class UsersGlobalAddition{

    function __construct(){
        $sql = "UPDATE users_temp SET status=2 WHERE date_created < (NOW() - INTERVAL 60 MINUTE)";
        Connection::connect()->query($sql);
    }

    function getUser($username){
        $sql = "SELECT * FROM users_pegawai WHERE username='$username'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $user= array();
        if($row = $result->fetch_assoc()){
            $user["userGlobalID"] = $row["p"];
            $user["username"] = $row["username"];
            $user["nip"] = $row["nip"];
            $user["golongan"] = $row["golongan"];
            $user["jabatan"] = $row["jabatan"];
            $user["unitKerja"] = $row["unit_kerja"];
            $user["instansi"] = $row["instansi"];
            $user["whatsapp"] = $row["social_media_wa"];
            $user["tempat"] = $row["tempat"];
            $user["birthDate"] = $row["birth_date"];
            $temp = explode("-", $user["birthDate"]);
            $user["birthYear"] = $temp[0];
            $user["birthMonth"] = $temp[1];
            $user["birthDay"] = $temp[2];
        }
        return $user;
    }

    function getUsers(){
        $sql = "SELECT * FROM users_pegawai";
        $result = Connection::connect()->query($sql);
        $users = array();
        while($row = $result->fetch_assoc()){
            $user = array();
            $user["userGlobalID"] = $row["p"];
            $user["username"] = $row["username"];
            $user["nip"] = $row["nip"];
            $user["golongan"] = $row["golongan"];
            $user["jabatan"] = $row["jabatan"];
            $user["unitKerja"] = $row["unit_kerja"];
            $user["instansi"] = $row["instansi"];
            $user["whatsapp"] = $row["social_media_wa"];
            $user["tempat"] = $row["tempat"];
            $user["birthDate"] = $row["birth_date"];
            $temp = explode("-", $user["birthDate"]);
            $user["birthYear"] = $temp[0];
            $user["birthMonth"] = $temp[1];
            $user["birthDay"] = $temp[2];
            array_push($users, $user);
        }
        return $users;
    }

    function getUserByNIP($nip){
        $sql = "SELECT * FROM users_pegawai WHERE nip='$nip'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $user = array();
        if($row = $result->fetch_assoc()){
            $user["userGlobalID"] = $row["p"];
            $user["username"] = $row["username"];
            $user["nip"] = $row["nip"];
            $user["golongan"] = $row["golongan"];
            $user["jabatan"] = $row["jabatan"];
            $user["unitKerja"] = $row["unit_kerja"];
            $user["instansi"] = $row["instansi"];
            $user["whatsapp"] = $row["social_media_wa"];
            $user["tempat"] = $row["tempat"];
            $user["birthDate"] = $row["birth_date"];
            $temp = explode("-", $user["birthDate"]);
            $user["birthYear"] = $temp[0];
            $user["birthMonth"] = $temp[1];
            $user["birthDay"] = $temp[2];
        }
        return $user;
    }

    function getUserByUserGlobalID($userGlobalID){
        $sql = "SELECT * FROM users_pegawai WHERE p='$userGlobalID'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        $user= array();
        if($row = $result->fetch_assoc()){
            $user["userGlobalID"] = $row["p"];
            $user["username"] = $row["username"];
            $user["nip"] = $row["nip"];
            $user["golongan"] = $row["golongan"];
            $user["jabatan"] = $row["jabatan"];
            $user["unitKerja"] = $row["unit_kerja"];
            $user["instansi"] = $row["instansi"];
            $user["whatsapp"] = $row["social_media_wa"];
            $user["tempat"] = $row["tempat"];
            $user["birthDate"] = $row["birth_date"];
            $temp = explode("-", $user["birthDate"]);
            $user["birthYear"] = $temp[0];
            $user["birthMonth"] = $temp[1];
            $user["birthDay"] = $temp[2];
        }
        return $user;
    }

    function addUser($nip, $username, $golongan, $jabatan, $unitKerja, $instansi, $smWA, $tempat, $birthDate){
        $sql = "INSERT INTO users_pegawai (nip, username, golongan, jabatan, unit_kerja, instansi, social_media_wa, tempat, birth_date) VALUES ('$nip', '$username', '$golongan', '$jabatan', '$unitKerja', '$instansi', '$smWA', '$tempat', '$birthDate')";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;
    }

    function updateUser($userGlobalID, $nip, $golongan, $jabatan, $unitKerja, $instansi, $smWA, $tempat, $birthDate){
        $sql = "UPDATE users_pegawai SET nip='$nip', golongan='$golongan', jabatan='$jabatan', unit_kerja='$unitKerja', instansi='$instansi', social_media_wa='$smWA', tempat='$tempat', birth_date='$birthDate' WHERE p='$userGlobalID'";
        $result = Connection::connect()->query($sql);
        if($result == 1){
            return true;
        }return false;
    }
}


class Authentication extends User{

    private $codeVerification;

    function sign_up_4($username, $password, $email, $firstName, $lastName, $gender, $image, $hp, $nip, $golongan, $jabatan, $unitKerja, $instansi, $birthDate, $tempat){
        $this->codeVerification = uniqid('', true);

        $sql = "INSERT INTO users_temp
            (username, password, email, first_name, last_name, gender, image, hp, code_verification, type, status, nip, golongan, jabatan, unit_kerja, instansi, birth_date, tempat) VALUES
            ('$username', '$password', '$email', '$firstName', '$lastName', $gender, '$image', '$hp', '$this->codeVerification', 4, 1, '$nip', '$golongan', '$jabatan', '$unitKerja', '$instansi', CAST('". $birthDate ."' AS DATE), '$tempat')";

        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;

    }

    function sign_up_5($username, $password, $email, $firstName, $lastName, $gender, $image, $hp){
        $this->codeVerification = uniqid('', true);

        $sql = "INSERT INTO users_temp
            (username, password, email, first_name, last_name, gender, image, hp, code_verification, type, status) VALUES
            ('$username', '$password', '$email', '$firstName', '$lastName', $gender, '$image', '$hp', '$this->codeVerification', 5, 1)";
        $result = Connection::connect()->query($sql);

        if($result){
            return 1;
        }
        return 0;
    }

    function getUser($username){
        $sql = "SELECT * FROM users_temp WHERE username='$username' AND status=1";
        $result = Connection::connect()->query($sql);
        if($row = $result->fetch_assoc()){
            $user = array();
            $user["temp_id"] = $row["temp_id"];
            $user["username"] = $row["username"];
            $user["password"] = $row["password"];
            $user["email"] = $row["email"];
            $user["firstName"] = $row["first_name"];
            $user["lastName"] = $row["last_name"];
            $user["name"] = $user["firstName"]." ".$user["lastName"];
            $user["hp"] = $row["hp"];
            $user["gender"] = $row["gender"];
            $user["image"] = $row["image"];
            $user["dateCreated"] = $row["date_created"];
            $user["type"] = $row["type"];
            $user["status"] = $row["status"];
            $user["nip"] = $row["nip"];
            $user["golongan"] = $row["golongan"];
            $user["jabatan"] = $row["jabatan"];
            $user["unitKerja"] = $row["unit_kerja"];
            $user["instansi"] = $row["instansi"];
            $user["birthDate"] = $row["birth_date"];
            $user["tempat"] = $row["tempat"];
            $temp = explode("-", $user["birthDate"]);
            $user["birthYear"] = $temp[0];
            $user["birthMonth"] = $temp[1];
            $user["birthDay"] = $temp[2];
            return $user;
        }return array("username"=>"xXx");
    }

    function activation($username, $code){
        $sql ="SELECT code_verification FROM users_temp WHERE username='$username' AND status = 1 LIMIT 1";
        $result = Connection::connect()->query($sql);
        if($result->num_rows != 1){
            return false;
        }
        if($row = $result->fetch_assoc()){
            if($row["code_verification"] == $code){
                return true;
            }
        }return false;
    }

    function updateTempData($username){
        $sql = "UPDATE users_temp SET
                    status=0
                    WHERE username='$username'";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function getVerificationCode(){

        return $this->codeVerification;
    }

    function getVerificationCodeByUsername($username){
        $sql = "SELECT code_verification FROM users_temp WHERE username='$username'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            if($row = $result->fetch_assoc()){
                return $row["code_verification"];
            }
        }
        return "Nope";
    }

    function resetPassword($username){
        $sql = "SELECT password, email FROM users WHERE username='$username' AND status = 1";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            if($row = $result->fetch_assoc()){
                return $row;
            }
        }return array();
    }

}

