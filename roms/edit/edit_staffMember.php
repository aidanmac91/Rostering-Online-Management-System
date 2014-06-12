<!--
  File Name: edit_staffMember.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for editing a staff member
-->
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
        $user['username'] =$_POST['username'];//set username to value in $_POST['username']
        $user['name']=$_POST['name'];//set name to value in $_POST['name']
        $user['email']=$_POST['email'];//set email to value in $_POST['email']
        $user['staffType']=$_POST['staffType'];//set staffType to value in $_POST['staffType']
        $user['hoursPerWeek']=$_POST['hoursPerWeek'];//set hoursPerWeek to value in $_POST['hoursPerWeek']

        $newdata = array('$set' => array('username' => $_POST['username'],'name'=>$_POST['name'],
            'email'=>$_POST['email'],'staffType'=>$_POST['staffType'],'hoursPerWeek'=>$_POST['hoursPerWeek']));
         //create array of data
        //update document with username =$_POST['origianlStaffMember'] with the array
        $collection->update(array('username' => $_POST['origianlStaffMember']), $newdata); 
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
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
    $database   = $connection->selectDB('staff');//database
    $collection = $database->selectCollection('users');//collecion
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$appQuery = array('name' => $staffName);//query to compare

$cursor = $collection->find($appQuery);//documents matching the query
?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Edit staff member</title>
    <link type="text/css" rel="stylesheet" href="" />
    </head>
    <body>
        <?php include '../common.php';?>
        <?php while ($cursor->hasNext()):
        //loops through cursor and display information back to the user
        $user = $cursor->getNext(); ?>
        <h2><?= $user['name'] ?></h2>
        <strong>Username:</strong> <?= $user['username']?> <br />
        <strong>Name:</strong> <?= $user['name']?> <br />
        <strong>Email:</strong> <?= $user['email']?> <br />
        <strong>Staff Type:</strong> <?= $user['staffType']?> <br />
        <strong>Hours Per Week:</strong> <?= $user['hoursPerWeek']?> <br />

    <?php endwhile;?>
    <?php if ($_SESSION['type'] == "Administrator"): ?><!--if the user is an admin-->
    <h1>Edit Staff Member</h1>
    <?php if ($trigger === 'show_form'): ?>

    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <input type="hidden" name="origianlStaffMember" value="<?= $user['username']?>" id="origianlStaffMember" /></p><!--hidden value of orig staff member-->
        <label for="username">Username<br /></label>
        <p><input type="text" name="username" value="<?= $user['username']?>" id="username" /></p><!--input field of username-->
        <label for="name">Name<br /></label>
        <p><input type="text" name="name" value="<?= $user['name']?>" id="name" /></p><!--input field of name-->
        <label for="email">Email<br /></label>
        <p><input type="text" name="email" value="<?= $user['email']?>" id="email" /></p><!--input field of email-->

        <label for="staffType">Staff Type<br /></label>
        <select id="myList" name="staffType" onchange="show()"><!--dropdown list of staff type-->
        <!--if the value is equal to the value in db it is set to the index-->
            <option value="Care Staff"<?php if ($user['staffType'] == 'Care Staff') echo ' selected="selected"'; ?>>Care Staff</option>
            <option value="Nursing Staff"<?php if ($user['staffType'] == 'Nursing Staff') echo ' selected="selected"'; ?>>Nursing Staff</option>
            <option value="Administrator"<?php if ($user['staffType'] == 'Administrator') echo ' selected="selected"'; ?>>Administrator</option>

        </select><br>
        <label for="hoursPerWeek">Hours per week<br /></label>
        <select name="hoursPerWeek">
          <?php for ($i = 1; $i <= 42; $i++) : ?><!--dropdown list of number of hours-->
        <!--if the value is equal to the value in db it is set to the index-->
          <option value="<?php echo $i; ?>" <?php if ($user['hoursPerWeek'] == $i) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
          <?php 
          $select = $user['hoursPerWeek'] == $value ? ' selected' : '';
          endfor; ?>
          <br>
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

<?php include '../footer.php';?>
</body>
</html>