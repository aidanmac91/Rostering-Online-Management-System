<!--
  File Name: edit_timeOffMessage.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for editing a time off message
-->
<?php
session_start();//start session
$messageID = $_GET['messageID'];//sets session variable
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
    $collection = $database->selectCollection('timeoffMessages');//collection

    $message1               = array();
    $message1['name']=$_POST['name'];//set name to value in $_POST['name']
    $message1['from']=$_POST['from'];//set from to value in $_POST['from']
    $message1['swapWith'] =$_POST['swapWith'];//set swapWith to value in $_POST['swapWith']
    $message1['approved']=isset($_POST['approved']);//set approved to value in $_POST['approved']

    $newdata = array('$set' => array('name' =>$message1['name'],'seen'=>'true','approved'=>isset($_POST['approved'])));
 //create array of data
        //update document with name =$messageID with the array
    $collection->update(array('name' => $messageID), $newdata); 
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
  $collection = $database->selectCollection('timeoffMessages');
}
catch(MongoConnectionException $e)
{
  die("Failed to connect to database ".$e->getMessage());
}
    $appQuery = array('name' => $messageID);//query to compare

$cursor = $collection->find($appQuery);//document that matches query
?>

<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Edit swap message</title>
  <link type="text/css" rel="stylesheet" href="" />
      </head>
      <body>
        <?php include '../common.php';?>
        <?php while ($cursor->hasNext()):
        //loop through cursor and display information to user
        $message = $cursor->getNext(); ?>
        <h2><?= $message['name'] ?></h2>
        <h4><?=$message['reason']?></h4>
        <h4><?=$message['duration']?></h4>
        <h4><?=$message['date']?></h4>
        <h4><?=$message['otherInfo']?></h4>
      <?php endwhile;?>
      <?php if ($_SESSION['type'] == "Administrator"): ?><!--if user is admin-->
      <h1>Message editor</h1>
      <?php if ($trigger === 'show_form'): ?>

      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <input type="hidden" name="name" value="<?= $message['name']?>" id="name" width="150px" readonly/></p><!--readonly input field of name-->
        <p>From: <input type="text" name="from" value="<?= $message['from']?>" id="from" readonly/></p><!--readonly input field of from-->
        <p>Reason: <input type="text" name="reason" value="<?= $message['reason']?>" id="reason" readonly/></p><!--readonly input field of reason-->
        <p>Duration: <input type="text" name="duration" value="<?= $message['duration']?>" id="duration" readonly/></p><!--readonly input field of duration-->
        <p>Date: <input type="text" name="date" value="<?= $message['date']?>" id="date" readonly/></p><!--readonly input field of date-->
        <p>Other information: <textarea type="textarea" name="otherInfo" value="<?= $message['otherInfo']?>" readonly id="otherInfo" /><?= $message['otherInfo']?></textarea></p><!--readonly input field of other Info-->
        <input type="checkbox" name="approved" value="<?= $message['approved']?>" <?php echo ($message['approved']==1 ? 'checked' : '');?> />Approved<br>  <!--readonly checkbox for approved-->
        <p><input type="submit" name="submit" value="Save"/></p>
      </form>
    <?php else: ?>
    <p>
      Swap message saved.<br>
      <a href="../view/viewTimeOffMessage.php">View More requests</a><br>
      
      <a href="../home.php">Main Menu</a>
    </p>
  <?php endif;?>
<?php endif; ?>

<?php include '../footer.php';?>
</body>
</html>