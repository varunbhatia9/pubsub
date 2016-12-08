<?php
//MYSQL QUERY DETAILS - INSERT, UPDATE, DELETE
define('DB_READ_NAME', 'pubsub');   //DATABASE NAME
define('DB_READ_USER', 'root');  //DATABASE USERNAME FOR ONLY READING PURPOSE
define('DB_READ_PASSWORD', 'password');  //DATABASE PASSWORD FOR ONLY READING PURPOSE
define('DB_READ_HOST', 'localhost');  //DATABASE HOST
//MYSQL QUERY DETAILS - INSERT, UPDATE, DELETE
define('DB_WRITE_NAME', 'pubsub');   //DATABASE NAME
define('DB_WRITE_USER', 'root');  //DATABASE USERNAME FOR ONLY READING PURPOSE
define('DB_WRITE_PASSWORD', 'password');  //DATABASE PASSWORD FOR ONLY READING PURPOSE
define('DB_WRITE_HOST', 'localhost');  //DATABASE HOST


define('MESSAGE_LENGTH',200);
// miuntes a subscriber gets to acknowledge that it received and processed a message
define('MINUTES_TO_ACKNOWLEDGE',1);
define('MESSAGE_PER_POLL',2);

require_once 'sqlquery.class.php';
require_once 'miniQ.class.php';


?>