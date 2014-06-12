<!--
  File Name: viewRosterByLocation.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for viewing rosters for a certain accommodation
-->
<?php

session_start();//start session

$rosterLocationID = $_GET['rosterLocationID'];//get session variable
?>

<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
    $database   = $connection->selectDB('staff');//database
    $collection = $database->selectCollection('rosters');//collection
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}
 
$appQuery = array('location' => $rosterLocationID);//query to be compared

$cursor = $collection->find($appQuery);//result of the query
?>

<!DOCTYPE html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title> <?$rosterStaffID?>'s Rosters </title>
    <link type="text/css" rel="stylesheet" href="" />
</head>
<body>
    <?php include '../common.php';?><!-- include common.php-->
     <?php while ($cursor->hasNext())://loop through cursor and display information back to the user
    $roster = $cursor->getNext(); ?>
    <h2><?= $roster['name'] ?></h2>
    <strong>Date:</strong> <?= $roster['weekDay']?> <br />
     <strong>Staff Member:</strong> <?= $roster['staffMember']?> <br />
    <strong>Location:</strong> <?= $roster['location']?> <br />
    <strong>Time slot:</strong> <?= $roster['timeslot']?> <br />
<?php endwhile;?>
    </body>
</html>