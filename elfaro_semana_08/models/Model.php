<?php
/**
 * models/Model.php
 * Clase base abstracta para todos los modelos del sistema.
 * Provee acceso compartido a la base de datos y métodos CRUD genéricos.
 *
 * Todos los modelos deben extender esta clase.
 */

require_once ROOT_PATH . 'config/Database.php';

abstract class Model
{
    /** @var PDO Conexión compartida entre todos los modelos */
    protected PDO $db;

    /** @var string Nombre de la tabla asociada al modelo (definida en cada subclase) */
    protected string $table = '';

    public function __construct()
    {
        // Obtener la conexión PDO desde el Singleton
        $this->db = Database::getInstance()->getConnection();
    }

    // ─────────────────────────────────────────────────────
    // MÉTODOS CRUD GENÉRICOS
    // Disponibles para todos los modelos que extiendan esta clase
    // ─────────────────────────────────────────────────────

    /**
     * Obtener todos los registros de la tabla.
     * @return array Lista de registros como arrays asociativos
     */
    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    /**
     * Buscar un registro por su ID primario.
     * @param int $id
     * @return array|false
     */
    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Insertar un nuevo registro en la tabla.
     * @param array $data Datos a insertar (columna => valor)
     * @return int ID del registro insertado
     */
    public function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Sanitizar un string para prevenir XSS.
     * Usar siempre antes de mostrar datos en las vistas.
     * @param string $value
     * @return string
     */
    protected function sanitize(string $value): string
    {
        return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
    }
}
