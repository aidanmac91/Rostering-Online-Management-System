<!--
  File Name: edit_roster.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for editing a roster
-->
<?php
session_start();//start session
$rosterID = $_GET['rosterID'];//set roster variable
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
  {
        $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
        $database   = $connection->selectDB('staff');//database
        $collection = $database->selectCollection('rosters');//collection

        $roster= array();
        $name=$_POST['staffMember'];
        $name .=" ";
        $name .=$_POST['weekDay'];
        $roster['name']= $name;//concatenated string is set as name
        $roster['weekDay'] =$_POST['weekDay'];//set weekDay to value in $_POST['weekDay']
        $roster['location']=$_POST['location'];//set location to value in $_POST['location']
        $roster['timeslot']=$_POST['timeslot'];//set timeslot to value in $_POST['timeslot']
        $roster['staffMember']=$_POST['rosterStaffID'];//set rosterStaffID to value in $_POST['rosterStaffID']

        $newdata = array('$set' => array('name' => $name,'weekDay'=>$_POST['weekDay'],
          'location'=>$_POST['location'],'timeslot'=>$_POST['timeslot'],'staffMember'=>$_POST['staffMember']));
             //create array of data
            //update document with name =$_POST['origianlStaffMember'] with the array
        $collection->update(array('name' => $_POST['origianlStaffMember']), $newdata); 
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
      $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
      $database   = $connection->selectDB('staff');
      $collection = $database->selectCollection('rosters');
    }
    catch(MongoConnectionException $e)
    {
      die("Failed to connect to database ".$e->getMessage());
    }

$appQuery = array('name' => $rosterID);//query to compare

$cursor = $collection->find($appQuery);//documents matching query
?>

<?php
try
{
  $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
  $database   = $connection->selectDB('staff');
  $collection = $database->selectCollection('accommodations');
}
catch(MongoConnectionException $e)
{
  die("Failed to connect to database ".$e->getMessage());
}

$cursor1 = $collection->find();//gets all documents

?>

<?php
try
{
  $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
  $database   = $connection->selectDB('staff');
  $collection = $database->selectCollection('users');
}
catch(MongoConnectionException $e)
{
  die("Failed to connect to database ".$e->getMessage());
}

$cursor2 = $collection->find();//gets all documents

?>

<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Edit roster</title>
  <link type="text/css" rel="stylesheet" href="" />
      </head>

      <body>
        <?php
        $loc=array();
        //loop through cursor1 and populate loc with accomodation Name
        while ($cursor1->hasNext()):
          $accom = $cursor1->getNext(); 
        array_push($loc, $accom['accomodationName']);
        ?>
      <?php endwhile;?>

      <?php
      $staffArray=array();
      //loop through cursor2 and populate staffArray with staff Name
      while ($cursor2->hasNext()):
        $staff = $cursor2->getNext(); 
      array_push($staffArray, $staff['name']);
      ?>
    <?php endwhile;?>


    <?php include '../common.php';?>
    <?php while ($cursor->hasNext())://loop though cursor and display information to user
    $roster = $cursor->getNext(); ?>
    <h2><?= $roster['name'] ?></h2>
    <strong>Date:</strong> <?= $roster['weekDay']?> <br />
    <strong>Staff Member:</strong> <?= $roster['staffMember']?> <br />
    <strong>Location:</strong> <?= $roster['location']?> <br />
    <strong>Time slot:</strong> <?= $roster['timeslot']?> <br />
  <?php endwhile;?>


  <?php if ($_SESSION['type'] == "Administrator"): ?><!--if user is admin-->
  <h1>Roster Edit</h1>
  <?php if ($trigger === 'show_form'): ?>

  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><!--form-->
    <h3>Status</h3>
    <p><input type="hidden" name="origianlStaffMember" value="<?= $roster['name']?>" id="origianlStaffMember" /></p><!--hidden value of orig staff member-->
    <label for="timeslot">Time</label>
    <select id="myList" name="timeslot" onchange="show()"><!--dropdown list of timeslot-->
        <!--if the value is equal to the value in db it is set to the index-->
     <option value="08:00-20:00"<?php if ($roster['timeslot'] == '08:00-20:00') echo ' selected="selected"'; ?>>08:00-20:00</option> 
     <option value="20:00-8:00"<?php if ($roster['timeslot'] == '20:00-8:00') echo ' selected="selected"'; ?>>20:00-8:00</option>
     <option value="20:00-24:00"<?php if ($roster['timeslot'] == '20:00-24:00') echo ' selected="selected"'; ?>>20:00-24:00</option>
   </select><br>
   <label for="location">Location</label>
   <select id="location" name="location" onchange="show()"><!--dropdown list of locations-->
        <!---->
     <?php
     foreach ($loc as $value) {
       $select = $roster['location'] == $value ? ' selected' : '';//if the value is equal to the value in db it is set to the index
       echo'<option value="'.$value.'"' . $select . '>'.$value.'</option>';
     }
     ?>
   </select>
   <label for="weekDay">Date</label>
   <p><input type="text" name="weekDay" value="<?= $roster['weekDay']?>" id="weekDay" /></p><!--input field of date-->
<!--DATE PICKER-->
   <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
   <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
   <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
   <link rel="stylesheet" href="/resources/demos/style.css"/>
   <script>
   $(function() {
    $( "#weekDay" ).datepicker();
  });
   </script>
 </p>

 <label for="staffMember">Staff Member</label>
 <select id="staffMember" name="staffMember" onchange="show()"><!--dropdown list of staff members-->
  <?php
  foreach ($staffArray as $value) {
   $select1 = $roster['staffName'] == $value ? ' selected' : '';//if the value is equal to the value in db it is set to the index
   echo'<option value="'.$value.'"' . $select1 . '>'.$value.'</option>';
 }
 ?>
</select>
</p>
<p><input type="submit" name="submit" value="Save"/></p>
</form>
<?php else: ?>
  <p>
    roster saved.
    <a href="../view/viewRoster.php">Edit another roster?</a>
    <a href="../home.php">Main Menu</a>
  </p>
<?php endif;?>
<?php endif; ?>

<?php include '../footer.php';?>
</body>
</html>