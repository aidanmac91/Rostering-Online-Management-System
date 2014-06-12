<!--
  File Name: viewSwapMessage.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for viewing swap messages
-->
<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
    $database   = $connection->selectDB('staff');//select database
    $collection = $database->selectCollection('swapMessages');//select collection
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();//get all documents

?>

<?php
session_start();//session start
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
    <h1>Swap Message</h1>
    
    <?php
    $messageArray=array();
        while ($cursor->hasNext())://loop through cursor and populate messageArray with message names
        $message = $cursor->getNext(); 
        array_push($messageArray, $message['name']);
        ?>
    <?php endwhile;?>


    <form method="get" action="../edit/edit_swapMessage.php"><!-- form -->
      <label for="appointmentID">Swap message</label>
      <select id="messageID" name="messageID" onchange="show()"><!-- dropdown list of swap message names-->
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