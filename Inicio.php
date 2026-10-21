<!-- Link del video -->
<!-- https://youtu.be/CyVUHHEtFU4 -->

<?php
require_once "Proteger.php";
require_once "conexion.php";
$sql = "SELECT * FROM contactos ORDER BY id DESC";
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">

<script>
window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        window.location.reload();
    }
});
</script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Telefónica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="agenda.png">
</head>

 <body class="back" style="background-color: #eae9e5; color: white;">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="color: #3c3b38;">Bienvenido, <?php echo htmlspecialchars($_SESSION["nombre"]); ?></h1>
            <p class="mb-0" style="color: #3c3b38;"> Esta es la Agenda Telefonica de la empresa</p>
        </div>

        <a href="CerrarSesion.php" class="btn" style="background-color: #838282; color: white;">Cerrar sesión</a>
    </div>

    <div class="card shadow-sm p-4 mb-4">

        <h5 class="mb-3">Agregar nuevo contacto</h5>

        <form action="Procesar.php" method="POST" id="formContacto">

            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Correo</label>
                    <input type="email" name="correo" id="correo" class="form-control" placeholder="correo@ejemplo.com" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control" placeholder="55555555" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Empresa</label>
                    <input type="text" name="empresa" id="empresa" class="form-control" placeholder="Empresa" required>
                </div>
                

            </div>
            </br>
            <button type="submit" class="btn" style="background-color: #00ff6a; color: #f8f8f8;" class="btn mt-3">Guardar contacto</button>

        </form>

    </div>

    <div class="card shadow-sm p-4">


            <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-3">Estos son tus cotactos guardados :)</h5>

                <div class="input-group" style="max-width: 350px;">
                    <span class="input-group-text">🔍</span>
                    <input type="text" id="buscarContacto" class="form-control" placeholder="Buscar contacto...">
                </div>
            </div>

        <div class="table-responsive">

            <table class="table table-striped table-hover align-middle">

                <thead style="--bs-table-bg: #3c3b38; --bs-table-color: white;">
                    <tr>
                        <th style="width: 10%;">Nombre</th>
                        <th style="width: 20%;">Correo</th>
                        <th style="width: 11%;">Teléfono</th>
                        <th style="width: 24%;">Empresa</th>
                        <!-- <th style="width: 24%;">Columna</th> -->
                        <th style="width: 35%;">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>

                        <tr class="filaContacto">

                            <td><?php echo htmlspecialchars($fila["nombre"]); ?></td>

                            <td><?php echo htmlspecialchars($fila["correo"]); ?></td>

                            <td><?php echo htmlspecialchars($fila["telefono"]); ?></td>

                            <td><?php echo htmlspecialchars($fila["empresa"]); ?></td>
                            <!-- <td><?php echo htmlspecialchars($fila["empresa"]); ?></td> -->

                            <td>

                                <button type="button" class="btn btn-sm" style="background-color: #26ff00; color: white;" onclick="LlamarContacto('<?php echo htmlspecialchars($fila["nombre"], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($fila["telefono"], ENT_QUOTES); ?>')">
                                    📞 Llamar
                                </button>

                                <a href="Editar.php?id=<?php echo $fila["id"]; ?>" class="btn btn-sm" style="background-color: #008cff; color: white;">
                                    Editar
                                </a>

                                <a href="Eliminar.php?id=<?php echo $fila["id"]; ?>" class="btn btn-danger btn-sm" style="background-color: #dc3545; color: white;" onclick="return confirm('¿Está seguro de eliminar este contacto?');">
                                    Eliminar
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>
            </table>

        </div>

    </div>

</div>

<script>
    
document.getElementById("formContacto").addEventListener("submit", function(event) {

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

document.getElementById("buscarContacto").addEventListener("keyup", function() {

    let busqueda = this.value.toLowerCase();

    let filas = document.querySelectorAll(".filaContacto");

    filas.forEach(function(fila) {

        let nombre = fila.cells[0].textContent.toLowerCase();
        let correo = fila.cells[1].textContent.toLowerCase();
        let telefono = fila.cells[2].textContent.toLowerCase();
        let empresa = fila.cells[3].textContent.toLowerCase();

        if (
            nombre.includes(busqueda) ||
            correo.includes(busqueda) ||
            telefono.includes(busqueda) ||
            empresa.includes(busqueda)
        ) {
            fila.style.display = "";
        } else {
            fila.style.display = "none";
        }

    });

});

function LlamarContacto(nombre, telefono) {

    Swal.fire({
        title: "📞 Llamando...",
        html: "<strong>" + nombre + "</strong><br>Teléfono: " + telefono,
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Finalizar llamada",
        cancelButtonText: "Cancelar",
        allowOutsideClick: false
    }).then((resultado) => {

        if (resultado.isConfirmed) {

            Swal.fire({
                title: "Llamada finalizada",
                text: "La llamada con " + nombre + " ha terminado.",
                icon: "success",
                confirmButtonText: "Aceptar"
            });

        }

    });

}
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>