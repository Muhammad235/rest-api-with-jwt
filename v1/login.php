<?php

//include vendor

require '../vendor/autoload.php';
use \Firebase\JWT\JWT;


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

    if (!empty($data->email) && !empty($data->password)) {

        $user_obj->email = $data->email;
        // $user_obj->password = $data->password;

        $user_data = $user_obj->check_email();

        if (!empty($user_data)) {
            $name = $user_data['name'];
            $email = $user_data['email'];
            $password = $user_data['password'];

            //verifying user password with database/stored password
            if (password_verify($data->password, $password)) {

                //jwt payload data parameters
                $iss = "localhost";
                $iat = time();
                $nbf = $iat + 10;
                // $exp = $iat + 0;
                $aud = "myusers";
                $use_arr_data = array(
                    "id" => $user_data['id'],
                    "name" => $user_data['name'],
                    "email" => $user_data['email']
                );


                $secret_key = "authorization_secret_key";
                $algorithm = 'HS256';

                $payload_info = array(
                    "iss" => $iss,
                    "iat" => $iat,
                    "nbf" => $nbf,
                    // "exp" => $exp,
                    "aud" => $aud,
                    "data" => $use_arr_data
                );

                //encoding jwt token
                $jwt =  JWT::encode($payload_info, $secret_key, $algorithm);

               http_response_code(200);

               //method 2
            //    $result[] = array(
            //     "status" => 200,
            //     "jwt" => $jwt,
            //      "message" => "User logged in successfully"
            //    );
            //    $response['result'] = $result;

               echo json_encode(array(

                "status" => 200,
                "jwt" => $jwt,
                 "message" => "User logged in successfully"
               ));


            }else {
                http_response_code(401); //Unauthorized - a valid email with an invalid password = Unauthorized

                echo json_encode(array(
 
                 "status" => 401,
                  "message" => "Unauthorized, Invalid user password"
                ));
            }

        }else {
            http_response_code(404); //Not found - email or password not correct

            echo json_encode(array(
                "status" => 404,
                "message" => "Invalid credentials"
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



