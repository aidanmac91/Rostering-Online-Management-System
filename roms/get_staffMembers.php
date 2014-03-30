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
<h1>Staff members</h1>
 
<?php while ($cursor->hasNext()):
    $staff = $cursor->getNext(); ?>
    <h2><?= $staff['name'] ?></h2>
    <strong>Username:</strong> <?= $staff['username']?> <br />
    <strong>Email:</strong> <?= $staff['email']?><br />
    <strong>Staff Type:</strong> <?= $staff['staffType']?><br />
    <strong>Hours Per Week:</strong> <?= $staff['hoursPerWeek']?><br />
<?php endwhile;?>
</body>
</html>