
<?php
// Perro guardian
// No saltarse el login ni el URL, valida la sesion
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] !== true) {
    header("Location: login.php");
    exit();
}

?>