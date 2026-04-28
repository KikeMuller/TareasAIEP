<?php
/**
 * models/Articulo.php
 * Entidad ARTÍCULO del sistema El Faro.
 *
 * Representa una noticia o artículo publicado en el periódico.
 * Permite crear, listar y filtrar artículos por sección.
 *
 * Tabla BD: articulos
 * Columnas: id, titulo, cuerpo, categoria, seccion, autor_id, fecha_publicacion, destacado
 */

require_once __DIR__ . '/Model.php';

class Articulo extends Model
{
    // ── Tabla asociada ────────────────────────────────────
    protected string $table = 'articulos';

    // ── Propiedades encapsuladas ──────────────────────────
    private int    $id               = 0;
    private string $titulo           = '';
    private string $cuerpo           = '';
    private string $categoria        = '';
    private string $seccion          = 'inicio';   // 'inicio' | 'deporte' | 'negocios'
    private int    $autorId          = 0;
    private string $fechaPublicacion = '';
    private bool   $destacado        = false;

    // ─────────────────────────────────────────────────────
    // GETTERS
    // ─────────────────────────────────────────────────────
    public function getId(): int               { return $this->id; }
    public function getTitulo(): string        { return $this->titulo; }
    public function getCuerpo(): string        { return $this->cuerpo; }
    public function getCategoria(): string     { return $this->categoria; }
    public function getSeccion(): string       { return $this->seccion; }
    public function getAutorId(): int          { return $this->autorId; }
    public function isDestacado(): bool        { return $this->destacado; }

    // ─────────────────────────────────────────────────────
    // MÉTODOS DE NEGOCIO
    // ─────────────────────────────────────────────────────

    /**
     * Publicar un nuevo artículo en la base de datos.
     *
     * @param string $titulo    Título del artículo
     * @param string $cuerpo    Texto completo del artículo
     * @param string $categoria Badge de categoría (Ej: "Fútbol", "Startup")
     * @param string $seccion   Sección destino: inicio | deporte | negocios
     * @param int    $autorId   ID del usuario redactor
     * @param bool   $destacado Si es artículo hero/principal
     * @return int ID del artículo creado
     */
    public function publicar(
        string $titulo,
        string $cuerpo,
        string $categoria,
        string $seccion,
        int    $autorId,
        bool   $destacado = false
    ): int {
        return $this->create([
            'titulo'    => $this->sanitize($titulo),
            'cuerpo'    => $this->sanitize($cuerpo),
            'categoria' => $this->sanitize($categoria),
            'seccion'   => $seccion,
            'autor_id'  => $autorId,
            'destacado' => (int) $destacado,
        ]);
    }

