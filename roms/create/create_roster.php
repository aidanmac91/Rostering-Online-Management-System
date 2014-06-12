<!--
  File Name: roster.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for creating a roster
-->
<?php
try
//populate object with data from accommodations db
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
    $database   = $connection->selectDB('staff');//which database
    $collection = $database->selectCollection('accommodations');//which collection
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();//saves all documents into the cursor object

?>

<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
    $database   = $connection->selectDB('staff');//which database
    $collection = $database->selectCollection('users');//which collection
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$cursor1 = $collection->find();//saves all documents into the cursor1 object

?>



<?php
$trigger = "";

if((!empty($_POST['submit'])) && ($_POST['submit'] === 'Save')) 
{
    $trigger = "do_save";
}
else
{
    $trigger = "show_form";
}

switch($trigger) 
{
    case 'do_save':
    
    try
    //save roster
    {
        $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
        $database   = $connection->selectDB('staff');//which database
        $collection = $database->selectCollection('rosters');//which collection
        
        $roster               = array();//roster array
        $name=$_POST['staffMember'];
        $name .=" ";
        $name .=$_POST['weekDay'];
        $roster['name']=$name; //sets concatinated string to name 
        $roster['weekDay']      = $_POST['weekDay'];//set weekDay to value in $_POST['weekDay']
        $roster['location']     = $_POST['location'];//set location to value in $_POST['location']
        $roster['timeslot']    = $_POST['timeslot'];//set timeslot to value in $_POST['timeslot']
        $roster['staffMember']    = $_POST['staffMember'];//set staffMember to value in $_POST['staffMember']
        
        $collection->insert($roster);      //inserts roster array 
    } 
    catch(MongoConnectionException $e) 
    {

        die("Failed to connect to database ".$e->getMessage());
    }
    
    catch(MongoException $e) 
    {

        $die('Failed to insert data '.$e->getMessage());
    }
    break;
    
    case 'show_form':
    default:
}
?>

<!DOCTYPE html>
<head>

  <?php
   //loops through cursor and save the accomodationName of the object into the accommodationArray
  $accommodationArray=array();
  while ($cursor->hasNext()):
   $accomodation = $cursor->getNext(); 
    array_push($accommodationArray, $accomodation['accomodationName']);
?>
<?php endwhile;?>

<?php
 //loops through cursor1 and save the name of the object into the staffArray
$staffArray=array();
while ($cursor1->hasNext()):
    $staff = $cursor1->getNext(); 
array_push($staffArray, $staff['name']);
?>
<?php endwhile;?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Add a roster</title>
<link type="text/css" rel="stylesheet" href="" />
    </head>
    <body>
        <?php include '../common.php';?><!--include common.php -->
        <h1>roster Creator</h1>
        <?php if ($trigger === 'show_form'): ?>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <!-- DATE PICKER WIDGET-->
            <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
            <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
            <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
            <link rel="stylesheet" href="/resources/demos/style.css"/>
            <script>
            $(function() {
              $( "#datepicker" ).datepicker();
          });
            </script>
        </p>

        <p>
          <label for="weekDay">Date<br></label>
          <input id="datepicker" name="weekDay"/><!-- DISPLAYS CALANDAR -->
      </p>

      <p>
        <label for="location">Location<br /></label>
        <select id="myList" name="location" onchange="show()"><!-- Dropdown list of location-->
           <?php

           foreach ($accommodationArray as $value) {
            echo'<option value="'.$value.'">'.$value.'</option>'; //loops through array and creates an option for each object
        }
        ?>
    </select>
</select>
</p>

<p>
    <label for="timeslot">Timeslot<br /></label>
</p>
<select id="myList" name="timeslot" onchange="show()"><!-- Dropdown list of timeslot-->
    <option>08:00-20:00</option>
    <option>20:00-8:00</option>
    <option>20:00-24:00</option>
</select>
<p>
  <label for="staffMember">Staff Member<br /></label>
</p>
<select id="myList" name="staffMember" onchange="show()"><!-- Dropdown list of timeslot-->
 <?php

 foreach ($staffArray as $value) {
    echo'<option value="'.$value.'">'.$value.'</option>'; //loops through array and creates an option for each object
}
?>
</select>

<p><input type="submit" name="submit" value="Save"/></p>
</form>
<?php else: ?>
    <p>
        roster saved.
        <a href="<?php echo $_SERVER['PHP_SELF'];?>">Add another roster?</a>
        <a href="../home.php">Main Menu</a>
    </p>
<?php endif;?>

<?php include '../footer.php';?>
</body>
</html>