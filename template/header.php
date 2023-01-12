<?php
@session_start();

    if(!isset($_SESSION['username'])){
        header('Location: /authentication/?youAreNotLoggedIn');
    }
    date_default_timezone_set('Asia/Jakarta');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> LCB KEMENKUMHAM JATENG </title>
    <!-- <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"> -->
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>
    <link rel="apple-touch-icon" sizes="180x180" href="/template_vendor/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/dashboard/drive/images/tesla lc.png" sizes="34x32">
    <link rel="manifest" href="/template_vendor/json/manifest.json">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500,700">
    <link rel="manifest" href="/template_vendor/json/manifest.json">
    <link rel="stylesheet" href="/template_vendor/css/vendor.min.css">
    <link rel="stylesheet" href="/template_vendor/css/elephant.min.css">
    <link rel="stylesheet" href="/template_vendor/css/application.min.css">
    <link rel="stylesheet" href="/template_vendor/css/demo.min.css">
    <link rel="stylesheet" href="/template_vendor/css/profile.min.css">
    <link rel="stylesheet" href="/template_vendor/css/messenger.min.css">
    <style>

        ::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
 
::-webkit-scrollbar-track {
    background-color: #e4e4e4;
    border-radius: 100px;
}
 
::-webkit-scrollbar-thumb {
    background-color: #d4aa70;
    border-radius: 100px;
}


        .my-color{

            background-image: linear-gradient(#8B0000, #DC143C);
            color:white;
        }

        .bg-crimson{
            /*background-color: #990000;*/
            background-image: linear-gradient(#8B0000, #DC143C);
            color: white;
        }

        .text-white{
            color:white;
        }

        .text-white:hover{
            color:black;
            background-color: white;
        }

        .text-crimson{
            color:#990000;
        }

        .btn-download:hover{
            background-image: linear-gradient(white, white);
            color:#990000;
        }

        #my-icon:hover{
            opacity: 0.5;
        }
        .resize-img{
            width: 250px;
            height: 200px;

        }
        .resize-img2{
            width: 250px;
            height: 200px;
            object-fit: cover;
        }

        .resize-img3{
            width: 350px;
            height: 200px;
            object-fit: cover;
        }

        .carousel-image-haha{
            min-height: 400px;
            height: 400px;
            object-fit: cover;

        }

        .carousel-hehe{

            object-fit: cover;
            margin: auto;
        }

        .navbar-menu-quy{
            color: white;
        }
        .subject-body-resize{
            height: :100px;
        }

        .each-subject:hover{
            opacity: 0.8;
        }
    </style>
</head>
