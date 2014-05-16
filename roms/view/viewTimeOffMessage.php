<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
    $database   = $connection->selectDB('staff');
    $collection = $database->selectCollection('timeoffMessages');
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();

?>

<?php

session_start();

$_SESSION['messageID'] = $messageID;

?>


<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Message Viewer</title>
    
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        
    </head>
    <body>
        <?php include '../common.php';?>
        <h1>Time off Message</h1>
        
        <?php
        $messageArray=array();
        while ($cursor->hasNext()):
            

            $message = $cursor->getNext(); 
        array_push($messageArray, $message['name']);
        ?>
    <?php endwhile;?>


    <form method="get" action="../edit/edit_timeOffMessage.php">
      <label for="appointmentID">Time off message</label>
      <select id="messageID" name="messageID" onchange="show()">
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