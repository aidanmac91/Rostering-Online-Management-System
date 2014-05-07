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

$locArray=array();
 while ($cursor1->hasNext()):
    

    $staff = $cursor1->getNext(); 
    array_push($locArray, $staff['accommodationAddress']);
    print_r($locArray);
?>
<?php
      foreach ($locArray as $value) {
            echo'<option value="'.$value.'">'.$value.'</option>'; 
            }
            ?>
            <?php endwhile;?>

?>