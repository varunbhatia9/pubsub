<?php
require_once 'bootstrap.php';

// subscriber object
$subscriber = new Subscriber(1);

// if queue exists we get Queue Id , else new queue is created
$queue_id = $subscriber->createQueue('News');

// subscribes to channel , if not already
$subscriber_add = $subscriber->subscriberAddRequest($queue_id);
$x = 0;
while($x<100)
{
    //ob_start();

    $messages = $subscriber->pollMessages();

//var_dump($messages);
if(count($messages['msg']) >0)
{
    //messages in queue - Process
    foreach ($messages['msg'] as $message_id=>$message_content)
    {
        echo "\nReceived $message_id - $message_content";
        echo "\n Acknowledge ";
        $subscriber->acknowledgeMessage($message_id);
        
    }
    
}
else
{
    echo "\n no messages to process, retrying";
    
}
//die;
//ob_flush();
sleep(1);
$x++;
}
?>
