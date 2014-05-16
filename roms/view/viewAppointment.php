<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
    $database   = $connection->selectDB('staff');
    $collection = $database->selectCollection('appointments');
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();

?>

<?php

session_start();

$_SESSION['appointmentID'] = $appointmentID;

?>


<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Appointment Viewer</title>

    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    </head>
    <body>
        <?php include '../common.php';?>
        <h1>Appointment</h1>

        <?php
        $appointArray=array();
        while ($cursor->hasNext()):


            $appoint = $cursor->getNext(); 
        array_push($appointArray, $appoint['name']);
        ?>
    <?php endwhile;?>


    <form method="get" action="../edit/edit_appointment.php">
      <label for="appointmentID">Accommodation</label>
      <select id="appointmentID" name="appointmentID" onchange="show()">
        <?php

        foreach ($appointArray as $value) {
            echo'<option value="'.$value.'">'.$value.'</option>'; 
        }
        ?>
    </select>
    <input type="submit">
</form>




</body>
</html>