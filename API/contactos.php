
<?php

header("Content-Type: application/json; charset=utf-8");

// Validar API KEY
require_once __DIR__ . "/autenticacion.php";
validarApiKey();

// Conexion a la base de datos
require_once __DIR__ . "/../conexion.php";

// Obtener el metodo http
$metodo = $_SERVER["REQUEST_METHOD"];

// Respuesta en JSON
function responder($codigo, $estado, $mensaje, $datos = []) {
    http_response_code($codigo);
    echo json_encode([
        "estado" => $estado,
        "mensaje" => $mensaje,
        "data" => $datos
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// Obtener el ID
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

// Validar el ID si se proporciona
if (isset($_GET["id"]) && (!$id || $id <= 0)) {
    responder(400, false, "El ID proporcionado no es válido.");
}


// GET - OBTENER CONTACTOS
if ($metodo === "GET") {

    if ($id) {

        $sql = "SELECT id, nombre, correo, telefono, empresa FROM contactos WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);
        $contacto = mysqli_fetch_assoc($resultado);
        mysqli_stmt_close($stmt);

        if (!$contacto) {
            responder(404, false, "Contacto no encontrado.");
        }

        responder(200, true, "Contacto obtenido correctamente.", $contacto);

    } else {

        $sql = "SELECT id, nombre, correo, telefono, empresa FROM contactos ORDER BY id DESC";
        $resultado = mysqli_query($conexion, $sql);

        $contactos = [];

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $contactos[] = $fila;
        }

        http_response_code(200);

        echo json_encode([
            "estado" => true,
            "mensaje" => "Contactos obtenidos correctamente.",
            "total" => count($contactos),
            "data" => $contactos
        ], JSON_UNESCAPED_UNICODE);

        exit();
    }
}


// POST - CREAR CONTACTO

if ($metodo === "POST") {

    $datos = json_decode(file_get_contents("php://input"), true);

    if (!is_array($datos)) {
        responder(400, false, "Debe enviar un JSON válido.");
    }

    $nombre = trim($datos["nombre"] ?? "");
    $correo = trim($datos["correo"] ?? "");
    $telefono = trim($datos["telefono"] ?? "");
    $empresa = trim($datos["empresa"] ?? "");

    if ($nombre === "" || $correo === "" || $telefono === "" || $empresa === "") {
        responder(400, false, "Todos los campos son obligatorios.");
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        responder(400, false, "El correo no es válido.");
    }

    if (!ctype_digit($telefono)) {
        responder(400, false, "El teléfono solo puede contener números.");
    }

    $sql = "INSERT INTO contactos (nombre, correo, telefono, empresa) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);

    mysqli_stmt_bind_param($stmt, "ssss", $nombre, $correo, $telefono, $empresa);

    if (mysqli_stmt_execute($stmt)) {

        $nuevoId = mysqli_insert_id($conexion);
        mysqli_stmt_close($stmt);

        responder(201, true, "Contacto creado correctamente.", [
            "id" => $nuevoId,
            "nombre" => $nombre,
            "correo" => $correo,
            "telefono" => $telefono,
            "empresa" => $empresa
        ]);
    }

    responder(500, false, "Error al crear el contacto.");
}


// PUT - ACTUALIZAR CONTACTO
if ($metodo === "PUT") {

    if (!$id) {
        responder(400, false, "Debe proporcionar el ID del contacto.");
    }

    $datos = json_decode(file_get_contents("php://input"), true);

    if (!is_array($datos)) {
        responder(400, false, "Debe enviar un JSON válido.");
    }

    $nombre = trim($datos["nombre"] ?? "");
    $correo = trim($datos["correo"] ?? "");
    $telefono = trim($datos["telefono"] ?? "");
    $empresa = trim($datos["empresa"] ?? "");

    if ($nombre === "" || $correo === "" || $telefono === "" || $empresa === "") {
        responder(400, false, "Todos los campos son obligatorios.");
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        responder(400, false, "El correo no es válido.");
    }

    if (!ctype_digit($telefono)) {
        responder(400, false, "El teléfono solo puede contener números.");
    }

    // COMPROBAR QUE EL CONTACTO EXISTE
    $sqlBuscar = "SELECT id FROM contactos WHERE id = ?";
    $stmtBuscar = mysqli_prepare($conexion, $sqlBuscar);
    mysqli_stmt_bind_param($stmtBuscar, "i", $id);
    mysqli_stmt_execute($stmtBuscar);

    $resultado = mysqli_stmt_get_result($stmtBuscar);
    $existe = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmtBuscar);

    if (!$existe) {
        responder(404, false, "Contacto no encontrado.");
    }

    // Update
    $sql = "UPDATE contactos SET nombre = ?, correo = ?, telefono = ?, empresa = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);

    mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $correo, $telefono, $empresa, $id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);

        responder(200, true, "Contacto actualizado correctamente.", [
            "id" => $id,
            "nombre" => $nombre,
            "correo" => $correo,
            "telefono" => $telefono,
            "empresa" => $empresa
        ]);
    }

    responder(500, false, "Error al actualizar el contacto.");
}

// DELETE - ELIMINAR CONTACTO
if ($metodo === "DELETE") {

    if (!$id) {
        responder(400, false, "Debe proporcionar el ID del contacto.");
    }

    $sql = "DELETE FROM contactos WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $filasAfectadas = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($filasAfectadas === 0) {
        responder(404, false, "Contacto no encontrado.");
    }

    responder(200, true, "Contacto eliminado correctamente.", [
        "id" => $id
    ]);
}

// METODO NO PERMITIDO
header("Allow: GET, POST, PUT, DELETE");
responder(405, false, "Método HTTP no permitido.");

?>
