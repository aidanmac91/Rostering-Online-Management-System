<!--
  File Name: viewRoster.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for viewing all rosters
-->
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

$cursor = $collection->find();//get all documents

?>

<?php

session_start();//start session

$_SESSION['rosterID'] = $rosterID;//set session variable

?>


<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Roster Viewer</title>

    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    </head>
    <body>
        <?php include '../common.php';?><!-- include common.php-->
        <h1>Roster</h1>

        <?php
        $rosterArray=array();
        while ($cursor->hasNext())://loop through cursor and add to rosterArray the roster's name
            $roster = $cursor->getNext(); 
        array_push($rosterArray, $roster['name']);
        ?>
    <?php endwhile;?>


    <form method="get" action="../edit/edit_roster.php"><!--Form -->
      <label for="rosterID">Client Name</label>
      <select id="rosterID" name="rosterID" onchange="show()"><!--Dropdown list of rosters-->
        <?php
        foreach ($rosterArray as $value) {
            echo'<option value="'.$value.'">'.$value.'</option>'; 
        }
        ?>
    </select>
    <input type="submit">
</form>
</body>
</html>