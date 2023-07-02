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
        $query = "INSERT INTO ". $this->users_tbl . " SET name = ?, email = ?, password = ?";

        //prepare query
        $prepare = $this->conn->prepare($query);

        //sanitize input 
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        //binding parameter
        $prepare->bind_param("sss", $this->name, $this->email, $this->password);

        if ($prepare->execute()) {
           return true;
        }
        
        return false;
    
    }

    public function check_email(){
        //check if email exist
        $query = "SELECT * FROM " . $this->users_tbl . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);

        //sanitize input 
        $this->email = htmlspecialchars(strip_tags($this->email));

        //binding param
        $stmt->bind_param("s", $this->email);

        if ($stmt->execute()) {
            $user_data = $stmt->get_result();

            return $user_data->fetch_assoc();
        }
        return array();
    }

    public function create_project(){
        //insert project
        $query = "INSERT INTO ". $this->projects_tbl . " SET user_id = ?, name = ?, description = ?, status = ?";

        //prepare query
        $prepare = $this->conn->prepare($query);

        //sanitize input 
        $this->name = htmlspecialchars(strip_tags($this->user_id));
        $this->email = htmlspecialchars(strip_tags($this->name));
        $this->password = htmlspecialchars(strip_tags($this->description));
        $this->password = htmlspecialchars(strip_tags($this->status));

        //binding parameter
        $prepare->bind_param("isss", $this->user_id, $this->name, $this->description, $this->status);

        if ($prepare->execute()) {
           return true;
        }
        
        return false;
    }

}