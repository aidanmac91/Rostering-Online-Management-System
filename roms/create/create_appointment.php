<?php

session_start();

$_SESSION['regName'] = $regValue;


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
            $name=$_POST['clientName'];//+" "+$_POST['weekDay'];
            $name .=" ";
            $name .=$_POST['appointmentDate'];
            $appointment['name']=$name;
            //$task['status']     = $_POST['status'];
            //$task['context']    = $_POST['context'];
            $appointment['clientName'] =$_POST['clientName'];
            $appointment['appointmentDate']=$_POST['appointmentDate'];
            $appointment['location']=$_POST['location'];
            $appointment['attended']=false;
            $appointment['time']=$_POST['time'];
            $appointment['otherInfo']=$_POST['otherInfo'];
            $appointment['saved_date']   = new MongoDate();
            
            $collection->insert($appointment);       
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
        
        $cursor1 = $collection->find();

        ?>

        <!DOCTYPE html>
        <head>
          <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
          <title>Add a appointment</title>
          <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
      </head>
      <body>
        <?php include '../common.php';?>

        <?php
        $clientArray=array();
        while ($cursor1->hasNext()):
          

          $client = $cursor1->getNext(); 
        array_push($clientArray, $client['clientName']);
        ?>
      <?php endwhile;?>
      <h1>Appointment Creater</h1>
      <?php if ($trigger === 'show_form'): ?>
      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
       <label for="clientName">Client Name<br /></label>
       <select id="myList" name="clientName" onchange="show()">
         <?php

         foreach ($clientArray as $value) {
          echo'<option value="'.$value.'">'.$value.'</option>'; 
        }
        ?>
      </select>
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
      </p>
      <body>
        <p>
          <label for="appointmentDate">Date<br></label>
          <input id="datepicker" name="appointmentDate"/>
        </p>
      </body>
    </p>
    <p>
      <label for="location">Where<br /> </label>
      <input id="location" name="location"/>
    </p>
    <p>
      <label for="time">Time <br /></label>
      <input id="time" name="time"/>
    </p>
    <p>
      <label for="otherInfo">Other Information <br /></label>
      <textarea id="otherInfo" name="otherInfo"/>
    </textarea>
  </p>
  <p><input type="submit" name="submit" value="Save"/></p>
</form>
<?php else: ?>
  <p>
    Appointment saved.
    <a href="<?php echo $_SERVER['PHP_SELF'];?>">Add another appointment?</a><br>
    <a href="../home.php">Main Menu</a>
  </p>
<?php endif;?>


</form>
</body>
</html>