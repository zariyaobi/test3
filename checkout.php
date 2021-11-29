<?php
    include 'conn.php';
    session_start();

    //check if user is logged in, if not then redirect him to login pag
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
    if(!isset($_SESSION['cart'])){
		$_SESSION['cart'] = array();
	}
    $cartsql = "SELECT * FROM products WHERE productID IN (".implode(',',$_SESSION['cart']).")";

    if(!isset($_SESSION['total'])){
        $_SESSION['total'] = 0;
    }
    if(!isset($_SESSION['totalTax'])){
        $_SESSION['totalTax'] = 0;
    }
    if(!isset($_SESSION['lastid'])){
        $_SESSION['lastid'] = 0;
    }

    //FORM VALIDATION AND PROCESSING//
    //define and initialize variables
    $fname = $lname = $email = $add1 = $add2 = $district = $country = $pay = $cname = $cardnum = $exp = $cvv = $pname = $pemail = "";

    require_once "conn.php";

    //processing form data on submit
    if($_SERVER["REQUEST_METHOD"] == "POST"){

            //get values for variables from the form
            $fname =  mysqli_real_escape_string($conn, $_REQUEST["fname"]);
            $lname = mysqli_real_escape_string($conn, $_REQUEST["lname"]);
            $email =  mysqli_real_escape_string($conn, $_REQUEST["email"]);
            $add1 = mysqli_real_escape_string($conn, $_REQUEST["add1"]);
            $add2 = mysqli_real_escape_string($conn, $_REQUEST["add2"]);
            $district =  mysqli_real_escape_string($conn, $_REQUEST["district"]);
            $country = mysqli_real_escape_string($conn, $_REQUEST["country"]);
            $pay =  mysqli_real_escape_string($conn, $_REQUEST["pay"]);
            $cname = mysqli_real_escape_string($conn, $_REQUEST["cname"]);
            $cardnum = mysqli_real_escape_string($conn, $_REQUEST["cardnum"]);
            $exp =  mysqli_real_escape_string($conn, $_REQUEST["expire"]);
            $cvv = mysqli_real_escape_string($conn, $_REQUEST["cvv"]);
            $pname =  mysqli_real_escape_string($conn, $_REQUEST["pname"]);
            $pemail = mysqli_real_escape_string($conn, $_REQUEST["pemail"]);
        
            // Prepare an insert statement
            $paymentSql = "INSERT INTO payment (fname, lname, email, address, address2, district, country, paymentmethodID, nameOnCard, cardNum, expiration, cvv, accountName, paypalEmail)
            VALUES ('$fname', '$lname', '$email', '$add1', '$add2', '$district', '$country', '$pay', '$cname', '$cardnum', '$exp', '$cvv', '$pname', '$pemail')";
         
            if(mysqli_multi_query($conn, $paymentSql)){

                $payment_last_id = mysqli_insert_id($conn);
                $_SESSION['lastid'] = $payment_last_id;
                $invoicetotal = $_SESSION['total'];
                $invoicetotalTax = $_SESSION['totalTax'];

                $invoiceSql = "INSERT INTO invoice (paymentID, invoiceTotalnotax, tax, invoiceTotalwithtax, note)
                VALUES ('$payment_last_id', '$invoicetotal', '12.5', '$invoicetotalTax', 'Thank you for your purchase!')";

                if(mysqli_multi_query($conn, $invoiceSql)){
                    header("location: thankyou.php");
                } else {
                    echo "Oops! Invoice error. Please try again later.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="img/cart4.svg">
    <title>Checkout</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>

<body>
    <!--navbar-->
    <nav class="navbar sticky-top navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">Best Online Shopping</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav nav-fill mr-auto">
                <li class="nav-item">
                    <a class="nav-link disabled" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                      </svg>&emsp;Hi, <?php echo htmlspecialchars($_SESSION["username"]); ?></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16">
                            <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.371 2.371 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976l2.61-3.045zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0zM1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5zM4 15h3v-5H4v5zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3zm3 0h-2v3h2v-3z"/>
                          </svg>&emsp;Products</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="home.php#clothes">Clothes</a>
                        <a class="dropdown-item" href="home.php#shoes">Shoes</a>
                        <a class="dropdown-item" href="home.php#groceries">Groceries</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-check" viewBox="0 0 16 16">
                        <path d="M11.354 6.354a.5.5 0 0 0-.708-.708L8 8.293 6.854 7.146a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"/>
                        <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                      </svg>&emsp;Checkout</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0v2z"/>
                        <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
                      </svg>&emsp;Sign Out</a>
                </li>
            </ul>
            <span class="badge"><?php echo count($_SESSION['cart']); ?></span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                        <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>
                      </svg>&emsp;Your Cart
        </div>
    </nav>
    <h1 class=" jumbotron text-center" style="padding: 100px;">Checkout Form</h1>
    <div class="container" id="checkoutform">
        <div class="row">
            <!--Cart-->
            <div class="col-md-4 order-md-2">
                <h4>Your Cart</h4>
                <ul class="list-group">
                <?php
                    include 'conn.php';
                    $total = 0;
                    $index = 0;
					if(!empty($_SESSION['cart'])){
						$query = $conn->query($cartsql);
					while($row = $query->fetch_assoc()){
				?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo $row['productName']; ?>
                        <span class="badge badge-primary badge-pill">$<?php echo $row['productCost']; ?></span>
                    </li>
                    <?php
                            $total = $total + $row['productCost'];
                            $_SESSION['total'] = $total;
						    $index ++;
							}
						}
						else{
					?>
						<li class="list-group-item d-flex justify-content-between align-items-center">No Items in the Cart</li>
					<?php
						}
					?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Total<span class="badge"><strong>$<?php echo $total; ?></strong></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        General Sales Tax<span class="badge">12.5%</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Total with Tax<span class="badge"><strong>$
                            <?php 
                                $tax = $total * .125;
                                $totalTax = $total + $tax;
                                $_SESSION['totalTax'] = number_format($totalTax, 2);
                                echo number_format($totalTax, 2);
                            ?>
                        </strong></span>
                    </li>
                </ul><br>
                <div class="row">
                <div class="col-md-7">
                    <a href="home.php#clothes" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                        <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>
                      </svg>&emsp;Add another Item</a>
                </div>
                <div class="col-md-5 text-right">
			        <a href="clear_cart.php" class="btn btn-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                      </svg>&emsp;Clear Cart</a>
                </div>
                </div>
            </div>
            <!--Shipping-->
            <div class="col-md-8 order-md-1">
                <h4>Shipping Address</h4>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">    
                <div class="row">
                        <div class="col-md-6">
                            <label for="fname">First Name</label>
                            <input type="text" class="form-control" name="fname" required maxlength="50">
                        </div>
                        <div class="col-md-6">
                            <label for="lname">Last Name</label>
                            <input type="text" class="form-control" name="lname" required maxlength="50">
                        </div>
                    </div>
                    </div>
                    <div class="form-group">
                        <label for="mail">Email</label>
                        <input type="email" class="form-control" placeholder="name@mail.com" name="email" required maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" name="add1" placeholder="123 Your St." name="add1" required>
                    </div>
                    <div class="form-group">
                        <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                        <input type="text" class="form-control" name="add2" placeholder="Apartment, Suite, etc." name="add2">
                    </div>
                    <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="district">District</label>
                            <select class="custom-select d-block w-100 form-control" name="district" required>
                                <option value="">Choose...</option>
                                <option>Corozal</option>
                                <option>Orange Walk</option>
                                <option>Belize</option>
                                <option>Cayo</option>
                                <option>Stann Creek</option>
                                <option>Toledo</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $empty_err; ?></span>
                        </div>
                        <div class="col-md-8">
                            <label for="country">Country</label>
                            <input type="text" class="form-control" name="country" value="Belize (BZ)" readonly>
                        </div>
                    </div>
                    </div>
                    <br>
                    <hr class="my-4">
                    <!--Payment-->
                    <h4>Payment</h4>
                    <!--payment method-->
                    <div class="form-group">
                    <div class="btn-group btn-block" role="group" id="payment" required>
                        <input type="radio" class="btn" name="pay" value="1" id="dccard">
                        <label for="credit">Credit Card or Debit Card</label>
                        <input type="radio" class="btn" name="pay" value="2" id="paypal">
                        <label for="paypal">Paypal</label>
                    </div>
                    <!--card form-->
                    <div class="my-3" id="dccardform">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="cname">Name on Card</label>
                                    <input type="text" class="form-control" name="cname" id="cname">
                                    <small class="text-muted">Full name as displayed on card</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="cardnum">Card Number</label>
                                    <input type="number" class="form-control" name="cardnum" id="cardnum">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="expire">Expiration</label>
                                    <input type="date" class="form-control" name="expire" id="expire">
                                </div>
                                <div class="col-md-6">
                                    <label for="cvv">CVV</label>
                                    <input type="number" class="form-control" name="cvv" id="cvv">
                                </div>
                            </div>
                    </div>
                    <!--paypal form-->
                    <div class="my-3" id="paypalform">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="payname">Account Name</label>
                                    <input type="text" class="form-control" name="pname" id="pname">
                                </div>
                                <div class="col-md-6">
                                    <label for="payemail">Email</label>
                                    <input type="email" class="form-control" name="pemail" id="pemail">
                                </div>
                            </div>
                    </div>
                    </div>
                    <hr class="my-4">
                    <div class="form-group">
                        <input class="btn btn-success btn-lg btn-block my-4" type="submit" value="Checkout">
                        <span class="text-danger"><?php if(empty($_SESSION["cart"])){ echo "Please add an item to the cart to checkout."; } ?></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--footer-->
    <div class="jumbotron text-center" style="margin-bottom: 0; margin-top: 50px; background-color: aquamarine;">
        <div class="row">
            <div class="col-md-8">
                <h1 id="welcome">Thank you for choosing Best Online Shop!</h1>
            </div>
            <div class="col-md-4 text-center" style="margin-right: 0px;">
                <h5>Visit our Social Media</h5>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                    <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                  </svg>&emsp;&emsp;<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                  </svg>&emsp;&emsp;<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                    <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
                  </svg><br><br>
                <h5>or Contact Us via</h5>
                <p>Phone Number: 111-2233
                    <br>Email: <a href="# ">bestonlineshop@gmail.com</a></p>
            </div>
        </div>
    </div>
    <script src="index.js"></script>
</body>

</html>