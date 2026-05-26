<?php

require_once __DIR__ . '/../core/Validator.php';
require_once __DIR__ . '/../core/database.php';
require_once __DIR__ . '/../core/TableSchema.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/UserListService.php';

header('Content-Type: application/json');

if (!Validator::validateRequestMethod('GET')) {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : UserListService::defaultLimit();
$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;

$usersConfig = require __DIR__ . '/../config/users_table.php';
$orm = new ORM();
$schema = new TableSchema($orm, $usersConfig);

$service = new UserListService(new UserRepository($orm, $schema));
$result = $service->listUsers($limit, $offset);

http_response_code($result['status']);

if ($result['success']) {
    echo json_encode([
        'status' => 'success',
        'message' => $result['message'],
        'data' => $result['data'],
        'meta' => $result['meta'],
    ]);
    exit;
}

$response = [
    'status' => 'error',
    'message' => $result['message'],
];

if (!empty($result['errors'])) {
    $response['errors'] = $result['errors'];
}

echo json_encode($response);
