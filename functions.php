<?php

function sanitize($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function formatProducto($producto) {
    $producto['nombre'] = sanitize($producto['nombre']);
    $producto['descripcion'] = sanitize($producto['descripcion']);
    $producto['video_url'] = sanitize($producto['video_url']);

    // Ventajas
    if (!empty($producto['ventajas'])) {
        $decoded = htmlspecialchars_decode($producto['ventajas']);
        $producto['ventajas'] = json_decode($decoded, true);
    } else {
        $producto['ventajas'] = null;
    }

    // Especificaciones
    if (!empty($producto['especificaciones'])) {
        $decoded = htmlspecialchars_decode($producto['especificaciones']);
        $producto['especificaciones'] = json_decode($decoded, true);
    } else {
        $producto['especificaciones'] = null;
    }

    return $producto;
}
