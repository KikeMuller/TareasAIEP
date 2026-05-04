<?php
/**
 * controllers/ComicController.php
 * Controlador de la sección Comics.
 *
 * Carga los comics desde el modelo Comic y los pasa
 * a la vista comics/index.php.
 *
 * Soporta filtrado por categoría mediante ?cat=nombre
 */

require_once CTRL_PATH . 'Controller.php';

class ComicController extends Controller
{
    /**
     * Mostrar la galería de comics de War and Peas.
     * Soporta filtrado opcional por categoría: ?page=comics&cat=humor negro
     */
    public function index(): void
    {
        $comicModel = new Comic();

        // Obtener todos los comics (demo sin BD, o desde BD si está activa)
        $todosLosComics = $comicModel->datosDemostracion();

        // Filtrar por categoría si se recibe el parámetro ?cat=
        $categoriaActiva = isset($_GET['cat']) ? trim($_GET['cat']) : '';
        if ($categoriaActiva !== '') {
            $comics = array_filter(
                $todosLosComics,
                fn($c) => strtolower($c['categoria']) === strtolower($categoriaActiva)
            );
            $comics = array_values($comics); // reindexar
        } else {
            $comics = $todosLosComics;
        }

        // Obtener lista de categorías únicas para el filtro
        $categorias = $comicModel->getCategorias($todosLosComics);

        $this->render('comics/index', [
            'titulo'          => 'Comics — El Faro',
            'comics'          => $comics,
            'categorias'      => $categorias,
            'categoriaActiva' => $categoriaActiva,
            'totalComics'     => count($todosLosComics),
        ]);
    }
}
