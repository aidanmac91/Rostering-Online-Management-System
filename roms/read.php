<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
    $database   = $connection->selectDB('staff');
    $collection = $database->selectCollection('testing');
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
    <title>Tasks Viewer</title>
 
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
 
</head>
<body>
<h1>My Tasks</h1>
 
<?php while ($cursor->hasNext()):
    $task = $cursor->getNext(); ?>
    <h2><?= $task['title'] ?></h2>
    <strong>Status:</strong> <?= $task['status']?> <br />
    <strong>Context:</strong> <?= $task['context']?><br />
<?php endwhile;?>
</body>
</html>