<?php
require 'db.php';
require 'functions.php';
require 'cors.php';

$termino = $_GET['q'] ?? '';

if (empty($termino)) {
    echo json_encode(['status' => 'error', 'message' => 'Debe proporcionar un término de búsqueda']);
    exit;
}

// Prepara el término para usarlo en LIKE (añade % para coincidencias parciales)
$searchTerm = '%' . $termino . '%';

// Buscar categorías similares
$stmt = $pdo->prepare("SELECT * FROM categorias WHERE nombre LIKE ? OR url LIKE ?");
$stmt->execute([$searchTerm, $searchTerm]);
$categorias = $stmt->fetchAll();

// Buscar productos similares (por nombre, descripción o ventajas)
$stmt = $pdo->prepare("
    SELECT * FROM productos 
    WHERE nombre LIKE ? 
       OR descripcion LIKE ?
       OR ventajas LIKE ?
");
$stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
$productos = $stmt->fetchAll();

// Opcional: formatear los productos
$productosFormateados = array_map('formatProducto', $productos);

echo json_encode([
    'status' => 'success',
    'data' => [
        'categorias_similares' => $categorias,
        'productos_similares' => $productosFormateados
    ]
]);
