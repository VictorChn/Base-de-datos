<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psicnutrition";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$idPaciente = isset($_GET['idPaciente']) ? $_GET['idPaciente'] : '';

if ($idPaciente) {
    $stmt = $conn->prepare("SELECT Nombre_Paciente, Apellido_Paciente, Telefono_Contacto, Correo_Electronico FROM paciente WHERE ID_Paciente = ?");
    $stmt->bind_param("s", $idPaciente); 

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $patientData = $result->fetch_assoc();
        echo json_encode($patientData);
    } else {
        echo json_encode(['error' => 'Paciente no encontrado']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['error' => 'ID de paciente no proporcionado']);
}

$conn->close();
?>

