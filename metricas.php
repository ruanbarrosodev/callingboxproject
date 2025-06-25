<?php 
header('Content-Type: application/json');
require('connection.php');

$tipo = $_POST['filterTime'] ?? 'dia'; 
$dateControl = $_POST['dateControl'] ?? '';

if (empty($dateControl)) {
    // Sem dateControl: usa CURDATE()
    switch (strtolower($tipo)) {
        case 'semana':
            $where = "YEARWEEK(time, 1) = YEARWEEK(CURDATE(), 1)";
            break;
        case 'mes':
            $where = "YEAR(time) = YEAR(CURDATE()) AND MONTH(time) = MONTH(CURDATE())";
            break;
        default:
            $where = "DATE(time) = CURDATE()";
    }
} else {
    // Com dateControl: usa a data enviada
    $date = mysqli_real_escape_string($conn, $dateControl);

    switch (strtolower($tipo)) {
        case 'semana':
            $where = "YEARWEEK(time, 1) = YEARWEEK('$date', 1)";
            break;
        case 'mes':
            $where = "YEAR(time) = YEAR('$date') AND MONTH(time) = MONTH('$date')";
            break;
        default:
            $where = "DATE(time) = '$date'";
    }
}

$sql = "SELECT * FROM calling WHERE $where";
$result = mysqli_query($conn, $sql);
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

$sql2 = "SELECT COUNT(*) AS metrica6
         FROM calling
         WHERE doneTime IS NULL
           AND time < NOW() - INTERVAL 2 DAY";

$result2 = mysqli_query($conn, $sql2);
$metrica6 = 0;

if ($result2 && $row = mysqli_fetch_assoc($result2)) {
    $metrica6 = (int) $row['metrica6'];
}

$response = array(
    'count' => mysqli_num_rows($result),
    'query' => $sql,
    'dateControl' => $dateControl,
    'filterTime' => $tipo,
    'metrica6' => $metrica6
);

echo json_encode($response);