<?php
// load config.php to connect to the database
include_once 'config.php';

// initialize variables
$pageContent = NULL;

// check $_GET for postID to load a single post
if (filter_has_var(INPUT_GET, 'postID')) {

	// get the postID from the query string
	$postID = filter_input(INPUT_GET, 'postID');

	// call the blogPost function from config.php
	$postData = blogPost($conn, $postID);
  

	// pull the values from the array returned by the blogPost() function
	$postTitle = $postData[0];
	$postContent = $postData[1];

	// assemble the HTML
	$pageContent = "<h2>$postTitle</h2>
	$postContent\n
	<p><a href='blog.php'>Back to Blog</a></p>";

} else { // load the default blog list

	// initialize the list
	$postList = "<ul>";
	// call the blogPosts function from config.php
	$postListData = blogPosts($conn);
	// build the list from the multidimensional array returned by blogPosts() function
	foreach ($postListData as $blogPost) {
		foreach ($blogPost as $postID => $postTitle) {
			if($postID == 0) { // list error message returned from function
				$postList = <<<HERE
			<p>$postTitle</p>
HERE;      
			} else { // build the blog post list
			$postList .= <<<HERE
			<li><a href="blog.php?postID=$postID">$postTitle</a></li>
HERE;
			}
		}
	}
	// close the list
	$postList .= "</ul>";

	// assemble the HTML
	$pageContent = <<<HERE
		<h2>Daily Blog Selections</h2>
		<p>Please select a blog post below.</p>
		$postList
HERE;

}

$pageTitle = "My Blog";
include 'template.php';
?>