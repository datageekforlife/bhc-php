<?php
// load config.php to connect to the database
include_once 'config.php';

if (!$conn) {
	echo "Failed to connect to MySQL: ".mysqli_connect_error();
}
  $pageTitle = "Blog";
  $title = $content = NULL;
  $invalid_title = $invalid_content = NULL;
  $pageContent = $msg = NULL;
  
if(isset($_SESSION['memberID'])){
  $memberID = $_SESSION['memberID'];
} else {
  $memberID = 28;
}

if(filter_has_var(INPUT_POST, 'edit')){
    $edit = TRUE;
  } else {  
    $edit = FASLE;
  }

  if (filter_has_var(INPUT_POST, 'postID')) {
    $postID = filter_input(INPUT_POST, 'postID');
  } elseif(filter_has_var(INPUT_GET, 'postID')) {
    $postID = filter_input(INPUT_GET, 'postID');
  } else {
    $postID = NULL;
  }
  if($postID) {
      $stmt = $conn->stmt_init();
      if ($stmt-> prepare("SELECT `postTitle`, `postContent` FROM `blog` WHERE `postID`= ?")){
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $stmt->bind_result($title, $content);
        $stmt->fetch();
        $stmt->close();
      }
$buttons = <<<HERE
      <div class="form-group">
		      <input type="hidden" name="postID" value="$postID"> 
          <input type="hidden" name="process"> 
          <input type="submit" name="update" value="Update Post" class="btn btn-success"> 
      </div>  
HERE;
} else {  
$buttons = <<<HERE
      <div class="form-group">
          <input type="hidden" name="process"> 
          <input type="submit" name="insert" value="Save Post" class="btn btn-success"> 
      </div>  
HERE;
}

  if(filter_has_var(INPUT_POST, 'delete')){
    $stmt = $conn->stmt_init();
    if($stmt->prepare("DELETE FROM 'blog' WHERE 'postID' = ?")) {
      $stmt->bind_param("i", $postID);
      $stmt->execute();
      $stmt->close();
    }
  
    header("Location: blog-admin.php");
    exit();
  }
    if(filter_has_var(INPUT_POST, 'process')){
     $valid = TRUE;
     $title = mysqli_real_escape_string($conn, trim(filter_input(INPUT_POST, 'title')));
     if (empty($tiltle)){
      $invalid_title = '<span class="error">Required field</span>';
      $invalid = FALSE;
     }
$content = mysqli_real_escape_string($conn, trim(filter_input(INPUT_POST, 'content')));
     if (empty($content)){
      $invalid_content = '<span class="error">Required field</span>';
      $invalid = FALSE;
}
if($valid) {
  if(filter_has_var(INPUT_POST, 'insert')){
    $stmt = $conn->stmt_init();
    if($stmt->prepare("INSERT INTO 'blog' ('title','content','authorID') VALUES (?, ?, ?)")) {
      $stmt->bind_param("ssi", $title, $content, $memberID);
      $stmt->execute();
      $stmt->close();
  }
 
//$postID = mysqli_insert_id($conn)

      header("Location: blog-admin.php?postID=$postID");
      exit();
  }
  if(filter_has_var(INPUT_POST, 'update')){
    $stmt = $conn->stmt_init();
    if($stmt->prepare("UPDATE 'blog' SET 'title'= ?,'content'= ? WHERE 'postID'= ?")){
      $stmt->bind_param("ssi", $title, $content, $postID);
      $stmt->execute();
      $stmt->close();
    }
      header("Location: blog-admin.php?postID=$postID");
      exit();
    }
 }
}

if ($edit) {
  $pageContent .= <<<HERE
		<section class="container>"
		$msg
	  <p>Please complete the form.</p>
	  <form action="blog-admin.php" method="post">
		  <div class="form-group">
		    <label for="title">Blog Title</label>
			  <input type="text" name="title" id="title" value="$title" class="form-control"> 
      $invalid_title
		  </div>
		  <div class="form-group">
			<label for="content">Last Name</label>
			<textarea name="content" id="content" class="form-control">$content</textarea>
       $invalid_content
		  </div>
      $buttons
      </form>
      <form action="blog-admin.php" method="post">
        <div class="form-group">
          <input type="submit" name="cancel" value="Show Blog List" class="btn btn-primary">
       </div>
      </form>
	  </section>\n

          
	if($classList_row_cnt > 0){ // make sure we have at least 1 record
		$selectPost = <<<HERE
		<ul>\n
HERE;
		
} elseif ($postID) {
  $pageContent = <<<HERE
    <h2> Blog Post </h2>
    <h3>$title</h3>
    <p>$content</p>
    <form action="blog-admin.php" method="post">
      <div class="form-group">
        <input type="hidden" name="postID" value="$postID">
        <input type="submit" name="edit" value="Edit Post" class="btn btn-success">
        </div>
      </form>
      <form action="blog-admin.php" method="post">
      <div class="form-group">
       <input type="submit" name="cancel" value="Show Blog List" class="btn btn-primary">
      </div>
    </form>
    <form action="blog-admin.php" method="post">
      <input type="hidden" name="postID" value="$postID">
      <div class="form-group">
       <input type="submit" name="delete" value="Delete Post" class="btn btn-danger">
      </div>
    </form>
HERE;
 } else {
  $where = 1;
  $stmt = $conn->stmt_init();
  if ($stmt->prepare("SELECT `postID`, `postTitle` FROM `blog`")) {
    $stmt->execute();
    $stmt->bind_result($postID, $title);
    $stmt->store_result();
    $classList_row_cnt = $stmt->num_rows();
    
    if($classList_row_cn > 0){
      $selectPost = <<<HERE
      <ul>\n
HERE;
      while($stmt->fetch()){ // loop through the result set to build our list
			$selectPost .= <<<HERE
      <li><a href="blog-admin.php?postID=$postID">$title</a></li>\n
     
HERE;     
			
      }
      $selectPost .=<<<HERE
       <ul>\n
HERE;
	  } else {
		$selectPost = "<p>There are zero Blog Posts available at this time.</p>";
	  }
	  $stmt->free_result();
	  $stmt->close();
    
} else {
	$selectPost = "<p>The blog site is down now. Please try again later.</p>";
}
  $pageContent = <<<HERE
  <h2>Blog List</h2>
  $selectPost
  <form action="blog-admin.php" method="post">
   <div class="form-group">
       <input type="submit" name="edit" value="Create New Post" class="btn btn-success">
      </div>
   </form>
HERE;
}

echo $pageContent;
include 'footer.php';
include 'header.php';
?>