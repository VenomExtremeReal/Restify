<?php
/**
 * Configuração do banco de dados SQLite - Padrão Singleton
 */
class Database {
    private static $instance = null;
    private $conn;
    private $db_path;

    private function __construct() {
        $this->db_path = __DIR__ . '/../database/restify.db';
    }

    /**
     * Singleton - Obter instância única
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Conecta ao banco de dados SQLite
     */
    public function connect() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO("sqlite:" . $this->db_path);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->createTables();
            } catch(PDOException $e) {
                throw new Exception("Erro de conexão: " . $e->getMessage());
            }
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

    // Prevenir clonagem
    private function __clone() {}
    
    // Prevenir deserialização
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
?>