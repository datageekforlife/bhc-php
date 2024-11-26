<?php
include_once 'config.php'; // include connection to the database

if (!$conn) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$pageTitle = 'login';
$msg = $pageContent = $invalid_user = $invalid_password = NULL;

if (filter_has_var(INPUT_POST, 'login')) {
	//Get the information from form
	$username = strip_tags(filter_input(INPUT_POST, 'username'));
	$passwordSubmit = trim(filter_input(INPUT_POST, 'password'));
	$valid = TRUE;

	if ($username == NULL) {
		//If a field is empty, set an error message for that field and load the form
		$invalid_user = '<span class="error">Required field</span>';
		$valid = FALSE;
	}

	if ($passwordSubmit == NULL) {
		//If a field is empty, set an error message for that field and load the form
		$invalid_password = '<span class="error">Required field</span>';
		$valid = FALSE;
	}


	if ($valid) { // if the form data are valid
		$stmt = $conn->stmt_init(); // create the database connection
		if ($stmt->prepare("SELECT `memberID`, `password` FROM `membership` WHERE `username` = ?")) { // prepare the db query
			$stmt->bind_param("s", $username); // lookup this user
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($memberID, $password); // bind the stored password from the db record to a variable
			$stmt->fetch();
			$stmt->free_result();
			$stmt->close();
		} else {
			$msg = <<<HERE
		<h3 class="error">We could not find you in the system. 
		New users must register before gaining access to the site. 
		If you forgot your login, please use the Password Recover tool.</h3>
HERE;
		}

	if ($passwordSubmit === $password) { // checks submitted password against stored password for a match

 			$stmt = $conn->stmt_init();
			if ($stmt->prepare("SELECT `firstname`, `lastname`, `email` FROM `membership` WHERE `memberID` = ?")) {
				$stmt->bind_param("i", $memberID);
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($firstname, $lastname, $email); // get authenticated member record

				if ($stmt->num_rows == 1) {

					$stmt->fetch();

					$_SESSION['memberID'] = $memberID;

					setcookie("firstname", $firstname, time()+(3600*3));

					header("Location: profile.php?msg=You are logged in.");
					exit();
				} else {
					$msg = <<<HERE
				<h3 class="error">We could not access the login records.</h3>
HERE;
				}
				$stmt->close();
			} else {
				$msg = <<<HERE
			<h3 class="error">We could not find your information.</h3>
HERE;
			}
		} else {
			$msg = <<<HERE
		<h3 class="error">We could not find you in the system. 
		New users must register before gaining access to the site. 
		If you forgot your login, please use the Password Recover tool.</h3>
HERE;
		}
	}
}
$pageContent .= <<<HERE
	<section class="container">
	$password<br>
	$msg
		<form action="login.php" method="post">
		<div class="form-group">
    <label>Username</label>
		<input type="text" class="form-control" id="username" name="username" required>$invalid_user
	</div>
	<div class="form-group">
	<label>Password</label>
	<input type="text" class="form-control" id="password" name="password" required>$invalid_password
    </div>
	  <input type="submit" name="login" value="Login" class="btn btn-primary">
	</form>
	</section>
HERE;
include 'template.php'
?>