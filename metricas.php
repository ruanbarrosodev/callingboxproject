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

$response = array(
    'count' => mysqli_num_rows($result),
    'query' => $sql,
    'dateControl' => $dateControl,
    'filterTime' => $tipo
);

echo json_encode($response);