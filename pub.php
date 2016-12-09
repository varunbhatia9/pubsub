<?php
require_once 'bootstrap.php';

// publisher object
$publisher = new Publisher(1);
$queue_id = $publisher->createQueue('News');

$x=0;
while($x<5)
{
$publish = $publisher->publishMessage($queue_id, 'hi'.$x);
echo "\n Published Message - hi$x";
sleep(1);
$x++;
}

?>
