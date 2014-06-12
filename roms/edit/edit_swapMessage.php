<!--
  File Name: edit_swapMessage.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for approving and editing swap messages
-->
<?php
session_start();
$messageID = $_GET['messageID'];
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
    $collection = $database->selectCollection('swapMessages');//collection

    $message1               = array();
    $message1['name']=$_POST['name'];//set name to value in $_POST['name']
    $message1['from']=$_POST['from'];//set from to value in $_POST['from']
    $message1['swapWith'] =$_POST['swapWith'];//set swapWith to value in $_POST['swapWith']
    $message1['approved']=isset($_POST['approved']);//set approved to value in $_POST['approved']
    $message1['date']=$_POST['date'];//set name to value in $_POST['name']
    if(($message1['approved'])=='true')//if message is approved
    {
      $collection1 = $database->selectCollection('rosters');//connect to roster db
      $name=$_POST['from'];
      $name .=" ";
      $name .=$_POST['date'];
      $newdata1 = array('$set' => array('name' =>$name,'staffMember'=>$message1['from']));
      $collection1->update(array('name' => $_POST['swapWith']), $newdata1); //update roster with new infromation
    }
    $newdata = array('$set' => array('name' =>$message1['name'],'seen'=>'true', 'approved'=>$message1['approved']));
    //create array of data
        //update document with name =$_POST['name'] with the array
    $collection->update(array('name' => $_POST['name']), $newdata); 
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
  $database   = $connection->selectDB('staff');//database
  $collection = $database->selectCollection('swapMessages');//collection
}
catch(MongoConnectionException $e)
{
  die("Failed to connect to database ".$e->getMessage());
}

    $appQuery = array('name' => $messageID);//query to compare
$cursor = $collection->find($appQuery);//documents matching query
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
        //loop through cursor and display information back to the user
        $message = $cursor->getNext(); ?>
        <h2><?= $message['name'] ?></h2>
        <h3>Approved: <?= $message['approved'] ?></h3>
      <?php endwhile;?> 


      <?php if ($_SESSION['type'] == "Administrator"): ?><!--is user is admin-->
      <h1>Message editor</h1>
      <?php if ($trigger === 'show_form'): ?>

      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <input type="hidden" name="name" value="<?= $message['name']?>" id="name" width="150px" readonly/></p><!--readonly input field of name-->
        <p>From: <input type="text" name="from" value="<?= $message['from']?>" id="from" readonly/></p><!--readonly input field of form-->
        <p>Swap with: <input type="text" name="swapWith" value="<?= $message['swapWith']?>" id="swapWith" readonly/></p><!--readonly input field of swapWith-->
        <p>When: <input type="text" name="date" value="<?= $message['date']?>" id="date" readonly/></p><!--readonly input field of date-->
        <input type="checkbox" name="approved" value="<?= $message['approved']?>" <?php echo ($message['approved']==1 ? 'checked' : '');?> />Approved<br>  <!--check box for approved-->
        <p><input type="submit" name="submit" value="Save"/></p>
      </form>
    <?php else: ?>
    <p>
      Swap message saved.<br>
      <a href="../view/viewSwapMessage.php">View More requests</a><br>
      
      <a href="../home.php">Main Menu</a>
    </p>
  <?php endif;?>
<?php endif; ?>

<?php include '../footer.php';?>
</body>
</html>