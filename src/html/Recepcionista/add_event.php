<?php

$host = 'localhost'; 
$dbname = 'psicnutrition';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$idPaciente = isset($data['id-patient']) ? (int)$data['id-patient'] : null;
$cedulaProfesional = isset($data['cedula']) ? (int)$data['cedula'] : null;
$fechaProgramada = isset($data['date']) ? $data['date'] : null;
$tipoCita = isset($data['appointment-type']) ? $data['appointment-type'] : null;
$consultorio = isset($data['consulting-room']) ? $data['consulting-room'] : null;


if ($idPaciente && $cedulaProfesional && $fechaProgramada && $tipoCita && $consultorio) {
    $sql = "INSERT INTO cita (ID_Paciente, Cedula_Profesional, Fecha_Programada, Tipo_Cita, Consultorio) 
            VALUES (:idPaciente, :cedulaProfesional, :fechaProgramada, :tipoCita, :consultorio)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':idPaciente', $idPaciente, PDO::PARAM_INT);
    $stmt->bindParam(':cedulaProfesional', $cedulaProfesional, PDO::PARAM_INT);
    $stmt->bindParam(':fechaProgramada', $fechaProgramada, PDO::PARAM_STR);
    $stmt->bindParam(':tipoCita', $tipoCita, PDO::PARAM_STR);
    $stmt->bindParam(':consultorio', $consultorio, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cita creada exitosamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear la cita']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos o inválidos']);
}
?>
