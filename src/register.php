<?php

require_once __DIR__ . '/../core/Validator.php';
require_once __DIR__ . '/../core/database.php';
require_once __DIR__ . '/../core/TableSchema.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/RegisterService.php';
require_once __DIR__ . '/../validators/RegisterValidator.php';

header('Content-Type: application/json');

if (!Validator::validateRequestMethod('POST')) {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

$usersConfig = require __DIR__ . '/../config/users_table.php';
$orm = new ORM();
$schema = new TableSchema($orm, $usersConfig);
$validator = new RegisterValidator($schema, $usersConfig);

$data = Validator::getJsonBody();
if (Validator::isEmptyBody($data)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid JSON in request body. Required fields: '
            . implode(', ', $validator->getClientRequiredFields()),
    ]);
    exit;
}

$service = new RegisterService(
    new UserRepository($orm, $schema),
    $validator,
    $schema,
    $usersConfig
);

$result = $service->register($data);

http_response_code($result['status']);

if ($result['success']) {
    echo json_encode([
        'status' => 'success',
        'message' => $result['message'],
        'data' => $result['data'],
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
