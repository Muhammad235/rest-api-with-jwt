<?php

//headers
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once "../config/Database.php";
include_once "../classes/Users.php";

//database object
$db = new Database();

$connection = $db->connect();

//student obj
$user_obj = new Users($connection);

if ($_SERVER['REQUEST_METHOD']  === "GET") {
    
    $data = $user_obj->get_all_project();

    if ($data->num_rows > 0) {

        $projects = array();
        
        while ($row  = $data->fetch_assoc()) {

                $projects[] = array(
                    "id" => $row['id'],
                    "project_name" => $row['name'],
                    "description" => $row['description'],
                    "status" => $row['status'],
                    "created_at" => date("Y-m-d", strtotime($row['created_at']))

                );
        } 

        http_response_code(200); // Ok (success)

        echo json_encode(array(
            "status" => 200,
            "All projects" => $projects
        ));

    }else {

        http_response_code(404); // Not found

        echo json_encode(array(
            "status" => 404,
            "message" => "No project found for this user"
        ));
    }


}else {

    http_response_code(405); // method not allowed

    echo json_encode(array(
        "status" => 405,
        "message" => "Access denied, only GET methd is allowed"
    ));
}

