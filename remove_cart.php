<?php
	session_start();
 
	//remove the id from our cart array
	$key = array_search($_GET['productID'], $_SESSION['cart']);	
	unset($_SESSION['cart'][$key]);

	header('location: home.php');
?>