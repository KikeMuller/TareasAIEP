<?php
/**
 * controllers/HomeController.php
 * Controlador de la página de inicio.
 *
 * Carga los artículos de las tres secciones principales
 * y los envía a la vista home/index.php
 */

require_once CTRL_PATH . 'Controller.php';

class HomeController extends Controller
{
    /**
     * Acción principal: muestra la portada del periódico.
     * Carga artículos de las secciones inicio, deporte y negocios.
     */
    public function index(): void
    {
        // Instanciar el modelo de artículos
        $articuloModel = new Articulo();

        // Obtener artículos de cada sección
        // Se usa datosDemostracion() para funcionar sin BD activa
        $articulosInicio   = $articuloModel->datosDemostracion('inicio');
        $articulosDeporte  = $articuloModel->datosDemostracion('deporte');
        $articulosNegocios = $articuloModel->datosDemostracion('negocios');

        // Obtener planes de suscripción para el banner lateral
        $suscripcionModel = new Suscripcion();
        $planes = $suscripcionModel->getPlanes();

        // Renderizar la vista pasando los datos como variables
        $this->render('home/index', [
            'titulo'             => 'Portada — El Faro',
            'articulosInicio'    => $articulosInicio,
            'articulosDeporte'   => $articulosDeporte,
            'articulosNegocios'  => $articulosNegocios,
            'planes'             => $planes,
        ]);
    }
}
