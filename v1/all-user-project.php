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

//student obj
$user_obj = new Users($connection);

if ($_SERVER['REQUEST_METHOD']  === "POST") {

    $headers  = getallheaders();

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

        $data = $user_obj->get_all_user_project();

        if ($data->num_rows > 0) {
    
            $projects["projects"] = array();
            while ($row  = $data->fetch_assoc()) {
    
                array_push($projects["projects"], array(
    
                    "id" => $row['id'],
                    "project_name" => $row['name'],
                    "description" => $row['description'],
                    "status" => $row['status'],
                    "created_at" => date("Y-m-d", strtotime($row['created_at']))
    
                ));
            } 
    
            http_response_code(200); // Ok (success)
    
            echo json_encode(array(
                "status" => 200,
                "projects" => $projects['projects']
            ));
    
        }else {
    
            http_response_code(404); // Not found
    
            echo json_encode(array(
                "status" => 404,
                "message" => "No project found for this user"
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

    http_response_code(405); // method not allowed

    echo json_encode(array(
        "status" => 405,
        "message" => "Access denied, only POST methd is allowed"
    ));
}

