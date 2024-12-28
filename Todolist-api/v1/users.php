<!-- Todolist-api/v1/users.php -->

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

include_once 'config/database.php';  // Akses ke folder config
include_once 'models/user.php';      // Akses ke folder models

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// Handle GET request (Read All Users)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $user->read();
    $num = $result->num_rows;

    if ($num > 0) {
        $users_arr = array();
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $user_item = array(
                "id" => $id,
                "name" => $name,
                "email" => $email,
                "created_at" => $created_at
            );
            array_push($users_arr, $user_item);
        }
        http_response_code(200);
        echo json_encode($users_arr);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No users found."));
    }
}

// Handle POST request (Create User)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->name) && !empty($data->email)) {
        $user->name = $data->name;
        $user->email = $data->email;

        if ($user->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "User created successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create user."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete data."));
    }
}

// Handle PUT request (Update User)
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id) && !empty($data->name) && !empty($data->email)) {
        $user->id = $data->id;
        $user->name = $data->name;
        $user->email = $data->email;

        if ($user->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "User updated successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update user."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete data."));
    }
}

// Handle DELETE request (Delete User)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        $user->id = $data->id;

        if ($user->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "User deleted successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete user."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete data."));
    }
}
?>