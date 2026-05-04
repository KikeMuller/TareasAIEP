<?php
/**
 * controllers/HomeController.php
 * Controlador de la página de inicio.
 *
 * RF-B: Presenta artículos por sección desde la base de datos.
 * Si la BD está vacía o no disponible, usa datosDemostracion()
 * como fallback para que el sitio siempre tenga contenido visible.
 */

require_once CTRL_PATH . 'Controller.php';

class HomeController extends Controller
{
    public function index(): void
    {
        $articuloModel    = new Articulo();
        $suscripcionModel = new Suscripcion();

        // ── RF-B: Cargar artículos desde la BD ───────────────
        // porSeccion() ejecuta SELECT con JOIN a usuarios
        // Si la BD devuelve resultados, los usa.
        // Si la tabla está vacía, cae al fallback con datos demo.
        $articulosInicio   = $this->cargarSeccion($articuloModel, 'inicio');
        $articulosDeporte  = $this->cargarSeccion($articuloModel, 'deporte');
        $articulosNegocios = $this->cargarSeccion($articuloModel, 'negocios');

        $planes = $suscripcionModel->getPlanes();

        $this->render('home/index', [
            'titulo'             => 'Portada — El Faro',
            'articulosInicio'    => $articulosInicio,
            'articulosDeporte'   => $articulosDeporte,
            'articulosNegocios'  => $articulosNegocios,
            'planes'             => $planes,
        ]);
    }

    /**
     * Intentar cargar artículos de una sección desde la BD.
     * Si la BD no tiene registros para esa sección, retorna
     * los datos de demostración para que el sitio no quede vacío.
     *
     * @param Articulo $model   Instancia del modelo
     * @param string   $seccion 'inicio' | 'deporte' | 'negocios'
     * @return array
     */
    private function cargarSeccion(Articulo $model, string $seccion): array
    {
        try {
            // Intenta leer desde la BD
            $articulos = $model->porSeccion($seccion, 10);

            // Si hay datos reales en la BD, los devuelve
            if (!empty($articulos)) {
                return $articulos;
            }

            // BD vacía para esta sección → usar datos demo
            return $model->datosDemostracion($seccion);

        } catch (\Throwable $e) {
            // Error de conexión u otro problema → usar datos demo
            return $model->datosDemostracion($seccion);
        }
    }
}
