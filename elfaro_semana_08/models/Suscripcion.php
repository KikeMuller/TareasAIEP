<?php
/**
 * models/Suscripcion.php
 * Entidad SUSCRIPCIÓN del sistema El Faro.
 *
 * Gestiona los planes de acceso al diario virtual:
 * gratuito, básico y premium.
 *
 * Tabla BD: suscripciones
 * Columnas: id, usuario_id, plan, fecha_inicio, fecha_fin, activa
 */

require_once __DIR__ . '/Model.php';

class Suscripcion extends Model
{
    protected string $table = 'suscripciones';

    // Planes disponibles y sus precios mensuales (CLP)
    public const PLANES = [
        'gratuito' => ['nombre' => 'Gratuito',  'precio' => 0,      'descripcion' => 'Acceso a noticias generales'],
        'basico'   => ['nombre' => 'Básico',    'precio' => 4990,   'descripcion' => 'Acceso completo sin publicidad'],
        'premium'  => ['nombre' => 'Premium',   'precio' => 9990,   'descripcion' => 'Acceso total + archivo histórico'],
    ];

    /**
     * Crear una suscripción para un usuario.
     *
     * @param int    $usuarioId ID del usuario
     * @param string $plan      'gratuito' | 'basico' | 'premium'
     * @return int ID de la suscripción creada
     */
    public function suscribir(int $usuarioId, string $plan = 'gratuito'): int
    {
        $inicio = date('Y-m-d');
        // El plan gratuito no expira; los otros duran 30 días
        $fin = ($plan === 'gratuito') ? null : date('Y-m-d', strtotime('+30 days'));

        return $this->create([
            'usuario_id'  => $usuarioId,
            'plan'        => $plan,
            'fecha_inicio'=> $inicio,
            'fecha_fin'   => $fin,
            'activa'      => 1,
        ]);
    }

    /**
     * Obtener la suscripción activa de un usuario.
     *
     * @param int $usuarioId
     * @return array|false
     */
    public function obtenerActiva(int $usuarioId): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table}
             WHERE usuario_id = :uid AND activa = 1
             ORDER BY fecha_inicio DESC LIMIT 1"
        );
        $stmt->execute(['uid' => $usuarioId]);
        return $stmt->fetch();
    }

    /**
     * Retornar la lista de planes disponibles.
     */
    public function getPlanes(): array
    {
        return self::PLANES;
    }
}
