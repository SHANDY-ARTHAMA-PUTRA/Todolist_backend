<!-- Todolist-api/models/category.php -->

<?php
class Category {
    private $conn;
    private $table_name = "categories";

    public $id;
    public $category_name;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET category_name=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->category_name);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // READ
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->query($query);
        return $stmt;
    }

    // UPDATE
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET category_name=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $this->category_name, $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // DELETE
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>