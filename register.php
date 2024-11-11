<?php 
// This script performs an INSERT query to add a record to the users table.


include('template.php'); 
$page_title = 'Register';

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errors = []; // Initialize an error array.

	// Check for a first name:
	if (empty($_POST['first_name'])) {
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$fn = trim($_POST['first_name']);
	}

	// Check for a last name:
	if (empty($_POST['last_name'])) {
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$ln = trim($_POST['last_name']);
	}
	if (empty($_POST['user_name'])) {
		$errors[] = 'You forgot to enter your user name.';
	} else {
		$un = trim($_POST['user_name']);
	}
	// Check for an email address:
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$e = trim($_POST['email']);
	}

	// Check for a password and match against the confirmed password:
        if (!empty($_POST['pass1'])) {
            if ($_POST['pass1'] != $_POST['pass2']) {
                $errors[] = 'Your password did not match the confirmed password.';
            } else {
                $p = mysqli_real_escape_string($conn, trim($_POST['pass1']));
            }
        } else {
            $errors[] = 'You forgot to enter your password.';
        }

	if (empty($errors)) { // If everything's OK.

		// Register the user in the database...

		require('config.php'); // Connect to the db.

		// Make the query:
		$query = "INSERT INTO `membership` VALUES (DEFAULT,'$fn','$ln','$un','$e','$p', '$imageName');";
		$r = mysqli_query($conn, $q); // Run the query.
		if ($r) { // If it ran OK.

			// Print a message:
			echo '<h1>Thank you!</h1>
		<p>You are now registered. </p><p><br></p>';

		} else { // If it did not run OK.

			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';

			// Debugging message:
			echo '<p>' . mysqli_error($conn) . '<br><br>Query: ' . $q . '</p>';

		} // End of if ($r) IF.

		mysqli_close($conn); // Close the database connection.

		// Include the footer and quit the script:
		include('includes/footer.html');
		exit();

	} else { // Report the errors.

		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br>';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br>\n";
		}
		echo '</p><p>Please try again.</p><p><br></p>';

	} // End of if (empty($errors)) IF.

} // End of the main Submit conditional.
include_once 'config.php';

if(isset($_POST['submit'])) {
	$firstname = mysqli_real_escape_string($conn, trim($_POST['firstname']));
	$lastname = mysqli_real_escape_string($conn, trim($_POST['lastname']));
	$username = mysqli_real_escape_string($conn, trim($_POST['username']));
	$email = mysqli_real_escape_string($conn, trim($_POST['email']));
	$password = mysqli_real_escape_string($conn, trim($_POST['password']));

	if (!$conn) {
		echo "Failed to connect to MySQL: ".mysqli_connect_error($conn);
	}
	
	
	} else {
		$row_count = mysqli_affected_rows($conn);
		if ($row_count == 1) {
			// retrieve the last record inserted id
			$memberID = mysqli_insert_id($conn);
			$insert_success = TRUE;
			echo "<p>Record inserted</p>";
		} else {
			echo "<p>Insert failed</p>";
		}
	}
	// now $memberID can be used in a SELECT query to retrieve the database record

?>
<h1>Register</h1>
<form action="register.php" method="post">
	<p>First Name: <input type="text" name="first_name" size="15" maxlength="20" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>"></p>
	<p>Last Name: <input type="text" name="last_name" size="15" maxlength="40" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>"></p>
	<p>User Name: <input type="text" name="user_name" size="20" maxlength="60" value="<?php if (isset($_POST['user_name'])) echo $_POST['user_name']; ?>" > </p>
    <p>Email Address: <input type="email" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" > </p>
	<p>Password: <input type="password" name="pass1" size="10" maxlength="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>" ></p>
	<p>Confirm Password: <input type="password" name="pass2" size="10" maxlength="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>" ></p>
	<p><input type="submit" name="submit" value="Register"></p>
</form>
