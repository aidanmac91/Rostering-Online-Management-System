<!--
  File Name: viewClient.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for viewing an appointment
-->
<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
    $database   = $connection->selectDB('staff');//selet database
    $collection = $database->selectCollection('clients');//select collection
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();//saves all documents into the cursor object

?>

<?php

session_start();//start session

$_SESSION['clientID'] = $clientID;//set session variable

?>


<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Client Viewer</title>
    
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        
    </head>
    <body>
        <?php include '../common.php';?><!-- Include common.php-->
        <h1>Clients</h1>
        
        <?php
        //loop through cursor and populate array with client's name
        $clientArray=array();
        while ($cursor->hasNext()):
            $client = $cursor->getNext(); 
        array_push($clientArray, $client['clientName']);
        ?>
    <?php endwhile;?>


    <form method="get" action="../edit/edit_client.php"><!-- Form-->
      <label for="appointmentID">Client Name</label>
      <select id="clientID" name="clientID" onchange="show()"><!-- Dropdown list of clients-->
        <?php

        foreach ($clientArray as $value) {
            echo'<option value="'.$value.'">'.$value.'</option>'; //value in array
        }
        ?>
    </select>
    <input type="submit">
</form>




</body>
</html>