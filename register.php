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


	If ($valid) {
		$filetype = pathinfo($_FILES['profilePic']['name'],PATHINFO_EXTENSION);
		if ((($filetype == "gif") or ($filetype == "jpg") or ($filetype == "png")) and $_FILES['profilePic']['size'] < 300000) {
		$valid = false;
		}
	}
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
			} else {
			$invalid_image .=  '<p><span class="error">Your file counld not be uploaded.';
			}	
		}
	}	
	

	/* Read a txt File Example */
	If ($logged_in) {
	$query = "SELECT * FROM `membership` WHERE `memberID` = $memberID;";
	$result = mysqli_query($conn,$query);
	if (!$result) {
		die(mysqli_error($conn));
		}
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


$pageContent .= <<<HERE
		<section class="container">
	<p>New members must register before entering.</p>
	<form action="register.php" enctype="multipart/form-data" method="post">
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

		<p>Please select an image for your profile.</p>
		<div class="form-group">
			<input type="hidden" name="MAX_FILE_SIZE" value="300000">
			<label for="profilePic">File to Upload:</label> <span class="text-danger">$invalid_image </span>
			<input type="file" name="profilePic" id="profilePic" class="form-control">
		</div>
		<div class="form-group">
			<input type="hidden" name="imageName" value="$image" class="btn btn-primary">
			<input type="hidden" name="memberID" value="$memberID" class="btn btn-primary">
			<input type="submit" name="submit" value="Submit Profile" class="btn btn-primary">
	</div>
	</form>
	</section>\n
	
	HERE;
		
	
	include 'template.php';
	?>