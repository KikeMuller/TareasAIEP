<?php
/**
 * models/Contacto.php
 * Entidad CONTACTO del sistema El Faro.
 *
 * Almacena los mensajes enviados mediante el formulario de contacto.
 * Incluye validación de datos y protección básica contra spam.
 *
 * Tabla BD: contactos
 * Columnas: id, nombre, email, mensaje, fecha_envio, leido
 */

require_once __DIR__ . '/Model.php';

class Contacto extends Model
{
    protected string $table = 'contactos';

    // Propiedades del mensaje
    private string $nombre   = '';
    private string $email    = '';
    private string $mensaje  = '';
    private array  $errores  = [];

    // ─────────────────────────────────────────────────────
    // VALIDACIÓN
    // ─────────────────────────────────────────────────────

    /**
     * Validar los datos del formulario de contacto.
     * Rellena el array $errores si hay campos inválidos.
     *
     * @param string $nombre
     * @param string $email
     * @param string $mensaje
     * @return bool true si todos los campos son válidos
     */
    public function validar(string $nombre, string $email, string $mensaje): bool
    {
        $this->errores = [];

        // Validar nombre
        if (empty(trim($nombre))) {
            $this->errores['nombre'] = 'El nombre es obligatorio.';
        } elseif (strlen(trim($nombre)) < 2) {
            $this->errores['nombre'] = 'El nombre debe tener al menos 2 caracteres.';
        }

        // Validar email
        if (empty(trim($email))) {
            $this->errores['email'] = 'El correo electrónico es obligatorio.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errores['email'] = 'El formato del correo no es válido.';
        }

        // Validar mensaje
        if (empty(trim($mensaje))) {
            $this->errores['mensaje'] = 'El mensaje es obligatorio.';
        } elseif (strlen(trim($mensaje)) < 10) {
            $this->errores['mensaje'] = 'El mensaje debe tener al menos 10 caracteres.';
        }

        return empty($this->errores);
    }

    /**
     * Guardar un mensaje de contacto válido en la base de datos.
     *
     * @param string $nombre
     * @param string $email
     * @param string $mensaje
     * @return int ID del mensaje guardado
     */
    public function guardar(string $nombre, string $email, string $mensaje): int
    {
        return $this->create([
            'nombre'  => $this->sanitize($nombre),
            'email'   => strtolower(trim($email)),
            'mensaje' => $this->sanitize($mensaje),
            'leido'   => 0,
        ]);
    }

    /**
     * Obtener los errores de validación.
     * @return array
     */
    public function getErrores(): array
    {
        return $this->errores;
    }
}
