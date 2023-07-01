<?php

class Users
{

    public $name;
    public $email;
    public $password;
    public $user_id;
    public $project_name;
    public $description;
    public $status;

    private $conn;
    private $users_tbl;
    private $projects_tbl;

    //constructor
    public function __construct($db) {
        $this->conn = $db;
        $this->users_tbl = "users";
        $this->projects_tbl = "projects";
    }

    public function create_user(){
        //insert data
        $query = "INSERT INTO ". $this->users_tbl . " SET name = ?, email = ?, mobile = ?";

        //prepare query
        $prepare = $this->conn->prepare($query);

        //sanitize input 
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->mobile = htmlspecialchars(strip_tags($this->mobile));

        //binding parameter
        $prepare->bind_param("ssi", $this->name, $this->email, $this->mobile);

        if ($prepare->execute()) {
           return true;
        }else {
            return false;
        }
    }


}