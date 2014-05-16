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
    $collection = $database->selectCollection('swapMessages');

    $message               = array();
            $name=$_POST['staffName'];//+" "+$_POST['weekDay'];
            $name .=" swapped with ";
            $name .=$_POST['swap'];
            $message['name']=$name;
            $message['swapWith'] =$_POST['swap'];
            $message['from'] =$_POST['staffName'];
            $message['seen'] =false;
            $message['approved'] =false;
            $message['date']= $_POST['weekDay'];
            $message['saved_date']   = new MongoDate();

            $collection->insert($message);       
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
          $collection = $database->selectCollection('rosters');
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



        <!DOCTYPE html>
        <head>
          <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
          <title>Add a message</title>
          <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
      </head>
      <body>
        <?php include '../common.php';?>

        <?php
        $rosterArray=array();
        while ($cursor->hasNext()):


          $roster = $cursor->getNext(); 
        array_push($rosterArray, $roster['name']);
        ?>
      <?php endwhile;?>

      <?php
      $staffArray=array();
      while ($cursor1->hasNext()):


        $staff = $cursor1->getNext(); 
      array_push($staffArray, $staff['name']);
      ?>
    <?php endwhile;?>
    <h1>Message Creater</h1>
    <?php if ($trigger === 'show_form'): ?>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
     <label for="swap">Swap with<br /></label>
     <select id="myList" name="swap" onchange="show()">
       <?php

       foreach ($rosterArray as $value) {
        echo'<option value="'.$value.'">'.$value.'</option>'; 
      }
      ?>
    </select>
    <p>
      <label for="staffName">Staff Member</label>
      <select id="staffName" name="staffName" onchange="show()">
        <?php

        foreach ($staffArray as $value) {
          echo'<option value="'.$value.'">'.$value.'</option>'; 
        }
        ?>
      </select>
      <p><input type="hidden" name="weekDay" value="<?= $roster['weekDay']?>" id="weekDay" /></p>
      <p><input type="submit" name="submit" value="Save"/></p>
    </form>
  <?php else: ?>
  <p>
    Message saved.
    <a href="<?php echo $_SERVER['PHP_SELF'];?>">Add another message?</a><br>
    <a href="../home.php">Main Menu</a>
  </p>
<?php endif;?>


</form>
</body>
</html>