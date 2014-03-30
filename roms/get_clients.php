<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
    $database   = $connection->selectDB('staff');
    $collection = $database->selectCollection('clients');
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
    <title>Client Viewer</title>
 
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
 
</head>
<body>
<h1>Clients</h1>
 
<?php while ($cursor->hasNext()):
    $client = $cursor->getNext(); ?>
    <h2><?= $task['title'] ?></h2>
    <strong>Client Name:</strong> <?= $client['clientName']?> <br />
    <strong>Location:</strong> <?= $client['accommodation']?><br />
    <strong>Next Of Kin Name:</strong> <?= $client['nextOfKinName']?><br />
    <strong>Next Of Kin Number:</strong> <?= $client['nextOfKinNumber']?><br />
    <strong>otherInfo:</strong> <?= $client['otherInfo']?><br />
<?php endwhile;?>
</body>
</html>