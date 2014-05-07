<?php
try
{
    $connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
    $database   = $connection->selectDB('staff');
    $collection = $database->selectCollection('clients');
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}
 
$cursor1 = $collection->find();

$clientArray=array();
 while ($cursor1->hasNext()):
    

    $client = $cursor1->getNext(); 
    array_push($clientArray, $client['clientName']);

      foreach ($clientArray as $value) {
            echo'<option value="'.$value.'">'.$value.'</option>'; 
            }
            ?>
            <?php endwhile;?>

?>