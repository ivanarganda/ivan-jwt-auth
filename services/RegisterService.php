<?php

require_once __DIR__ . '/../validators/RegisterValidator.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../core/Uuid.php';

class RegisterService
{
    private UserRepository $users;
    private RegisterValidator $validator;
    private TableSchema $schema;
    private array $config;

    public function __construct(
        UserRepository $users,
        RegisterValidator $validator,
        TableSchema $schema,
        array $config
    ) {
        $this->users = $users;
        $this->validator = $validator;
        $this->schema = $schema;
        $this->config = $config;
    }

    /**
     * @return array{success: bool, status: int, message?: string, errors?: array, data?: array}
     */
    public function register(array $data): array
    {
        $errors = $this->validator->validate($data);
        if ($errors !== []) {
            $status = isset($errors['schema']) ? 500 : 422;

            return [
                'success' => false,
                'status' => $status,
                'message' => isset($errors['schema']) ? 'Database schema mismatch' : 'Validation failed',
                'errors' => $errors,
            ];
        }

        $input = $this->validator->normalized($data);
        $plainPassword = $data['password'];

        if ($this->schema->hasColumn('email') && isset($input['email'])) {
            if ($this->users->findByEmail($input['email']) !== null) {
                return [
                    'success' => false,
                    'status' => 409,
                    'message' => 'Email already exists',
                ];
            }
        }

        $row = $this->buildRowForInsert($input, $plainPassword);
        $rowMissing = $this->missingRequiredValues($row);

        if ($rowMissing !== []) {
            return [
                'success' => false,
                'status' => 500,
                'message' => 'Could not populate required columns',
                'errors' => ['missing' => $rowMissing],
            ];
        }

        $id = $this->users->create($row);
        $created = $this->users->findById($id);

        return [
            'success' => true,
            'status' => 201,
            'message' => 'User registered successfully',
            'data' => $created ?? $this->schema->filterResponse($row),
        ];
    }

    private function buildRowForInsert(array $input, string $plainPassword): array
    {
        $row = $input;
        $now = date('Y-m-d H:i:s');

        if ($this->schema->hasColumn('uuid')) {
            $row['uuid'] = Uuid::v4();
        }

        foreach ($this->config['defaults'] as $column => $value) {
            if ($this->schema->hasColumn($column) && !isset($row[$column])) {
                $row[$column] = $value;
            }
        }

        if ($this->schema->hasColumn('password')) {
            $row['password'] = password_hash($plainPassword, PASSWORD_DEFAULT);
        }

        if ($this->schema->hasColumn('created_at')) {
            $row['created_at'] = $now;
        }

        if ($this->schema->hasColumn('updated_at')) {
            $row['updated_at'] = $now;
        }

        return array_intersect_key($row, array_flip($this->schema->getColumns()));
    }

    /**
     * @return string[]
     */
    private function missingRequiredValues(array $row): array
    {
        $missing = [];

        foreach ($this->config['required_columns'] as $column) {
            if (!$this->schema->hasColumn($column)) {
                continue;
            }

            if (!array_key_exists($column, $row) || $row[$column] === '' || $row[$column] === null) {
                $missing[] = $column;
            }
        }

        return $missing;
    }
}
