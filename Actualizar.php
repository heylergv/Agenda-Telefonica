<?php

require_once "Proteger.php";
require_once "conexion.php";

$id = $_POST["id"] ?? "";
$nombre = trim($_POST["nombre"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$empresa = trim($_POST["empresa"] ?? "");

if (!is_numeric($id)) {
    die("ID inválido. <a href='Inicio.php'>Volver</a>");
}

if (empty($nombre) || empty($correo) || empty($telefono) || empty($empresa)) {
    die("Todos los campos son obligatorios. <a href='Inicio.php'>Volver</a>");
}

if (!is_numeric($telefono)) {
    die("El teléfono debe contener únicamente números. <a href='Inicio.php'>Volver</a>");
}

$id = (int)$id;

$sql = "UPDATE contactos SET nombre = ?, correo = ?, telefono = ?, empresa = ? WHERE id = ?";

$stmt = mysqli_prepare($conexion, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssssi",
    $nombre,
    $correo,
    $telefono,
    $empresa,
    $id
);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);
mysqli_close($conexion);

header("Location: Inicio.php");
exit();

?>