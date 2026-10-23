
<?php

require_once "Proteger.php";
require_once "conexion.php";

header("Content-Type: application/json; charset=utf-8");

// RESPUESTA JSON
function responder($codigo, $estado, $mensaje) {

    http_response_code($codigo);

    echo json_encode([
        "estado" => $estado,
        "mensaje" => $mensaje
    ], JSON_UNESCAPED_UNICODE);

    exit();
}

// VALIDAR METODO POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    responder(405, false, "Método no permitido.");
}

// OBTENER ENTRENADOR DESDE LA SESION
$idUsuario = (int)($_SESSION["id"] ?? 0);

if ($idUsuario <= 0) {
    responder(401, false, "No se encontró el entrenador en la sesión.");
}

// RECIBIR DATOS DEL POKEMON
$datos = json_decode(file_get_contents("php://input"), true);

if (!is_array($datos)) {
    responder(400, false, "Datos inválidos.");
}

$numero = filter_var($datos["numero"] ?? null, FILTER_VALIDATE_INT);
$nombre = trim($datos["nombre"] ?? "");

if ($numero === false || $numero === null || $numero <= 0 || $nombre === "" || strlen($nombre) > 100) {
    responder(400, false, "Número o nombre del Pokémon inválido.");
}

// BUSCAR REGION EN MYSQL
$sqlRegion = "SELECT id_region FROM regiones
              WHERE ? BETWEEN numero_inicio AND numero_fin
              LIMIT 1";

$stmtRegion = mysqli_prepare($conexion, $sqlRegion);
mysqli_stmt_bind_param($stmtRegion, "i", $numero);
mysqli_stmt_execute($stmtRegion);

$resultadoRegion = mysqli_stmt_get_result($stmtRegion);
$region = mysqli_fetch_assoc($resultadoRegion);

mysqli_stmt_close($stmtRegion);

if (!$region) {
    responder(400, false, "No se encontró una región para este Pokémon.");
}

$idRegion = (int)$region["id_region"];

// REGISTRAR POKEMON
try {

    $sql = "INSERT INTO registropokemon
            (id_usuario, numero_pokemon, nombre_pokemon, id_region)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conexion, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "iisi",
        $idUsuario,
        $numero,
        $nombre,
        $idRegion
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    responder(201, true, "¡" . ucfirst($nombre) . " fue registrado en tu Pokédex!");

} catch (mysqli_sql_exception $error) {

    if ($error->getCode() == 1062) {
        responder(409, false, "Este Pokémon ya está registrado en tu Pokédex.");
    }

    responder(500, false, "Ocurrió un error al registrar el Pokémon.");
}

?>
