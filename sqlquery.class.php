<?php

/**
 * This class responsible for handling mysql database via mysqli functions.
 * Class provides 2 connections based on read and write abilities.
 * As this class is designed on basis of singleton pattern.
 * It can be accessed by SQLQuery::getInstance();
 * Every instance of the class contains 1 read and 1 write connection.
 * @package PubSub
 *
 */
class SQLQuery {

    private static $_instance;
    public $mysqli_read, $mysqli_write;

    /**
     * intialize the connections
     */
    private function __construct() {
        $this->mysqli_read = new mysqli(DB_READ_HOST, DB_READ_USER, DB_READ_PASSWORD, DB_READ_NAME);
        $this->mysqli_read->set_charset("utf8");
        $this->mysqli_write = new mysqli(DB_WRITE_HOST, DB_WRITE_USER, DB_WRITE_PASSWORD, DB_WRITE_NAME);
        $this->mysqli_write->set_charset("utf8"); 
    }

    /**
     * get the instance of the class
     * @return SQLQuery Object
     */
    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * execute read queries
     * @param string sql statement
     * @return mysqli_query result
     */
    public function read_query($query) {
        //return self::getInstance()->mysqli_read->query($query);
        $return = self::getInstance()->mysqli_read->query($query);
        if (!$return) {
            error_log(mysqli_error(self::getInstance()->mysqli_read));
        }
        return $return;
    }


    /**
     * execute read queries
     * @param string sql statement
     * @return mysqli_query result
     */
    public function read_query_wifi($query) {
        //return self::getInstance()->mysqli_read->query($query);
        $return = self::getInstance()->mysqli_readwifi->query($query);
        if (!$return) {
            error_log(mysqli_error(self::getInstance()->mysqli_readwifi));
        }
        return $return;
    }


    /**
     * execute write queries
     * @param string sql statement
     * @return mysqli_query result
     */
    public function write_query($query) {
        $return = self::getInstance()->mysqli_write->query($query);
        #$return = self::getInstance()->mysqli_write->query($query);
        if (!$return) {
            error_log(mysqli_error(self::getInstance()->mysqli_write));
        }
        return $return;
    }


    
    public function escape_string($instanceOf, $escapeVar) {
        return self::getInstance()->$instanceOf->real_escape_string($escapeVar);
    }
}
?>
