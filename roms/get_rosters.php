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
    $roster = $cursor->getNext(); ?>
    <h2><?= $task['title'] ?></h2>
    <strong>Week Day:</strong> <?= $roster['weekDay']?> <br />
    <strong>Location:</strong> <?= $roster['location']?><br />
    <strong>Timeslot:</strong> <?= $roster['timeslot']?><br />
    <strong>StaffMember:</strong> <?= $roster['staffMember']?><br />
    <strong>StaffMember:</strong> <?= $roster['_id']?><br />
<?php endwhile;?>
</body>
</html>