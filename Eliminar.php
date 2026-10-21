<?php

require_once "Proteger.php";
require_once "conexion.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("ID inválido. <a href='Inicio.php'>Volver</a>");
}

$id = (int)$_GET["id"];

$sql = "DELETE FROM contactos WHERE id = ?";

$stmt = mysqli_prepare($conexion, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);
mysqli_close($conexion);

header("Location: Inicio.php");
exit();

?>