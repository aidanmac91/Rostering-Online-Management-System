<!--
  File Name: viewAppointment.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for viewing an appointment
-->
<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
    $database   = $connection->selectDB('staff');//which database
    $collection = $database->selectCollection('appointments');//which collection
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();//saves all documents into the cursor object

?>

<?php

session_start();//start session

$_SESSION['appointmentID'] = $appointmentID;//set session variable

?>


<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Appointment Viewer</title>

    <link type="text/css" rel="stylesheet" href="" />

</head>
<body>
    <?php include '../common.php';?><!-- include common.php-->
    <h1>Appointment</h1>

    <?php
    //loop through cursor and populate array with appointment name's
    $appointArray=array();
    while ($cursor->hasNext()):
        $appoint = $cursor->getNext(); 
    array_push($appointArray, $appoint['name']);
    ?>
<?php endwhile;?>


<form method="get" action="../edit/edit_appointment.php"><!-- Form-->
  <label for="appointmentID">Accommodation</label>
  <select id="appointmentID" name="appointmentID" onchange="show()"><!-- Dropdown list of appointments-->
    <?php
    foreach ($appointArray as $value) {
        echo'<option value="'.$value.'">'.$value.'</option>'; //value in array
    }
    ?>
</select>
<input type="submit">
</form>
</body>
</html>