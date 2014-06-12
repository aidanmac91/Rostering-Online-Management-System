<!--
  File Name: viewMedical.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage that shows a client's medical information
-->

<?php
$trigger = "";
session_start();//start session
$messageID = $_SESSION['authorised'];//sets messageID to the session variable authorised

if(($messageID)!="YES")//if the authorised variable is not YES
{
header("Location: http://aidanmac91.eu01.aws.af.cm/home.php");//redirect to home page
}



if((!empty($_POST['submit'])) && ($_POST['submit'] === 'Save')) 
{
    $trigger = "do_save";//save 
}
else
{
    $trigger = "show_form";//show form
}

switch($trigger) 
{
    case 'do_save'://if save
    //save to database
    try
    {
        $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//conect to mongolab
        $database   = $connection->selectDB('staff');//select database
        $collection = $database->selectCollection('clients');//select collection

        $client               = array();//client array
        $client['clientName'] =$_POST['clientName'];//set clientName to value in $_POST['clientName']
        $client['otherInfo']=$_POST['otherInfo'];//set otherInfo to value in $_POST['otherInfo']

        $newdata = array('$set' => array('otherInfo'=>$_POST['otherInfo']));//creates array

        $collection->update(array('clientName'=>$_POST['clientName']), $newdata); //updates document
    } 
    catch(MongoConnectionException $e) //no connection
    {

        die("Failed to connect to database ".$e->getMessage());
    }

    catch(MongoException $e) //exception
    {

        $die('Failed to insert data '.$e->getMessage());
    }
    break;

    case 'show_form':
    default:
}
?>
<?php
//gets client information from database
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//conect to mongolab
        $database   = $connection->selectDB('staff');//select database
        $collection = $database->selectCollection('clients');//select collection

    }
    catch(MongoConnectionException $e)
    {
        die("Failed to connect to database ".$e->getMessage());
    }

//$cursor = $collection->find();
$appQuery = array('clientName' => $_SESSION['clientNo'] );//prepares query to match against document

$cursor = $collection->find($appQuery);//finds any instance that matches the query.
?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Edit client</title>
    <link type="text/css" rel="stylesheet" href="" />

</head>
<body>
    <?php include 'common.php';?><!--include common.php-->
        <?php while ($cursor->hasNext()): //while there are objects in the cursor object
        $client = $cursor->getNext(); // gets object to which this cursor points, and advance the cursor
        ?>
        <h2><?= $client['clientName'] ?></h2><!-- display name -->
        <strong>Information:</strong> <?= $client['otherInfo']?> <br /><!-- display information -->
    <?php endwhile;?>
<?php if ($_SESSION['type'] == "Administrator"): ?> <!-- if administrator-->
    <h1>Client Editor</h1>
    <?php if ($trigger === 'show_form'): ?>
   <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><!-- form to edit client -->
    <input type="hidden" name="clientName" value="<?= $client['clientName']?>" id="clientName"/>
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