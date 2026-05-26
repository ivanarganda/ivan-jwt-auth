<?php

class Validator
{
  private static $requiredFieldsBody = [
    'register' => ['first_name', 'email', 'password'],
    'login' => ['email', 'password'],
    'refresh' => ['refresh_token'],
    'logout' => ['refresh_token'],
    'profile' => ['Authorization'],
  ];

  public static function getRequiredFields(string $endpoint): array
  {
    return self::$requiredFieldsBody[$endpoint] ?? [];
  }

  public static function validateRequestMethod(string $method): bool
  {
    return strtoupper($_SERVER['REQUEST_METHOD']) === strtoupper($method);
  }

  public static function getJsonBody(): ?array
  {
    $input = file_get_contents('php://input');
    if ($input === false || $input === '') {
      return null;
    }

    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      return null;
    }

    return is_array($data) ? $data : null;
  }

  public static function isEmptyBody(?array $data): bool
  {
    return $data === null || $data === [];
  }

  /**
   * @return string[] Field names that are missing or empty non-strings
   */
  public static function validateRequiredFields(array $data, array $fields): array
  {
    $missing = [];

    foreach ($fields as $field) {
      if (!array_key_exists($field, $data)) {
        $missing[] = $field;
        continue;
      }

      $value = $data[$field];
      if (!is_string($value) || trim($value) === '') {
        $missing[] = $field;
      }
    }

    return $missing;
  }

  public static function validateEmail(string $email): bool
  {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
  }

  public static function validatePassword(string $password): bool
  {
    return strlen($password) >= 8 && preg_match('/\d/', $password) === 1;
  }
}
