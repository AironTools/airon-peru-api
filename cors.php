<?php
// Habilita CORS 
header("Access-Control-Allow-Origin: *");

// Métodos permitidos
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Encabezados permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
header('Content-Type: application/json');