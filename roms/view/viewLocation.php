<!--
  File Name: viewLocation.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for viewing an location
-->
<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
    $database   = $connection->selectDB('staff');//database
    $collection = $database->selectCollection('accommodations');//collection
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();//saves all documents into the cursor object

?>

<?php

session_start();//start session

$_SESSION['locationName'] = $locationName;//set session variable

?>


<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Accommodation Viewer</title>

    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    </head>
    <body>
        <?php include '../common.php';?><!-- Include common.php-->
        <h1>Accommodations</h1>

        <?php
        $locArray=array();
        while ($cursor->hasNext()):
               //loop through cursor and populate array with accommodation's name
            $location = $cursor->getNext(); 
        array_push($locArray, $location['accomodationName']);
        ?>
    <?php endwhile;?>

    <form method="get" action="../edit/edit_location.php"><!-- Form-->
      <label for="locationName">Accommodation</label>
      <select id="locationName" name="locationName" onchange="show()"><!-- Dropdown list of accommodations-->
        <?php
        foreach ($locArray as $value) {
            echo'<option value="'.$value.'">'.$value.'</option>'; 
        }
        ?>
    </select>
    <input type="submit">
</form>




</body>
</html>