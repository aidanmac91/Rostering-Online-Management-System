<!--
  File Name: edit_client.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for editing a client
-->
<?php

session_start();

$clientID = $_GET['clientID'];

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
        $collection = $database->selectCollection('clients');//collection

        $client               = array();
        $client['clientName'] =$_POST['clientName'];//set clientName to value in $_POST['clientName']
        $client['nextOfKinName']=$_POST['nextOfKinName'];//set nextOfKinName to value in $_POST['nextOfKinName']
        $client['accommodation']=$_POST['accommodation'];//set accommodation to value in $_POST['accommodation']
        $client['nextOfKinNumber']=$_POST['nextOfKinNumber'];//set nextOfKinNumber to value in $_POST['nextOfKinNumber']

        $newdata = array('$set' => array('clientName' => $_POST['clientName'],'nextOfKinName'=>$_POST['nextOfKinName'],
            'accommodation'=>$_POST['accommodation'],'nextOfKinNumber'=>$_POST['nextOfKinNumber'],'time'=>$_POST['time']));
            //create array of data
            //update document with name =$clientID with the array
        $collection->update(array('name'=>$clientID), $newdata); 
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
    $collection = $database->selectCollection('clients');//collection
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$appQuery = array('clientName' => $clientID );//query to compare

$cursor = $collection->find($appQuery);//documents matching the query
?>

<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
    $database   = $connection->selectDB('staff');//database
    $collection = $database->selectCollection('accommodations');//collection
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$cursor1 = $collection->find();//get all documents.

?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Edit client</title>
    <link type="text/css" rel="stylesheet" href="" />
</head>
<body>
    <?php include '../common.php';?><!--include common.php-->
    <?php while ($cursor->hasNext()):
        //loop through cursor and display information back to user
    $client = $cursor->getNext(); ?>
    <h2><?= $client['clientName'] ?></h2>
    <strong>location:</strong> <?= $client['accommodation']?> <br />
    <strong>Next Of Kin Name:</strong> <?= $client['nextOfKinName']?> <br />
    <strong>Next Of Kin Number:</strong> <?= $client['nextOfKinNumber']?> <br />
<?php endwhile;?>

<?php
$locArray=array();
while ($cursor1->hasNext()):
        //loop through cursor1 and populate the array locArray with location's names
    $location = $cursor1->getNext(); 
array_push($locArray, $location['accomodationName']);
?>
<?php endwhile;?>
<?php if ($_SESSION['type'] == "Administrator"): ?><!--if user is admin-->
    <h1>Client Creator</h1>
    <?php if ($trigger === 'show_form'): ?>

    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><!--FORM-->
        <p>Name:<input type="text" name="clientName" value="<?= $client['clientName']?>" id="clientName" readonly /></p><!--input field of client name-->
        <label for="locationName">Accommodation</label>
        <select id="accommodation" name="accommodation" onchange="show()" ><!--dropdown list of accommodations-->
            <?php

            foreach ($locArray as $value) {
             $select = $client['accommodation'] == $value ? ' selected' : '';//if the value is equal to the value in db it is set to the index
             echo'<option value="'.$value.'"' . $select . '>'.$value.'</option>';
         }
         ?>
     </select>

     <p>Next of Kin<input type="text" name="nextOfKinName" value="<?= $client ['nextOfKinName']?>" id="nextOfKinName" /></p><!--input field of next of kin name-->

     <p>Next of Kin Number<input type="text" name="nextOfKinNumber" value="<?= $client ['nextOfKinNumber']?>" id="nextOfKinNumber" /></p><!--input field of next of kin number-->

     <p><input type="submit" name="submit" value="Save"/></p>
 </form>
<?php else: ?>
    <p>
        Client edited.
        <a href="../view/viewClient.php">Edit another client?</a>
        <a href="../home.php">Main Menu</a>
    </p>
<?php endif;?>
<?php endif; ?>
<a href="http://aidanmac91.eu01.aws.af.cm/clientInfo.php" action=<?php $_SESSION['clientNo'] = $clientID;?>>View Client Infomation</a><!--link to viewing client other information-->

<?php include '../footer.php';?>
</body>
</html>