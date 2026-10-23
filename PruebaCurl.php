<?php

if (function_exists("curl_init")) {
    echo "¡Excelente! cURL está habilitado correctamente.";
} else {
    echo "ERROR: cURL todavía no está habilitado.";
}

?>