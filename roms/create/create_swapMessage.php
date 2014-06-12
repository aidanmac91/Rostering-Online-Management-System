<!--
  File Name: create_swapMessage.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for creating a swap message
-->
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
  //save swap message
  {
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish 
    $database   = $connection->selectDB('staff');//which database
    $collection = $database->selectCollection('swapMessages');//which collection

            $message               = array();//message array
            $name=$_POST['staffName'];
            $name .=" swapped with ";
            $name .=$_POST['swap'];
            $message['name']=$name;//sets concatinated string to name 
            $message['swapWith'] =$_POST['swap'];//set swapWith to value in $_POST['swap']
            $message['from'] =$_POST['staffName'];//set from to value in $_POST['staffName']
            $message['seen'] =false;//set seen to false
            $message['approved'] =false;//set approved to false
            $message['date']= $_POST['weekDay'];//set date to value in $_POST['weekDay']

            $collection->insert($message);   //inserts message into db    
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
        //retrieves information from database
        {
          $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
          $database   = $connection->selectDB('staff');//which database
          $collection = $database->selectCollection('rosters');//which collection
        }
        catch(MongoConnectionException $e)
        {
          die("Failed to connect to database ".$e->getMessage());
        }

        $cursor = $collection->find();//saves all documents into the cursor object

        ?>
        <?php
        try
        {
          $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
          $database   = $connection->selectDB('staff');//which database
          $collection = $database->selectCollection('users');//which collection
        }
        catch(MongoConnectionException $e)
        {
          die("Failed to connect to database ".$e->getMessage());
        }

        $cursor1 = $collection->find();//saves all documents into the cursor1 object

        ?>



        <!DOCTYPE html>
        <head>
          <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
          <title>Add a message</title>
          <link type="text/css" rel="stylesheet" href="" />
        </head>
        <body>
          <?php include '../common.php';?><!--include common.php -->

          <?php
           //loops through cursor and save the name of the object into the rosterArray
          $rosterArray=array();
          while ($cursor->hasNext()):
            $roster = $cursor->getNext(); 
          array_push($rosterArray, $roster['name']);
          ?>
        <?php endwhile;?>

        <?php
        //loops through cursor1 and save the name of the object into the staffArray
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
       <select id="myList" name="swap" onchange="show()"><!-- Dropdown list of swap-->
         <?php

         foreach ($rosterArray as $value) {
          echo'<option value="'.$value.'">'.$value.'</option>'; 
        }
        ?>
      </select>
      <p>
        <label for="staffName">Staff Member</label>
        <select id="staffName" name="staffName" onchange="show()"><!-- Dropdown list of staffName-->
          <?php

          foreach ($staffArray as $value) {
            echo'<option value="'.$value.'">'.$value.'</option>'; 
          }
          ?>
        </select>
        <p><input type="hidden" name="weekDay" value="<?= $roster['weekDay']?>" id="weekDay" /></p><!-- hidden from user-->
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

<?php include '../footer.php';?>
</body>
</html>