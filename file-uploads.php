<?php

$firstname = $lastname = $email = $password = $password2 = $pageContent = $username = $fileInfo = $errMsg = NULL;

$invalid_firstname = $invalid_lastname = $invalid_email = $invalid_email_format = $invalid_password = $invalid_image = NULL;
$valid = true;
$logged_in = false;

if (isset($_POST['submit'])) {


	if (empty($_POST['firstname'])) {
		$invalid_firstname = '<span class="text-danger">Required </span>';
		$valid = false;
	} else {
		$firstname = ucfirst(htmlspecialchars(trim($_POST['firsttname'])));		
	}
		
	if (empty($_POST['lastname'])){
			$invalid_lastname = '<span class="text-danger">Required </span>';
			$valid = false;
	} else {
			$lastname = ucfirst(htmlspecialchars(trim($_POST['lastname'])));	
	}	
	$password = trim($_POST['password']);
	if (empty($password)) {
		$invalid_password = '<span class="error">Required Field </span>';
		$valid = false;
	}
	$password2 = trim($_POST['password2']);
	if (strcmp($password, $password2)) {
		$mismatch_password = '<span class="error">Passwords Do Not Match </span>';
		$valid = false;
	} 
	if (empty($_POST['email'])) {	
		$invalid_email = '<span class="error">Required </span>';
		$valid = false;
	} else {
		$email = trim($_POST['email']);

	// validate email using a regular expression
	if (!preg_match('/[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}/', $email)) {
					// returns 1 (true) for match, 0 (false) for no match
		$invalid_email_format = "<p class='error'>Invalid email address</p>";
		$valid = false;
				
		}
	}


// only allow files that are gif, png or jpeg files to be uploaded and restrict size to 20KB
$filetype = pathinfo($_FILES['profilePic']['name'],PATHINFO_EXTENSION);
if ((($filetype == "gif") or ($filetype == "jpg") or ($filetype == "png")) and $_FILES['profilePic']['size'] < 100000) {
	// check to make sure there is no error on the upload. If so, display the errror 
	
	if ($_FILES["profilePic"]["error"] > 0) {
		$pageContent .=  "Return Code: " . $_FILES["profilePic"]["error"] . "<br>";
	} else {
		// display information about the file 
		$pageContent .=  "Upload: " . $_FILES["profilePic"]["name"] . "<br>";
		$pageContent .=  "Type: " . $_FILES["profilePic"]["type"] . "<br>";
		$pageContent .=  "Size: " . ($_FILES["profilePic"]["size"] / 1024) . " Kb<br>";
		$pageContent .=  "Temp file: " . $_FILES["profilePic"]["tmp_name"] . "<br>";
		
		// if the file already exists in the upload directory, give an error
		if (file_exists("upload/" . $_FILES["profilePic"]["name"])) {
			$pageContent .= $_FILES["profilePic"]["name"] . " already exists. ";
		} else {
			// move the file to a permanent location
			move_uploaded_file($_FILES["profilePic"]["tmp_name"],"upload/" . $_FILES["profilePic"]["name"]);
			$pageContent .= "Stored in: " . "upload/" . $_FILES["profilePic"]["name"];
			}
		}
	} else {
	$pageContent .=  "Invalid file";
	}
}

/* Append a txt File Example */
// specify the name of the txt file
$filename = "membership.txt";

// format the form data to be saved to the txt file
$data_entry = $firstname . "," . $lastname . "," . $email . "," . $username . "\n";

// open the txt file ($filename) to append (a) and assign it to a file handle ($fp)
$fp = fopen($filename, "a") or die ("Couldn't open file, sorry.");
// write (fwrite) the data ($data_entry) to the file using the file handle ($fp)
if (fwrite($fp, $data_entry) > 0) { // successful write operation returns 1, failure returns 0
	// do this on success
	$logged_in = TRUE; // sets a login trigger for the program to switch from form display to content display
} else {
	// do this on failure
	echo "Your information was not saved. Please try again at another time.";
}
$fp = fclose($fp); // close the file
		


$poem = "poem.txt";
$fp = fopen($poem, "r") or die ("Couldn't open file, sorry.");

	if (!feof($fp)) { 
$poemText = fgets($fp); 
	} else {
	$pageContent .= "Your information was not found. Please try again at another time.<br>";
}
$fp = fclose($fp); 

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
