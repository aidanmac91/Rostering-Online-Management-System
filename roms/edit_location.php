<?php

session_start();

$locationName = $_GET['locationName'];


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
            $collection = $database->selectCollection('accommodations');
 
            $accommodation               = array();
            //$task['title']      = $_POST['title'];
            //$task['status']     = $_POST['status'];
            //$task['context']    = $_POST['context'];
            $accommodation['accomodationName'] =$_POST['accomodationName'];
            $accommodation['accommodationAddress']=$_POST['accommodationAddress'];
            $accommodation['phoneNumber']=$_POST['phoneNumber'];
            // $accommodation['attended']=false;
            $accommodation['numberOfClients']=$_POST['numberOfClient'];
            // $accommodation['otherInfo']=$_POST['otherInfo'];
            $accommodation['saved_date']   = new MongoDate();
 
            //$collection->insert($task); 

            //echo $_POST['accomodationName']; 
            //echo $accommodation['accomodationName'];       

              //$latLong = array($lat,$lon);

                $newdata = array('$set' => array('accomodationName' => $_POST['accomodationName'],'accommodationAddress'=>$_POST['accommodationAddress'],
                    'phoneNumber'=>$_POST['phoneNumber'],'attended'=>true,'numberOfClients'=>$_POST['numberOfClient']));
                print_r($newdata);

                 $collection->update(array('accomodationName' => $_POST['accomodationName']), $newdata); 
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
    $collection = $database->selectCollection('accommodations');
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}
 
$cursor = $collection->find();
$appQuery = array('accomodationName' => $locationName);//,'context'=>'Bob');// && array('context'=>'Innishannon');

$cursor = $collection->find($appQuery);
?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Edit accomodationName</title>
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <?php include 'common.php';?>
     <?php while ($cursor->hasNext()):
    $accommodation = $cursor->getNext(); ?>
    <h2><?= $accommodation['accomodationName'] ?></h2>
    <strong>accommodationAddress:</strong> <?= $accommodation['accommodationAddress']?> <br />
<?php endwhile;?>
    <h1>accommodation Creator</h1>
    <?php if ($trigger === 'show_form'): ?>
   
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <h3>Status</h3>
        <p><input type="text" name="accomodationName" value="<?= $accommodation['accomodationName']?>" id="accomodationName" /></p>
        <p><input type="text" name="accommodationAddress" value="<?= $accommodation['accommodationAddress']?>" id="accommodationAddress" /></p>

        <p><input type="text" name="phoneNumber" value="<?= $accommodation['phoneNumber']?>" id="phoneNumber" /></p>

        <p><input type="text" name="numberOfClient" value="<?= $accommodation['numberOfClients']?>" id="numberOfClient" /></p>

         
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