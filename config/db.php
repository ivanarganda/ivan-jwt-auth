<?php 

require_once 'parameters.php';

class Database {
    private $host;
    private $dbname;
    private $charset;
    private $port;
    private $user;
    private $pass;

    public function __construct() {
        $this->host = DB_HOST;
        $this->dbname = DB_NAME;
        $this->charset = DB_CHARSET;
        $this->port = DB_PORT;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
    }

    public function getConnection() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset};port={$this->port}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            return new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

}