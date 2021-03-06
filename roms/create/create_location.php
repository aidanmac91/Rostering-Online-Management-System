<!--
  File Name: create_location.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for creating an accommodation
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
    
    try
    //saves accommodation
    {
        $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
        $database   = $connection->selectDB('staff');//which datbase
        $collection = $database->selectCollection('accommodations');//which collection
    
        $accommodation              = array();//accommodation array
        $accommodation['accomodationName'] =$_POST['accomodationName'];//set accomodationName to value in $_POST['accomodationName']
        $accommodation['accommodationAddress']=$_POST['accommodationAddress'];//set accommodationAddress to value in $_POST['accommodationAddress']
        $accommodation['phoneNumber']=$_POST['phoneNumber'];//set phoneNumber to value in $_POST['phoneNumber']
        $accommodation['numberOfClients']=$_POST['numberOfClients'];//set numberOfClients to value in $_POST['numberOfClients']
        $collection->insert($accommodation);     //inserts accommodation array  
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

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Add a accommodation</title>
    <link type="text/css" rel="stylesheet" href="" />
    </head>
    <body>
        <?php include '../common.php';?><!--include common.php -->
        <h1>accommodation Creater</h1>
        <?php if ($trigger === 'show_form'): ?>
        <!-- Form for creating a location -->
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
           <!-- input for accomodation name -->
           <p>
            <label for="accomodationName">Accommodation Name <br /></label>
            <input id="accommodationName" name="accomodationName"/>
        </p>
        <!-- input for accomodation address -->
        <p>
            <label for="accommodationAddress">Address<br /> </label>
            <br>
            <textArea id="accommodationAddress" name="accommodationAddress"></textArea>
        </p>
        <!-- input for accomodation phone number -->
        <p>
            <label for="phoneNumber">Phone Number <br /></label>
            <input id="phoneNumber" name="phoneNumber"/>
        </p>
        <!-- 
            input for accomodation address 
            dropdown list with hard coded numbers
        -->
        <p>
          <label for="numberOfClients">Number Of Clients<br /></label>
          <select id="myList" name="numberOfClients" onchange="show()">
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
        </select>
    </p>
    <p><input type="submit" name="submit" value="Save"/></p><!--save button that calls the function save-->
</form>
<?php else: ?>
    <p>
        accommodation saved.
        <a href="<?php echo $_SERVER['PHP_SELF'];?>">Add another accommodation?</a>
        <a href="../home.php">Main Menu</a>
    </p>
<?php endif;?>

 <?php include '../footer.php';?>
</body>
</html>