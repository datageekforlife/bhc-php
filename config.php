<?php
session_start(); // start a session

$conn = mysqli_connect("localhost", "sherd_WilliamShatner", "s3YcxUBL34kf3","sherd_WilliamShatner");

// Set up debug mode
function debug_data() { // called in template to print arrays at top of any page.
    echo '<pre>SESSION is ';
    echo print_r($_SESSION);
    echo 'COOKIE is ';
    echo print_r($_COOKIE);
    echo 'POST is ';
    echo print_r($_POST);
    echo 'GET is ';
    echo print_r($_GET);
    echo '</pre>';
}
// debug_data(); // Comment this out to hide debug information
function auth_user() {
	if(isset($_SESSION['memberID'])) {
		return TRUE;
	} else {
		return FALSE;
	}
}
$postTitle =NULL;
// this function returns the contents of a blog post given its postID
function blogPost($conn, $postID) {
	$stmt = $conn->stmt_init();
	if ($stmt->prepare("SELECT postTitle, postContent FROM blog WHERE postID = ?")) {
		$stmt->bind_param("i", $postID);
		$stmt->execute();
		$stmt->bind_result($postTitle, $postContent);
		$stmt->fetch();
		$stmt->close();
	}
	$postData = array($postTitle, $postContent);
	return $postData;
}

// this function returns a multidimensional array of blog post IDs and titles
function blogPosts($conn) {
	$stmt = $conn->stmt_init();
	if ($stmt->prepare("SELECT postID, postTitle FROM blog")) {
		$stmt->execute();
		$stmt->bind_result($postID, $postTitle);
		$stmt->store_result();
		$classList_row_cnt = $stmt->num_rows();
		if($classList_row_cnt > 0) { // make sure we have at least 1 record
			while($stmt->fetch()) { // loop through the result set
				// array for each record
				$postData = [$postID => $postTitle];
				// add each record to the multidimensional array
				$postListData[] = $postData;
			}
		} else { // no records in the db
			$postData = [0 => "There are no posts at this time."];
			$postListData[] = $postData;
		}
		$stmt->free_result();
		$stmt->close();
	} else { // db connection error
		$postData = ["The blog is down now. Please try again later."];
		$postListData[] = $postData;
	}
	return $postListData;
}

?>