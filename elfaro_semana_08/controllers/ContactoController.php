<?php
/**
 * controllers/ContactoController.php
 * Controlador del formulario de contacto.
 *
 * Muestra el formulario (GET) y procesa el envío (POST).
 * Usa el modelo Contacto para validar y guardar el mensaje.
 */

require_once CTRL_PATH . 'Controller.php';

class ContactoController extends Controller
{
    /**
     * Mostrar el formulario de contacto o procesar el envío.
     */
    public function index(): void
    {
        $errores  = [];
        $exito    = false;
        $formData = [];

        if ($this->isPost()) {
            // Leer datos del formulario
            $nombre  = $this->post('nombre');
            $email   = $this->post('email');
            $mensaje = $this->post('mensaje');

            // Conservar para repoblar el formulario si hay errores
            $formData = compact('nombre', 'email', 'mensaje');

            // Validar con el modelo
            $contactoModel = new Contacto();

            if ($contactoModel->validar($nombre, $email, $mensaje)) {
                // Guardar el mensaje en la base de datos
                $contactoModel->guardar($nombre, $email, $mensaje);
                $exito    = true;
                $formData = [];   // Limpiar formulario tras éxito
            } else {
                $errores = $contactoModel->getErrores();
            }
        }

        $this->render('contacto/index', [
            'titulo'   => 'Contacto — El Faro',
            'errores'  => $errores,
            'exito'    => $exito,
            'formData' => $formData,
        ]);
    }
}
