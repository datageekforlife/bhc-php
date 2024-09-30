
<?php

$shipping = 2.99;
$downloadPrice = 9.99;
$cdPrice = 12.99;
$heading = "Cost by Quantity";
$orderList = NULL;

if(empty($_POST['userName'])) {
	$userName = "Guest";
	$userNameError ="<p class ='error'>Username is missing from the form and is required to process your order. Please <a href='form.php'> go back to the order form</a> and complete the form
	.</p>";	
} else {
	$userName = $_POST['userName'];
	$userNameError = NULL;
}

if(empty($_POST['quantity'])) {
	$quantity = NULL;
	$userQuantityError ="<p class ='error'>Quantity is missing from the form and is required to process your order. Please <a href='form.php'> go back to the order form</a> and complete the form
	.</p>";	
} else {
	$quantity= $_POST['quantity'];
	$userQuantityError = NULL;
}
if (!isset($_POST['media'])) {
	$media = NULL;
	$userMediaError ="<p class ='error'>Media is missing from the form and is required to process your order. Please <a href='form.php'> go back to the order form</a> and complete the form
	.</p>";	
} else {
	$media= $_POST['media'];
	$userMediaError = NULL;
}

if($media == 'cd'){
  $heading .= " - CDs";
	for($i = 1; $i <= $quantity; $i++) {
		$cost = 0;
		$cost += $i * $cdPrice + $shipping;
		$orderList .= "<p> The price for $i Cds is $cost</p> ";

	}
}
if($media == 'download'){
	$heading .= " - Downloads";
	$i = 1;
	while( $i <= $quantity) {
		 $cost = 0;
		$cost += $i * $downloadPrice;
		$orderList .= "<p> The price for $i Downloads is $cost.</p> ";
		$i++;
	}
}
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/style.css">
    <title>Form Processing</title>
  </head> 

  <body>
		<div class="container">
			<header>
				<h1>Form Processing</h1>
			</header>
			<nav>
				<a href="index.php">Home</a> | <a href="form.php">Album Order Form</a> | <a href="">Contact</a>
			</nav>
			<section>
				<h2><?php echo $heading; ?></h2>
				<article>
					<?php
					echo "<h3> Order for $userName</h3>";
					echo $orderList;
					echo $userNameError;
					echo $userQuantityError;
					echo $userMediaError;
					//echo "<pre>POST ";
					//print_r($_POST);
					//echo "</pre>";
					?>
				</article>
			</section>
			<footer>
				<p>Footer content goes here</p>
			</footer>
		</div>
  </body>

</html>
