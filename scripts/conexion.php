<?php
// Establecer la conexión PDO con UTF-8
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET CHARACTER SET utf8'
);

$host = 'localhost';
$dbname = 'jorge_tesis';
$username = 'root';
$password = '';
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    $conexion = new PDO($dsn, $username, $password, $options);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage(); // Agregar manejo de errores
}
?>