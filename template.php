<?php
if(isset($_SESSION['memberID'])){
  $loginbutton = '<a href="logout.php">Log Out</a>';
} else {
  $loginbutton = '<a href="login.php">Login</a>';
}


?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $pageTitle; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
  </head>

  <body>
    <header>
      <h1>Register</h1>
    </header>
    <nav>
      
      <a href="index.php">Home</a> | <a href="invoice.php">Invoice</a> | <a href="form.php">Music Order Form</a> | <a href="calendar.php">Calendar</a> | <a href="register.php">Register</a> || <?= $loginbutton; ?>|
      
    </nav>
    <main class="container">
      
    <?= $pageContent; ?>

    </main>


    

    <footer>Stacy Moore BHC PHP Fall 2024</footer>
  </body>

</html>