<?php

require_once __DIR__ . '/../core/database.php';
require_once __DIR__ . '/../core/TableSchema.php';

class UserRepository
{
    private ORM $orm;
    private TableSchema $schema;

    public function __construct(ORM $orm, TableSchema $schema)
    {
        $this->orm = $orm;
        $this->schema = $schema;
    }

    public function findByEmail(string $email): ?array
    {
        if (!$this->schema->hasColumn('email')) {
            return null;
        }

        $columns = $this->schema->getSelectableColumns();
        $sql = 'SELECT ' . implode(', ', $columns)
            . ' FROM ' . $this->schema->getTable()
            . ' WHERE email = :email';

        if ($this->schema->hasColumn('deleted_at')) {
            $sql .= ' AND deleted_at IS NULL';
        }

        $sql .= ' LIMIT 1';

        $rows = $this->orm->fetchAll($sql, ['email' => $email]);

        return $rows[0] ?? null;
    }

    public function create(array $data): int
    {
        unset($data['id']);

        $this->orm->insert($this->schema->getTable(), $data);

        return (int) $this->orm->conn->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        if (!$this->schema->hasColumn('id')) {
            return null;
        }

        $columns = $this->schema->getSelectableColumns();
        $sql = 'SELECT ' . implode(', ', $columns)
            . ' FROM ' . $this->schema->getTable()
            . ' WHERE id = :id LIMIT 1';

        $rows = $this->orm->fetchAll($sql, ['id' => $id]);

        $row = $rows[0] ?? null;

        return $row !== null ? $this->schema->filterResponse($row) : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findAll(int $limit = 50, int $offset = 0): array
    {
        $columns = $this->schema->getSelectableColumns();
        $sql = 'SELECT ' . implode(', ', $columns)
            . ' FROM ' . $this->schema->getTable();

        if ($this->schema->hasColumn('deleted_at')) {
            $sql .= ' WHERE deleted_at IS NULL';
        }

        if ($this->schema->hasColumn('created_at')) {
            $sql .= ' ORDER BY created_at DESC';
        } elseif ($this->schema->hasColumn('id')) {
            $sql .= ' ORDER BY id DESC';
        }

        $sql .= ' LIMIT :limit OFFSET :offset';

        $rows = $this->orm->fetchAll($sql, [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return array_map(
            fn(array $row) => $this->schema->filterResponse($row),
            $rows
        );
    }

    public function countAll(): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM ' . $this->schema->getTable();

        if ($this->schema->hasColumn('deleted_at')) {
            $sql .= ' WHERE deleted_at IS NULL';
        }

        $rows = $this->orm->fetchAll($sql);

        return (int) ($rows[0]['total'] ?? 0);
    }
}
