<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psicnutrition";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT Cedula_Profesional, CONCAT(Nombre_Especialista, ' ', Apellido_Especialista) AS full_name FROM especialista";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $doctors = [];
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
    echo json_encode($doctors); 
} else {
    echo json_encode([]); 
}

$conn->close();
?>
