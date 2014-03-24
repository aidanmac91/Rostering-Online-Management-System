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
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Add a roster</title>
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
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
      <option>House 1    </option>
      <option>House 2    </option>
      <option>House 3    </option>
      <option>House 4    </option>
      <option>House 5    </option>
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
    <option>John Smith</option>
    <option>Jane Murphy</option>
    <option>Tom Byrne</option>
  </select>
  
        <p><input type="submit" name="submit" value="Save"/></p>
    </form>
    <?php else: ?>
    <p>
        roster saved. _id: <?php echo $task['_id'];?>.
        <a href="<?php echo $_SERVER['PHP_SELF'];?>">Add another roster?</a>
    </p>
<?php endif;?>
    </body>
</html>