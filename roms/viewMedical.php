<?php

session_start();

?>

<?php
$trigger = "";

$messageID = $_SESSION['authorised'];
$pass=" YES";
// if ($messageID!=$pass) {
//   header("Location: http://aidanmac91.eu01.aws.af.cm/index.php");
// }
if(($messageID)!="YES")
{
header("Location: http://aidanmac91.eu01.aws.af.cm/home.php");
}



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
        $collection = $database->selectCollection('clients');

        $client               = array();
        $client['clientName'] =$_POST['clientName'];
        $client['otherInfo']=$_POST['otherInfo'];
        $clients['saved_date']   = new MongoDate();

            //$collection->insert($task);      

              //$latLong = array($lat,$lon);

        $newdata = array('$set' => array('clientName' => $_SESSION['clientNo'],'otherInfo'=>$_POST['otherInfo']));

        $collection->update(array('name'=>$_SESSION['clientNo']), $newdata); 
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
    $collection = $database->selectCollection('clients');
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();
$appQuery = array('clientName' => $_SESSION['clientNo'] );//,'context'=>'Bob');// && array('context'=>'Innishannon');

$cursor = $collection->find($appQuery);
?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Edit client</title>
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    </head>
    <body>
        <?php include 'common.php';?>
        <?php while ($cursor->hasNext()):
        $client = $cursor->getNext(); ?>
        <h2><?= $client['clientName'] ?></h2>
        <strong>Information:</strong> <?= $client['otherInfo']?> <br />
    <?php endwhile;?>

    <?php
    $locArray=array();
    while ($cursor1->hasNext()):


        $location = $cursor1->getNext(); 
    array_push($locArray, $location['accomodationName']);
    ?>
<?php endwhile;?>
 <?php if ($_SESSION['type'] == "Administrator"): ?>
<h1>Client Creator</h1>
<?php if ($trigger === 'show_form'): ?>

    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">

       <textarea name='otherInfo' value="<?= $client['otherInfo']?>" id="otherInfo" /><?= $client['otherInfo']?></textarea>

       <p><input type="submit" name="submit" value="Save"/></p>
   </form>
<?php else: ?>
    <p>
        Client edited.
        <a href="/view/viewClient.php">Edit another client?</a>
        <a href="/home.php">Main Menu</a>
    </p>
<?php endif;?>
<?php endif; ?>
</body>
</html>