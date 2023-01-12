<?php
@session_start();

    if(!isset($_SESSION['username'])){
        header('Location: /authentication/?youAreNotLoggedIn');
    }
    date_default_timezone_set('Asia/Jakarta');
    $currentUser = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $_SESSION['username']; ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/template_vendor/img/favicon2.ico" sizes="32x32">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500,700">
    <link rel="stylesheet" href="/template_vendor/css/vendor.min.css">
    <link rel="stylesheet" href="/template_vendor/css/elephant.min.css">
    <link rel="stylesheet" href="/template_vendor/css/application.min.css">
    <link rel="stylesheet" href="/template_vendor/css/demo.min.css">

    <!-- <link rel="stylesheet" href="/template_vendor/css/landing-page.min.css"> -->
    <style>
        #my-icon:hover{
            opacity: 0.5;
        }
        .resize-img{
            width: 250px;
            height: 100px;
            object-fit: cover;
        }

        .subject-body-resize{
            height: :100px;
        }

        .each-subject:hover{
            opacity: 0.8;
        }

        .text-white{
            color: white;
        }

        .navbar-hover-menu:hover{
            background-color: blue;
            color: white;
        }
    </style>
</head>
