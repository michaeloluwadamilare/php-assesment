<?php
require_once '../config/database.php';
require_once '../models/Task.php';

class TaskController {
    private $db;
    private $task;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->task = new Task($this->db);
    }

    /**
     * Get all tasks
     * @return array - List of tasks
     */
    public function getAllTasks() {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ''
        ];

        try {
            $stmt = $this->task->read();
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $response['success'] = true;
            $response['data'] = $tasks;
            $response['message'] = 'Tasks retrieved successfully';

        } catch (Exception $e) {
            error_log("Get tasks error: " . $e->getMessage());
            $response['message'] = "Unable to retrieve tasks.";
        }

        return $response;
    }

    /**
     * Get task by ID
     * @param int $id - Task ID
     * @return array - Task data
     */
    public function getTaskById($id) {
        $response = [
            'success' => false,
            'data' => null,
            'message' => ''
        ];

        try {
            $this->task->id = (int)$id;
            
            if (!$this->task->exists()) {
                $response['message'] = "Task not found.";
                return $response;
            }

            $stmt = $this->task->read();
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tasks as $task) {
                if ($task['id'] == $id) {
                    $response['success'] = true;
                    $response['data'] = $task;
                    $response['message'] = 'Task retrieved successfully';
                    break;
                }
            }

        } catch (Exception $e) {
            error_log("Get task error: " . $e->getMessage());
            $response['message'] = "Unable to retrieve task.";
        }

        return $response;
    }

    /**
     * Create a new task
     * @param array $data - Task data (task_name, description, user_id, status)
     * @return array - Creation result
     */
    public function createTask($data) {
        $response = [
            'success' => false,
            'message' => '',
            'task_id' => null
        ];

        try {
            // Validate required fields
            $required_fields = ['task_name', 'description', 'user_id'];
            foreach ($required_fields as $field) {
                if (empty($data[$field])) {
                    $response['message'] = "Missing required field: $field";
                    return $response;
                }
            }

            // Set task properties
            $this->task->task_name = sanitizeInput($data['task_name']);
            $this->task->description = sanitizeInput($data['description']);
            $this->task->user_id = (int)$data['user_id'];
            $this->task->status = !empty($data['status']) ? sanitizeInput($data['status']) : 'pending';

            if ($this->task->create()) {
                $response['success'] = true;
                $response['message'] = 'Task created successfully';
                $response['task_id'] = $this->db->lastInsertId();
            } else {
                $response['message'] = 'Unable to create task';
            }

        } catch (Exception $e) {
            error_log("Create task error: " . $e->getMessage());
            $response['message'] = "Unable to create task.";
        }

        return $response;
    }

    /**
     * Update an existing task
     * @param int $id - Task ID
     * @param array $data - Updated task data
     * @return array - Update result
     */
    public function updateTask($id, $data) {
        $response = [
            'success' => false,
            'message' => ''
        ];

        try {
            $this->task->id = (int)$id;
            
            if (!$this->task->exists()) {
                $response['message'] = "Task not found.";
                return $response;
            }

            // Set task properties
            if (!empty($data['task_name'])) {
                $this->task->task_name = sanitizeInput($data['task_name']);
            }
            if (!empty($data['description'])) {
                $this->task->description = sanitizeInput($data['description']);
            }
            if (!empty($data['status'])) {
                $this->task->status = sanitizeInput($data['status']);
            }

            if ($this->task->update()) {
                $response['success'] = true;
                $response['message'] = 'Task updated successfully';
            } else {
                $response['message'] = 'Unable to update task';
            }

        } catch (Exception $e) {
            error_log("Update task error: " . $e->getMessage());
            $response['message'] = "Unable to update task.";
        }

        return $response;
    }

    /**
     * Delete a task
     * @param int $id - Task ID
     * @return array - Delete result
     */
    public function deleteTask($id) {
        $response = [
            'success' => false,
            'message' => ''
        ];

        try {
            $this->task->id = (int)$id;
            
            if (!$this->task->exists()) {
                $response['message'] = "Task not found.";
                return $response;
            }

            if ($this->task->delete()) {
                $response['success'] = true;
                $response['message'] = 'Task deleted successfully';
            } else {
                $response['message'] = 'Unable to delete task';
            }

        } catch (Exception $e) {
            error_log("Delete task error: " . $e->getMessage());
            $response['message'] = "Unable to delete task.";
        }

        return $response;
    }
}

// Helper function for sanitization
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>