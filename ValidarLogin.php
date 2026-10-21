<!-- Bienvenido a la Fase I del proyecto
Heyler Fernando Galvez Pacheco
9490 - 15 1250
Desarrollo Web -->

<?php
session_start();
require_once "conexion.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit();
}

$correo = trim($_POST["correo"] ?? "");
$clave = $_POST["clave"] ?? "";

if ($correo === "" || $clave === "") {
    $_SESSION["mensaje"] = "Debe ingresar correo y contraseña.";
    header("Location: login.php");
    exit();
}

// VALIDAR FORMATO DEL CORREO

$patronCorreo = '/^[a-zA-Z0-9._]+@proyecto\.com$/';
if (!preg_match($patronCorreo, $correo)) {
    $_SESSION["mensaje"] = "El correo debe tener el formato usuario@proyecto.com.";
    header("Location: login.php");
    exit();
}

$sql = "SELECT id, nombre, correo, clave, rol, intentos FROM usuarios WHERE correo = ? LIMIT 1";
$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) {
    $_SESSION["mensaje"] = "Ocurrió un error al procesar el inicio de sesión.";
    header("Location: login.php");
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $correo);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (!$resultado || mysqli_num_rows($resultado) !== 1) {
    $_SESSION["mensaje"] = "Correo o contraseña incorrectos.";

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);

    header("Location: login.php");
    exit();
}

$usuarios = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

// VALIDAR SI EL USUARIO YA ESTÁ BLOQUEADO 
$intentos = (int)$usuarios["intentos"];

if ($intentos >= 3) {
    $_SESSION["mensaje"] = "Usuario bloqueado. Contacte al administrador para verificarse.";

    mysqli_close($conexion);

    header("Location: login.php");
    exit();
}

// VALIDAR CONTRASEÑA
if (password_verify($clave, $usuarios["clave"])) {
// if ($clave === $usuarios["clave"]) {

// LOGIN CORRECTO
    $sqlReset = "UPDATE usuarios SET intentos = 0 WHERE id = ?";
    $stmtReset = mysqli_prepare($conexion, $sqlReset);
    if ($stmtReset) {
        mysqli_stmt_bind_param($stmtReset, "i", $usuarios["id"]);
        mysqli_stmt_execute($stmtReset);
        mysqli_stmt_close($stmtReset);
    }

//  GENERAR NUEVO ID DE SESIÓN
    session_regenerate_id(true);


// GUARDAR DATOS EN SESIÓN
    $_SESSION["autenticado"] = true;
    $_SESSION["id"] = $usuarios["id"];
    $_SESSION["nombre"] = $usuarios["nombre"];
    $_SESSION["correo"] = $usuarios["correo"];
    $_SESSION["rol"] = $usuarios["rol"];
    mysqli_close($conexion);
    header("Location: Inicio.php");
    exit();

} 
else {
// CONTRASEÑA INCORRECTA
    $nuevoIntento = $intentos + 1;
    $sqlIntento = "UPDATE usuarios SET intentos = ? WHERE id = ?";
    $stmtIntento = mysqli_prepare($conexion, $sqlIntento);

    if ($stmtIntento) {
        mysqli_stmt_bind_param($stmtIntento, "ii", $nuevoIntento, $usuarios["id"]);
        mysqli_stmt_execute($stmtIntento);
        mysqli_stmt_close($stmtIntento);
    }

    if ($nuevoIntento >= 3) {
        $_SESSION["mensaje"] = "Usuario bloqueado. Contacte al administrador para verificarse.";
    } else {
        $intentosRestantes = 3 - $nuevoIntento;
        $_SESSION["mensaje"] = "Correo o contraseña incorrectos. Intentos restantes: " . $intentosRestantes;
    }

    mysqli_close($conexion);
    header("Location: login.php");
    exit();
}

?>