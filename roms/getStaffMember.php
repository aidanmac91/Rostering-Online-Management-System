<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
    $database   = $connection->selectDB('staff');
    $collection = $database->selectCollection('users');
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}
 
$cursor = $collection->find();

?>

<?php

session_start();

$_SESSION['staffName'] = $regValue;

?>


<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Staff Viewer</title>
 
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
 
</head>
<body>
    <?php include 'common.php';?>
<h1>Staff members</h1>
 
<?php
$staffArray=array();
 while ($cursor->hasNext()):
    

    $staff = $cursor->getNext(); 
    array_push($staffArray, $staff['name']);
?>

<form method="get" action="edit_staffMember.php">
      <label for="regValue">Client Name123<br /></label>
         <select id="regValue" name="regValue" onchange="show()">
            <?php

            foreach ($staffArray as $value) {
            echo'<option value="'.$value.'">'.$value.'</option>'; 
            }
            ?>
        </select>
    <input type="submit">
  </form>




</body>
</html>