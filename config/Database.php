<?php


class Database
{

    //declaring properties
    private $hostname;
    private $dbname;
    private $username;
    private $password;
    private $conn;

    //connect method
    public function connect(){
        $this->hostname = 'localhost';
        $this->dbname = 'rest_jwt';
        $this->username = 'root';
        $this->password = '';

        $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_errno) {
            die("Connection failed: ". $this->conn->connect_error);
        }else {
            // print("conneted");
            return $this->conn;
        }
    } 
}


// $db = new Database;

// $db->connect();