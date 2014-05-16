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

$cursor = $collection->find();

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

$cursor1 = $collection->find();

?>

<?php

session_start();

$_SESSION['rosterStaffID'] = $rosterStaffID;

?>


<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Roster Viewer</title>

    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    </head>
    <body>
        <?php include '../common.php';?>
        <h1>Roster</h1>

       <?php
            $staffArray=array();
            while ($cursor->hasNext()):


              $staff = $cursor->getNext(); 
            array_push($staffArray, $staff['name']);
            ?>
          <?php endwhile;?>

          <form method="get" action="viewRosterByStaffMember.php">
            <label for="rosterStaffID">Staff Member</label>
            <select id="rosterStaffID" name="rosterStaffID" onchange="show()">
              <?php

              foreach ($staffArray as $value) {
                echo'<option value="'.$value.'">'.$value.'</option>'; 
              }
              ?>
            </select>
            <input type="submit">
          </form>

           <?php
            $loc=array();
            while ($cursor1->hasNext()):


              $staff = $cursor1->getNext(); 
            array_push($loc, $staff['accomodationName']);
            ?>
          <?php endwhile;?>

          <form method="get" action="viewRosterByLocation.php">
            <label for="rosterLocationID">Location</label>
            <select id="rosterLocationID" name="rosterLocationID" onchange="show()">
              <?php

              foreach ($loc as $value) {
                echo'<option value="'.$value.'">'.$value.'</option>'; 
              }
              ?>
            </select>
            <input type="submit">
          </form>

          <form method="get" action="viewRosterByDate.php">
            <label for="rosterLocationID">Date</label>
             <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
          <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
          <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
          <link rel="stylesheet" href="/resources/demos/style.css"/>
          <script>
          $(function() {
            $( "#datepicker" ).datepicker();
          });
          </script>
          <p>Date: <input type="text" name="rosterLocationID" rosterLocationID id="datepicker" /></p>
            <input type="submit">
          </form>
</body>
</html>