<?php 

header('Content-Type: application/json');

echo json_encode([
    'status' => false,
    'message' => 'Registration is currently unavailable'
]);