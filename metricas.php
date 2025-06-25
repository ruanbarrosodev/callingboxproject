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
$countTotal = mysqli_num_rows($result);
$data = [];
$metrica = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}



$sql5 = "SELECT count(*) as metrica5
FROM calling
WHERE doneTime IS NOT NULL
AND TIMESTAMPDIFF(DAY, time, doneTime) <= 2";

$result5 = mysqli_query($conn, $sql5);
if ($result5 && $row = mysqli_fetch_assoc($result5)) {
    $countMetrica5 = (int) $row['metrica5'];
    $metrica[5] = $countMetrica5/$countTotal;
}

$sql6 = "SELECT COUNT(*) AS metrica6
         FROM calling
         WHERE doneTime IS NULL
           AND time < NOW() - INTERVAL 2 DAY";
$result6 = mysqli_query($conn, $sql6);
if ($result6 && $row = mysqli_fetch_assoc($result6)) {
    $metrica[6] = (int) $row['metrica6'];
}

$response = array(
    'countTotal' => $countTotal,
    'query' => $sql,
    'dateControl' => $dateControl,
    'filterTime' => $tipo,
    'metrica5' => $metrica[5],
    'metrica6' => $metrica[6]
);

echo json_encode($response);