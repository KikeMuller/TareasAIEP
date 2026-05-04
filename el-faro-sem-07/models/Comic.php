<?php
/**
 * models/Comic.php
 * Entidad COMIC del sistema El Faro.
 *
 * Gestiona los comics de humor editorial que el periódico
 * publica como contenido de entretenimiento para sus lectores.
 *
 * Fuente externa: War and Peas (warandpeas.com)
 * Los comics se referencian con URL de imagen y enlace al original.
 * No se almacenan imágenes localmente — se vincula al sitio fuente.
 *
 * Tabla BD (opcional): comics
 * Columnas: id, titulo, imagen_url, enlace_original, descripcion,
 *           fecha_publicacion, categoria, activo
 */

require_once __DIR__ . '/Model.php';

class Comic extends Model
{
    // ── Tabla asociada ────────────────────────────────────
    protected string $table = 'comics';

    // ── Propiedades encapsuladas ──────────────────────────
    private int    $id               = 0;
    private string $titulo           = '';
    private string $imagenUrl        = '';
    private string $enlaceOriginal   = '';
    private string $descripcion      = '';
    private string $categoria        = 'humor';
    private string $fechaPublicacion = '';
    private bool   $activo           = true;

    // ─────────────────────────────────────────────────────
    // GETTERS
    // ─────────────────────────────────────────────────────
    public function getId(): int             { return $this->id; }
    public function getTitulo(): string      { return $this->titulo; }
    public function getImagenUrl(): string   { return $this->imagenUrl; }
    public function getEnlace(): string      { return $this->enlaceOriginal; }
    public function getDescripcion(): string { return $this->descripcion; }
    public function getCategoria(): string   { return $this->categoria; }
    public function isActivo(): bool         { return $this->activo; }

    // ─────────────────────────────────────────────────────
    // MÉTODOS DE NEGOCIO
    // ─────────────────────────────────────────────────────

    /**
     * Guardar referencia a un comic externo en la base de datos.
     *
     * @param string $titulo          Título del comic
     * @param string $imagenUrl       URL directa a la imagen del comic
     * @param string $enlaceOriginal  URL al post original en warandpeas.com
     * @param string $descripcion     Descripción / alt text del comic
     * @param string $categoria       Categoría del humor (absurdo, negro, etc.)
     * @return int ID del comic guardado
     */
    public function guardar(
        string $titulo,
        string $imagenUrl,
        string $enlaceOriginal,
        string $descripcion = '',
        string $categoria   = 'humor'
    ): int {
        return $this->create([
            'titulo'           => $this->sanitize($titulo),
            'imagen_url'       => $imagenUrl,
            'enlace_original'  => $enlaceOriginal,
            'descripcion'      => $this->sanitize($descripcion),
            'categoria'        => $this->sanitize($categoria),
            'activo'           => 1,
        ]);
    }

