<?php
    include 'conn.php';
    session_start();

    //check if user is logged in, if not then redirect him to login pag
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="img/gift.svg">
    <title>Thank You</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>

<body>
    <h1 class=" jumbotron text-center" style="padding: 100px; background-color: #007bff;">Thank You!!</h1>
    <div class="container text-center">
        <h5>Thank you for choosing the Best Online Shopping App!</h5><br>
        <a class="btn btn-primary" href="clear_cart.php"> Continue Shopping</a>&emsp;&emsp;
        <a class="btn btn-primary" target="_blank" href="receipt.php">Generate Receipt</a>
    </div>
</body>
</html>
