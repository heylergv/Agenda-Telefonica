<?php
$clave = "12345";
$claveSegura = password_hash($clave, PASSWORD_DEFAULT);
echo $claveSegura;
?>