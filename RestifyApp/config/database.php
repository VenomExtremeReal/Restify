<?php
/**
 * Configuração do banco de dados SQLite
 */
class Database {
    private $db_path;
    private $conn;

    public function __construct() {
        $this->db_path = __DIR__ . '/../database/restify.db';
    }

    /**
     * Conecta ao banco de dados SQLite
     */
    public function connect() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("sqlite:" . $this->db_path);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch(PDOException $e) {
            echo "Erro de conexão: " . $e->getMessage();
        }
        
        return $this->conn;
    }

    /**
     * Criar tabelas se não existirem
     */
    private function createTables() {
        $sql = file_get_contents(__DIR__ . '/../database/schema.sql');
        $this->conn->exec($sql);
    }
}
?>