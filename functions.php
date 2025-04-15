<?php

function sanitize($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function formatProducto($producto) {
    $producto['nombre'] = sanitize($producto['nombre']);
    $producto['descripcion'] = sanitize($producto['descripcion']);
    $producto['url'] = sanitize($producto['url']);
    $producto['video_url'] = sanitize($producto['video_url']);
    $producto['url_image'] = sanitize($producto['url_image']);

    // Ventajas
    if (!empty($producto['ventajas'])) {
        $decoded = htmlspecialchars_decode($producto['ventajas']);
        $producto['ventajas'] = json_decode($decoded, true);
    }

    // Especificaciones
    if (!empty($producto['especificaciones'])) {
        $decoded = htmlspecialchars_decode($producto['especificaciones']);
        $producto['especificaciones'] = json_decode($decoded, true);
    }
    
    // Complementos
    if (!empty($producto['complementos'])) {
        $decoded = htmlspecialchars_decode($producto['complementos']);
        $producto['complementos'] = json_decode($decoded, true);
    }

    return $producto;
}

function formatCategoria($categoria) {
    $categoria['nombre'] = sanitize($categoria['nombre']);
    $categoria['url'] = sanitize($categoria['url']);
    $categoria['url_image'] = sanitize($categoria['url_image']);
    $categoria['Especial'] = sanitize($categoria['Especial']);
    $categoria['remember_token'] = sanitize($categoria['remember_token']);

    return $categoria;
}