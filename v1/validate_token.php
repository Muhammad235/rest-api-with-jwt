<?php

//include vendor
require '../vendor/autoload.php';
use Firebase\JWT\JWT;
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
    
    // $data = json_decode(file_get_contents("php://input"));

    $headers = getallheaders();
    $jwt_token = $headers['Authorization'];


    if (!empty($jwt_token)) {


        try {
 
            //secret_key and algorithm
            $secret_key = "authorization_secret_key";
            $algorithm = 'HS256';

            //decoding jwt token
            $decoded = JWT::decode($jwt_token, new Key($secret_key, $algorithm));

            $decoded_data = array($decoded);
        
            http_response_code(200);
            echo json_encode(array(

            "status" => 200,
            "user data" => $decoded_data,
            "message" => "Jwt received successfully"
            ));

            
        } catch (Exception $ex) {
            http_response_code(500); //internal server error

            echo json_encode(array(
                "status" => 500,
                "message" => $ex->getMessage()
            ));
            
        }

    }else {
        http_response_code(404); //Not found - jwt not found/empty

        echo json_encode(array(
            "status" => 404,
            "message" => "Invalid credentials"
        ));  
    }
}


