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
<h1>Rosters</h1>
 
<?php while ($cursor->hasNext()):
    $appointment = $cursor->getNext(); 
    $t=$appointment['attended']?>
    <h2><?= $task['clientName'] ?></h2>
    <strong>Week Day:</strong> <?= $appointment['appointmentDate']?> <br />
    <strong>Location:</strong> <?= $appointment['location']?><br />
    <strong>attended:</strong> <?= $appointment['attended']?><br />
    <strong>time:</strong> <?= $appointment['time']?><br />
    <strong>Other Info:</strong> <?= $appointment['otherInfo']?><br />
    
     <input type="checkbox" name="newsletter[]" value="newsletter" checked=<?$t?>>i want to sign up   for newsletter<br>
<?php endwhile;?>

</body>
</html>