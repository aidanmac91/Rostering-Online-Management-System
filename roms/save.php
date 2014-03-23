<?php
$trigger = "";
 
if((!empty($_POST['submit'])) && ($_POST['submit'] === 'Save')) 
{
    $trigger = "do_save";
}
else
{
    $trigger = "show_form";
}
 
switch($trigger) 
{
    case 'do_save':
 
        try
        {
            $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
            $database   = $connection->selectDB('staff');
            $collection = $database->selectCollection('testing');
 
            $task               = array();
            $task['title']      = $_POST['title'];
            $task['status']     = $_POST['status'];
            $task['context']    = $_POST['context'];
            $task['saved_date']   = new MongoDate();
 
            $collection->insert($task);       
        } 
        catch(MongoConnectionException $e) 
        {
 
            die("Failed to connect to database ".$e->getMessage());
        }
 
        catch(MongoException $e) 
        {
 
            $die('Failed to insert data '.$e->getMessage());
        }
        break;
 
    case 'show_form':
    default:
}
?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Add a task</title>
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <h1>Task Creator</h1>
    <?php if ($trigger === 'show_form'): ?>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <h3>Title</h3>
        <p><input type="text" name="title" id="title" /></p>
        <h3>Status</h3>
        <p><input type="text" name="status" id="status" /></p>
        <h3>Context</h3>
        <p><input type="text" name="context" id="context" /></p>
        <p><input type="submit" name="submit" value="Save"/></p>
    </form>
    <?php else: ?>
    <p>
        Task saved. _id: <?php echo $task['_id'];?>.
        <a href="<?php echo $_SERVER['PHP_SELF'];?>">Add another task?</a>
    </p>
<?php endif;?>
    </body>
</html>