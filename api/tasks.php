<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../controllers/TaskController.php';

$taskController = new TaskController();
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if(isset($_GET['id'])) {
            // Get single task
            $result = $taskController->getTaskById($_GET['id']);
            if($result['success']) {
                http_response_code(200);
                echo json_encode($result['data']);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => $result['message']));
            }
        } else {
            // Get all tasks
            $result = $taskController->getAllTasks();
            if($result['success']) {
                http_response_code(200);
                echo json_encode(array(
                    "success" => true,
                    "data" => $result['data'],
                    "message" => $result['message']
                ));
            } else {
                http_response_code(404);
                echo json_encode(array("message" => $result['message']));
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        
        if(!$data) {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid JSON data."));
            break;
        }

        $result = $taskController->createTask($data);
        
        if($result['success']) {
            http_response_code(201);
            echo json_encode(array(
                "message" => $result['message'],
                "task_id" => $result['task_id']
            ));
        } else {
            http_response_code(400);
            echo json_encode(array("message" => $result['message']));
        }
        break;

    case 'PUT':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if(!$id) {
            http_response_code(400);
            echo json_encode(array("message" => "Task ID is required."));
            break;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        
        if(!$data) {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid JSON data."));
            break;
        }

        $result = $taskController->updateTask($id, $data);
        
        if($result['success']) {
            http_response_code(200);
            echo json_encode(array("message" => $result['message']));
        } else {
            http_response_code(400);
            echo json_encode(array("message" => $result['message']));
        }
        break;

    case 'DELETE':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if(!$id) {
            http_response_code(400);
            echo json_encode(array("message" => "Task ID is required."));
            break;
        }

        $result = $taskController->deleteTask($id);
        
        if($result['success']) {
            http_response_code(200);
            echo json_encode(array("message" => $result['message']));
        } else {
            http_response_code(400);
            echo json_encode(array("message" => $result['message']));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        break;
}
?>