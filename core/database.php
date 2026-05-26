<?php 

require_once '../config/db.php';

class ORM {

    public $conn;

    public function __construct() {

        $this->conn = (new Database())->getConnection();

    }

    public function query($sql, $params = []) {

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;

    }

    public function execute($sql, $params = []) {

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);

    }

    public function fetchAll($sql, $params = []) {

        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getColumns($table, $columns = []){

        $rows = $this->fetchAll("DESCRIBE $table");
        $cols = [];

        foreach ($rows as $row) {
            $cols[] = $row['Field'];
        }

        if (!empty($columns)) {
            $cols = array_intersect($cols, $columns);
        }

        return $cols;

    }

    public function insert($table, $data) {

        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        return $this->execute($sql, $data);

    }
    
}