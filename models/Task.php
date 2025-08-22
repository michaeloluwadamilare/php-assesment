<?php
class Task {
    private $conn;
    private $table_name = "tasks";

    public $id;
    public $user_id;
    public $task_name;
    public $description;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET user_id=:user_id, task_name=:task_name, description=:description, status=:status";
        
        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->task_name = htmlspecialchars(strip_tags($this->task_name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // Bind parameters
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":task_name", $this->task_name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":status", $this->status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                 SET task_name=:task_name, description=:description, status=:status 
                 WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->task_name = htmlspecialchars(strip_tags($this->task_name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameters
        $stmt->bindParam(":task_name", $this->task_name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function exists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
?>