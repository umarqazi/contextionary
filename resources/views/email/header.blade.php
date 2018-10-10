<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Project Name</title>

    <style>
        body {
            margin: 0px;
            padding: 0px;
        }

        .container {
            max-width: 400px;
            margin: 30px auto;
            background: url({!! asset('assets/images/main-bg.png') !!}) no-repeat right top;
            padding: 40px 120px;
            font-family: Arial, Helvetica, sans-serif;
            -webkit-box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.37);
            -moz-box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.37);
            box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.37);
            text-align: center;
        }

        .logo {
            display: block;
            margin: 0 auto 60px auto;
            max-width: 100px;
        }

        .container h1 {
            color: #000;
            font-weight: bold;
            font-size: 32px;
        }

        .container h3 {
            text-align: left;
            font-weight: bold;
        }

        p {
            color: #464646;
            font-size: 14px;
            line-height: 26px;
        }

        .codeText {
            text-align: center;
            letter-spacing: 16px;
            color: #434343;
            font-weight: bold;
            font-size: 26px;
            margin-top: 25px;
            display: block;
        }

        .socail-media {
            margin: 40px 0px 0px;
            padding: 0px;
            list-style: none;
        }

        .socail-media li {
            list-style: none;
            display: inline-block;
        }

        .socail-media li a {
            display: inline-block;
            margin: 0px 5px;
            opacity: 0.7;
        }

        .socail-media li a:hover {
            opacity: 1;
        }

        @media only screen and (max-width: 600px) {
            .container {
                max-width: 75%;
                padding: 40px 60px;
            }
        }

    </style>

</head>
<body>
<div class="container">
    <img src="{!! asset('assets/images/landing-logo.png') !!}" class="logo" />