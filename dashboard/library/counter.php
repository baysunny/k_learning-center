<?php


class Counter{


    function getTotalRead(){
        $sql = "SELECT ";
    }

    function getnDownload($subjectID, $username){
        $sql = "SELECT download_id FROM crs_download WHERE subject_id='$subjectID' AND username='$username'";
        $result = Connection::connect()->query($sql);
        $total_download = 0;
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $total_download += 1;
            }
        }return $total_download;;
    }

    function updateDownload($subjectID, $username, )
}
