<?php

session_start();

$staffName = $_GET['staffName'];

?>

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
            $collection = $database->selectCollection('users');
 
            $user               = array();
            $user['username'] =$_POST['username'];
            $user['name']=$_POST['name'];
            $user['email']=$_POST['email'];
            $user['staffType']=$_POST['staffType'];
            $user['hoursPerWeek']=$_POST['hoursPerWeek'];
            $user['saved_date']   = new MongoDate();

            $newdata = array('$set' => array('username' => $_POST['username'],'name'=>$_POST['name'],
                    'email'=>$_POST['email'],'staffType'=>$_POST['staffType'],'hoursPerWeek'=>$_POST['hoursPerWeek']));
                 $collection->update(array('username' => $_POST['username']), $newdata); 
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
$appQuery = array('username' => $staffName);//,'context'=>'Bob');// && array('context'=>'Innishannon');

$cursor = $collection->find($appQuery);
?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Edit staff member</title>
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <?php include '../common.php';?>
     <?php while ($cursor->hasNext()):
    $user = $cursor->getNext(); ?>
    <h2><?= $user['name'] ?></h2>
    <strong>Username:</strong> <?= $user['username']?> <br />
    <strong>Name:</strong> <?= $user['name']?> <br />
    <strong>Email:</strong> <?= $user['email']?> <br />
    <strong>Staff Type:</strong> <?= $user['staffType']?> <br />
    <strong>Hours Per Week:</strong> <?= $user['hoursPerWeek']?> <br />

<?php endwhile;?>
 <?php if ($_SESSION['type'] == "Administrator"): ?>
    <h1>Edit Staff Member</h1>
    <?php if ($trigger === 'show_form'): ?>
   
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <h3>Status</h3>
        <p><input type="text" name="username" value="<?= $user['username']?>" id="username" /></p>
        <p><input type="text" name="name" value="<?= $user['name']?>" id="name" /></p>

        <p><input type="text" name="email" value="<?= $user['email']?>" id="email" /></p>

        <p><input type="text" name="staffType" value="<?= $user['staffType']?>" id="staffType" /></p>
         <p><input type="text" name="hoursPerWeek" value="<?= $user['hoursPerWeek']?>" id="hoursPerWeek" /></p>

          
        <p><input type="submit" name="submit" value="Save"/></p>
    </form>
    <?php else: ?>
    <p>
        Staff member Edit. 
        <a href="../view/viewStaffMember.php">Edit another task?</a>
        <a href="../home.php">Main Menu</a>
    </p>
<?php endif;?>
<?php endif; ?>
    </body>
</html>