<?php

//headers
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once "../config/Database.php";
include_once "../classes/Users.php";

//database object
$db = new Database();
$connection = $db->connect();

$user_obj = new Users($connection);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->name) && !empty($data->email) && !empty($data->password)) {

        $user_obj->name = $data->name;
        $user_obj->email = $data->email;
        $user_obj->password =  password_hash($data->password, PASSWORD_BCRYPT);

        $email_to_check = $user_obj->check_email();
        
        if (!empty($email_to_check)) {

            http_response_code(400); //bad request

            echo json_encode(array(
                "status" => 400,
                "message" => "User already exist"
            ));

        }else {
            
            if ($user_obj->create_user()) {

                http_response_code(201); //OK

                echo json_encode(array(
                    "status" => 201,
                    "message" => "User created successfully"
                ));

            }else {
                http_response_code(500); //internal server error

                echo json_encode(array(
                    "status" => 500,
                    "message" => "Failed to create user"
                ));
                
            }
        }

    }else {
        http_response_code(400); //bad request

        echo json_encode(array(
            "status" => 400,
            "message" => "Provide all parameters"
        ));
    }
    
}else {
    http_response_code(405); // method not allowed

    echo json_encode(array(
        "status" => 405,
        "message" => "Access denied, only POST methd is allowed"
    ));
}