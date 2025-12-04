<?php
header("Content-Type: application/json");

// Obtiene el nombre del modelo que se envía por la URL
$modelo = $_GET['modelo'];

$apiKey = "ta_5d1b16dd6895445e8e57c6969daeb4e8";
$modelFilePath   = __DIR__ ."/img/modelos/". $modelo;   // Ruta de la imagen de la persona
$clothesFilePath = __DIR__ . "/icono2.png";             // Ruta de la prenda

// 1️⃣ Se prepara lo que se enviará: la foto de la persona y la prenda
$postfields = [
    "person_images"  => new CURLFile($modelFilePath),
    "garment_images" => new CURLFile($clothesFilePath)
];

// Se envía la solicitud a Tryon API
$ch = curl_init("https://tryon-api.com/api/v1/tryon");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $apiKey"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
if (!$response) {
    // Si no responde la API, se devuelve un error
    echo json_encode(["error" => "No response from Tryon API"]);
    exit;
}

$data = json_decode($response, true);

// Revisa si la API devolvió la URL donde se consulta el estado
if (!isset($data["statusUrl"])) {
    echo json_encode(["error" => "Tryon API no devolvió statusUrl", "raw" => $data]);
    exit;
}

$statusUrl = $data["statusUrl"];
// Si la ruta viene incompleta, se completa con el dominio
if (strpos($statusUrl, "/") === 0) {
    $statusUrl = "https://tryon-api.com" . $statusUrl;
}

// 2️⃣ Ahora empieza a preguntar cada pocos segundos si la imagen ya está lista
$maxRetries = 300; // Puede intentar por mucho tiempo
$delay = 3; // Espera 3 segundos entre intentos
$imageUrl = null;

for ($i = 0; $i < $maxRetries; $i++) {
    $ch = curl_init($statusUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $apiKey"]);
    $statusResponse = curl_exec($ch);
    curl_close($ch);

    if (!$statusResponse) {
        sleep($delay);
        continue;
    }

    $statusData = json_decode($statusResponse, true);
    if (!isset($statusData["status"])) {
        sleep($delay);
        continue;
    }

    // Si ya está terminado y hay imagen
    if ($statusData["status"] === "completed" && isset($statusData["imageUrl"])) {
        $imageUrl = $statusData["imageUrl"];
        break;
    }

    // Si falló
    if ($statusData["status"] === "failed") {
        echo json_encode(["error" => "Job failed"]);
        exit;
    }

    sleep($delay);
}

// 3️⃣ Si la imagen se generó, se devuelve al cliente
if ($imageUrl) {
    echo json_encode(["imageUrl" => $imageUrl]);
} else {
    // Si nunca terminó, se informa
    echo json_encode(["error" => "Timeout esperando resultado"]);
}
?>