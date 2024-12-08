<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psicnutrition";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT Cedula_Profesional, CONCAT('Dr. ', Nombre_Especialista, ' ', Apellido_Especialista) AS especialista, Tarifa, Especialidad FROM especialista";
$result = $conn->query($sql);

$especialistas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $especialistas[] = $row;
    }
}

echo json_encode($especialistas);

$conn->close();
?>
