<?php
require 'db.php';
require 'functions.php';

header('Content-Type: application/json');

// Simula obtener datos de una tabla "productos"
$stmt = $pdo->query("SELECT * FROM productos");
$productos = $stmt->fetchAll();

$productosFormateados = array_map('formatProducto', $productos);

// Responde con los productos como JSON
echo json_encode([
    'status' => 'success',
    'data' => $productos
]);

?>
