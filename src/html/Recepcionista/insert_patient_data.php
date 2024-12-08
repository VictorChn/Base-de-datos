<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "psicnutrition";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar que se ha recibido el id_paciente
if (isset($_POST['id_paciente'])) {
    $id_paciente = $_POST['id_paciente'];
} else {
    die('Error: El ID del paciente no se ha proporcionado.');
}

// Obtener los datos del formulario
$nombre_paciente = $_POST['nombre_paciente'];
$apellido_paciente = $_POST['apellido_paciente'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$telefono_contacto = $_POST['telefono_contacto'];
$correo_electronico = $_POST['correo_electronico'];

// Insertar los datos en la tabla
$sql = "INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, fecha_nacimiento, telefono_contacto, correo_electronico) 
        VALUES ('$id_paciente', '$nombre_paciente', '$apellido_paciente', '$fecha_nacimiento', '$telefono_contacto', '$correo_electronico')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => "Paciente registrado exitosamente."]);
} else {
    echo json_encode(["error" => "Error: " . $conn->error]);
}

// Cerrar la conexión
$conn->close();
?>
