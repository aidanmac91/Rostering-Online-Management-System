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

$staffArray=array();
 while ($cursor1->hasNext()):
    

    $staff = $cursor1->getNext(); 
    array_push($staffArray, $staff['name']);

      foreach ($staffArray as $value) {
            echo'<option value="'.$value.'">'.$value.'</option>'; 
            }
            ?>
            <?php endwhile;?>

?>