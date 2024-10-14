<?php

$name = $email = $activity = $instrument = "";
$nameErr = $emailErr = $activityErr = $instrumentErr = "";
$animals = array();
$animalErr = "";

// define the arrays for the check boxes, drop down list, and radio buttons
$animalOptions = array("Dogs", "Cats", "Chickens", "Hamsters", "Rabbits");
$activityOptions = array("Soccer", "Basketball", "Tennis", "Baseball", "Swimming");
$instrumentOptions = array("Piano", "Guitar", "Violin", "Drums");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
   
    $name = ucwords($name);
    
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed";
    }
  }
  
  // validate email
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
  }
  
 
  if (empty($_POST["animals"])) {
    $animalErr = "Please select up to 2 favorite animals";
  } else {
    $animals = $_POST["animals"];
   
    if (count($animals) > 2) {
      $animalErr = "You can only select up to 2 favorite animals";
    }
  }
  
  // validate activity
  if (empty($_POST["activity"])) {
    $activityErr = "Please select a favorite activity";
  } else {
    $activity = test_input($_POST["activity"]);
    // check if the selected activity is in the activity options array
    if (!in_array($activity, $activityOptions)) {
      $activityErr = "Invalid activity selection";
    }
  }
  
  // validate instrument
  if (empty($_POST["instrument"])) {
    $instrumentErr = "Please select a favorite musical instrument";
  } else {
    $instrument = test_input($_POST["instrument"]);
   
    if (!in_array($instrument, $instrumentOptions)) {
      $instrumentErr = "Invalid instrument selection";
    }
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>


<h2>PHP Form Validation Example</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  Name: <input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  E-mail: <input type="text" name="email" value="<?php echo $email;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  Favorite animals: <br>
  <?php
 
  foreach ($animalOptions as $option) {
    echo "<input type='checkbox' name='animals[]' value='$option'";
  
    if (in_array($option, $animals)) {
      echo " checked";
    }
    echo ">$option<br>";
  }
  ?>
  <span class="error">* <?php echo $animalErr;?></span>
  <br><br>
  Favorite activity: 
  <select name="activity">
    <option value="">Select...</option>
    <?php
   
    foreach ($activityOptions as $option) {
      echo "<option value='$option'";
   
      if ($option == $activity) {
        echo " selected";
      }
      echo ">$option</option>";
    }
    ?>
  </select>
  <span class="error">* <?php echo $activityErr;?></span>
  <br><br>
  Your Favorite musical instrument: <br>
  <?php
  
  foreach ($instrumentOptions as $option) {
    echo "<input type='radio' name='instrument' value='$option'";
    // check if the option is the same as the selected instrument and mark it as checked
    if ($option == $instrument) {
      echo " checked";
    }
    echo ">$option<br>";
  }
  ?>
  <span class="error">* <?php echo $instrumentErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>


<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($name) && !empty($email) && !empty($animals) && !empty($activity) && !empty($instrument)) {
  echo "<h2>Your Input:</h2>";
  echo "Name: $name<br>";
  echo "Email: $email<br>";
  echo "Favorite animals: ";
  // loop through the selected animals array and display the values
  foreach ($animals as $value) {
    echo "$value ";
  }
  echo "<br>";
  echo "Favorite activity: $activity<br>";
  echo "Favorite musical instrument: $instrument<br>";
}
?>