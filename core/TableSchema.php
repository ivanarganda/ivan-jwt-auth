<?php

require_once __DIR__ . '/database.php';

class TableSchema
{
    private ORM $orm;
    private array $config;
    private ?array $columns = null;

    public function __construct(ORM $orm, array $config)
    {
        $this->orm = $orm;
        $this->config = $config;
    }

    public function getTable(): string
    {
        return $this->config['table'];
    }

    public function getColumns(): array
    {
        if ($this->columns === null) {
            $this->columns = $this->orm->getColumns($this->getTable());
        }

        return $this->columns;
    }

    /**
     * Configured required columns that are missing from the table.
     *
     * @return string[]
     */
    public function missingRequiredColumns(): array
    {
        return array_values(array_diff($this->config['required_columns'], $this->getColumns()));
    }

    /**
     * Fields the client must send in JSON (from config + actual columns).
     *
     * @return string[]
     */
    public function getClientRequiredFields(): array
    {
        $required = $this->config['required_columns'];
        $serverGenerated = $this->config['server_generated'];
        $withDefaults = array_keys($this->config['defaults']);
        $blocked = $this->config['blocked_input'];

        $clientFields = array_diff($required, $serverGenerated, $withDefaults, $blocked);

        return array_values(array_intersect($clientFields, $this->getColumns()));
    }

    /**
     * Optional fields the client may send if they exist in the table.
     *
     * @return string[]
     */
    public function getOptionalClientFields(): array
    {
        $blocked = array_merge(
            $this->config['blocked_input'],
            $this->config['required_columns'],
            $this->config['server_generated']
        );

        return array_values(array_diff($this->getColumns(), $blocked));
    }

    public function filterInput(array $data): array
    {
        $allowed = array_diff($this->getColumns(), $this->config['blocked_input']);

        return array_intersect_key($data, array_flip($allowed));
    }

    public function filterResponse(array $row): array
    {
        $hidden = $this->config['hidden_columns'];

        return array_diff_key($row, array_flip($hidden));
    }

    /**
     * @return string[]
     */
    public function getSelectableColumns(): array
    {
        return array_values(array_diff($this->getColumns(), $this->config['hidden_columns']));
    }

    public function hasColumn(string $column): bool
    {
        return in_array($column, $this->getColumns(), true);
    }
}
