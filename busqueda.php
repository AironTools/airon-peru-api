<?php
require 'db.php';
require 'functions.php';
require 'cors.php';

$termino = $_GET['q'] ?? '';

if (empty($termino)) {
    echo json_encode(['status' => 'error', 'message' => 'Debe proporcionar un término de búsqueda']);
    exit;
}

// Prepara el término para usarlo en la búsqueda FULLTEXT
$searchTerm = $termino;

// Buscar categorías similares usando FULLTEXT
$stmt = $pdo->prepare("
    SELECT *, MATCH(nombre, url) AGAINST (? IN NATURAL LANGUAGE MODE) AS relevancia 
    FROM categorias 
    WHERE MATCH(nombre, url) AGAINST (? IN NATURAL LANGUAGE MODE)
    ORDER BY relevancia DESC
");
$stmt->execute([$searchTerm, $searchTerm]);
$categorias = $stmt->fetchAll();

// Buscar productos similares usando FULLTEXT (por nombre, descripción o ventajas)
$stmt = $pdo->prepare("
    SELECT *, MATCH(nombre, descripcion) AGAINST (? IN NATURAL LANGUAGE MODE) AS relevancia 
    FROM productos
    WHERE MATCH(nombre, descripcion) AGAINST (? IN NATURAL LANGUAGE MODE)
    ORDER BY relevancia DESC
");
$stmt->execute([$searchTerm, $searchTerm]);
$productos = $stmt->fetchAll();


$categoriasFormateadas = array_map('formatCategoria', $categorias);

$productosFormateados = array_map('formatProducto', $productos);

echo json_encode([
    'status' => 'success',
    'data' => [
        'categorias_similares' => $categoriasFormateadas,
        'productos_similares' => $productosFormateados
    ]
]);
