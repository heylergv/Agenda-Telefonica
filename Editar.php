<?php

require_once "Proteger.php";
require_once "conexion.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("ID inválido. <a href='Inicio.php'>Volver</a>");
}

$id = (int)$_GET["id"];

$sql = "SELECT * FROM contactos WHERE id = ?";

$stmt = mysqli_prepare($conexion, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

$contacto = mysqli_fetch_assoc($resultado);

if (!$contacto) {
    die("Contacto no encontrado. <a href='Inicio.php'>Volver</a>");
}

mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <h1 class="mb-4">Editar contacto</h1>

    <div class="card shadow-sm p-4">

        <form action="Actualizar.php" method="POST" id="formEditar">

            <input type="hidden" name="id" value="<?php echo $contacto["id"]; ?>">

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo htmlspecialchars($contacto["nombre"]); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Correo</label>
                <input type="email" name="correo" id="correo" class="form-control" value="<?php echo htmlspecialchars($contacto["correo"]); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" value="<?php echo htmlspecialchars($contacto["telefono"]); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Empresa</label>
                <input type="text" name="empresa" id="empresa" class="form-control" value="<?php echo htmlspecialchars($contacto["empresa"]); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar contacto</button>

            <a href="Inicio.php" class="btn btn-secondary">Cancelar</a>

        </form>

    </div>

</div>

<script>
document.getElementById("formEditar").addEventListener("submit", function(event) {

    let nombre = document.getElementById("nombre").value.trim();
    let correo = document.getElementById("correo").value.trim();
    let telefono = document.getElementById("telefono").value.trim();
    let empresa = document.getElementById("empresa").value.trim();

    if (nombre === "" || correo === "" || telefono === "" || empresa === "") {
        alert("Todos los campos son obligatorios.");
        event.preventDefault();
        return;
    }

    if (!/^[0-9]+$/.test(telefono)) {
        alert("El teléfono solo puede contener números.");
        event.preventDefault();
        return;
    }

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>