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

$sql = "SELECT ID_Cita, Fecha_Programada FROM cita";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$events = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $events[] = [
        'id' => $row['ID_Cita'], 
        'title' => "Cita ID: " . $row['ID_Cita'], 
        'start' => $row['Fecha_Programada'], 
    ];
}

echo json_encode($events);
?>
