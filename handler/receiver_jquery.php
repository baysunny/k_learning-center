<?php

@session_start();


include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/dashboard/library/users.php";
include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";


if(isset($_POST["loadUser"])){
    userlist($_POST["loadUser"]);
}else if(isset($_POST["loadProfileImage"])){

    echo '
    <a id="profile-image"><img id="preview-img" class="timeline-media img-rounded" width="150" height="150" style="object-fit: cover;"
        src="/dashboard/drive/images/'.User::getImageByID($_POST["userID"]).'">
    </a>';
}else if(isset($_POST["loadmmm"])){
    foreach ($_POST["loadmmm"] as $subject) {
        echo '
        <tr>
            <td id="username-'.$subject["subjectID"].'">'.$subject["username"].'</td>
            <td id="subjectID-'.$subject["subjectID"].'">'.$subject["subjectID"].'</td>
            <td id="startRead-'.$subject["subjectID"].'">'.$subject["startRead"].'</td>
            <td id="endRead-'.$subject["subjectID"].'">'.$subject["endRead"].'</td>
            <td id="totalRead-'.$subject["subjectID"].'">'.$subject["totalRead"].'</td>
            <td id="timeRead-'.$subject["subjectID"].'">'.$subject["timeRead"].'</td>
            <td id="tabCode-'.$subject["subjectID"].'">'.$subject["tabCode"].'</td>
        </tr>

        ';
    }

}

