<?php
/**
 * models/Usuario.php
 * Entidad USUARIO del sistema El Faro.
 *
 * Gestiona el registro, autenticación y datos de cuenta
 * de los lectores/suscriptores del periódico.
 *
 * Tabla BD: usuarios
 * Columnas: id, nombre, email, password_hash, tipo, fecha_registro, activo
 */

require_once __DIR__ . '/Model.php';

class Usuario extends Model
{
    // ── Tabla asociada ────────────────────────────────────
    protected string $table = 'usuarios';

    // ── Propiedades de la entidad (encapsuladas) ──────────
    private int    $id            = 0;
    private string $nombre        = '';
    private string $email         = '';
    private string $passwordHash  = '';
    private string $tipo          = 'lector';    // 'lector' | 'editor' | 'admin'
    private string $fechaRegistro = '';
    private bool   $activo        = true;

    // ─────────────────────────────────────────────────────
    // GETTERS
    // ─────────────────────────────────────────────────────
    public function getId(): int            { return $this->id; }
    public function getNombre(): string     { return $this->nombre; }
    public function getEmail(): string      { return $this->email; }
    public function getTipo(): string       { return $this->tipo; }
    public function getFechaRegistro(): string { return $this->fechaRegistro; }
    public function isActivo(): bool        { return $this->activo; }

    // ─────────────────────────────────────────────────────
    // SETTERS (con validación básica)
    // ─────────────────────────────────────────────────────
    public function setNombre(string $nombre): void
    {
        $this->nombre = $this->sanitize($nombre);
    }

    public function setEmail(string $email): void
    {
        // Validar formato de email antes de asignar
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('El email no tiene un formato válido.');
        }
        $this->email = strtolower(trim($email));
    }

    // ─────────────────────────────────────────────────────
    // MÉTODOS DE NEGOCIO
    // ─────────────────────────────────────────────────────

    /**
     * Registrar un nuevo usuario en la base de datos.
     * Hashea la contraseña con password_hash() antes de guardar.
     *
     * @param string $nombre   Nombre completo
     * @param string $email    Correo único
     * @param string $password Contraseña en texto plano (se hashea)
     * @param string $tipo     Tipo de usuario (lector por defecto)
     * @return int ID del usuario creado, 0 si hubo error
     */
    public function registrar(
        string $nombre,
        string $email,
        string $password,
        string $tipo = 'lector'
    ): int {
        // Verificar que el email no esté en uso
        if ($this->buscarPorEmail($email)) {
            return 0;   // Email ya existe → registro fallido
        }

        // Hashear la contraseña con el algoritmo recomendado por PHP
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar en la tabla usando el método create() del modelo base
        return $this->create([
            'nombre'        => $this->sanitize($nombre),
            'email'         => strtolower(trim($email)),
            'password_hash' => $hash,
            'tipo'          => $tipo,
            'activo'        => 1,
        ]);
    }

    /**
     * Verificar credenciales para el inicio de sesión.
     *
     * @param string $email    Email del usuario
     * @param string $password Contraseña en texto plano
     * @return array|false Datos del usuario si es válido, false si no
     */
    public function autenticar(string $email, string $password): array|false
    {
        $usuario = $this->buscarPorEmail($email);

        if (!$usuario) {
            return false;   // Usuario no encontrado
        }

        // Verificar contraseña contra el hash almacenado
        if (!password_verify($password, $usuario['password_hash'])) {
            return false;   // Contraseña incorrecta
        }

        if (!$usuario['activo']) {
            return false;   // Cuenta desactivada
        }

        return $usuario;
    }

    /**
     * Buscar un usuario por su dirección de correo electrónico.
     *
     * @param string $email
     * @return array|false
     */
    public function buscarPorEmail(string $email): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1"
        );
        $stmt->execute(['email' => strtolower(trim($email))]);
        return $stmt->fetch();
    }

    /**
     * Cambiar la contraseña de un usuario.
     * Verifica la contraseña actual antes de guardar la nueva.
     *
     * @param int    $id          ID del usuario
     * @param string $passwordActual Contraseña actual en texto plano
     * @param string $passwordNueva  Nueva contraseña en texto plano
     * @return bool true si se cambió correctamente, false si la actual no coincide
     */
    public function cambiarPassword(int $id, string $passwordActual, string $passwordNueva): bool
    {
        // Obtener el hash actual desde la BD
        $stmt = $this->db->prepare(
            "SELECT password_hash FROM {$this->table} WHERE id = :id LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return false;

        // Verificar que la contraseña actual sea correcta
        if (!password_verify($passwordActual, $row['password_hash'])) {
            return false;
        }

        // Hashear la nueva contraseña y guardarla
        $nuevoHash = password_hash($passwordNueva, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET password_hash = :hash WHERE id = :id"
        );
        return $stmt->execute(['hash' => $nuevoHash, 'id' => $id]);
    }

    /**
     * Obtener todos los usuarios activos del sistema.
     * @return array
     */
    public function listarActivos(): array
    {
        $stmt = $this->db->query(
            "SELECT id, nombre, email, tipo, fecha_registro
             FROM {$this->table}
             WHERE activo = 1
             ORDER BY fecha_registro DESC"
        );
        return $stmt->fetchAll();
    }
}
