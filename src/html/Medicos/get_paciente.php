<?php
// Establecer la conexión con la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psicnutrition";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar si se ha pasado un ID de cita
if (isset($_GET['id'])) {
    $id_cita = $_GET['id'];

    // Consulta SQL para obtener los detalles de una cita específica
    $sql = "SELECT 
                c.ID_Cita, 
                c.ID_Paciente, 
                c.Cedula_Profesional, 
                c.Fecha_Programada, 
                c.Tipo_Cita, 
                c.Consultorio,
                p.Nombre_Paciente, 
                p.Apellido_Paciente, 
                p.Fecha_Nacimiento, 
                p.Telefono_Contacto, 
                p.Correo_Electronico 
            FROM 
                cita c
            JOIN 
                paciente p ON c.ID_Paciente = p.id_paciente
            WHERE 
                c.ID_Cita = ?";  // Filtramos por ID_Cita

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cita); // Vinculamos el parámetro de ID_Cita
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificamos si hay resultados
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Devolvemos los datos como JSON
        echo json_encode(array(
            'id_cita' => $row['ID_Cita'],
            'id_paciente' => $row['ID_Paciente'],
            'cedula_profesional' => $row['Cedula_Profesional'],
            'fecha_programada' => $row['Fecha_Programada'],
            'tipo_cita' => $row['Tipo_Cita'],
            'consultorio' => $row['Consultorio'],
            'paciente' => array(
                'nombre' => $row['Nombre_Paciente'],
                'apellido' => $row['Apellido_Paciente'],
                'fecha_nacimiento' => $row['Fecha_Nacimiento'],
                'telefono' => $row['Telefono_Contacto'],
                'correo' => $row['Correo_Electronico']
            )
        ));
    } else {
        echo json_encode(array('error' => 'Cita no encontrada'));
    }
} else {
    echo json_encode(array('error' => 'ID de cita no proporcionado'));
}

$conn->close();
?>
