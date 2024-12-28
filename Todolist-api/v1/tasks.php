<!-- Todolist-api/v1/tasks.php -->

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config/database.php';  // Akses ke folder config
include_once 'models/task.php';      // Akses ke folder models

$database = new Database();
$db = $database->getConnection();

$task = new Task($db);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $task->read();
    $num = $stmt->rowCount();

    if($num > 0){
        $tasks_arr = array();
        $tasks_arr["tasks"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $task_item = array(
                "task_id" => $task_id,
                "task_name" => $task_name,
                "user_id" => $user_id,
                "category_id" => $category_id,
                "status_id" => $status_id,
                "is_completed" => $is_completed,
                "due_date" => $due_date,
                "created_at" => $created_at
            );
            array_push($tasks_arr["tasks"], $task_item);
        }

        http_response_code(200);
        echo json_encode($tasks_arr);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No tasks found."));
    }
}

// Handle POST request (Create)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->task_name) && !empty($data->user_id) && !empty($data->category_id) && !empty($data->status_id)){
        $task->task_name = $data->task_name;
        $task->user_id = $data->user_id;
        $task->category_id = $data->category_id;
        $task->status_id = $data->status_id;
        $task->is_completed = $data->is_completed;
        $task->due_date = $data->due_date;

        if($task->create()){
            http_response_code(201);
            echo json_encode(array("message" => "Task was created."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create task."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create task. Data is incomplete."));
    }
}

// Menangani permintaan PUT (Update) 
if ($_SERVER['REQUEST_METHOD'] === 'PUT') { $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->task_id)){
        $task->task_id = $data->task_id;
        $task->task_name = $data->task_name;
        $task->user_id = $data->user_id;
        $task->category_id = $data->category_id;
        $task->status_id = $data->status_id;
        $task->is_completed = $data->is_completed;
        $task->due_date = $data->due_date;
    
        if($task->update()){
            http_response_code(200);
            echo json_encode(array("message" => "Task was updated."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update task."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to update task. Data is incomplete."));
    }
}

// Menangani permintaan DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') { $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->task_id)){
        $task->task_id = $data->task_id;
    
        if($task->delete()){
            http_response_code(200);
            echo json_encode(array("message" => "Task was deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete task."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to delete task. Data is incomplete."));
    }
}
?>