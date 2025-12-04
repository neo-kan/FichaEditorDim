<?php
header("Content-Type: application/json");

$modelo = $_GET['modelo'];

$apiKey = "ta_5d1b16dd6895445e8e57c6969daeb4e8";
$modelFilePath   = __DIR__ ."/img/modelos/". $modelo;
$clothesFilePath = __DIR__ . "/icono2.png";

// 1️⃣ Enviar job
$postfields = [
    "person_images"  => new CURLFile($modelFilePath),
    "garment_images" => new CURLFile($clothesFilePath)
];

$ch = curl_init("https://tryon-api.com/api/v1/tryon");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $apiKey"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
if (!$response) {
    echo json_encode(["error" => "No response from Tryon API"]);
    exit;
}

$data = json_decode($response, true);

//file_put_contents("debug_tryon.log", print_r($data, true));

if (!isset($data["statusUrl"])) {
    echo json_encode(["error" => "Tryon API no devolvió statusUrl", "raw" => $data]);
    exit;
}

$statusUrl = $data["statusUrl"];
if (strpos($statusUrl, "/") === 0) {
    $statusUrl = "https://tryon-api.com" . $statusUrl;
}

// 2️⃣ Polling hasta completar
$maxRetries = 300; // hasta 15 minutos
$delay = 3; // segundos entre intentos
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

    if ($statusData["status"] === "completed" && isset($statusData["imageUrl"])) {
        $imageUrl = $statusData["imageUrl"];
        break;
    }

    if ($statusData["status"] === "failed") {
        echo json_encode(["error" => "Job failed"]);
        exit;
    }

    sleep($delay);
}

// 3️⃣ Devolver resultado final
if ($imageUrl) {
    echo json_encode(["imageUrl" => $imageUrl]);
} else {
    echo json_encode(["error" => "Timeout esperando resultado"]);
}
