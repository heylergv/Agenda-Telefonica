<!-- Link del video -->
<!-- https://youtu.be/CyVUHHEtFU4 -->

<?php
session_start();

$mensaje = $_SESSION["mensaje"] ?? "";
unset($_SESSION["mensaje"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Agenda Telefónica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="agenda.png">
</head>

<!-- <body class="bg-light"> -->
    <body style="background-color: #ffecdac1; color: white;">
    <p style="color: #3c3b38;">Link del video: https://youtu.be/CyVUHHEtFU4</p>

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">

                    <h3 class="text-center mb-4">Iniciar Sesión</h3>

                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($mensaje); ?>
                        </div>
                    <?php endif; ?>

                    <form action="ValidarLogin.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="text" name="correo" class="form-control" placeholder="usuario@proyecto.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="clave" class="form-control" required>
                        </div>
                        <button type="submit" class="btn w-100" style="background-color: #5274ff; color: white;">Ingresar</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>