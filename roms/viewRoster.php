<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
    $database   = $connection->selectDB('staff');
    $collection = $database->selectCollection('rosters');
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}
 
$cursor = $collection->find();

?>

<?php

session_start();

$_SESSION['rosterID'] = $rosterID;

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
    <?php include 'common.php';?>
<h1>Roster</h1>
 
<?php
$rosterArray=array();
 while ($cursor->hasNext()):
    

    $roster = $cursor->getNext(); 
    array_push($rosterArray, $roster['name']);
?>
<?php endwhile;?>


<form method="get" action="edit_roster.php">
      <label for="rosterID">Client Name</label>
         <select id="rosterID" name="rosterID" onchange="show()">
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