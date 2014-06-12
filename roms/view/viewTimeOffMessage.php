<!--
  File Name: viewTimeOffMessage.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for viewing time off messages
-->
<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
    $database   = $connection->selectDB('staff');//select database
    $collection = $database->selectCollection('timeoffMessages');//select collection
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();//get all documents

?>

<?php

session_start();//start session
$_SESSION['messageID'] = $messageID;//set session variable 

?>


<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Message Viewer</title>
    
    <link type="text/css" rel="stylesheet" href="" />
        
    </head>
    <body>
        <?php include '../common.php';?><!-- include common.php-->
        <h1>Time off Message</h1>
        
        <?php
        $messageArray=array();
        //loop through cursor and populate messageArray with message names
        while ($cursor->hasNext()):
        $message = $cursor->getNext(); 
        array_push($messageArray, $message['name']);
        ?>
    <?php endwhile;?>
    <form method="get" action="../edit/edit_timeOffMessage.php"><!--Form-->
      <label for="appointmentID">Time off message</label>
      <select id="messageID" name="messageID" onchange="show()"><!-- dropdown list of message names-->
        <?php
        foreach ($messageArray as $value) {
            echo'<option value="'.$value.'">'.$value.'</option>'; 
        }
        ?>
    </select>
    <input type="submit">
</form>
</body>
</html>