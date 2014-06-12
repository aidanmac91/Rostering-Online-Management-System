<!--
  File Name: create_appointment.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for creating an appointment
-->
<?php

session_start();

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
  //save appointment
  try
  {
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish connection
    $database   = $connection->selectDB('staff');//connect to database
    $collection = $database->selectCollection('appointments');//select collection
    
    $appointment               = array();//appointment array
    $name=$_POST['clientName'];
    $name .=" ";
    $name .=$_POST['appointmentDate'];
    $appointment['name']=$name;//sets concatinated string to name 
    $appointment['clientName'] =$_POST['clientName'];//set clientName to value in $_POST['clientName']
    $appointment['appointmentDate']=$_POST['appointmentDate'];//set appointmentDate to value in $_POST['appointmentDate']
    $appointment['location']=$_POST['location'];//set location to value in $_POST['location']
    $appointment['attended']=false;//set attended to false
    $appointment['time']=$_POST['time'];//set time to value in $_POST['time']
    $appointment['otherInfo']=$_POST['otherInfo'];//set otherInfo to value in $_POST['otherInfo']

    $collection->insert($appointment);       //inserts appointment array
  } 
  catch(MongoConnectionException $e) //connection exception
  {

    die("Failed to connect to database ".$e->getMessage());
  }

  catch(MongoException $e) //exception
  {

    $die('Failed to insert data '.$e->getMessage());
  }
  break;

  case 'show_form':
  default:
}
?>

<?php
try
//retrieve information from database
{
  $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
  $database   = $connection->selectDB('staff');//which database
  $collection = $database->selectCollection('clients');//which collection
}
catch(MongoConnectionException $e)
{
  die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();//saves all document into the cursor object

?>

<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Add a appointment</title>
  <link type="text/css" rel="stylesheet" href="" />
      </head>
      <body>
        <?php include '../common.php';?><!--include common.php -->

        <?php
        //loops through cursor and save the name of the object into the clientArray
        $clientArray=array();
        while ($cursor->hasNext()):
          $client = $cursor->getNext(); 
        array_push($clientArray, $client['clientName']);
        ?>
      <?php endwhile;?>

      <h1>Appointment Creater</h1>
      <?php if ($trigger === 'show_form'): ?>
      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><!-- the form for creating an appointmet-->
      
       <label for="clientName">Client Name<br /></label>
       <select id="myList" name="clientName" onchange="show()"><!-- Dropdown list of client name-->
         <?php
         foreach ($clientArray as $value) //loops through array and creates an option for each object
         {
          echo'<option value="'.$value.'">'.$value.'</option>'; 
        }
        ?>
      </select>
      <p>
        <!-- THE DATE PICKER WIDGET-->
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
      <body>
        <p>
          <label for="appointmentDate">Date<br></label>
          <input id="datepicker" name="appointmentDate"/><!-- Displays the date picker-->
        </p>
      </body>
    </p>
    <p>
      <label for="location">Where<br /> </label>
      <input id="location" name="location"/><!-- input field for location -->
    </p>
    <p>
      <label for="time">Time <br /></label>
      <input id="time" name="time"/><!-- input field for time -->
    </p>
    <p>
      <label for="otherInfo">Other Information <br /></label>
      <br>
      <textarea id="otherInfo" name="otherInfo"/>
    </textarea><!-- textarea field for otherInfo -->
  </p>
  <p><input type="submit" name="submit" value="Save"/></p>
</form>
<?php else: ?>
  <p>
    Appointment saved.
    <a href="<?php echo $_SERVER['PHP_SELF'];?>">Add another appointment?</a><br>
    <a href="../home.php">Main Menu</a>
  </p>
<?php endif;?>


</form>

<?php include '../footer.php';?><!-- Include footer.php -->
</body>

</html>