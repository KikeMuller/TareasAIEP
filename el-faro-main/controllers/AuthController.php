<?php
/**
 * controllers/AuthController.php
 * Controlador de autenticación.
 *
 * Gestiona:
 *   - login()    → mostrar formulario + procesar inicio de sesión
 *   - register() → mostrar formulario + procesar registro de cuenta
 *   - logout()   → cerrar sesión
 */

require_once CTRL_PATH . 'Controller.php';

class AuthController extends Controller
{
    // ─────────────────────────────────────────────────────
    // LOGIN
    // ─────────────────────────────────────────────────────

    /**
     * Mostrar el formulario de inicio de sesión (GET)
     * o procesar las credenciales enviadas (POST).
     */
    public function login(): void
    {
        $error = '';

        // Si ya hay sesión activa, redirigir al home
        if (!empty($_SESSION['usuario'])) {
            $this->redirect('home');
        }

        // Procesar el formulario si la petición es POST
        if ($this->isPost()) {
            $email    = $this->post('email');
            $password = $this->post('password');

            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->autenticar($email, $password);

            if ($usuario) {
                // Guardar datos mínimos en sesión (nunca el hash)
                $_SESSION['usuario'] = [
                    'id'     => $usuario['id'],
                    'nombre' => $usuario['nombre'],
                    'email'  => $usuario['email'],
                    'tipo'   => $usuario['tipo'],
                ];
                $this->redirect('home');
            } else {
                $error = 'Correo o contraseña incorrectos. Intenta nuevamente.';
            }
        }

        // Renderizar la vista de login con el posible mensaje de error
        $this->render('auth/login', [
            'titulo' => 'Iniciar sesión — El Faro',
            'error'  => $error,
        ]);
    }

    // ─────────────────────────────────────────────────────
    // REGISTRO
    // ─────────────────────────────────────────────────────

    /**
     * Mostrar el formulario de registro (GET)
     * o procesar la creación de cuenta (POST).
     */
    public function register(): void
    {
        $errores  = [];
        $exito    = false;
        $formData = []; // Para repoblar el formulario si hay errores

        if ($this->isPost()) {
            // Leer y sanitizar datos del formulario
            $nombre   = $this->post('nombre');
            $email    = $this->post('email');
            $password = $this->post('password');
            $confirma = $this->post('confirma_password');
            $plan     = $this->post('plan', 'gratuito');

            // Guardar datos para repoblar el form
            $formData = compact('nombre', 'email', 'plan');

            // ── Validaciones ──────────────────────────────
            if (empty($nombre) || strlen($nombre) < 2) {
                $errores['nombre'] = 'El nombre debe tener al menos 2 caracteres.';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores['email'] = 'El correo electrónico no es válido.';
            }

            if (strlen($password) < 8) {
                $errores['password'] = 'La contraseña debe tener mínimo 8 caracteres.';
            }

            if ($password !== $confirma) {
                $errores['confirma'] = 'Las contraseñas no coinciden.';
            }

            // ── Registrar si no hay errores de validación ─
            if (empty($errores)) {
                $usuarioModel     = new Usuario();
                $suscripcionModel = new Suscripcion();

                $nuevoId = $usuarioModel->registrar($nombre, $email, $password);

                if ($nuevoId > 0) {
                    // Crear suscripción inicial según el plan elegido
                    $suscripcionModel->suscribir($nuevoId, $plan);

                    // Iniciar sesión automáticamente tras el registro
                    $_SESSION['usuario'] = [
                        'id'     => $nuevoId,
                        'nombre' => $nombre,
                        'email'  => $email,
                        'tipo'   => 'lector',
                    ];
                    $exito = true;
                } else {
                    $errores['email'] = 'Este correo ya está registrado.';
                }
            }
        }

        // Obtener planes para mostrar en el formulario
        $planes = (new Suscripcion())->getPlanes();

        $this->render('auth/register', [
            'titulo'   => 'Crear cuenta — El Faro',
            'errores'  => $errores,
            'exito'    => $exito,
            'formData' => $formData,
            'planes'   => $planes,
        ]);
    }

    // ─────────────────────────────────────────────────────
    // CAMBIAR CONTRASEÑA
    // ─────────────────────────────────────────────────────

    /**
     * Mostrar formulario de cambio de contraseña (GET)
     * o procesar el cambio (POST).
     * Requiere sesión activa.
     */
    public function cambiarPassword(): void
    {
        $this->requireLogin();

        $errores = [];
        $exito   = false;

        if ($this->isPost()) {
            $actual    = $this->post('password_actual');
            $nueva     = $this->post('password_nueva');
            $confirma  = $this->post('password_confirma');

            // Validaciones
            if (empty($actual)) {
                $errores['actual'] = 'Debes ingresar tu contraseña actual.';
            }
            if (strlen($nueva) < 8) {
                $errores['nueva'] = 'La nueva contraseña debe tener mínimo 8 caracteres.';
            }
            if ($nueva !== $confirma) {
                $errores['confirma'] = 'Las contraseñas no coinciden.';
            }
            if (!empty($nueva) && $nueva === $actual) {
                $errores['nueva'] = 'La nueva contraseña debe ser distinta a la actual.';
            }

            if (empty($errores)) {
                $usuarioModel = new Usuario();
                $ok = $usuarioModel->cambiarPassword(
                    (int) $_SESSION['usuario']['id'],
                    $actual,
                    $nueva
                );

                if ($ok) {
                    $exito = true;
                } else {
                    $errores['actual'] = 'La contraseña actual es incorrecta.';
                }
            }
        }

        $this->render('auth/cambiar_password', [
            'titulo'  => 'Cambiar contraseña — El Faro',
            'errores' => $errores,
            'exito'   => $exito,
        ]);
    }

    // ─────────────────────────────────────────────────────
    // LOGOUT
    // ─────────────────────────────────────────────────────

    /**
     * Cerrar la sesión del usuario y redirigir al home.
     */
    public function logout(): void
    {
        session_unset();
        session_destroy();
        $this->redirect('home');
    }
}
