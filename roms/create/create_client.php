<!--
  File Name: create_client.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for creating a client
-->
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
    //Save client
    try
    {
        $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
        $database   = $connection->selectDB('staff');//which database
        $collection = $database->selectCollection('clients');//which collection
        
        $client               = array();//client array
        $client['clientName'] =$_POST['clientName'];//set clientName to value in $_POST['clientName']
        $client['nextOfKinName']=$_POST['nextOfKinName'];//set nextOfKinName to value in $_POST['nextOfKinName']
        $client['accommodation']=$_POST['accommodation'];//set accommodation to value in $_POST['accommodation']
        $client['nextOfKinNumber']=$_POST['nextOfKinNumber'];//set nextOfKinNumber to value in $_POST['nextOfKinNumber']
        $client['otherInfo']=$_POST['otherInfo'];//set otherInfo to value in $_POST['otherInfo']
        
        $collection->insert($client); //inserts the client array      
    } 
    catch(MongoConnectionException $e) //connection exception
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
try
//retrieves information from database
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
    $database   = $connection->selectDB('staff');//which database
    $collection = $database->selectCollection('accommodations');//which collection
}
catch(MongoConnectionException $e)//exception
{
    die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();//saves all documents into the cursor object

?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Add a client</title>
    <link type="text/css" rel="stylesheet" href="" />
</head>
<body>
    <?php include '../common.php';?><!--include common.php -->
    <?php
         //loops through cursor and save the accomodationName of the object into the accommodationArray
    $accommodationArray=array();
    while ($cursor->hasNext()):
     $accomodation = $cursor->getNext(); 
    array_push($accommodationArray, $accomodation['accomodationName']);
 ?>
<?php endwhile;?>
<h1>Client Creater</h1>
<?php if ($trigger === 'show_form'): ?>
 <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><!--form for creating a client-->
     <label for="clientName">Client Name<br /></label>
     <input id="clientName" name="clientName"/><!-- input field for client name -->
     <p>
        <label for="accommodation">Accommodation<br /></label>
        <select id="myList" name="accommodation" onchange="show()"><!-- Dropdown list of client name-->
         <?php
         foreach ($accommodationArray as $value) 
         {
            echo'<option value="'.$value.'">'.$value.'</option>';  //loops through array and creates an option for each object
        }
        ?>
    </select>
</p>
<p>
    <label for="nextOfKinName">Next Of Kin Name <br /></label>
    <input id="nextOfKinName" name="nextOfKinName"/><!-- input field for next of kin name -->
</p>
<p>
    <label for="nextOfKinNumber">Next Of Kin Number <br /></label>
    <input id="nextOfKinNumber" name="nextOfKinNumber"/><!-- input field for next of kin number -->
</p>
</p>
<label for="otherInfo">Other Information <br /></label>
<br>
<textarea id="otherInfo" name="otherInfo"/>
</textarea><!-- textarea field for other information -->
<p><input type="submit" name="submit" value="Save"/></p>
</form>
<?php else: ?>
    <p>
        Client saved.
        <a href="<?php echo $_SERVER['PHP_SELF'];?>">Add another client?</a>
        <a href="../home.php">Main Menu</a>
    </p>
<?php endif;?>

<?php include '../footer.php';?>
</body>
</html>