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
            $collection = $database->selectCollection('clients');
 
            $client               = array();
            //$task['title']      = $_POST['title'];
            //$task['status']     = $_POST['status'];
            //$task['context']    = $_POST['context'];
            $client['clientName'] =$_POST['clientName'];
            $client['nextOfKinName']=$_POST['nextOfKinName'];
            $client['accommodation']=$_POST['accommodation'];
            $client['nextOfKinNumber']=$_POST['nextOfKinNumber'];
            $client['otherInfo']=$_POST['otherInfo'];
            $client['saved_date']   = new MongoDate();
 
            $collection->insert($client);       
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
    <title>Add a client</title>
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <h1>Client Creater</h1>
    <?php if ($trigger === 'show_form'): ?>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
         <label for="clientName">Client Name<br /></label>
        <input id="clientName" name="clientName"/>
  

  <p>
    <label for="accommodation">Accommodation<br /></label>
    <select id="myList" name="accommodation" onchange="show()">
      <option>House 1</option>
      <option>House 2</option>
      <option>House 3</option>
      <option>House 4</option>
      <option>House 5</option>
    </select>
  </p>
  <p>
    <label for="nextOfKinName">Next Of Kin Name <br /></label>
    <input id="nextOfKinName" name="nextOfKinName"/>
  </p>
  <p>
    <label for="nextOfKinNumber">Next Of Kin Number <br /></label>
    <input id="nextOfKinNumber" name="nextOfKinNumber"/>
  </p>
</p>
<label for="otherInfo">Other Information <br /></label>
  <textarea id="otherInfo" name="otherInfo"/>
</textarea>
        <p><input type="submit" name="submit" value="Save"/></p>
    </form>
    <?php else: ?>
    <p>
        Client saved. _id: <?php echo $task['_id'];?>.
        <a href="<?php echo $_SERVER['PHP_SELF'];?>">Add another client?</a>
    </p>
<?php endif;?>
    </body>
</html>