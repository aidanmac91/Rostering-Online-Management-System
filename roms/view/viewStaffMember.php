<!--
  File Name: viewRosters.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  The webpage for viewing staff members
-->
<?php
try
{
  $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish
  $database   = $connection->selectDB('staff');//select database
  $collection = $database->selectCollection('users');//select collection
}
catch(MongoConnectionException $e)
{
  die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();//get all documents

?>

<?php

session_start();//start session

$_SESSION['staffName'] = $staffName;//set session variable

?>


<!DOCTYPE html>
<head>

 <link href="css/bootstrap.min.css" rel="stylesheet">

 <!-- Custom styles for this template -->
 <link href="jumbotron.css" rel="stylesheet">
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
      <title>Staff Viewer</title>

      <link type="text/css" rel="stylesheet" href="" />

      </head>
      <body>
        <?php include '../common.php';?><!--include common.php-->
            <h1>Staff members</h1>

            <?php
            // loop through cursor and populate staffArray with staff names
            $staffArray=array();
            while ($cursor->hasNext()):
              $staff = $cursor->getNext(); 
            array_push($staffArray, $staff['name']);
            ?>
          <?php endwhile;?>

          <form method="get" action="../edit/edit_staffMember.php"><!-- form -->
            <label for="staffName">Staff Member</label>
            <select id="staffName" name="staffName" onchange="show()"><!-- dropdown list of staff names-->
              <?php
              foreach ($staffArray as $value) {
                echo'<option value="'.$value.'">'.$value.'</option>'; 
              }
              ?>
            </select>
            <input type="submit">
          </form>
        </div></div>
      </body>
      </html>