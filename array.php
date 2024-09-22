<!DOCTYPE html>
<html lang = "en">

<head>
    
        <meta charset="utf-8">
        <title>Title</title>
        <link href="css/style.css" rel="stylesheet">
</head>
<body>

<?php
$pageContent = null;

// associative array
$albums = array("Radio" => "1", "Whitney Houston" => "2", "Heart" => "3", "King of Rock" => "4", "TGIF" => "5");
$albums["Abbey Road"] = "10";
foreach ($albums as $title => $rating) {
	$pageContent .= "$title is $rating<br>";
}


 
/* Multidimensional array */
$artists = array(
	"The Beatles" => array("A Hard Day's Night" => "1964", "Help!" => "1965", "Rubby Soul" => "1965", "Abby Road" => "1969"),
	"Led Zepplin" => array("LedZepplin IV" => "1971"),
	"Rolling Stones" => array("Let it Bleed" => "1969", "Sticky Fingers" => "1971"),
    "The Who" => array("Tommy" => "1969", "Quadrophenia" => "1973", "The Who by Numbers" => "1975")
);

 
$tommyRelease = $artists["The Who"]["Tommy"];

 $pageContent .= "<h1> Tommy was Release in $tommyRelease<h1>";

/* Nested foreach loops */
foreach ($artists as $artist => $albums) { // steps through $artiist and selects $artists as sub-array value
    $pageContent .= "<h1> " . $artist . "</h1>\n";
    $pageContent .= "<ul>\n";
    foreach ($albums as $album => $date) { // uses the value of the sub-arrays above as the array name for the nested loop
        $pageContent .= "<li>$album, $date</li>\n";
    }
	$pageContent .= "</ul>\n";
}


/* Select greater than 1969 foreach loops */
$pageContent .= "<h1>Albums Produce after 1970</h1>\n";
$pageContent .= "<ul>\n";
foreach ($artists as $artist => $albums) { // steps through $artiist and selects $artists as sub-array value
  foreach ($albums as $album => $date) { // uses the value of the sub-arrays above as the array name for the nested loop
    if($date > "1970") {
      $pageContent .= "<li>$album, $date</li>\n"; 
    }
  }
}
$pageContent .= "</ul>\n";

echo $pageContent;
?>

</body>

</html>