<?php

session_start();

$clientID = $_GET['clientID'];

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
            $collection = $database->selectCollection('clients');
 
            $client               = array();
            //$task['title']      = $_POST['title'];
            //$task['status']     = $_POST['status'];
            //$task['context']    = $_POST['context'];
            $client['clientName'] =$_POST['clientName'];
            $client['nextOfKinName']=$_POST['nextOfKinName'];
            $client['accommodation']=$_POST['accommodation'];
            $client['nextOfKinNumber']=$_POST['nextOfKinNumber'];
            $client['otherInfo']=$_POST['otherInfo'];
            $clients['saved_date']   = new MongoDate();
 
            //$collection->insert($task);      

              //$latLong = array($lat,$lon);

                $newdata = array('$set' => array('clientName' => $_POST['clientName'],'nextOfKinName'=>$_POST['nextOfKinName'],
                    'accommodation'=>$_POST['accommodation'],'nextOfKinNumber'=>$_POST['nextOfKinNumber'],'time'=>$_POST['time'],'otherInfo'=>$_POST['otherInfo']));

                 $collection->update(array('John Smith' => $clientName), $newdata); 
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
    $collection = $database->selectCollection('clients');
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}
 
$cursor = $collection->find();
$appQuery = array('clientName' => $clientID );//,'context'=>'Bob');// && array('context'=>'Innishannon');

$cursor = $collection->find($appQuery);
?>

<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
    $database   = $connection->selectDB('staff');
    $collection = $database->selectCollection('accommodations');
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}
 
$cursor1 = $collection->find();

?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Edit client</title>
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>
<body>
    <?php include 'common.php';?>
     <?php while ($cursor->hasNext()):
    $client = $cursor->getNext(); ?>
    <h2><?= $client['clientName'] ?></h2>
    <strong>location:</strong> <?= $client['accommodation']?> <br />
<?php endwhile;?>

<?php
$locArray=array();
 while ($cursor1->hasNext()):
    

    $location = $cursor1->getNext(); 
    array_push($locArray, $location['accomodationName']);
?>
<?php endwhile;?>
    <h1>Client Creator</h1>
    <?php if ($trigger === 'show_form'): ?>
   
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <h3>Status</h3>
        <p>Name:<input type="text" name="clientName" value="<?= $client['clientName']?>" id="clientName" readonly /></p>
        <!-- <p>Location<input type="text" name="accommodation" value="<?= $client['accommodation']?>" id="accommodation" /></p> -->

        <label for="locationName">Accommodation</label>
         <select id="accommodation" name="accommodation" onchange="show()" >
            <?php

            foreach ($locArray as $value) {
                 $select = $client['accommodation'] == $value ? ' selected' : '';
            echo'<option value="'.$value.'"' . $select . '>'.$value.'</option>';
            }
            ?>
        </select>

        <p>Next of Kin<input type="text" name="nextOfKinName" value="<?= $client ['nextOfKinName']?>" id="nextOfKinName" /></p>

        <p>Next of Kin Number<input type="text" name="nextOfKinNumber" value="<?= $client ['nextOfKinNumber']?>" id="nextOfKinNumber" /></p>

        
          <textarea name='otherInfo' value="<?= $client['otherInfo']?>" id="otherInfo" /><?= $client['otherInfo']?></textarea>

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