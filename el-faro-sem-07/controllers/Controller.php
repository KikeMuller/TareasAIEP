<?php
/**
 * controllers/Controller.php
 * Clase base abstracta para todos los controladores.
 *
 * Proporciona el método render() que carga el layout completo
 * (header + vista + footer) pasando datos a las vistas.
 */

abstract class Controller
{
    /**
     * Renderizar una vista dentro del layout principal.
     *
     * @param string $view   Ruta relativa a views/ (Ej: 'home/index')
     * @param array  $data   Variables a pasar a la vista
     */
    protected function render(string $view, array $data = []): void
    {
        // Extraer el array $data para que cada clave sea una variable
        // Ejemplo: ['titulo' => 'Inicio'] → $titulo disponible en la vista
        extract($data);

        // Cargar header (nav + head HTML)
        require_once VIEW_PATH . 'layouts/header.php';

        // Cargar la vista específica
        $viewFile = VIEW_PATH . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            echo "<p class='text-danger text-center py-5'>Vista no encontrada: {$view}</p>";
        }

        // Cargar footer
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    /**
     * Redirigir a otra página del sistema.
     * @param string $page  Parámetro de la URL (?page=...)
     */
    protected function redirect(string $page): void
    {
        header("Location: index.php?page={$page}");
        exit;
    }

    /**
     * Verificar si el usuario está autenticado.
     * Si no lo está, redirige al login.
     */
    protected function requireLogin(): void
    {
        if (empty($_SESSION['usuario'])) {
            $this->redirect('login');
        }
    }

    /**
     * Verificar si el usuario tiene uno de los roles permitidos.
     * Si no lo tiene, redirige al home con un mensaje de acceso denegado.
     *
     * @param array $roles Roles permitidos, ej: ['admin', 'editor']
     */
    protected function requireRole(array $roles): void
    {
        $tipoUsuario = $_SESSION['usuario']['tipo'] ?? '';
        if (!in_array($tipoUsuario, $roles, true)) {
            $_SESSION['error_acceso'] = 'No tienes permisos para acceder a esa sección.';
            $this->redirect('home');
        }
    }

    /**
     * @param string $key     Clave del campo del formulario
     * @param string $default Valor por defecto si no existe
     * @return string
     */
    protected function post(string $key, string $default = ''): string
    {
        return isset($_POST[$key])
            ? htmlspecialchars(trim($_POST[$key]), ENT_QUOTES, 'UTF-8')
            : $default;
    }

    /**
     * Verificar si la petición es POST.
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}
