<?php
function priceCalc($price, $quantity) {
if($quantity > 5) {
        $quantityIndex = 5;
} else {
        $quantityIndex=$quantity;
}
$discountPercent = array(0.0,0.0,0.05,0.1,0.2,0.25);
$discountPrice = $price -(($discountPercent[$quantityIndex]*$price)/100);
$total = $discountPrice * $quantity;
return $total;
}

?>
