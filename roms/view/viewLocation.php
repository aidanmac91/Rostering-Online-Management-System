<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
    $database   = $connection->selectDB('staff');
    $collection = $database->selectCollection('accommodations');
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();

?>

<?php

session_start();

$_SESSION['locationName'] = $locationName;

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
        <?php include '../common.php';?>
        <h1>Accommodations</h1>

        <?php
        $locArray=array();
        while ($cursor->hasNext()):


            $location = $cursor->getNext(); 
        array_push($locArray, $location['accomodationName']);
        ?>
    <?php endwhile;?>

    <form method="get" action="../edit/edit_location.php">
      <label for="locationName">Accommodation</label>
      <select id="locationName" name="locationName" onchange="show()">
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