    /**
     * Listar artículos de una sección específica.
     * El primero devuelto puede usarse como artículo hero (destacado).
     *
     * @param string $seccion  'inicio' | 'deporte' | 'negocios'
     * @param int    $limite   Máximo de resultados
     * @return array
     */
    public function porSeccion(string $seccion, int $limite = 10): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.nombre AS autor_nombre
             FROM {$this->table} a
             LEFT JOIN usuarios u ON a.autor_id = u.id
             WHERE a.seccion = :seccion
             ORDER BY a.destacado DESC, a.fecha_publicacion DESC
             LIMIT :limite"
        );
        $stmt->bindValue(':seccion', $seccion, PDO::PARAM_STR);
        $stmt->bindValue(':limite',  $limite,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Listar todos los artículos para el panel de administración.
     * Incluye el nombre del autor y ordena por fecha descendente.
     *
     * @return array
     */
    public function listarTodos(): array
    {
        $stmt = $this->db->query(
            "SELECT a.*, u.nombre AS autor_nombre
             FROM {$this->table} a
             LEFT JOIN usuarios u ON a.autor_id = u.id
             ORDER BY a.fecha_publicacion DESC"
        );
        return $stmt->fetchAll();
    }

    /**
     * Actualizar un artículo existente en la base de datos.
     *
     * @param int    $id        ID del artículo a editar
     * @param string $titulo    Nuevo título
     * @param string $cuerpo    Nuevo cuerpo
     * @param string $categoria Nueva categoría
     * @param string $seccion   Nueva sección
     * @param bool   $destacado Si es artículo destacado
     * @return bool  true si se actualizó correctamente
     */
    public function actualizar(
        int    $id,
        string $titulo,
        string $cuerpo,
        string $categoria,
        string $seccion,
        bool   $destacado = false
    ): bool {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table}
             SET titulo    = :titulo,
                 cuerpo    = :cuerpo,
                 categoria = :categoria,
                 seccion   = :seccion,
                 destacado = :destacado
             WHERE id = :id"
        );
        return $stmt->execute([
            'titulo'    => $this->sanitize($titulo),
            'cuerpo'    => $this->sanitize($cuerpo),
            'categoria' => $this->sanitize($categoria),
            'seccion'   => $seccion,
            'destacado' => (int) $destacado,
            'id'        => $id,
        ]);
    }

    /**
     * Obtener artículos de muestra (datos hardcodeados).
     * Se usan cuando no hay base de datos disponible (demo).
     *
     * @param string $seccion
     * @return array
     */
    public function datosDemostracion(string $seccion): array
    {
        $datos = [
            'inicio' => [
                ['id'=>1,'titulo'=>'Senado aprueba histórica reforma al sistema de agua potable rural','cuerpo'=>'La Cámara Alta ratificó por 35 votos la modificación a la Ley de Agua Potable Rural. Beneficiará a más de 400 localidades con fondos de $18.000 millones anuales.','categoria'=>'Nacional','seccion'=>'inicio','destacado'=>1,'fecha_publicacion'=>'2026-03-24','autor_nombre'=>'Redacción El Faro'],
                ['id'=>2,'titulo'=>'Investigadores de la U. de Chile desarrollan vacuna nasal contra cepa gripal','cuerpo'=>'Vacuna intranasal con eficacia del 91% frente a la cepa H5N2. Publicada en The Lancet Infectious Diseases.','categoria'=>'Ciencia','seccion'=>'inicio','destacado'=>0,'fecha_publicacion'=>'2026-03-24','autor_nombre'=>'Redacción El Faro'],
                ['id'=>3,'titulo'=>'Concepción inaugura el primer corredor verde interurbano del país','cuerpo'=>'Ciclovía-parque de 18 km evaluada en $9.200 millones, con paneles solares e iluminación LED.','categoria'=>'Regional','seccion'=>'inicio','destacado'=>0,'fecha_publicacion'=>'2026-03-02','autor_nombre'=>'Redacción El Faro'],
            ],
            'deporte' => [
                ['id'=>4,'titulo'=>'Colo-Colo golea 3-0 a River Plate y sella pase a semis de la Libertadores','cuerpo'=>'Con dos goles de Bolados y uno de Amor, el Cacique aplastó a la banda cruzada ante 47.000 hinchas.','categoria'=>'Fútbol','seccion'=>'deporte','destacado'=>1,'fecha_publicacion'=>'2026-03-23','autor_nombre'=>'Redacción Deportes'],
                ['id'=>5,'titulo'=>'Tabilo avanza a cuartos del Masters 1000 de Miami','cuerpo'=>'El chileno superó a Rublev (4° ATP) con parciales de 7-5 y 6-4. En cuartos enfrentará a Alcaraz.','categoria'=>'Tenis','seccion'=>'deporte','destacado'=>0,'fecha_publicacion'=>'2026-03-24','autor_nombre'=>'Redacción Deportes'],
                ['id'=>6,'titulo'=>'La Roja Sub-20 clasifica al Mundial de Qatar 2027','cuerpo'=>'Chile aseguró su boleto venciendo 3-0 a Ecuador. Goles de Nicolás Pino y Darío Osorio Jr.','categoria'=>'Selección','seccion'=>'deporte','destacado'=>0,'fecha_publicacion'=>'2026-03-22','autor_nombre'=>'Redacción Deportes'],
            ],
            'negocios' => [
                ['id'=>7,'titulo'=>'Greenbite recauda US$12M para escalar plataforma de alimentación sostenible','cuerpo'=>'La startup chilena de foodtech cerró Serie A con Kaszek Ventures. Reduce el desperdicio en un 40%.','categoria'=>'Startups','seccion'=>'negocios','destacado'=>1,'fecha_publicacion'=>'2026-03-24','autor_nombre'=>'Redacción Negocios'],
                ['id'=>8,'titulo'=>'Precios de arriendo en Santiago caen 8% por primera vez en 5 años','cuerpo'=>'Baja en Providencia, Las Condes y Santiago Centro. Nuevas unidades y tasas hipotecarias más bajas.','categoria'=>'Inmobiliario','seccion'=>'negocios','destacado'=>0,'fecha_publicacion'=>'2026-03-23','autor_nombre'=>'Redacción Negocios'],
                ['id'=>9,'titulo'=>'SolarAndes firma PPA para abastecer el 30% de energía del norte','cuerpo'=>'Contrato de 20 años con el Ministerio de Energía para 1.800 GWh anuales. 2.400 empleos directos.','categoria'=>'Energía','seccion'=>'negocios','destacado'=>0,'fecha_publicacion'=>'2026-03-22','autor_nombre'=>'Redacción Negocios'],
            ],
        ];
        return $datos[$seccion] ?? [];
    }
}
