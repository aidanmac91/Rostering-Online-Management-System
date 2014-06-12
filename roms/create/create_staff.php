<!--
  File Name: create_staff.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for creating a staff member
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
  //save staff member
  {
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
    $database   = $connection->selectDB('staff');//which database
    $collection = $database->selectCollection('users');//which collection
    
    $user               = array();//user array
    $user['username']      = $_POST['username'];//set username to value in $_POST['username']
    $user['name']     = $_POST['name'];//set name to value in $_POST['name']
    $user['email']    = $_POST['email'];//set email to value in $_POST['email']
    $str1= $_POST['password'];
    $user['password']    = sha1($str1);//set password to value of the hash of the $_POST['password']
    $str=$_POST['recordPassword'];
    $user['recordPassword']=sha1($str);//set recordPassword to value of the hash of the $_POST['recordPassword']
    $user['staffType']    = $_POST['staffType'];//set staffType to value in $_POST['staffType']
    $user['hoursPerWeek']    = $_POST['hour'];//set hoursPerWeek to value in $_POST['hour']
    
    $collection->insert($user);   //insert user    
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
  <title>Add a staff member</title>
  <link type="text/css" rel="stylesheet" href="" />
      </head>
      <body><?php include '../common.php';?><!--include common.php -->
        <h1>User Creator</h1>
        <?php if ($trigger === 'show_form'): ?>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
          <label for="username">Username<br /></label>
          <input id="username" name="username"/><!-- input field for username -->
        </p>
        <p>
          <label for="name">Name<br /></label>
          <input id="name" name="name"/><!-- input field for name -->
        </p>
        <p>
          <label for="email">Email<br /> </label>
          <input id="email" name="email"/><!-- input field for email -->
        </p>
        <p>
          <label for="password">Password<br /> </label>
          <input id="password" type="password" name="password"/><!-- password field for password -->
        </p>
        <p>
          <label for="password">Record Password<br /> </label>
          <input id="recordPassword" type="password" name="recordPassword"/><!-- password field for recordPassword -->
        </p>
        <p>
          <label for="staffType">Staff Type<br /></label>
          <select id="myList" name="staffType" onchange="show()"><!-- Dropdown list of staff type-->
            <option>Care Staff</option>
            <option>Nursing Staff</option>
            <option>Administrator</option>
          </select>
        </p>
        <label for="hour">Hours per week<br /></label>
        <select name="hour"><!-- Dropdown list of hours per week-->
          <?php for ($i = 1; $i <= 42; $i++) : ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
        <?php endfor; ?>
      </select>
      <p><input type="submit" name="submit" value="Save"/></p>
    </form>
  <?php else: ?>
  <p>
    Staff Member saved.
    <a href="<?php echo $_SERVER['PHP_SELF'];?>">Add another staff member?</a>
    <a href="../home.php">Main Menu</a>
  </p>
<?php endif;?>

 <?php include '../footer.php';?>
</body>
</html>