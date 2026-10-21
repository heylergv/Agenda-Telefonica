<?php
$conexion = mysqli_connect(
    "localhost",
    "root",
    "",
    "desarrolloweb"
    // "heylerfernandogalvezpacheco9490151250"
);

if (!$conexion) {
    die("Error de conexión con la base de datos.");
}
mysqli_set_charset($conexion, "utf8mb4");
?>