    /**
     * Obtener comics activos ordenados por fecha descendente.
     *
     * @param int $limite Máximo de resultados
     * @return array
     */
    public function listar(int $limite = 12): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table}
             WHERE activo = 1
             ORDER BY fecha_publicacion DESC
             LIMIT :limite"
        );
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener comics de demostración con referencias reales a War and Peas.
     * Se usan cuando la base de datos no está disponible.
     *
     * Fuente: https://warandpeas.com/
     * Créditos: Elizabeth Pich & Jonathan Kunz — War and Peas
     *
     * @return array Lista de comics con metadatos
     */
    public function datosDemostracion(): array
    {
        return [
            [
                'id'               => 1,
                'titulo'           => 'Artefacts',
                'imagen_url'       => 'https://warandpeas.com/wp-content/uploads/2026/04/war-and-peas-artefacts-768x960.jpg',
                'enlace_original'  => 'https://warandpeas.com/2026/04/19/artefacts/',
                'descripcion'      => 'Dos astronautas alienígenas descubren un artefacto humano llamado "Internet" y encuentran un video de un gato. El comandante llora de emoción.',
                'categoria'        => 'Ciencia ficción',
                'fecha_publicacion'=> 'April 19, 2026',
                'tags'             => ['aliens', 'humor absurdo', 'internet', 'post-apocalíptico'],
            ],
            [
                'id'               => 2,
                'titulo'           => 'No Work Today',
                'imagen_url'       => 'https://warandpeas.com/wp-content/uploads/2026/04/war-and-peas-no-work-today-768x960.jpg',
                'enlace_original'  => 'https://warandpeas.com/2026/04/12/no-work-today/',
                'descripcion'      => 'Un castor se despide de su familia para ir al trabajo, llega al bosque y descubre que los humanos construyeron una represa gigante. Solicita subsidio de cesantía.',
                'categoria'        => 'Humor negro',
                'fecha_publicacion'=> 'April 12, 2026',
                'tags'             => ['castor', 'medioambiente', 'humor negro', 'trabajo'],
            ],
            [
                'id'               => 3,
                'titulo'           => 'Still Pissed',
                'imagen_url'       => 'https://warandpeas.com/wp-content/uploads/2026/04/war-and-peas-still-pissed-768x960.jpg',
                'enlace_original'  => 'https://warandpeas.com/2026/04/05/still-pissed/',
                'descripcion'      => 'Jesús en terapia. El terapeuta intenta ayudarlo a superar su "fantasía de resurrección" mientras él sigue enojado por lo que le hicieron.',
                'categoria'        => 'Humor existencial',
                'fecha_publicacion'=> 'April 5, 2026',
                'tags'             => ['existencial', 'terapia', 'humor filosófico'],
            ],
            [
                'id'               => 4,
                'titulo'           => 'The Mistake',
                'imagen_url'       => 'https://warandpeas.com/wp-content/uploads/2026/03/war-and-peas-the-mistake-768x960.jpg',
                'enlace_original'  => 'https://warandpeas.com/2026/03/29/the-mistake/',
                'descripcion'      => 'Un personaje comete un error pequeño y su cerebro lo hace revivir durante décadas. Un retrato muy preciso de la ansiedad humana.',
                'categoria'        => 'Humor cotidiano',
                'fecha_publicacion'=> 'March 29, 2026',
                'tags'             => ['ansiedad', 'cotidiano', 'humor'],
            ],
            [
                'id'               => 5,
                'titulo'           => 'Sustainable',
                'imagen_url'       => 'https://warandpeas.com/wp-content/uploads/2026/03/war-and-peas-sustainable-768x960.jpg',
                'enlace_original'  => 'https://warandpeas.com/2026/03/22/sustainable/',
                'descripcion'      => 'Una reflexión irónica sobre la sostenibilidad y el consumismo moderno. Las contradicciones del "vivir verde" en un mundo capitalista.',
                'categoria'        => 'Crítica social',
                'fecha_publicacion'=> 'March 22, 2026',
                'tags'             => ['sostenibilidad', 'crítica social', 'medioambiente'],
            ],
            [
                'id'               => 6,
                'titulo'           => 'The Upgrade',
                'imagen_url'       => 'https://warandpeas.com/wp-content/uploads/2026/03/war-and-peas-the-upgrade-768x960.jpg',
                'enlace_original'  => 'https://warandpeas.com/2026/03/15/the-upgrade/',
                'descripcion'      => 'La mejora tecnológica que nadie pidió pero que todos terminan usando. Reflexión cómica sobre la adopción forzosa de nueva tecnología.',
                'categoria'        => 'Tecnología',
                'fecha_publicacion'=> 'March 15, 2026',
                'tags'             => ['tecnología', 'humor', 'actualidad'],
            ],
        ];
    }

    /**
     * Obtener las categorías únicas disponibles.
     * @param array $comics Lista de comics
     * @return array Lista de categorías únicas
     */
    public function getCategorias(array $comics): array
    {
        $cats = array_unique(array_column($comics, 'categoria'));
        sort($cats);
        return $cats;
    }
}
