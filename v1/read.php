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
    
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->jwt)) {


        try {
        //user jwt 
        // $jwt_token =$data->jwt;

        //secret_key and algorithm
        $secret_key = "authorization_secret_key";
        $algorithm = 'HS256';

        //decoding jwt token
        $decoded_data = JWT::decode($data->jwt, new Key($secret_key, $algorithm));
    
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

    }
}
