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

$cursor1 = $collection->find();

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
        $collection = $database->selectCollection('rosters');
        
        $roster               = array();
        $name=$_POST['staffMember'];//+" "+$_POST['weekDay'];
            $name .=" ";
            $name .=$_POST['weekDay'];
            $roster['name']=$name;
        $roster['weekDay']      = $_POST['weekDay'];
        $roster['location']     = $_POST['location'];
        $roster['timeslot']    = $_POST['timeslot'];
        $roster['staffMember']    = $_POST['staffMember'];
        $roster['saved_date']   = new MongoDate();
        
        $collection->insert($roster);       
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

  <?php
  $accommodationArray=array();
  while ($cursor->hasNext()):
   $accomodation = $cursor->getNext(); 
array_push($accommodationArray, $accomodation['accomodationName']);
?>
<?php endwhile;?>

<?php
$staffArray=array();
while ($cursor1->hasNext()):
    

    $staff = $cursor1->getNext(); 
array_push($staffArray, $staff['name']);
?>
<?php endwhile;?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Add a roster</title>
<link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <?php include '../common.php';?>
        <h1>roster Creator</h1>
        <?php if ($trigger === 'show_form'): ?>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
            <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
            <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
            <link rel="stylesheet" href="/resources/demos/style.css"/>
            <script>
            $(function() {
              $( "#datepicker" ).datepicker();
          });
            </script>
        </p>

        <p>
          <label for="weekDay">Date<br></label>
          <input id="datepicker" name="weekDay"/>
      </p>

      <p>
        <label for="location">Location<br /></label>
        <select id="myList" name="location" onchange="show()">
           <?php

           foreach ($accommodationArray as $value) {
            echo'<option value="'.$value.'">'.$value.'</option>'; 
        }
        ?>
    </select>
</select>
</p>

<p>
    <label for="timeslot">Timeslot<br /></label>
</p>
<select id="myList" name="timeslot" onchange="show()">
    <option>08:00-20:00</option>
    <option>20:00-8:00</option>
    <option>20:00-24:00</option>
</select>
<p>
  <label for="staffMember">Staff Member<br /></label>
</p>
<select id="myList" name="staffMember" onchange="show()">
 <?php

 foreach ($staffArray as $value) {
    echo'<option value="'.$value.'">'.$value.'</option>'; 
}
?>
</select>

<p><input type="submit" name="submit" value="Save"/></p>
</form>
<?php else: ?>
    <p>
        roster saved.
        <a href="<?php echo $_SERVER['PHP_SELF'];?>">Add another roster?</a>
        <a href="../home.php">Main Menu</a>
    </p>
<?php endif;?>
</body>
</html>