<?php
require 'db.php';
require 'functions.php';
require 'cors.php';

// Obtener el slug desde la URL
$producto_slug = $_GET['producto'] ?? null;

if (!$producto_slug) {
    echo json_encode(['status' => 'error', 'message' => 'Producto no especificado']);
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM productos WHERE url = ?");
$stmt->execute([$producto_slug]);
$producto = $stmt->fetch();

if (!$producto) {
    echo json_encode(['status' => 'error', 'message' => 'Producto no encontrado']);
    exit;
}

$productoFormateado = formatProducto($producto);
echo json_encode([
    'status' => 'success',
    'data' => $productoFormateado
]);