<!--
  File Name: edit_location.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for editing a location
-->
<?php
session_start();//start session
$locationName = $_GET['locationName'];//set location
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
            $collection = $database->selectCollection('accommodations');//collection

            $accommodation               = array();
            $accommodation['accomodationName'] =$_POST['accomodationName'];//set accomodationName to value in $_POST['accomodationName']
            $accommodation['accommodationAddress']=$_POST['accommodationAddress'];//set accommodationAddress to value in $_POST['accommodationAddress']
            $accommodation['phoneNumber']=$_POST['phoneNumber'];//set phoneNumber to value in $_POST['phoneNumber']
            $accommodation['numberOfClients']=$_POST['numberOfClients'];//set numberOfClients to value in $_POST['numberOfClients']

            $newdata = array('$set' => array('accomodationName' => $_POST['accomodationName'],'accommodationAddress'=>$_POST['accommodationAddress'],
                'phoneNumber'=>$_POST['phoneNumber'],'attended'=>true,'numberOfClients'=>$_POST['numberOfClients']));

             //create array of data
            //update document with accomodationName =$accomodationName with the array
            $collection->update(array('accomodationName' => $_POST['originalName']), $newdata); 
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
        $collection = $database->selectCollection('accommodations');
    }
    catch(MongoConnectionException $e)
    {
        die("Failed to connect to database ".$e->getMessage());
    }

$appQuery = array('accomodationName' => $locationName);//query to compare

$cursor = $collection->find($appQuery);//documents matching the query
?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Edit accomodationName</title>
    <link type="text/css" rel="stylesheet" href="" />
</head>
<body>
    <?php include '../common.php';?><!--include common.php-->
     <?php while ($cursor->hasNext())://loop through the cursor object and display information to user
     $accommodation = $cursor->getNext(); ?>
     <h2><?= $accommodation['accomodationName'] ?></h2>
     <strong>Accommodation Address:</strong> <?= $accommodation['accommodationAddress']?> <br />
     <strong>Accommodation Phone Number:</strong> <?= $accommodation['phoneNumber']?> <br />
 <?php endwhile;?>
 <?php if ($_SESSION['type'] == "Administrator"): ?><!--if user is admin-->
 <h1>Accommodation Editor</h1>
 <?php if ($trigger === 'show_form'): ?>

 <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><!--FORM-->
    <h3>Edit</h3>
    <input type="hidden" name="originalName" value="<?= $accommodation['accomodationName']?>" id="originalName" /></p><!--input field of accommodation name-->
    <label for="accomodationName">Accomodation Name<br /></label>
    <p><input type="text" name="accomodationName" value="<?= $accommodation['accomodationName']?>" id="accomodationName" /></p><!--input field of accommodation name-->
    <label for="accommodationAddress">accommodationAddress<br /></label>
    <p><input type="text" name="accommodationAddress" value="<?= $accommodation['accommodationAddress']?>" id="accommodationAddress" /></p><!--input field of accommodation address-->
    <label for="phoneNumber">phoneNumber<br /></label>
    <p><input type="text" name="phoneNumber" value="<?= $accommodation['phoneNumber']?>" id="phoneNumber" /></p><!--input field of phoneNumber-->
    <label for="numberOfClients">Number Of Clients<br /></label>
    <select id="myList" name="numberOfClients" onchange="show()"><!--dropdown list of number of clients-->
        <!--if the value is equal to the value in db it is set to the index-->
        <option value="1"<?php if ($accommodation['numberOfClients'] == '1') echo ' selected="selected"'; ?>>1</option>
        <option value="2"<?php if ($accommodation['numberOfClients'] == '2') echo ' selected="selected"'; ?>>2</option>
        <option value="3"<?php if ($accommodation['numberOfClients'] == '3') echo ' selected="selected"'; ?>>3</option>
        <option value="4"<?php if ($accommodation['numberOfClients'] == '4') echo ' selected="selected"'; ?>>4</option>
        <option value="5"<?php if ($accommodation['numberOfClients'] == '5') echo ' selected="selected"'; ?>>5</option>
    </select>

    <p><input type="submit" name="submit" value="Save"/></p>
</form>
<?php else: ?>
    <p>
        Accommodation edit.
        <a href="../view/viewLocation.php">Edit another accommodation?</a>
        <a href="../home.php">Add another task?</a>
    </p>
<?php endif;?>
<?php endif; ?>

<?php include '../footer.php';?>
</body>
</html>