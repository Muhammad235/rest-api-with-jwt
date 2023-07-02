<?php

//include vendor

require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;


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
    $headers  = getallheaders();

    if (!empty($data->name) && !empty($data->description) && !empty($data->status)) {

        try {

            $jwt_token = $headers['Authorization'];
            
            //secret_key and algorithm
            $secret_key = "authorization_secret_key";
            $algorithm = 'HS256';

            //decoding jwt token
            $decoded = JWT::decode($jwt_token, new Key($secret_key, $algorithm));
            $decoded_data = array($decoded);
            
            // get and set user_id from decoded data 
            $user_obj->user_id = $decoded_data[0]->data->id;
        
            $user_obj->name = $data->name; 
            $user_obj->description = $data->description; 
            $user_obj->status = $data->status; 

            if ($user_obj->create_project()) { 

                 http_response_code(200);
                echo json_encode(array(
    
                "status" => 200,
                "message" => "Project has been created successfully"
                ));
            }

        } catch (Exception $ex) {

            http_response_code(500); //internal server error

            echo json_encode(array(
                "status" => 500,
                "message" => $ex->getMessage()
            ));
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