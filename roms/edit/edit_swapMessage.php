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
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
    $database   = $connection->selectDB('staff');
    $collection = $database->selectCollection('swapMessages');

    $message1               = array();
    $message1['name']=$_POST['name'];
    $message1['from']=$_POST['from'];
    $message1['swapWith'] =$_POST['swapWith'];
    $message1['approved']=isset($_POST['approved']);
    $message1['date']=$_POST['date'];
    $newdata = array('$set' => array('name' =>$message1['name'],'staffMember'=>'true','approved'=>isset($_POST['approved'])));

    if(($message1['approved'])=='true')
     {
      $collection1 = $database->selectCollection('rosters');
      $name=$_POST['from'];
      $name .=" ";
      $name .=$_POST['date'];
      $newdata1 = array('$set' => array('name' =>$name,'staffMember'=>$message1['from']));
      $collection1->update(array('name' => $_POST['swapWith']), $newdata1); 
     }
    // else
    // {
      $newdata = array('$set' => array('name' =>$message1['name'],'seen'=>'true', 'approved'=>$message1['approved']));
   //}
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
  $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
  $database   = $connection->selectDB('staff');
  $collection = $database->selectCollection('swapMessages');
}
catch(MongoConnectionException $e)
{
  die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();
    $appQuery = array('name' => $messageID);//,'context'=>'Bob');// && array('context'=>'Innishannon');

$cursor = $collection->find($appQuery);
?>

<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Edit swap message</title>
  <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
      </head>
      <body>
        <?php include '../common.php';?>
        <?php while ($cursor->hasNext()):
        $message = $cursor->getNext(); ?>
        <h2><?= $message['name'] ?></h2>
        <!-- <strong>location:</strong> <?= $appointment['location']?> <br /> -->
      <?php endwhile;?> 
      <?php if ($_SESSION['type'] == "Administrator"): ?>
      <h1>Message editor</h1>
      <?php if ($trigger === 'show_form'): ?>

      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <input type="hidden" name="name" value="<?= $message['name']?>" id="name" width="150px" readonly/></p>
        <p>From: <input type="text" name="from" value="<?= $message['from']?>" id="from" readonly/></p>
        <p>Swap with: <input type="text" name="swapWith" value="<?= $message['swapWith']?>" id="swapWith" readonly/></p>
         <p>When: <input type="text" name="date" value="<?= $message['date']?>" id="date" readonly/></p>
        <input type="checkbox" name="approved" value="<?= $message['approved']?>" <?php echo ($message['approved']==1 ? 'checked' : '');?> />Approved<br>  
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
</body>
</html>