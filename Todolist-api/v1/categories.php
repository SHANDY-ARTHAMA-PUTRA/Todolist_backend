<!-- Todolist-api/v1/categories.php -->

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

include_once 'config/database.php';  // Akses ke folder config
include_once 'models/category.php';  // Akses ke folder models

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);

// Handle GET request (Read All Categories)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $category->read();
    $num = $result->num_rows;

    if ($num > 0) {
        $categories_arr = array();
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $category_item = array(
                "id" => $id,
                "category_name" => $category_name,
                "created_at" => $created_at
            );
            array_push($categories_arr, $category_item);
        }
        http_response_code(200);
        echo json_encode($categories_arr);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No categories found."));
    }
}

// Handle POST request (Create Category)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->category_name)) {
        $category->category_name = $data->category_name;

        if ($category->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "Category created successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create category."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete data."));
    }
}

// Handle PUT request (Update Category)
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id) && !empty($data->category_name)) {
        $category->id = $data->id;
        $category->category_name = $data->category_name;

        if ($category->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "Category updated successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update category."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete data."));
    }
}

// Handle DELETE request (Delete Category)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        $category->id = $data->id;

        if ($category->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Category deleted successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete category."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete data."));
    }
}
?>