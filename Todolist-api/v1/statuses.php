<!-- Todolist-api/v1/statuses.php -->

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

include_once 'config/database.php';  // Akses ke folder config
include_once 'models/status.php';    // Akses ke folder models

$database = new Database();
$db = $database->getConnection();

$status = new Status($db);

// Handle GET request (Read All Statuses)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $status->read();
    $num = $result->num_rows;

    if ($num > 0) {
        $statuses_arr = array();
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $status_item = array(
                "id" => $id,
                "status_name" => $status_name,
                "created_at" => $created_at
            );
            array_push($statuses_arr, $status_item);
        }
        http_response_code(200);
        echo json_encode($statuses_arr);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No statuses found."));
    }
}

// Handle POST request (Create Status)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->status_name)) {
        $status->status_name = $data->status_name;

        if ($status->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "Status created successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create status."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete data."));
    }
}

// Handle PUT request (Update Status)
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id) && !empty($data->status_name)) {
        $status->id = $data->id;
        $status->status_name = $data->status_name;

        if ($status->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "Status updated successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update status."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete data."));
    }
}
?>