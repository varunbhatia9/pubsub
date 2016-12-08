<?php
require_once 'bootstrap.php';

// publisher object
$publisher = new MiniQ(1);

$queue_id = $publisher->createQueue('News');

//var_dump($queue_id);

//echo '<br/> Add publisher';
//$publisher_add = $publisher->publisherAddRequest($queue_id);
//var_dump($publisher_add);

// subscriber object
$subscriber = new MiniQ(0,1);

//$subscriber_add = $subscriber->subscriberAddRequest($queue_id);

//echo "<br/>add subsciber";
//var_dump($subscriber_add);

//$publisher_delete = $publisher->publisherDeleteRequest($queue_id);
//echo '<br/> delete publisher';
//var_dump($publisher_delete);

//$subscriber_delete = $subscriber->subscriberDeleteRequest($queue_id);
//echo '<br/> delete subscriber';
//var_dump($subscriber_delete);

//$subscriber_queues = $subscriber->getSubscriberQueues();
//print_r($subscriber_queues);

//$publisher_queues = $publisher->getPublisherQueues();
//print_r($publisher_queues);
$x=0;
while($x<100)
{
$publish = $publisher->publishMessage($queue_id, 'hi'.$x);
echo "\n Published Message - hi$x";
sleep(1);
$x++;
}
?>





