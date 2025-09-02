<?php
include 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Leer datos JSON de PUT y POST
$input = json_decode(file_get_contents('php://input'), true);

switch($method) {

    case 'GET':
        if ($id) {
            $stmt = $conn->prepare("SELECT actor_id, first_name, last_name FROM actor WHERE actor_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            echo json_encode($result);
        } else {
            $result = $conn->query("SELECT actor_id, first_name, last_name FROM actor");
            $data = [];
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        break;

    case 'POST':
        if (!isset($input['first_name']) || !isset($input['last_name'])) {
            echo json_encode(["error"=>"Faltan datos"]);
            exit;
        }
        $stmt = $conn->prepare("INSERT INTO actor (first_name, last_name) VALUES (?, ?)");
        $stmt->bind_param("ss", $input['first_name'], $input['last_name']);
        if ($stmt->execute()) {
            echo json_encode(["message"=>"Actor creado", "id"=>$stmt->insert_id]);
        } else {
            echo json_encode(["error"=>$stmt->error]);
        }
        break;

    case 'PUT':
        if (!$id) { echo json_encode(["error"=>"Falta el id"]); exit; }
        if (!isset($input['first_name']) || !isset($input['last_name'])) {
            echo json_encode(["error"=>"Faltan datos"]); exit;
        }
        $stmt = $conn->prepare("UPDATE actor SET first_name = ?, last_name = ? WHERE actor_id = ?");
        $stmt->bind_param("ssi", $input['first_name'], $input['last_name'], $id);
        if ($stmt->execute()) {
            echo json_encode(["message"=>"Actor actualizado"]);
        } else {
            echo json_encode(["error"=>$stmt->error]);
        }
        break;

    case 'DELETE':
        if (!$id) { echo json_encode(["error"=>"Falta el id"]); exit; }
        $stmt = $conn->prepare("DELETE FROM actor WHERE actor_id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(["message"=>"Actor eliminado"]);
        } else {
            echo json_encode(["error"=>$stmt->error]);
        }
        break;

    default:
        echo json_encode(["error"=>"Método no soportado"]);
        break;
}
?>