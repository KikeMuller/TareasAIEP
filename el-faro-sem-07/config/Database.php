<?php
/**
 * config/Database.php
 * Clase de conexión a la base de datos.
 * Implementa el patrón Singleton para reutilizar la conexión PDO.
 */

class Database
{
    /** @var Database|null Instancia única (Singleton) */
    private static ?Database $instance = null;

    /** @var PDO Conexión PDO activa */
    private PDO $connection;

    /**
     * Constructor privado — solo se ejecuta una vez.
     * Crea la conexión PDO con los parámetros definidos en config.php.
     */
    private function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_NAME,
            DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // En producción, no mostrar detalles de la excepción
            die('Error de conexión a la base de datos: ' . $e->getMessage());
        }
    }

    /**
     * Obtener la instancia única del Singleton.
     * Si no existe, la crea; si ya existe, la devuelve.
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Devuelve el objeto PDO para ejecutar consultas.
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    // Prevenir clonación e instanciación externa
    private function __clone() {}
}
