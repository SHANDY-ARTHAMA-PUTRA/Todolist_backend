<!-- Todolist-api/models/task.php -->

<?php
class Task {
    private $conn;
    private $table_name = "tasks";

    public $task_id;
    public $task_name;
    public $user_id;
    public $category_id;
    public $status_id;
    public $is_completed;
    public $due_date;
    public $created_at;
    public $updated_at;

    // Constructor menerima koneksi database
    public function __construct($db){
        $this->conn = $db;
    }

    // Mengambil semua tugas
    public function read(){
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Menambah tugas baru
    public function create(){
        $query = "INSERT INTO " . $this->table_name . " 
                  SET task_name=:task_name, user_id=:user_id, category_id=:category_id, status_id=:status_id, 
                  is_completed=:is_completed, due_date=:due_date";

        $stmt = $this->conn->prepare($query);

        // Sanitasi input
        $this->task_name = htmlspecialchars(strip_tags($this->task_name));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->status_id = htmlspecialchars(strip_tags($this->status_id));
        $this->is_completed = htmlspecialchars(strip_tags($this->is_completed));
        $this->due_date = htmlspecialchars(strip_tags($this->due_date));

        // Binding values
        $stmt->bindParam(":task_name", $this->task_name);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":status_id", $this->status_id);
        $stmt->bindParam(":is_completed", $this->is_completed);
        $stmt->bindParam(":due_date", $this->due_date);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // Update task
    public function update(){
        $query = "UPDATE " . $this->table_name . " 
                  SET task_name=:task_name, user_id=:user_id, category_id=:category_id, status_id=:status_id, 
                  is_completed=:is_completed, due_date=:due_date
                  WHERE task_id=:task_id";

        $stmt = $this->conn->prepare($query);

        $this->task_name = htmlspecialchars(strip_tags($this->task_name));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->status_id = htmlspecialchars(strip_tags($this->status_id));
        $this->is_completed = htmlspecialchars(strip_tags($this->is_completed));
        $this->due_date = htmlspecialchars(strip_tags($this->due_date));
        $this->task_id = htmlspecialchars(strip_tags($this->task_id));

        $stmt->bindParam(":task_name", $this->task_name);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":status_id", $this->status_id);
        $stmt->bindParam(":is_completed", $this->is_completed);
        $stmt->bindParam(":due_date", $this->due_date);
        $stmt->bindParam(":task_id", $this->task_id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // Delete task
    public function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE task_id=:task_id";

        $stmt = $this->conn->prepare($query);

        $this->task_id = htmlspecialchars(strip_tags($this->task_id));

        $stmt->bindParam(":task_id", $this->task_id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
}
?>