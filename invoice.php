<?php
$albums=array("Whitney Houston"=>"Whitney", "Dolly Parton"=>"Rockstar", "Reba McEntire"=>"Reba","Little Big Town"=>"Little Big Town","Jorney"=>"Escape","The Supremes"=>"Meet The Supremes","Garth Brooks"=>"No Fences","Steve Miller Band"=>"Book Of Dreams","Prince"=>"1999","Donna Summers"=>"Bad Girls");
$albums["The Beatles"]="The White Album";
$albumsList = '<label for="album">Choose an Album</label><br>' . "\n";
$albumsList .= '<select name="album" id="album">' ."\n";
foreach ($albums as $album => $rating) {
    $albumsList .= '<option value="' . $album . '">' . $album . '</option>' . "\n";
}
$albumsList .= '</select>' . "\n";
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Form Processing</title>
  </head>

  <body>
    <div class="container">
        <header>
            <h1>Form Processing</h1>
        </header>
        <nav>
            <a href="index.php">Home</a> | <a href="form.php">Album Order Form</a> | <a href="">Contact</a>
        </nav> 
			
<fieldset>
    <legend> Album Request Form </legend>
    <form method="post" action="handle-form.php">
      <p>
        <label for ="userName">Name</label><br> 
        <input type="text" name="userName"  id="userName" value="" size="20" maxlength="40">
    </p>
    <p>
        <label for="quantity">Quantity</label><br>
        <input type="text" name="quantity" id="quantity" value="">
    </p>
    <p><?php echo $albumsList; ?></p>
    <p>
        <label> Media</label><br>
        <input type="radio" name="media" id="cd" value="cd">
        <label for="cd">CD</label>&emsp;
        <input type="radio" name="media" id="dl" value="download">
        <label for="dl">Download</label>
      </p>
      <p>
        <input type="submit" name="submit" value="Submit Request">
      </p>
    </form> 
    </fieldset>
<footer class="my-4 bg-primary">
      <p class="text-light text-center fs-5 p-3">&copy; 2024 PHP Class</p>
    </footer>
</div>
    <!-- Optional JavaScript -->
    <!-- Bundled Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  

</html>