<?php

Class Subscriber {

    function __construct($publisher_id = 0, $subscriber_id = 0) {
        // TODO : breakdown into publisher+ subscriber classes
        // TODO : move DB calls in a model class
        $this->publisher_id = $publisher_id;
        $this->subscriber_id = $subscriber_id;
        //singleton DB pattern, master Slave handled
        $this->db = SQLQuery::getInstance(); 
    }
    
    public function createQueue($topic) {
        // queue can be created by subscriber / publisher
        $query = "SELECT queue_id FROM queues WHERE topic='$topic'";
        $res = $this->db->read_query($query);

        if ($res->num_rows > 0) {
            $arr_queue = $res->fetch_assoc();
            return (int) $arr_queue['queue_id'];
        } else {
            $query_insert = "INSERT INTO queues (topic) VALUES ('$topic')";
            $res_insert = $this->db->write_query($query_insert);
            $queue_id = mysqli_insert_id($this->db->mysqli_write);
            return (int) $queue_id;
        }
    }

    public function subscriberAddRequest($queue_id) {
        //TODO : authentication of subscriber, queue_id validation
        if ($this->subscriber_id == 0) {
            return array('status_code' => 400, "msg" => "Invalid Subscriber id");
        }
        $query_insert = "INSERT INTO subscribers(subscriber_id,queue_id) VALUES($this->subscriber_id,$queue_id) ";
        $res = $this->db->write_query($query_insert);
        if ($res) {
            return array('status_code' => 200, 'msg' => 'Sucessfully added as subscriber');
        } else {
            return array('status_code' => 400, 'msg' => 'Already added as subscriber / invalid queue_id');
        }
    }

    public function subscriberDeleteRequest($queue_id) {
        //TODO : authentication of subscriber, queue_id validation
        if ($this->subscriber_id == 0) {
            return array('status_code' => 400, "msg" => "Invalid Subscriber id");
        }
        $query_delete = "DELETE FROM subscribers WHERE subscriber_id = $this->subscriber_id AND queue_id = $queue_id";
        //var_dump($query_delete);

        $res = $this->db->write_query($query_delete);
        if (mysqli_affected_rows($this->db->mysqli_write) > 0) {
            return array('status_code' => 200, 'msg' => 'Sucessfully removed as subscriber');
        } else {
            return array('status_code' => 400, 'msg' => 'You are not a subscriber to the topic');
        }
    }

    public function publisherAddRequest($queue_id) {
        //TODO : authentication of publisher, queue_id validation
        if ($this->publisher_id == 0) {
            return array('status_code' => 400, "msg" => "Invalid Publisher id");
        }
        $query_insert = "INSERT INTO publishers(publisher_id,queue_id) VALUES($this->publisher_id,$queue_id) ";
        $res = $this->db->write_query($query_insert);
        if ($res) {
            return array('status_code' => 200, 'msg' => 'Sucessfully added as publisher');
        } else {
            return array('status_code' => 400, 'msg' => 'Already added as publisher / invalid queue_id');
        }
    }

    public function publisherDeleteRequest($queue_id) {
        //TODO : authentication of publisher9 constructor), queue_id validation 
        if ($this->publisher_id == 0) {
            return array('status_code' => 400, "msg" => "Invalid Publisher id");
        }
        $query_delete = "DELETE FROM publishers WHERE publisher_id = $this->publisher_id and queue_id = $queue_id ";
        $res = $this->db->write_query($query_delete);
        if (mysqli_affected_rows($this->db->mysqli_write) > 0) {
            return array('status_code' => 200, 'msg' => 'Sucessfully removed as Publisher');
        } else {
            return array('status_code' => 400, 'msg' => 'You are not a  publisher  to the topic');
        }
    }

    public function pollMessages() {
        $subscriber_queues = $this->getSubscriberQueues();
        $messages = array();
        if($subscriber_queues['status_code']==200)
        {
            //echo 'hi';print_r($subscriber_queues['msg']);
            $queues = implode(',',array_keys($subscriber_queues['msg']));
            
            $query = "SELECT message_id,message_content FROM messages "
                    . "WHERE queue_id IN ($queues) and timestampdiff(MINUTE,time_pushed,NOW())>".MINUTES_TO_ACKNOWLEDGE." LIMIT ".MESSAGE_PER_POLL;
            //var_dump($query);die;
            $res = $this->db->read_query($query);
            
            if($res->num_rows>0)
            {
                while ($arr = $res->fetch_assoc()) {
                $messages[$arr['message_id']] = $arr['message_content'];
                
                
                
            }
            $messages_to_update = implode(',',array_keys($messages));
            
            $query_update = "UPDATE messages SET time_pushed = NOW() "
                    . "WHERE message_id IN($messages_to_update)";
            $this->db->write_query($query_update);
            
            }    
        return array('status_code'=>200,'msg'=>$messages);    
        }
        return array('status_code'=>400,'msg'=>$messages);
        
    }

    public function acknowledgeMessage($message_id) {
        
        if ($this->subscriber_id == 0) {
            return array('status_code' => 400, "msg" => "Invalid subscriber id");
        }
        $query = "SELECT count(1) FROM messages WHERE message_id =$message_id "
                . "AND timestampdiff(MINUTE,time_pushed,NOW())<".MINUTES_TO_ACKNOWLEDGE."";
    
        $res = $this->db->read_query($query);
        $arr = $res->fetch_assoc();
        if($arr['count(1)']>0)
        {
            $query_delete = "DELETE FROM messages WHERE message_id=$message_id";
            $this->db->write_query($query_delete);
            return array('status_code' => 200, "msg" => "Invalid message id");
        }
        else
        {
            return array('status_code' => 400, "msg" => "Invalid message id");
        }
    }

    public function getSubscriberQueues() {
        //TODO : authentication of subscriber, queue_id validation
        if ($this->subscriber_id == 0) {
            return array('status_code' => 400, "msg" => "Invalid Subscriber id");
        }
        
        $subscriber_queue = array();
        $query = "SELECT subscribers.queue_id,topic FROM subscribers "
                . "INNER JOIN queues ON subscribers.queue_id = queues.queue_id "
                . "WHERE subscribers.subscriber_id=$this->subscriber_id";
        //echo $query;
        $res = $this->db->read_query($query);
        if ($res->num_rows > 0) {
            while ($arr = $res->fetch_assoc()) {
                $subscriber_queue[$arr['queue_id']]= $arr['topic'];
            }
        }
        return array('status_code' => 200, 'msg' => $subscriber_queue);
    }

    public function getPublisherQueues() {
        
        //TODO : authentication of publisher-  constructor), queue_id validation 
        if ($this->publisher_id == 0) {
            return array('status_code' => 400, "msg" => "Invalid Publisher id");
        }
        $publisher_queue = array();
        $query = "SELECT publishers.queue_id,topic FROM publishers "
                . "INNER JOIN queues ON publishers.queue_id = queues.queue_id "
                . "WHERE publishers.publisher_id=$this->publisher_id";
        //echo $query;
        $res = $this->db->read_query($query);
        if ($res->num_rows > 0) {
            while ($arr = $res->fetch_assoc()) {
                $publisher_queue[$arr['queue_id']] = $arr['topic'];
            }
        }
        return array('status_code' => 200, 'msg' => $publisher_queue);
    }

    public function publishMessage($queue_id, $message) {
        
        // can only be used by  publisher 
        if($this->publisher_id == 0)
        {
            // TODO :move this to a function
            return array('status_code'=>400, 'msg'=> "Invalid publisher id");
        }
        // basic validation for length of message
        if(strlen($message)>MESSAGE_LENGTH)
        {
            
            return array('status_code'=>400, 'msg'=> "Invalid message length");
        }
        
        $query_insert = "INSERT INTO messages(queue_id,message_content,publisher_id)"
                . " VALUES ($queue_id,'$message',$this->publisher_id)";
        $res = $this->db->write_query($query_insert);
        
        // assuming there will be no DB insert errors
        
        
    }

}
