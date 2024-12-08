<?php
session_start(); // Inicia sesión para guardar datos del usuario

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psicnutrition";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$nombre_usuario = $_POST['nombre_usuario'];
$contrasena = $_POST['contrasena'];

if (empty($nombre_usuario) || empty($contrasena)) {
    echo "Por favor, ingresa tanto el nombre de usuario como la contraseña.";
    exit();
}

$sql = "SELECT * FROM usuario WHERE Nombre_Usuario = ?"; // Ajusta el nombre de la columna aquí
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nombre_usuario); // La variable PHP puede seguir llamándose $nombre_usuario
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    if ($contrasena === $usuario['Contrasena']) {
        // Guardar el nombre de usuario en la sesión
        $_SESSION['nombre_usuario'] = $usuario['Nombre_Usuario']; // Asegúrate de que coincida aquí también
        $_SESSION['id_recep'] = $usuario['ID_Recep']; // Si necesitas este dato también

        if ($usuario['Tipo'] == 'Especialista') {
            header("Location: src/html/Medicos/MedicDashboard.html");
        } else if ($usuario['Tipo'] == 'Recepcionista') {
            header("Location: src/html/Recepcionista/ReceptionDashboard.html"); 
        }
        exit();
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Usuario no encontrado.";
}

$conn->close();
?>
