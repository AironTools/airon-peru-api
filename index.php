<?php
require 'db.php';
require 'functions.php';
require 'cors.php';

// Responde con los productos como JSON
echo json_encode([
    'status' => 'success',
    'data' => 'Todo bien👍😁👍'
]);

?>
