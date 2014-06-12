
<!--
  File Name: create_timeoffMessage.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for creating a swap message
-->
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
  //save time off message
  {
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
    $database   = $connection->selectDB('staff');//which database
    $collection = $database->selectCollection('timeoffMessages');//which collection

    $message               = array();//message array
    $name=$_POST['staffName'];
    $name .=" time off  ";
    $name .=$_POST['date'];
    $message['name']=$name;//sets concatinated string to name 
    $message['from'] =$_POST['staffName'];//set from to value in $_POST['staffName']
    $message['reason'] =$_POST['reason'];//set reason to value in $_POST['reason']
    $message['duration'] =$_POST['duration'];//set duration to value in $_POST['duration']
    $message['otherInfo'] =$_POST['otherInfo'];//set otherInfo to value in $_POST['otherInfo']
    $message['date'] =$_POST['date'];//set date to value in $_POST['date']
    $message['seen'] =false;//set seen to false
    $message['approved'] =false;//set approved to false

    $collection->insert($message);       
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
      //retrieves information from database
{
  $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
  $database   = $connection->selectDB('staff');//which database
  $collection = $database->selectCollection('rosters');//which collection
}
catch(MongoConnectionException $e)
{
  die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();//saves all documents into the cursor object

?>
<?php
try
      //retrieves information from database
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



<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Add a message</title>
  <link type="text/css" rel="stylesheet" href="" />
      </head>
      <body>
        <?php include '../common.php';?><!--Include common.php-->

        <?php
         //loops through cursor and save the name of the object into the rosterArray
        $rosterArray=array();
        while ($cursor->hasNext()):
          $roster = $cursor->getNext(); 
        array_push($rosterArray, $roster['name']);
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
    <p>
      <!-- DATE PICKER -->
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
    <h1>Message Creater</h1>
    <?php if ($trigger === 'show_form'): ?>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
     <p>
      <label for="reason">Reason<br /></label>
      <select id="myList" name="reason" onchange="show()"><!-- Dropdown list of reason-->
        <option>Medical</option>
        <option>Personal</option>
        <option>Other</option>
      </select>
    </p>
    <p>
      <label for="duration">Duration<br /></label>
      <select id="myList" name="duration" onchange="show()"><!-- Dropdown list of duration-->
        <option>6 hours</option>
        <option>11 hours</option>
      </select>
    </p>
    <p>
      <label for="otherInfo">Other Information <br /></label>
      <textarea id="otherInfo" name="otherInfo"/>
    </textarea><!-- text area for other information-->
    <br>
  </select>
  <label for="staffName">Staff Member</label>
  <select id="staffName" name="staffName" onchange="show()"><!-- Dropdown list of staffName-->
    <?php

    foreach ($staffArray as $value) 
    {
      echo'<option value="'.$value.'">'.$value.'</option>'; //loops through array and creates an option for each object
    }
    ?>
  </select>
  <br>
  <p>
    <label for="date">Date<br></label>
    <input id="datepicker" name="date"/><!--DISPLAYS DATE PICKER -->
  </p>
  <p><input type="submit" name="submit" value="Save"/></p>
</form>
<?php else: ?>
  <p>
    Message saved.
    <a href="<?php echo $_SERVER['PHP_SELF'];?>">Add another Message?</a><br>
    <a href="../home.php">Main Menu</a>
  </p>
<?php endif;?>


</form>

 <?php include '../footer.php';?>
</body>
</html>