<?php
// Conexión con la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psicnutrition";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Recuperar el ID del paciente
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_paciente']) && !empty($_GET['id_paciente'])) {
    $id_paciente = $_GET['id_paciente'];

    // Consulta SQL para obtener los detalles del paciente
    $sql = "SELECT `id_paciente`, `Nombre_Paciente`, `Apellido_Paciente`, `Fecha_Nacimiento`, `Telefono_Contacto`, `Correo_Electronico` 
            FROM `paciente` 
            WHERE `id_paciente` = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_paciente);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el paciente
    if ($result->num_rows > 0) {
        // Obtener los datos del paciente
        $paciente = $result->fetch_assoc();
        
        // Devolver los datos en formato JSON
        echo json_encode($paciente);
    } else {
        // Si no se encuentra el paciente, devolver un error en JSON
        echo json_encode(['error' => 'Paciente no encontrado.']);
    }

    // Cerrar la declaración
    $stmt->close();
}

// Manejar la lógica de inserción (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados desde el formulario
    $tipo_servicio = $_POST['tipo_servicio']; // Psicología o Nutrición
    $id_expediente = $_POST['id_expediente'];
    $fecha_creacion = date('Y-m-d'); // Fecha actual
    $fecha_modificacion = date('Y-m-d H:i:s'); // Fecha y hora actual
    $id_diagnostico = $_POST['id_diagnostico'];
    $nombre_diagnostico = $_POST['nombre_diagnostico'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria'];
    $alergias = isset($_POST['alergias']) ? $_POST['alergias'] : 'No';
    $dieta = $_POST['dieta'];
    $id_paciente = $_POST['id_paciente'];

    // Insertar en la tabla correspondiente (expedientepsic o expedientenutri)
    if ($tipo_servicio === 'Psicología') {
        $sql_expediente = "INSERT INTO `expedientepsic` (`ID_Expediente`, `ID_Paciente`, `Fecha_Creacion`, `Fecha_Modificacion`) 
                           VALUES (?, ?, ?, ?)";
    } else {
        $sql_expediente = "INSERT INTO `expedientenutri` (`ID_Expediente`, `ID_Paciente`, `Fecha_Creacion`, `Fecha_Modificacion`) 
                           VALUES (?, ?, ?, ?)";
    }

    $stmt_expediente = $conn->prepare($sql_expediente);
    $stmt_expediente->bind_param("iiss", $id_expediente, $id_paciente, $fecha_creacion, $fecha_modificacion);
    if (!$stmt_expediente->execute()) {
        echo json_encode(['error' => 'Error al insertar el expediente: ' . $stmt_expediente->error]);
        exit;
    }

    // Insertar en la tabla de diagnóstico
    $sql_diagnostico = "INSERT INTO `diagnostico` (`ID_Diagnostico`, `ID_Paciente`, `Nombre_Diagnostico`, `Descripción`, `Categoría`) 
                        VALUES (?, ?, ?, ?, ?)";
    $stmt_diagnostico = $conn->prepare($sql_diagnostico);
    $stmt_diagnostico->bind_param("iisss", $id_diagnostico, $id_paciente, $nombre_diagnostico, $descripcion, $categoria);
    if (!$stmt_diagnostico->execute()) {
        echo json_encode(['error' => 'Error al insertar el diagnóstico: ' . $stmt_diagnostico->error]);
        exit;
    }

    echo json_encode(['success' => 'Datos guardados correctamente.']);
}

// Cerrar la conexión
$conn->close();
?>
