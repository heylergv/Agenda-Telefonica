<?php

// VALIDAR API KEY
function validarApiKey() {

    $apiKeyCorrecta = getenv("AGENDA_API_KEY");
    $apiKeyRecibida = $_SERVER["HTTP_X_API_KEY"] ?? "";

    if (!$apiKeyCorrecta || !is_string($apiKeyRecibida) || !hash_equals($apiKeyCorrecta, $apiKeyRecibida)) {

        http_response_code(401);

        echo json_encode([
            "estado" => false,
            "mensaje" => "API Key inválida o no proporcionada."
        ]);

        exit();
    }
}

?>