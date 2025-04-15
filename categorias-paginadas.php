<?php
require 'db.php';
require 'functions.php';
require 'cors.php';

header('Content-Type: application/json');

// Obtener el slug y paginación desde la URL
$categoria_slug = $_GET['categoria'] ?? null;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 20; // productos por página
$offset = ($page - 1) * $limit;

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

// 2. Contar total de productos en esa categoría
$stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE idcategoria = ?");
$stmt->execute([$categoria['id']]);
$totalProductos = (int)$stmt->fetchColumn();
$totalPages = ceil($totalProductos / $limit);

// 3. Obtener productos paginados
$stmt = $pdo->prepare("
    SELECT nombre, url, url_image, resumen, descripcion, video_url, complementos 
    FROM productos 
    WHERE idcategoria = ? 
    LIMIT ? OFFSET ?
");
$stmt->bindValue(1, $categoria['id'], PDO::PARAM_INT);
$stmt->bindValue(2, $limit, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$productos = $stmt->fetchAll();

// 4. Formatear productos
$productosFormateados = array_map('formatProducto', $productos);

// 5. Devolver respuesta
echo json_encode([
    'status' => 'success',
    'current_page' => $page,
    'total_products' => $totalProductos,
    'total_pages' => $totalPages,
    'data' => $productosFormateados
]);
