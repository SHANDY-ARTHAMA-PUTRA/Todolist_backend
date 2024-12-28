<!-- Todolist-api/models/user.php -->

<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET name=?, email=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $this->name, $this->email);

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
        $query = "UPDATE " . $this->table_name . " SET name=?, email=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $this->name, $this->email, $this->id);

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