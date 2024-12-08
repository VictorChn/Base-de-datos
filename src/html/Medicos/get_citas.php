<?php
// Establecer la conexión con la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psicnutrition";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consultar todas las citas
$sql = "SELECT `ID_Cita`, `ID_Paciente`, `Cedula_Profesional`, `Fecha_Programada`, `Tipo_Cita`, `Consultorio` FROM `cita`";
$result = $conn->query($sql);

$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convertir la fecha al formato correcto para FullCalendar
        $start_date = date('Y-m-d\TH:i:s', strtotime($row['Fecha_Programada']));
        
        $events[] = [
            'id' => $row['ID_Cita'],
            'title' => "Cita ID=" . $row['ID_Cita'],
            'start' => $start_date,
            'extendedProps' => [
                'id_paciente' => $row['ID_Paciente'],
                'consultorio' => $row['Consultorio'],
                'tipo_cita' => $row['Tipo_Cita']
            ]
        ];
    }
}

echo json_encode($events);

$conn->close();
?>
