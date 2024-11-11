<?php
$memberID = $_GET['memebrID'];

$query = "DELETE FROM `membership` WHERE `memberID` = $memberID LIMIT 1;";
$result = mysqli_query($conn,$query);
if (!$result) {
	die(mysqli_error($conn));
} else {
	$row_count = mysqli_affected_rows($conn);
	if ($row_count == 1) {
		echo "<p>Record deleted</p>";
	} else {
		echo "<p>Delete failed</p>";
	}
}


?>