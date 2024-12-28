<!-- Todolist-api/index.php -->

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if (isset($_GET['endpoint'])) {
    $endpoint = $_GET['endpoint'];

    if ($endpoint == 'categories') {
        include_once 'v1/categories.php';
    }

    if ($endpoint == 'statuses') {
        include_once 'v1/statuses.php';
    }

    if ($endpoint == 'tasks') {
        include_once 'v1/tasks.php';
    }

    if ($endpoint == 'users') {
        include_once 'v1/users.php';
    }
}
?>