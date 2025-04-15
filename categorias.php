<?php
require 'db.php';
require 'functions.php';

// Habilitar CORS para cualquier origen (solo para pruebas)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-Type: application/json');

// Responder preflight OPTIONS si es necesario (opcional para peticiones complejas)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Obtener el slug desde la URL
$categoria_slug = $_GET['categoria'] ?? null;

if (!$categoria_slug) {
    echo json_encode(['status' => 'error', 'message' => 'Categoría no especificada']);
    exit;
}

// 1. Buscar la categoría por el slug
$stmt = $pdo->prepare("SELECT id FROM categorias WHERE url = ?");
$stmt->execute([$categoria_slug]);
$categoria = $stmt->fetch();

if (!$categoria) {
    echo json_encode(['status' => 'error', 'message' => 'Categoría no encontrada']);
    exit;
}

// 2. Obtener los productos que pertenecen a esa categoría
$stmt = $pdo->prepare("SELECT * FROM productos WHERE idcategoria = ?");
$stmt->execute([$categoria['id']]);
$productos = $stmt->fetchAll();

// 3. Formatear productos (puedes reutilizar tu formateador si quieres)
$productosFormateados = array_map('formatProducto', $productos);

echo json_encode([
    'status' => 'success',
    'data' => $productosFormateados
]);
