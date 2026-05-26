<?php

require_once __DIR__ . '/../core/Validator.php';
require_once __DIR__ . '/../core/TableSchema.php';

class RegisterValidator
{
    private TableSchema $schema;
    private array $config;

    public function __construct(TableSchema $schema, array $config)
    {
        $this->schema = $schema;
        $this->config = $config;
    }

    public function getClientRequiredFields(): array
    {
        return $this->schema->getClientRequiredFields();
    }

    public function validate(array $data): array
    {
        $errors = [];

        $missingInDb = $this->schema->missingRequiredColumns();
        if ($missingInDb !== []) {
            $errors['schema'] = 'Table users is missing required columns: ' . implode(', ', $missingInDb);
            return $errors;
        }

        $required = $this->getClientRequiredFields();
        $missing = Validator::validateRequiredFields($data, $required);
        if ($missing !== []) {
            $errors['missing'] = $missing;
            return $errors;
        }

        foreach ($this->config['validation_rules'] as $field => $rule) {
            if (!$this->schema->hasColumn($field) || !array_key_exists($field, $data)) {
                continue;
            }

            $fieldError = $this->validateField($field, $data[$field], $rule);
            if ($fieldError !== null) {
                $errors[$field] = $fieldError;
            }
        }

        return $errors;
    }

    public function normalized(array $data): array
    {
        $normalized = $this->schema->filterInput($data);

        if (isset($normalized['email']) && is_string($normalized['email'])) {
            $normalized['email'] = strtolower(trim($normalized['email']));
        }

        if (isset($normalized['first_name']) && is_string($normalized['first_name'])) {
            $normalized['first_name'] = trim($normalized['first_name']);
        }

        foreach ($normalized as $key => $value) {
            if (is_string($value)) {
                $normalized[$key] = trim($value);
            }
        }

        unset($normalized['password']);

        return $normalized;
    }

    private function validateField(string $field, mixed $value, array $rule): ?string
    {
        $type = $rule['type'] ?? 'string';

        if ($type === 'email' && is_string($value) && !Validator::validateEmail(trim($value))) {
            return 'Invalid email format';
        }

        if ($type === 'password' && is_string($value) && !Validator::validatePassword($value)) {
            return 'Password must be at least 8 characters and contain at least one number';
        }

        if ($type === 'string' && is_string($value)) {
            $min = $rule['min_length'] ?? 0;
            if (strlen(trim($value)) < $min) {
                return ucfirst(str_replace('_', ' ', $field)) . " must be at least {$min} characters";
            }
        }

        return null;
    }
}
