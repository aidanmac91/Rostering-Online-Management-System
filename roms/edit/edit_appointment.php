<!--
  File Name: edit_timeOffMessage.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for editing a time off message
-->
<?php
session_start();//start session
$appointmentID = $_GET['appointmentID'];//set variable from session variable
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
  //update object
  {
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
    $database   = $connection->selectDB('staff');//select database
    $collection = $database->selectCollection('appointments');//select collection

    $appointment = array();
    $name=$_POST['clientName'];
    $name .=" ";
    $name .=$_POST['appointmentDate'];
            $appointment['name']=$name;//concated string is set for name
            $appointment['clientName'] =$_POST['clientName'];//set clientName to value in $_POST['clientName']
            $appointment['appointmentDate']=$_POST['appointmentDate'];//set appointmentDate to value in $_POST['appointmentDate']
            $appointment['location']=$_POST['location'];//set location to value in $_POST['location']
            $appointment['attended']=isset($_POST['attended']);//set attended to value in $_POST['attended']
            $appointment['time']=$_POST['time'];//set time to value in $_POST['time']
            $appointment['otherInfo']=$_POST['otherInfo'];//set otherInfo to value in $_POST['otherInfo']

            $newdata = array('$set' => array('name' =>  $appointment['name'],'clientName' => $_POST['clientName'],'appointmentDate'=>$_POST['appointmentDate'],
              'location'=>$_POST['location'],'attended'=>isset($_POST['attended']),'time'=>$_POST['time'],'otherInfo'=>$_POST['otherInfo']));
            //create array of data
            //update document with name =$_POST['appointmentName'] with the array
            $collection->update(array('name' =>  $_POST['appointmentName']), $newdata); 
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
        <?php
        try
        {
          $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish 
          $database   = $connection->selectDB('staff');//select database
          $collection = $database->selectCollection('appointments');//select appointment
        }
        catch(MongoConnectionException $e)
        {
          die("Failed to connect to database ".$e->getMessage());
        }

        $appQuery = array('name' => $appointmentID);//query to compare
        $cursor = $collection->find($appQuery);//document matching query
?>

<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Edit appointment</title>
  <link type="text/css" rel="stylesheet" href="" />
      </head>
      <body>
        <?php include '../common.php';?><!--include common.php-->
        <?php while ($cursor->hasNext())://loop through cursor and display information back to the user
        $appointment = $cursor->getNext(); ?>
        <h2><?= $appointment['clientName'] ?></h2>
        <strong>Where:</strong> <?= $appointment['location']?> <br />
        <strong>When:</strong> <?= $appointment['appointmentDate']?> <br />
        <strong>Time:</strong> <?= $appointment['time']?> <br />
        <strong>Information:</strong> <?= $appointment['otherInfo']?> <br />

      <?php endwhile;?> 
      <?php if ($_SESSION['type'] == "Administrator"): ?><!--if the user is an administrator-->
      <h1>Appointment Creator</h1>
      <?php if ($trigger === 'show_form'): ?>

      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><!--form -->
        <input type="hidden" name="appointmentName" value="<?= $appointment['name']?>" id="appointmentName"/><!--hidden from user-->
        <p>Name: <input type="text" name="clientName" value="<?= $appointment['clientName']?>" id="clientName" readonly/></p><!--input field for client Name. Readonly-->
        <p>Where: <input type="text" name="location" value="<?= $appointment['location']?>" id="location" /></p><!--input field for location-->

        <p>
          <!--DATE PICKER WIDGET-->
          <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
          <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
          <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
          <link rel="stylesheet" href="/resources/demos/style.css"/>
          <script>
          $(function() {
            $( "#datepicker" ).datepicker();
          });
          </script>
          <p>Date: <input type="text" name="appointmentDate" value="<?= $appointment['appointmentDate']?>" id="datepicker" /></p><!--input field for date-->

          <p>Time: <input type="text" name="time" value="<?= $appointment['time']?>" id="time" /></p><!--input field for time-->

          <input type="checkbox" name="attended" value="<?= $appointment['attended']?>" <?php echo ($appointment['attended']==1 ? 'checked' : '');?> />Attended<br><!--checkbox field for attended-->
          <!-- <input type="radio" name="attened" value="female">Not attended -->
          
          <p>Other information:<br> <textarea type="textarea" name="otherInfo" value="<?= $appointment['otherInfo']?>" id="otherInfo" /><?php echo  $appointment['otherInfo'];?></textarea></p><!--textarea field for info-->

          <p><input type="submit" name="submit" value="Save"/></p>
        </form>
      <?php else: ?>
      <p>
        Appointment edit.<br>
        <a href="../view/viewAppointment.php">Add another appointment?</a><br>
        <a href="../home.php">Main Menu</a>
      </p>
    <?php endif;?>
  <?php endif; ?>

  <?php include '../footer.php';?>
</body>
</html>