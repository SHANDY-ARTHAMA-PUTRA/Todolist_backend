<!-- Todolist-api/config/database.php -->

<?php
class Database {
    private $host = "localhost";
    private $db_name = "aplikasi_todo_list";
    private $username = "root";  // Ganti dengan username MySQL Anda
    private $password = "";  // Ganti dengan password MySQL Anda
    public $conn;

    public function getConnection(){
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>