<?php

require_once "Proteger.php";
require_once "conexion.php";

$nombre = trim($_POST["nombre"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$empresa = trim($_POST["empresa"] ?? "");
$categoria = trim($_POST["categoria"] ?? ""); // Agregar la variable $categoria

// Validación de que no vengan campos vacíos
// if (empty($nombre) || empty($correo) || empty($telefono) || empty($empresa)) {
if (empty($nombre) || empty($correo) || empty($telefono) || empty($empresa) || empty($categoria)) {
    die("Todos los campos son obligatorios. <a href='Inicio.php'>Volver</a>");
}

// Validación del teléfono
if (!is_numeric($telefono)) {
    die("El teléfono debe contener únicamente números. <a href='Inicio.php'>Volver</a>");
}

// $sql = "INSERT INTO contactos (nombre, correo, telefono, empresa) VALUES (?, ?, ?, ?)";
$sql = "INSERT INTO contactos (nombre, correo, telefono, empresa, categoria) VALUES (?, ?, ?, ?, ?)"; // Agregar la columna categoria

$stmt = mysqli_prepare($conexion, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssss",
    $nombre,
    $correo,
    $telefono,
    $empresa
    $categoria // Agregar la variable $categoria
);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);
mysqli_close($conexion);

header("Location: Inicio.php");
exit();

?>