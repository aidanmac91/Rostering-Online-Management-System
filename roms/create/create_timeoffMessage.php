<?php

session_start();

$_SESSION['regName'] = $regValue;


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
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
    $database   = $connection->selectDB('staff');
    $collection = $database->selectCollection('timeoffMessages');

    $message               = array();
    $name=$_POST['staffName'];//+" "+$_POST['weekDay'];
    $name .=" time off  ";
    $name .=$_POST['date'];
    $message['name']=$name;
    $message['from'] =$_POST['staffName'];
    $message['reason'] =$_POST['reason'];
    $message['duration'] =$_POST['duration'];
    $message['otherInfo'] =$_POST['otherInfo'];
    $message['date'] =$_POST['date'];
    $message['seen'] =false;
    $message['approved'] =false;
    $appointment['saved_date']   = new MongoDate();

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
{
  $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
  $database   = $connection->selectDB('staff');
  $collection = $database->selectCollection('rosters');
}
catch(MongoConnectionException $e)
{
  die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();

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

$cursor1 = $collection->find();

?>



<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Add a message</title>
  <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
      </head>
      <body>
        <?php include '../common.php';?>

        <?php
        $rosterArray=array();
        while ($cursor->hasNext()):


          $roster = $cursor->getNext(); 
        array_push($rosterArray, $roster['name']);
        ?>
      <?php endwhile;?>

      <?php
      $staffArray=array();
      while ($cursor1->hasNext()):


        $staff = $cursor1->getNext(); 
      array_push($staffArray, $staff['name']);
      ?>
    <?php endwhile;?>
    <p>
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
      <select id="myList" name="reason" onchange="show()">
        <option>Medical</option>
        <option>Personal</option>
        <option>Other</option>
      </select>
    </p>
    <p>
      <label for="duration">Duration<br /></label>
      <select id="myList" name="duration" onchange="show()">
        <option>6 hours</option>
        <option>11 hours</option>
      </select>
    </p>
    <p>
      <label for="otherInfo">Other Information <br /></label>
      <textarea id="otherInfo" name="otherInfo"/>
    </textarea>
    <br>
  </select>
  <label for="staffName">Staff Member</label>
  <select id="staffName" name="staffName" onchange="show()">
    <?php

    foreach ($staffArray as $value) {
      echo'<option value="'.$value.'">'.$value.'</option>'; 
    }
    ?>
  </select>
  <br>
  <p>
    <label for="date">Date<br></label>
    <input id="datepicker" name="date"/>
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
</body>
</html>