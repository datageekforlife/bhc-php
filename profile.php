<?php
include_once 'config.php';
if (!$conn) {
	echo "Failed to connect to MySQL: ".mysqli_connect_error();
}
if(isset($_POST['memberID'])) {
	$memberID = $_POST['memberID'];
} elseif(isset($_GET['memberID'])) {
	$memberID = $_GET['memberID'];
	
} else {
	header("Location: register.php");
	exit();
}
$pageTitle = "Profile";

$firstname = $lastname = $email = $password = $password2 = $pageContent = $username = $fileInfo = NULL;

$invalid_firstname = $invalid_lastname = $invalid_email = $invalid_email_format = $invalid_password = $invalid_image = $mismatch_password = NULL;
$valid = true;
$logged_in = FALSE;
$msg = Null;

if(isset($_GET['action'])) {
	$msg = "<p class='text-danger'>Record" . $_GET['action'] . "</p>";
}
if(isset($_GET['update'])) {
	$update = TRUE;
}

if(isset($_POST['update'])) {
	$firstname = mysqli_real_escape_string($conn, ucwords(trim($_POST['firstname'])));
	if (empty($firstname)) {
		$invalid_firstname = '<span class="error">Required field</span>';
		$valid = FALSE;
		} 
	}
			
	$lastname = mysqli_real_escape_string($conn, ucwords(trim($_POST['lastname'])));
	if (empty($lastname)) {
		$invalid_lastname = '<span class="error">Required </span>';
		$valid = FALSE;
	} 
		
	$email = htmlspecialchars($_POST['email']);
	if (empty($email)) {
		$invalid_email = '<span class="error">Required </span>';
		$valid = FALSE;
	} 
				
	// validate email using a regular expression
	if (!preg_match('/[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}/', $email)) {
					// returns 1 (true) for match, 0 (false) for no match
		$invalid_email_format = "<p class='error'>Invalid email address</p>";
		$valid = FALSE;	
	}

	if($valid) {
		$query = "UPDATE `membership` SET `firstname` = '$firstname', `lastname` = '$lastname',`email` = '$email' WHERE `memberID` = $memberID;";
		$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
		if (!$result) {
			die(mysqli_error($conn));
		
		}
	}
		
	
	$password = trim($_POST['password']);
	if (!empty($password)) {
		$password2 = trim($_POST['password2']);
	if (strcmp($password, $password2)) {
		$mismatch_password = '<span class="error">Passwords do not match</span>';
		$valid = FALSE;
		} else {
		$query = "UPDATE `membership` SET `password` = '$password' WHERE `memberID` = $memberID;";
			$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
			if (!$result) {
				die(mysqli_error($conn));
			} else {
				$row_count = mysqli_affected_rows($conn);
				if ($row_count == 1) {
					echo "<p>Record updated</p>";
				} else {
					echo "<p> Password Update failed</p>";
				}
			}
		}
	}

	//$username = strtolower(substr($firstname,0,1) . $lastname);

	if (!empty($_FILES['profilePic']['name'])) {
	 unlink	("uploads/" . $_POST['imageName']);
		$filetype = pathinfo($_FILES['profilePic']['name'] ,PATHINFO_EXTENSION);
	}
	if ((($filetype == "gif") or ($filetype == "jpg") or ($filetype == "png")) and $_FILES ['profilePic'] 
	['size'] < 300000) {	
				if ($_FILES["profilePic"]["error"] > 0) {
				$fileError = $_FILES["profilePic"]["error"];
				$invalid_image = "<p class= 'error'>Return Code: $fileError<br>";
				switch ($fileError) {
		case 1:
			$invalid_image .= 'The file exceeds the upload_max_filesize</p>';
			break;
		case 2:
			$invalid_image .= 'The file exceeds the upload_max_filesize in HTML form</p>';
			break;
		case 3:
			$invalid_image .= 'The file was only partially uploaded</p>';
			break;
		case 4:
			$invalid_image .= 'No file was uploaded</p>';
			break;
		case 6:
			$invalid_image .= 'Temporary folder does not exist</p>';
			break;
		default:
			$invalid_image .= 'Something unexpected happened.</p>';
			break;
		}
	}				
		} else {
				
			$imageName = $_FILES["profilePic"]["name"];
			$file = "uploads/$imageName";
			$fileInfo = "<p>Upload:  $imageName<br>";
			$fileInfo .= "Type: " . $_FILES["profilePic"]["type"] . "<br>";
			$fileInfo .= "Size: " . ($_FILES["profilePic"]["size"] / 1024) . " Kb<br>";
			$fileInfo .= "Temp file: " . $_FILES["profilePic"]["tmp_name"] . "</p>";
			
			// if the file already exists in the upload directory, give an error
			if (file_exists("$file")) {
				$invalid_image = "<span class='error'>$imageName  already exists.</span>";
				$valid = false;
				} else {
				// move the file to a permanent location
			if (move_uploaded_file($_FILES['profilePic']['tmp_name'], "$file")) {
				$fileInfo .= "<p>Your File has been uploaded. Stores as: $file</p>";
				
			
				$query = "UPDATE `membership` SET `image` = '$imageName' WHERE `memberID` = $memberID;";
				$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
				if (!$result) {
					die(mysqli_error($conn));
				} else {
					$row_count = mysqli_affected_rows($conn);
					if ($row_count == 1) {
						echo "<p>Record updated</p>";
					} else {
						echo "<p>Image Update failed</p>";
					}
				}
			} else {
			$invalid_image .=  '<p><span class="error">Your file counld not be uploaded.';
			}	
		}
	}	
	
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
	
	If (!$update) {
$pageContent .= <<<HERE
	<section class="container">
	$msg
		<figure><img src="upload/$image" alt="Profile image" class="profilePic" />
		<figcaption>Member: $firstname, $lastname</figcaption>
		</figure>
		<p>Thank You, $firstname, $lastname.</p>
		<p><a href="profile.php?update&memberID=$memberID">Update Profile</a></p>
		<p>Email: $email</p>
		<p> Welcome you have now been entered into the website </p>
		<p>Username: <strong>$username</strong></p>
		<p><a href="file-uploads.php">Reload Page</a></p>
		</section>\n
	HERE;

	} else {
	$pageContent .= <<<HERE
	
		<section class="container">
		$msg
	<p>Please update your information.</p>
	<form action="profile.php" enctype="multipart/form-data" method="post">
		<div class="form-group">
				<label for="firstname">First Name:</label>
			<input type="text" name="firstname" id="firstname" value="$firstname" class="form-control"> $invalid_firstname
		</div>
		<div class="form-group">
			<label for="lastname">Last Name:</label>
			<input type="text" name="lastname" id="lastname" value="$lastname" class="form-control"> $invalid_lastname
		</div>
		<div class="form-group">
			<label for="email">E-Mail:</label>
			<input type="text" name="email" id="email" value="$email" class="form-control"> $invalid_email $invalid_email_format
		</div>
		<div class="form-group">
		<label for="password">Password:</label>
		<input type="password" name="password" id="password" value="" class="form-control"> $invalid_password
	</div>
	<div class="form-group">
		<label for="password2">Password Verify:</label>
		<input type="password" name="password2" id="password2" value="" class="form-control">  
	</div>
	<figure><img src="uploads/$image" alt="Profile image" class="profilePic" />
	<figcaption>Member: $firstname, $lastname</figcaption>
	</figure>
		<p>Please select an image for your profile.</p>
		<div class="form-group">
			<input type="hidden" name="MAX_FILE_SIZE" value="300000">
			<label for="profilePic">File to Upload:</label> <span class="text-danger">$invalid_image </span>
			<input type="file" name="profilePic" id="profilePic" class="form-control">
		</div>
		<div class="form-group">
		<input type="hidden" name="imageName" value="$image" class="btn btn-primary">
		<input type="hidden" name="memberID" value="$memberID" class="btn btn-primary">
		<input type="submit" name="update" value="Update Profile" class="btn btn-primary">
		</div>
	</form>
	<form action="delete-verify.php" method="post">
	<div class="form-group">
	<input type="hidden" name="memberID" value="$memberID" class="btn btn-primary">
	<input type="submit" name="delete" value="Delete Profile" class="btn btn-primary">
		</div>
		</form>
	</section>\n
	HERE;
	}
	
	include 'template.php';
	?>