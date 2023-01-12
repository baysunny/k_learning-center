<?php
@session_start();

    // if(isset($_SESSION['username'])){
    //     header('Location: /dashboard');
    // }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Authentication</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    <link rel="apple-touch-icon" sizes="180x180" href="/template_vendor/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/dashboard/drive/images/tesla lc.png" sizes="34x32">
    <link rel="manifest" href="/template_vendor/json/manifest.json">
    <link rel="mask-icon" href="safari-pinned-tab.svg" color="#f1595d">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500,700">
    <link rel="stylesheet" href="/template_vendor/css/vendor.min.css">
    <link rel="stylesheet" href="/template_vendor/css/elephant.min.css">

    <link rel="stylesheet" href="/template_vendor/css/signup-2.min.css">
    <link rel="stylesheet" href="/template_vendor/css/login-2.min.css">
    <link rel="stylesheet" href="/template_vendor/css/landing-page.min.css">

    <style>
        .my-color{

            background-image: linear-gradient(#8B0000, #DC143C);
            color:white;
        }

        .bg-crimson{
            background-color: #990000;
            color: white;

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
            height: 100px;
            object-fit: cover;
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
