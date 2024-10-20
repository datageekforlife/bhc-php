<?php

$firstname = $lastname = $email = $password = $password2 = $pageContent = $username = $fileInfo = NULL;

$invalid_firstname = $invalid_lastname = $invalid_email = $invalid_email_format = $invalid_password = $invalid_image = $mismacth_password = NULL;
$valid = true;
$logged_in = false;
$msg = NULL;




if (isset($_POST['submit'])) {
	
	$firstname = htmlspecialchars($_POST['firstname']);
	if (empty($firstname)) {
		$invalid_firstname = '<span class="text-danger">Required </span>';
		$valid = false;
	} 
			
	$lastname = htmlspecialchars($_POST['lastname']);
	if (empty($lastname)) {
		$invalid_lastname = '<span class="text-danger">Required </span>';
		$valid = false;
	} 
		
	$email = htmlspecialchars($_POST['email']);
	if (empty($email)) {
		$invalid_email = '<span class="error">Required </span>';
		$valid = false;
	} 
				
	// validate email using a regular expression
	if (!preg_match('/[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}/', $email)) {
					// returns 1 (true) for match, 0 (false) for no match
		$invalid_email_format = "<p class='error'>Invalid email address</p>";
		$valid = false;
				
	}
	$username = strtolower(substr($firstname,0,1) . $lastname);
	$password = htmlspecialchars($_POST['password']);
	if (empty($password)) {
		$invalid_password = '<span class="error">Required </span>';
		$valid = false;
	} 

	If ($valid) {
		$filetype = pathinfo($_FILES['profilePic']['name'],PATHINFO_EXTENSION);
		if ((($filetype == "gif") or ($filetype == "jpg") or ($filetype == "png")) and $_FILES['profilePic']['size'] < 100000) {
		$valid = false;
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
				
			$imageName = $_FILES["profilePic"]["error"];
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
			if (move_uploaded_file($_FILES['profilePic']['tmp_name'], "$file")){
				$fileInfo .= "<p>Your File has been uploaded. Stores as: $file</p>";
				
				//update the membership file
			
			$filename = "membership.txt";
			$data_entry = $firstname . "," . $lastname . "," . $email . "," . $username . "," . $password. "\n";
			$fp = fopen($filename, "a") or die ("Couldn't open file, sorry.");
			
			if (fwrite($fp, $data_entry) > 0) { 
				$fp = fclose($fp);
				$logged_in = TRUE; 
			} else {
				$fp = fclose($fp);
				$msg = "Your information was not saved. Please try again at another time.<br>";
			}
			
			} else {
			$invalid_image .=  '<p><span class="error">Your file counld not be uploaded.';
			}	
		}
	}	
	

if ($logged_in) {
	$poem = "poem.txt";
	$fp = fopen($poem, "r") or die ("Couldn't open file, sorry.");
	if (!feof($fp)) { 
		$poemText = fgets($fp); 
		} else {
	$pageContent .= "Your information was not found. Please try again at another time.<br>";
}
$fp = fclose($fp); 

$pageContent .= <<<HERE
	$msg
	<p>Thank You, $firstname $lastname.</p>
		<figure><img src="uploads/name" alt="Profile image" class="profilePic" />
		<figcaption>Member: $firstname, $lastname</figcaption>
		</figure>
		<p>Email: $email </p>
		<p> Welcome you have now been entered into the website </p>
		<p>Username: <strong>$username</strong></p>
		<p><a href="file-uploads.php">Reload Page</a></p>
		<h2> My Poem </h2>
		<p>$poemText</p>
		</section>\n

		$pageContent .= <<<HERE
		<section class="container">
	<p>New members must register before entering.</p>
	<form action="file-uploads.php" enctype="multipart/form-data" method="post">
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
			<input type="submit" name="submit" value="Submit Profile" class="btn btn-primary">
		</div>
	</form>
	</section>\n
	
	HERE;

	$pageTitle = "File Uploads";
	include 'template.php';
	?>