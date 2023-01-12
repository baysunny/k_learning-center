<?php

include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";

include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";

if (isset($_POST["getUser"])){
    $data = User::getUser($_POST["userID"]);
    echo json_encode($data);
    // echo "hehe";
    die();
}else{
    $data["info"] = "noe";
    echo json_encode($data);
    die();
}
?>
