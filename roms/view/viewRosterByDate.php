<?php

session_start();

$rosterLocationID = $_GET['rosterLocationID'];


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
$appQuery = array('weekDay' => $rosterLocationID);

$cursor = $collection->find($appQuery);
?>

<!DOCTYPE html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title> <?$rosterStaffID?>'s Rosters </title>
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
    </body>
</html>