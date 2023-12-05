<?php

namespace Modules\Ws\Controllers;


use App\Controllers\BaseController;
use Modules\Ws\Models\Ws_model;

use stdClass;
use Exception;
use DateTime;

class Historial extends BaseController
{

    private $ws_model;
    
    public function __construct()
    {
        date_default_timezone_set('America/Santiago');
        $this->ws_model = new Ws_model();
    }

    public function index()
    {
        $request = \Config\Services::request();

        if (!empty($request->getGet("f"))) {
            $direccion = $request->getGet("f");
            $direccion = strtolower($direccion);
            $check = method_exists($this, $direccion);
            if ($check) {
                call_user_func(array($this, $direccion));
            } else {
                // En caso de no encontrarse la función llamada, se obliga a actualizar
            }
        }
    }


    public function obtenerReservas() {
        // Recupera el ID del usuario desde la solicitud
        $idUsuario = $_GET['idUsuario'] ?? null;
        $tipoUsuario = $_GET['tipo_usuario'] ?? null;
        // Llama a la función del modelo para obtener las reservas del usuario
        // Verificar el tipo de usuario y obtener las reservas correspondientes desde el modelo
            $reservas = $this->ws_model->obtenerReservasPorUsuario($idUsuario, $tipoUsuario);

            // Comprobar si se obtuvieron reservas
            if ($reservas) {
                // Codificar los datos en JSON
                $response = json_encode(array(
                    'success' => true,
                    'message' => 'Reservas obtenidas correctamente',
                    'especialistas' => $reservas
                ));
            } else {
                $response = json_encode(array(
                    'success' => false,
                    'message' => 'No se encontraron reservas'
                ));
            }

            // Imprimir la respuesta JSON
            echo $response;
            exit();
        }
            
            







}