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

?>

<?php

session_start();

$_SESSION['clientID'] = $clientID;

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
    <?php include 'common.php';?>
<h1>Clients</h1>
 
<?php
$clientArray=array();
 while ($cursor->hasNext()):
    

    $client = $cursor->getNext(); 
    array_push($clientArray, $client['clientName']);
?>
<?php endwhile;?>


<form method="get" action="edit_client.php">
      <label for="appointmentID">Client Name</label>
         <select id="clientID" name="clientID" onchange="show()">
            <?php

            foreach ($clientArray as $value) {
            echo'<option value="'.$value.'">'.$value.'</option>'; 
            }
            ?>
        </select>
    <input type="submit">
  </form>




</body>
</html>