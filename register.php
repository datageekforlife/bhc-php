<?php
include_once "config.php";
$pageTitle = "Register";


$firstname = $lastname = $email = $password = $password2 = $pageContent = $username = $fileInfo = NULL;

$invalid_firstname = $invalid_lastname = $invalid_email = $invalid_email_format = $invalid_password = $invalid_image = $mismatch_password = NULL;
$valid = true;
$logged_in = false;
$msg = Null;


if(isset($_POST['submit'])) {
	$firstname = mysqli_real_escape_string($conn, trim($_POST['firstname']));
	$lastname = mysqli_real_escape_string($conn, trim($_POST['lastname']));
	$username = mysqli_real_escape_string($conn, trim($_POST['username']));
	$email = mysqli_real_escape_string($conn, trim($_POST['email']));
	$password = mysqli_real_escape_string($conn, trim($_POST['password']));


	if (!$conn) {
		echo "Failed to connect to MySQL: ".mysqli_connect_error($conn);
	}




				
			
			$query = "INSERT INTO `membership` VALUES (DEFAULT,'$firstname','$lastname','$username','$email','$password', '$imageName');";
			$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
			if (!$result) {
				die(mysqli_error($conn));
		} else {
				$row_count = mysqli_affected_rows($conn);
				if ($row_count == 1) {
						// retrieve the last record inserted id
						$memberID = mysqli_insert_id($conn);
						$logged_in = TRUE;
						$msg = "<p>Record inserted</p>";
					} else {
						$msg = "<p>Insert failed</p>";
					}
				}		
	

	/* Read a txt File Example */
	$query = "SELECT * FROM `membership` WHERE `memberID` = $memberID;";
	$result = mysqli_query($conn,$query);
	if (!$result) {
		die(mysqli_error($conn));
	}
	
	if ($row = mysqli_fetch_assoc($result)) {
		// set the database field values to local variables for futher use in the script
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$username = $row['username'];
		$email = $row['email'];
		$image = $row['image'];
	} else {
		$msg = "Sorry, we couldn't find your record.";
	}

	echo "<p>Hello, $firstname $lastname. Your username is $username and your email is $email.</p>";
	echo '<img src="images/" . $image . " alt="Profile picture" />';
	?>	
	<h1>Register</h1>
<form action="register.php" method="post">
	<p>First Name: <input type="text" name="first_name" size="15" maxlength="20" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>"></p>
	<p>Last Name: <input type="text" name="last_name" size="15" maxlength="40" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>"></p>
	<p>Email Address: <input type="email" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" > </p>
	<p>Password: <input type="password" name="pass1" size="10" maxlength="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>" ></p>
	<p>Confirm Password: <input type="password" name="pass2" size="10" maxlength="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>" ></p>
	<p><input type="submit" name="submit" value="Register"></p>
</form>
<?php include('template.php'); ?>