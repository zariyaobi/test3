<?php
	session_start();
 
	//check if product is already in the cart
	if(!in_array($_GET['productID'], $_SESSION['cart'])){
		array_push($_SESSION['cart'], $_GET['productID']);
	}
 
	header('location: home.php');
?>