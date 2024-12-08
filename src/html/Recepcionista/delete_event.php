<?php

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

error_log("ID recibido: " . $id); 

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "psicnutrition"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "DELETE FROM cita WHERE ID_Cita = ?";  
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);  

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "No se pudo eliminar el evento"]);
}

$stmt->close();
$conn->close();
?>
