<?php
session_start(); 

if (!isset($_SESSION['nombre_usuario'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psicnutrition";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$nombre_usuario = $_SESSION['nombre_usuario'];

$sql = "SELECT r.Nombre_Recep, r.Apellido_Recep, r.ID_Recepcionista
        FROM recepcionista r 
        JOIN usuario u ON r.ID_Recepcionista = u.ID_Recep 
        WHERE u.Nombre_Usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nombre_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "nombre" => $row['Nombre_Recep'],
        "apellido" => $row['Apellido_Recep'],
        "id" => $row['ID_Recepcionista']
    ]);
} else {
    echo json_encode(["error" => "No se encontraron datos para el usuario"]);
}

$conn->close();
?>

