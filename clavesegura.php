<?php
$clave = "654321";
$claveSegura = password_hash($clave, PASSWORD_DEFAULT);
echo $claveSegura;
?>