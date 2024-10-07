
<?php
$pageTitle = "Invoice Results";
include_once 'functions.php';

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
		$cost = priceCalc($cdPrice, $i)+ $shipping;
		$orderList .= "<p> The price for $i Cds is $cost</p> ";

	}
}
if($media == 'download'){
	$heading .= " - Downloads";
	$i = 1;
	while( $i <= $quantity) {
		$cost = priceCalc($downloadPrice, $i);
		$orderList .= "<p> The price for $i Downloads is $cost.</p> ";
		$i++;
	}
}

$pageContent = <<< HERE
		<section>
			<h2>$heading</h2>
				<article>
				<h3> Order for $userName </h3>
					"<h3> Order for $userName</h3>";
					$orderList
					$userNameError
					$userQuantityError
					$userMediaError			
				</article>
			</section>
	HERE;
	include_once 'template.php';
	?>