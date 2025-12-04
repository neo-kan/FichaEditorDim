<?php
header("Content-Type: application/json");

$apiKey = "ta_5d1b16dd6895445e8e57c6969daeb4e8";

if (!isset($_GET["jobId"])) {
    echo json_encode(["error" => "Falta jobId"]);
    exit;
}

$jobId = preg_replace('/[^a-zA-Z0-9\-]/','', $_GET["jobId"]);
$statusEndpoint = "https://tryon-api.com/api/v1/tryon/status/$jobId";

$ch = curl_init($statusEndpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "x-api-key: $apiKey"
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

$response = curl_exec($ch);
$curlError = curl_error($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($curlError) {
    echo json_encode(["error" => "Curl error: $curlError"]);
    exit;
}

if ($httpcode !== 200) {
    echo json_encode(["error" => "API returned HTTP $httpcode", "body" => $response]);
    exit;
}

$data = json_decode($response, true);

// Devuelve status y URL de imagen final
$status = $data["status"] ?? null;
$outputUrl = $data["outputUrl"] ?? $data["imageUrl"] ?? null;

$out = ["status" => $status];
if ($outputUrl) $out["resultUrl"] = $outputUrl;

echo json_encode($out);
