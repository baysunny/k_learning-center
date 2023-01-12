<?php

// require '/usr/share/php/libphp-phpmailer/class.phpmailer.php';
// require '/usr/share/php/libphp-phpmailer/class.smtp.php';
require $_SERVER['DOCUMENT_ROOT'].'/dashboard/library/libphp-phpmailer/class.phpmailer.php';
require $_SERVER['DOCUMENT_ROOT'].'/dashboard/library/libphp-phpmailer/class.smtp.php';

class Email{


    function sendmail($to, $message){
        $mail = new PHPMailer;
        $mail->setFrom('learningcenter.kemenkumham@gmail.com');
        $mail->addAddress($to);
        $mail->Subject = 'new email!';
        $mail->Body = $message;
        $mail->IsSMTP();
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'ssl://smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->Username = "learningcenter.kemenkumham@gmail.com";
        $mail->Password = 'baysunny17';
        $mail->send();

        // $subject = "subject";
        // mail($to, $subject, $message, "From: learningcenter.kemenkumham@gmail.com");
    }

    function sendVerificationCode($email, $username, $code){
        include $_SERVER['DOCUMENT_ROOT']."/config.php";

        $url = $CONFIG["HOST"].sprintf("authentication?username=%s&verification-code=%s",
                        $username, $code);

        $message = sprintf("
            Terima kasih telah bergabung bersama E-Learning online, untuk verifikasi akun anda silahkan klik URL dibawah ini

            %s", $url);
        $subject = "Email Verification";

        $mail = new PHPMailer;
        $mail->setFrom('learningcenter.kemenkumham@gmail.com');
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->IsSMTP();
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'ssl://smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->Username = "learningcenter.kemenkumham@gmail.com";
        $mail->Password = 'baysunny17';
        $mail->send();


        // mail($email, $subject, $message, "From: learningcenter.kemenkumham@gmail.com");
    }

    function sendCertificate($email, $username, $code, $ctype){
        include $_SERVER['DOCUMENT_ROOT']."/config.php";

        $url = $CONFIG["HOST"].sprintf("dashboard/drive/certificate/?username=%s&certificate-id=%s&type=%s",
                        $username, $code, $ctype);

        $message = sprintf("
            Link sertifikat :

            %s", $url);
        $subject = "Sertifikat Diterima";

        $mail = new PHPMailer;
        $mail->setFrom('learningcenter.kemenkumham@gmail.com');
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->IsSMTP();
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'ssl://smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->Username = "learningcenter.kemenkumham@gmail.com";
        $mail->Password = 'baysunny17';
        $mail->send();

        // mail($email, $subject, $message, "From: learningcenter.kemenkumham@gmail.com");
    }

    function qnaMailAnswer($to, $question, $answer){
        $message = "
            Pertanyaan : $question

            Jawab : $answer
        ";
        $subject = "Menjawab";
        $mail = new PHPMailer;
        $mail->setFrom('learningcenter.kemenkumham@gmail.com');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->IsSMTP();
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'ssl://smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->Username = "learningcenter.kemenkumham@gmail.com";
        $mail->Password = 'baysunny17';
        $mail->send();

        // mail($to, $subject, $message, "From: learningcenter.kemenkumham@gmail.com");

    }
}
