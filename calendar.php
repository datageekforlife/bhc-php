
<?php

  $pageContent = NULL;
  $dateContent = NULL;
  $timeContent = NULL;
  $semesterContent = NULL;
  $holidayContent = NULL;
  $amChecked = $pmChecked = NULL;
  date_default_timezone_set('America/Chicago');

  $ampm = date('A');
  $seconds = date('s');
  $minutes = date('i');
  $hours = date('g');
  $displayhours = $hours;
  $month = date("m");
  $day = date("j");
  $year = date("Y");

If (filter_has_var(INPUT_POST, 'submit')) {
  $ampm = filter_input(INPUT_POST,'ampm');
  $seconds = filter_input(INPUT_POST,'seconds');
  $minutes = filter_input(INPUT_POST,'minutes');
  $displayhours = filter_input(INPUT_POST,'hours');
  $month = filter_input(INPUT_POST,'month');
  $day = filter_input(INPUT_POST,'day');
  $year = filter_input(INPUT_POST,'year');
  $hours = $displayhours;
}

if($ampm == 'PM') {
  if($hours < 12) {
    $hours += 12;
  }
$pmChecked = "checked";
} else { 
  if($hours == 12) {
    $hours = 0;
}
$amChecked = "checked";
}
$today = mktime($hours,$minutes,$seconds,$month,$day,$year);

$timeForm = <<<HERE
<p>show the results below.</p>
<form method= "post">
<input type="number" name="hours"  value="$displayhours" placeholder="HH" min="1" max="12">
<input type="number" name="minutes"  value="$minutes" placeholder="MM" min="0" max="59">
<input type="number" name="seconds"  value="$seconds" placeholder="SS" min="0" max="59">
<label><input type="radio" name="ampm"  value="AM" $amChecked>&nbsp;AM</label>
<label><input type="radio" name="ampm"  value="PM" $pmChecked>&nbsp;PM</label>
<input type="submit" name="submit" value="Show Selected">
<input type="submit" name="reset" value="Show Now">
HERE;

$month_select = array(1 =>'January','February','March','April','May','June','July','August','September','October','November','Decemeber');
$monthList = NULL;
foreach ($month_select as $key => $value) {
  if($key == $month) {
    $monthList .= <<<HERE
    <option value="$key" selected>$value</option>\n
HERE;
  }else{
    $monthList .= <<<HERE
    <option value="$key">$value</option>\n
HERE;
  }
}
$dayList = NULL;
for ($i=1; $i<= 31; $i++) {
  if($i == $day) {
    $dayList .= <<<HERE
    <option value="$i" selected>$i</option>\n
HERE;
    }else{
  $dayList .= <<<HERE
  <option value="$i">$i</option>\n
  
HERE;
    } 
}

$yearList = NULL;
for ($j = date('Y'); $j >= 2001; $j--) {
  if($j == $year){
    $yearList .= <<<HERE
      <option value="$j" selected>$j</option>\n
HERE;
  }else{
    $yearList .= <<<HERE
    <option value="$j">$j</option>\n
HERE;
        } 
  }

$dateForm = <<<HERE
<p> What If we use another date</p>
  <select name="month">
    $monthList
</select>
<select name="day">
    $dayList
</select>
<select name="year">
    $yearList
  </select>
<input type="submit" name="submit" value="Show Selected">
<input type="submit" name="reset" value="Show Today">
</form>
HERE;

$currentDate = date("l, F j, Y", $today);
$currentTime = date("g:i,A", $today);
$dateContent = <<<HERE
<h2>Happy Birthday! The day is $currentDate. The time is $currentTime </h2>
HERE;


$morning=6;
$daytime=12;
$evening=18;

if ($hours >= $evening) {
    $timeContent .= <<<HERE
  <figure>
      <img src='images/evening.jpg' alt="Evening image">
     <figcaption>It's Night Time...</figcaption>
  </figure>
HERE;  
} else if ($hours >= $daytime ) {
    $timeContent .= <<<HERE
  <figure>
      <img src='images/day.jpg' alt="Day image">
      <figcaption>It's Day Time...</figcaption>
  </figure>
HERE;
} else if ($hours >= $morning) {
  $timeContent .= <<<HERE
  <figure>
      <img src='images/morning.jpg' alt="Morning image">
      <figcaption>Get Going It's Morning...</figcaption>
  </figure>
HERE;
} else {
  $timeContent .= <<<HERE
  <figure>
    <img src='images/night.jpg' alt="Night image">
    <figcaption>It's Night Time...</figcaption>
  </figure>

HERE;
}

 
  $summer = 6;
  $fall = 9;

  if ($month >= $fall){
      $semesterContent .= <<<HERE
    <figure>
        <img src='images/fall.jpg' alt="Fall image">
        <figcaption>It's Fall Semester...</figcaption>
    </figure>
  HERE; 
} else if ($month >= $summer ) {
    $semesterContent .= <<<HERE
  <figure>
    <img src='images/summer.jpg' alt="Summer image">
    <figcaption>Grab your Swimsuit It's Summer Semester...</figcaption>
  </figure>
HERE;
} else {
  $semesterContent . <<<HERE
  <figure>
    <img src='images/spring.jpg' alt="Spring image">
    <figcaption>It's Spring Semester...</figcaption>
  </figure>
HERE;
}

$day1 = date('z',strtotime("December 25"));
$day2 = date('z', $today);
if ($day1 == $day2) {
  $holidayContent .= <<<HERE
  <figure>
    <img src='images/christmas.jpg' alt="Christmas image">
    <figcaption>Merry Christmas</figcaption>
  </figure>
HERE;
} else if ( $day1 > $day2 ) {
   $diff = $day1 - $day2;
  $holidayContent .= <<<HERE
  <figure>
    <img src='images/notchristmas.jpg' alt="Not Christmas image">
    <figcaption>There are $diff days until Christmas</figcaption>
  </figure>
HERE;
} else {
  $day4 = date('z',strtotime("December 31"));
  $day3 = date('z',strtotime("December 25 +1 year"));
  $diff = ($day4 - $day2) + $day3;
  $holidayContent .= <<<HERE
  <figure>
    <img src='images/notchristmas.jpg' alt="Not Christmas image">
    <figcaption>There are $diff days until Christmas</figcaption>
  </figure>
HERE;
}

$pageContent .= <<<HERE
$dateContent
<div class="container">
  <div class="row">
    <div class="col-md">
      $timeContent
    </div>
    <div class="col-md">
      $semesterContent
    </div>
    <div class="col-md">
      $holidayContent
    </div>
  </div>
      $timeForm
      $dateForm
</div>
HERE;


$pageTitle = "My Calendar";
include 'template.php';
?>
  

   


  
