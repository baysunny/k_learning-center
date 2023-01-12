<?php


class Certificate{

    function certificateList(){
        $sql = "SELECT * FROM cfc_certificate";
        $result = Connection::connect()->query($sql);
        $listCertificate = array();
        while($row = $result->fetch_assoc()){
            $certificate = array();
            $certificate["dateCreated"] = $row["date_created"];
            $certificate["username"] = $row["username"];
            $certificate["code"] = $row["code"];
            array_push($listCertificate, $certificate);
        }return $listCertificate;
    }

    function deleteCertificate($username){
        $sql = "DELETE FROM cfc_certificate WHERE username='$username'";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }return 0;
    }

    function isExists($username){
        $sql = "SELECT * FROM cfc_certificate WHERE username='$username'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            return true;
        }
        return false;
    }

    function isExistsByCode($code){
        $sql = "SELECT * FROM cfc_certificate WHERE code='$code'";
        $result = Connection::connect()->query($sql);
        if($result->num_rows == 1){
            return true;
        }
        return false;
    }

    function generateCode(){
        $characters = "0123456789";
        $charactersLength = strlen($characters);
        $result = '';
        for ($i = 0; $i < 20; $i++) {

            $result .= $characters[rand(0, $charactersLength - 1)];
            if($i % 3 == 0){
                $result .= "-";
            }
        }
        return $result;
    }

    function insertSertificate($username){
        $code = "";
        while(true){
            $code = self::generateCode();
            if(!self::isExistsByCode($code)){
                break;
            }
        }
        $sql = "INSERT INTO cfc_certificate (username, code)
                VALUES('$username', '$code')";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function insertTransaction($certificateID, $sentBy){
        $sql = "INSERT INTO cfc_transaction (certificate_id, sent_by)
                VALUES('$certificateID', '$sentBy')";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }

    function getCertificate($username, $code){
        $sql = "SELECT * FROM cfc_certificate WHERE username='$username' AND code='$code'";
        $result = Connection::connect()->query($sql);
        $certificate = array();
        if($result->num_rows == 1){
            if($row = $result->fetch_assoc()){
                $certificate["username"] = $row["username"];
                $certificate["dateCreated"] = $row["date_created"];
                $certificate["certificateID"] = $row["certificate_id"];
                $certificate["code"] = $row["code"];
                $certificate["totalSent"] = self::getTotalSent($row["certificate_id"]);
                return $certificate;
            }
        }
        return $certificate;;
    }

    function getCertificateByCode($code){
        $sql = "SELECT * FROM cfc_certificate WHERE code='$code'";
        $result = Connection::connect()->query($sql);
        $certificate = array();
        if($result->num_rows == 1){
            if($row = $result->fetch_assoc()){
                $certificate["username"] = $row["username"];
                $certificate["dateCreated"] = $row["date_created"];
                $certificate["certificateID"] = $row["certificate_id"];
                $certificate["code"] = $row["code"];
                $certificate["totalSent"] = self::getTotalSent($row["certificate_id"]);
                return $certificate;
            }
        }
        return $certificate;;
    }

    function getCertificateByUsername($username){
        $sql = "SELECT * FROM cfc_certificate WHERE username='$username'";
        $result = Connection::connect()->query($sql);
        $certificate = array();
        if($result->num_rows == 1){
            if($row = $result->fetch_assoc()){
                $certificate["username"] = $row["username"];
                $certificate["dateCreated"] = $row["date_created"];
                $certificate["certificateID"] = $row["certificate_id"];
                $certificate["code"] = $row["code"];
                $certificate["totalSent"] = self::getTotalSent($row["certificate_id"]);
                return $certificate;
            }
        }
        return $certificate;;
    }

    function getTransactionByUsername($certificateID){
        $sql = "SELECT * FROM cfc_transaction WHERE certificate_id='$certificateID'";
        $result = Connection::connect()->query($sql);
        $listTransaction = array();
        while($row = $result->fetch_assoc()){
            $transaction = array();
            $transaction["sentBy"] = $row["sent_by"];
            $transaction["dateCreated"] = $row["date_created"];
            array_push($listTransaction, $transaction);
        }return $listTransaction;

    }

    function getTotalSent($certificateID){
        $sql = "SELECT * FROM cfc_transaction WHERE certificate_id='$certificateID'";
        $result = Connection::connect()->query($sql);

        return $result->num_rows;

    }

}


class TextCertificate{
    function getText(){
        $template = array();
        $sql = "SELECT * FROM trash_holder WHERE type='certificate'";
        $result = Connection::connect()->query($sql);
        if($row = $result->fetch_assoc()){
            $template["backgroundImage"] = $row["background_image"];
            $template["trashID"] = $row["trash_id"];
            $template["x1"] = $row["x1"];
            $template["x2"] = $row["x2"];
            $template["x3"] = $row["x3"];
            $template["x4"] = $row["x4"];
            $template["x5"] = $row["x5"];
            $template["x6"] = $row["x6"];
            $template["x7"] = $row["x7"];
            $template["x8"] = $row["x8"];
            $template["x9"] = $row["x9"];

        }

        return $template;
    }

    function editText($x1, $x2, $x3, $x4, $x5, $x6, $x7, $x8, $x9, $backgroundImage){
        $sql = "UPDATE trash_holder SET
                x1='$x1',
                x2='$x2',
                x3='$x3',
                x4='$x4',
                x5='$x5',
                x6='$x6',
                x7='$x7',
                x8='$x8',
                x9='$x9',
                background_image='$backgroundImage'
                WHERE type='certificate'";
        $result = Connection::connect()->query($sql);
        if($result){
            return 1;
        }
        return 0;
    }
}
