<?php

session_start();

$appointmentID = $_GET['appointmentID'];


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
        $collection = $database->selectCollection('appointments');

        $appointment               = array();
            //$task['title']      = $_POST['title'];
            //$task['status']     = $_POST['status'];
            //$task['context']    = $_POST['context'];
            $name=$_POST['clientName'];//+" "+$_POST['weekDay'];
            $name .=" ";
            $name .=$_POST['appointmentDate'];
            $appointment['name']=$name;
            $appointment['clientName'] =$_POST['clientName'];
            $appointment['appointmentDate']=$_POST['appointmentDate'];
            $appointment['location']=$_POST['location'];
            $appointment['attended']=isset($_POST['attended']);
            $appointment['time']=$_POST['time'];
            $appointment['otherInfo']=$_POST['otherInfo'];
            $appointment['saved_date']   = new MongoDate();



            //$collection->insert($task);    

              //$latLong = array($lat,$lon);

            $newdata = array('$set' => array('name' =>  $appointment['name'],'clientName' => $_POST['clientName'],'appointmentDate'=>$_POST['appointmentDate'],
                'location'=>$_POST['location'],'attended'=>isset($_POST['attended']),'time'=>$_POST['time'],'otherInfo'=>$_POST['otherInfo']));
            print_r($newdata);
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
        $collection = $database->selectCollection('appointments');
    }
    catch(MongoConnectionException $e)
    {
        die("Failed to connect to database ".$e->getMessage());
    }

    $cursor = $collection->find();
$appQuery = array('name' => $appointmentID);//,'context'=>'Bob');// && array('context'=>'Innishannon');

$cursor = $collection->find($appQuery);
?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Edit appointment</title>
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
      <?php include 'common.php';?>
       <?php while ($cursor->hasNext()):
       $appointment = $cursor->getNext(); ?>
       <h2><?= $appointment['clientName'] ?></h2>
       <strong>location:</strong> <?= $appointment['location']?> <br />
   <?php endwhile;?>
   <h1>appointment Creator</h1>
   <?php if ($trigger === 'show_form'): ?>
   
   <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <h3>Status</h3>
    <p>Name: <input type="text" name="clientName" value="<?= $appointment['clientName']?>" id="clientName" readonly/></p>
    <p>Where: <input type="text" name="location" value="<?= $appointment['location']?>" id="location" /></p>

    <p>
      <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
      <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
      <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
      <link rel="stylesheet" href="/resources/demos/style.css"/>
      <script>
      $(function() {
        $( "#datepicker" ).datepicker();
    });
      </script>
      <p>Date: <input type="text" name="appointmentDate" value="<?= $appointment['appointmentDate']?>" id="datepicker" /></p>

      <p>Time: <input type="text" name="time" value="<?= $appointment['time']?>" id="time" /></p>

      <input type="checkbox" name="attended" value="<?= $appointment['attended']?>" <?php echo ($appointment['attended']==1 ? 'checked' : '');?> />Attended<br>
      <!-- <input type="radio" name="attened" value="female">Not attended -->

      <p>Other information: <textarea type="textarea" name="otherInfo" value="<?= $appointment['otherInfo']?>" id="otherInfo" />hi there</textarea></p>

      <p><input type="submit" name="submit" value="Save"/></p>
  </form>
<?php else: ?>
    <p>
        Appointment saved.<br>
        <a href="viewAppointment.php">Add another appointment?</a><br>
        <a href="index.php">Main Menu</a>
    </p>
<?php endif;?>
</body>
</html>