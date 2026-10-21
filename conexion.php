<?php
// LOCAL: true
// SERVIDOR: false
$usarConexionLocal = true;

if ($usarConexionLocal) {

// CONEXIÓN LOCAL - XAMPP
    $host = "localhost";
    $usuario = "root";
    $password = "";
    $baseDatos = "desarrolloweb";

} else {

// CONEXIÓN SERVIDOR 
    $host = getenv("DB_HOST");
    $usuario = getenv("DB_USER");
    $password = getenv("DB_PASSWORD");
    $baseDatos = getenv("DB_NAME");

}

// CONECTAR A MYSQL
$conexion = mysqli_connect($host, $usuario, $password, $baseDatos);
if (!$conexion) {
    die("Error de conexión con la base de datos.");
}
mysqli_set_charset($conexion, "utf8mb4");
?>