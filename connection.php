<?php
try {
    $conn = mysqli_connect("localhost:3306", "root", "", "callingboxproject");
    
    if (!$conn) {
        throw new Exception("Erro na conexão: " . mysqli_connect_error());
    }

} catch (Exception $err) {
    echo "Falha: " . $err->getMessage();
    exit;
}
ini_set('log_errors', 1);
ini_set('display_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log');


/*
try {
    $pdo = new PDO("mysql:host=localhost;port=3306;dbname=callingboxproject", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexão bem-sucedida!";
    
} catch (PDOException $err) {
    echo "Erro: " . $err->getMessage();
}
*/
?>
