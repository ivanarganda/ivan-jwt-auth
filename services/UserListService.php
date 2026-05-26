<?php

require_once __DIR__ . '/../repositories/UserRepository.php';

class UserListService
{
    private const MAX_LIMIT = 100;
    private const DEFAULT_LIMIT = 50;

    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @return array{success: bool, status: int, message: string, data?: array, meta?: array, errors?: array}
     */
    public function listUsers(int $limit, int $offset): array
    {
        if ($limit < 1 || $limit > self::MAX_LIMIT) {
            return [
                'success' => false,
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => [
                    'limit' => 'Limit must be between 1 and ' . self::MAX_LIMIT,
                ],
            ];
        }

        if ($offset < 0) {
            return [
                'success' => false,
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => [
                    'offset' => 'Offset must be zero or greater',
                ],
            ];
        }

        $users = $this->users->findAll($limit, $offset);
        $total = $this->users->countAll();

        return [
            'success' => true,
            'status' => 200,
            'message' => 'Users retrieved successfully',
            'data' => $users,
            'meta' => [
                'total' => $total,
                'count' => count($users),
                'limit' => $limit,
                'offset' => $offset,
            ],
        ];
    }

    public static function defaultLimit(): int
    {
        return self::DEFAULT_LIMIT;
    }
}
