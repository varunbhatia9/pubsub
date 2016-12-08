# pubsub
A simple queue implementation in PHP

Work in progress.

To initialize a Publisher

$publisher = new MiniQ(1,0);

The constructor takes first argument as publisher_id. Second as publisher_id.

To create a new Subscriber
$subscriber = new MiniQ(0,1);

To add a new Topic (if queue exists we get Queue Id , else new queue is created)

$queue_id = $subscriber->createQueue('News');

To become a Publisher

$subscriber = new MiniQ(0,1);
$subscriber_add = $subscriber->subscriberAddRequest($queue_id);

To become a Subscriber

$queue_id = $subscriber->createQueue('News');
$subscriber_add = $subscriber->subscriberAddRequest($queue_id);

To Publish a message to a queue

$publish = $publisher->publishMessage($queue_id, 'hi there my first message');

To Poll messages+ acknowledge messages

$messages = $subscriber->pollMessages();


refer pub.php to get sample implementation of a Publisher

refer sub-ack.php to get sample implementation of a subscriber.

Dry Run

on command line window 

php pub.php
This will add 100 messages to message queue.

php sub-ack.php
This will start polling for new messages from queue.
