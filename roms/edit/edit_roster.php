<?php

session_start();

$rosterID = $_GET['rosterID'];


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
            $collection = $database->selectCollection('rosters');
 
            $roster               = array();
            $name=$_POST['staffMember'];
            $name .=" ";
            $name .=$_POST['weekDay'];
            $roster['name']= $name;
            $roster['weekDay'] =$_POST['weekDay'];
            $roster['location']=$_POST['location'];
            $roster['timeslot']=$_POST['timeslot'];
            $roster['staffMember']=$_POST['staffMember'];
            $roster['saved_date']   = new MongoDate();

                $newdata = array('$set' => array('name' => $name,'weekDay'=>$_POST['weekDay'],
                    'location'=>$_POST['location'],'timeslot'=>$_POST['timeslot'],'staffMember'=>$_POST['staffMember']));

                 $collection->update(array('staffMember' => $_POST['staffMember']), $newdata); 
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
$appQuery = array('name' => $rosterID);//,'context'=>'Bob');// && array('context'=>'Innishannon');

$cursor = $collection->find($appQuery);
?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Edit roster</title>
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <?php include '../common.php';?>
     <?php while ($cursor->hasNext()):
    $roster = $cursor->getNext(); ?>
    <h2><?= $roster['name'] ?></h2>
    <strong>Date:</strong> <?= $roster['weekDay']?> <br />
     <strong>Staff Member:</strong> <?= $roster['staffMember']?> <br />
    <strong>Location:</strong> <?= $roster['location']?> <br />
    <strong>Time slot:</strong> <?= $roster['timeslot']?> <br />
<?php endwhile;?>
 <?php if ($_SESSION['type'] == "Administrator"): ?>
    <h1>Roster Edit</h1>
    <?php if ($trigger === 'show_form'): ?>
   
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <h3>Status</h3>
        <p><input type="text" name="staffMember" value="<?= $roster['staffMember']?>" id="staffMember" /></p>
        <p><input type="text" name="timeslot" value="<?= $roster['timeslot']?>" id="timeslot" /></p>

        <p><input type="text" name="location" value="<?= $roster['location']?>" id="location" /></p>

        <p><input type="text" name="weekDay" value="<?= $roster['weekDay']?>" id="weekDay" /></p>

         
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
    </body>
</html>