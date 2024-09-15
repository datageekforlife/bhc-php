<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8">
    <title>Title</title>
   
  </head>
  <?php
  /*  */
$quote = "The way to get started is to quit talking and begin doing. by Walt Disney";
$name = "Brookhaven College";
$xddress = "3939 Valley View Lane";
$zitystatezip = "Farmers Branch Texas 75244";
?>
 
  <body>
    <header>
      <h1>Welcome To Stacy's First PHP Page!</h1>
    </header>
    <nav>
      
<body>
<p>My goal is to get back to programming</p>
<?php
# The below are examples of math equations in php
# Homework 2
$txt1 = "Math";
$txt2 = " Calculations";
echo "<p style='font: 24px/21px Arial,tahoma,sans-serif;color:blue'>$txt1  $txt2 </p>";

?> <br><br>

<?php
$x = 5;
$y = 10;
$z = $x * $y;

echo "$x * $y = $z<br>";
?>
<?php
$z = $x + $y;
echo "$x + $y = $z<br>";
$z = $x - $y;
echo "$x - $y = $z<br>";
$z = $x / $y;
echo "$x / $y = $z<br>";
$z = $x % $y;
echo "$x % $y = $z<br>";
?>


<br>


<?php
define("user", "This is Stacy Moore's Index Page");
echo user;
?>
          




            
       
    </nav>
    <main>
      <section>
      <style>
      h2 {
        text-decoration: underline;
      }
    </style>
        <h2>College Address</h2>
        <p><?php print $name . "<br>" . $xddress . "<br>" . $zitystatezip; ?>
        </p>
        <article>
          <h2>Intro</h2>
          <p>This is my Intro</p>
        </article>
        <article>
          <h2>Quote</h2>
          <p><?php echo $quote; ?></p>


          <?php
echo $_SERVER['PHP_SELF'];
echo "<br>";
echo $_SERVER['SERVER_NAME'];
echo "<br>";
echo $_SERVER['HTTP_HOST'];
echo "<br>";
echo $_SERVER['HTTP_USER_AGENT'];
echo "<br>";
echo $_SERVER['SCRIPT_NAME'];
echo "<br>"
?>
        
      

   
  </body>

</html>