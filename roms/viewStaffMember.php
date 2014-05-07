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

session_start();

$_SESSION['staffName'] = $staffName;

?>


<!DOCTYPE html>
<head>

     <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Staff Viewer</title>
 
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
 
</head>
<body>
    <?php include 'common.php';?>
<div class="jumbotron">
      <div class="container">
<h1>Staff members</h1>
 
<?php
$staffArray=array();
 while ($cursor->hasNext()):
    

    $staff = $cursor->getNext(); 
    array_push($staffArray, $staff['name']);
?>
<?php endwhile;?>

<form method="get" action="edit_staffMember.php">
      <label for="staffName">Staff Member</label>
         <select id="staffName" name="staffName" onchange="show()">
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