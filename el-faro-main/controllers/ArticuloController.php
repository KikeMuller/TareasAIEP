<?php
/**
 * controllers/ArticuloController.php
 * Controlador de artículos del periódico.
 *
 * Métodos:
 *   index()   → Formulario para publicar nuevo artículo
 *   listar()  → Lista todos los artículos con botones editar
 *   editar()  → Formulario pre-relleno para editar un artículo existente
 */

require_once CTRL_PATH . 'Controller.php';

class ArticuloController extends Controller
{
    /**
     * Formulario para publicar un nuevo artículo (solo editores/admins).
     */
    public function index(): void
    {
        $this->requireLogin();
        $this->requireRole(['editor', 'admin']);

        $errores = [];
        $exito   = false;

        if ($this->isPost()) {
            $titulo    = $this->post('titulo');
            $cuerpo    = $this->post('cuerpo');
            $categoria = $this->post('categoria');
            $seccion   = $this->post('seccion', 'inicio');
            $destacado = isset($_POST['destacado']) ? 1 : 0;

            if (empty($titulo)) { $errores['titulo'] = 'El título es obligatorio.'; }
            if (empty($cuerpo))  { $errores['cuerpo'] = 'El cuerpo del artículo es obligatorio.'; }

            if (empty($errores)) {
                $articuloModel = new Articulo();
                $articuloModel->publicar(
                    $titulo, $cuerpo, $categoria, $seccion,
                    (int) $_SESSION['usuario']['id'],
                    (bool) $destacado
                );
                $exito = true;
            }
        }

        $this->render('articulos/nuevo', [
            'titulo'  => 'Publicar artículo — El Faro',
            'errores' => $errores,
            'exito'   => $exito,
        ]);
    }

    /**
     * Listar todos los artículos para el panel de administración.
     * Muestra una tabla con botones de editar por cada artículo.
     */
    public function listar(): void
    {
        $this->requireLogin();
        $this->requireRole(['editor', 'admin']);

        $articuloModel = new Articulo();
        $articulos     = $articuloModel->listarTodos();

        $this->render('articulos/lista', [
            'titulo'    => 'Gestionar artículos — El Faro',
            'articulos' => $articulos,
        ]);
    }

    /**
     * Editar un artículo existente.
     * GET  → Carga el formulario pre-relleno con los datos actuales.
     * POST → Guarda los cambios en la base de datos.
     *
     * Requiere el parámetro ?id=N en la URL.
     */
    public function editar(): void
    {
        $this->requireLogin();
        $this->requireRole(['editor', 'admin']);

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            $this->redirect('articulos_lista');
        }

        $articuloModel = new Articulo();
        $articulo      = $articuloModel->find($id);

        // Si no existe el artículo, volver al listado
        if (!$articulo) {
            $_SESSION['error_acceso'] = 'El artículo solicitado no existe.';
            $this->redirect('articulos_lista');
        }

        $errores = [];
        $exito   = false;

        if ($this->isPost()) {
            $titulo    = $this->post('titulo');
            $cuerpo    = $this->post('cuerpo');
            $categoria = $this->post('categoria');
            $seccion   = $this->post('seccion', 'inicio');
            $destacado = isset($_POST['destacado']);

            if (empty($titulo)) { $errores['titulo'] = 'El título es obligatorio.'; }
            if (empty($cuerpo))  { $errores['cuerpo'] = 'El cuerpo del artículo es obligatorio.'; }

            if (empty($errores)) {
                $articuloModel->actualizar(
                    $id, $titulo, $cuerpo, $categoria, $seccion, $destacado
                );
                $exito    = true;
                // Recargar los datos actualizados para mostrarlos en el form
                $articulo = $articuloModel->find($id);
            }
        }

        $this->render('articulos/editar', [
            'titulo'   => 'Editar artículo — El Faro',
            'articulo' => $articulo,
            'errores'  => $errores,
            'exito'    => $exito,
        ]);
    }
}
