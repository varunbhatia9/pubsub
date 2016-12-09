# pubsub
A simple queue implementation in PHP

Work in progress.

1.) To initialize a Publisher

$publisher = new Publisher(1);

The constructor takes first argument as publisher_id. Second as publisher_id.

2.) To create a new Subscriber
$subscriber = new Subscriber(1);

3.) To add a new Topic (if queue exists we get Queue Id , else new queue is created) . This can be used in subscriber/publisher

$queue_id = $subscriber->createQueue('News');

4.) To become a Publisher for a topic

$subscriber_add = $subscriber->subscriberAddRequest($queue_id);

5.) To become a Subscriber

$queue_id = $subscriber->createQueue('News');
$subscriber_add = $subscriber->subscriberAddRequest($queue_id);

6.) To Publish a message to a queue

$publish = $publisher->publishMessage($queue_id, 'hi there my first message');

7.) To Poll messages+ acknowledge messages

$messages = $subscriber->pollMessages();


8.) refer pub.php to get sample implementation of a Publisher

refer sub-ack.php to get sample implementation of a subscriber.

9.) Dry Run

on command line window 

php pub.php
This will add 100 messages to message queue.

php sub-ack.php
This will start polling for new messages from queue.


