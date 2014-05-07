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
            $collection = $database->selectCollection('accommodations');
 
            $accommodation              = array();
            $accommodation['accomodationName'] =$_POST['accomodationName'];
            $accommodation['accommodationAddress']=$_POST['accommodationAddress'];
            $accommodation['phoneNumber']=$_POST['phoneNumber'];
            $accommodation['numberOfClients']=$_POST['numberOfClients'];
            $accommodation['saved_date']   = new MongoDate();
 
            $collection->insert($accommodation);       
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
    <title>Add a accommodation</title>
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <?php include 'common.php';?>
    <h1>accommodation Creater</h1>
    <?php if ($trigger === 'show_form'): ?>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
         <p>
    <label for="accomodationName">Accommodation Name <br /></label>
    <input id="accommodationName" name="accomodationName"/>
  </p>
  <p>
    <label for="accommodationAddress">Address<br /> </label>
    <textArea id="accommodationAddress" name="accommodationAddress"></textArea>
  </p>
  <p>
    <label for="phoneNumber">Phone Number <br /></label>
    <input id="phoneNumber" name="phoneNumber"/>
  </p>

<p>
  <label for="numberOfClients">Number Of Clients<br /></label>
  <select id="myList" name="numberOfClients" onchange="show()">
    <option>1</option>
    <option>2</option>
    <option>3</option>
    <option>4</option>
    <option>5</option>
  </select>
</p>
        <p><input type="submit" name="submit" value="Save"/></p>
    </form>
    <?php else: ?>
    <p>
        accommodation saved. _id: <?php echo $task['_id'];?>.
        <a href="<?php echo $_SERVER['PHP_SELF'];?>">Add another accommodation?</a>
    </p>
<?php endif;?>
    </body>
</html>