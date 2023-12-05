<?php

namespace Modules\Ws\Controllers;


use App\Controllers\BaseController;
use Modules\Ws\Models\Ws_model;

use stdClass;
use Exception;
use DateTime;

class Especialista extends BaseController
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


    public function obtenerEspecialistas() {
        
        // Llama a la función del modelo para obtener los especialistas
        $especialistas = $this->ws_model->obtenerEspecialistas();

        // Comprueba si se obtuvieron especialistas
        if ($especialistas) {
            // Codifica los datos en JSON
            $response = json_encode(array(
                'success' => true,
                'message' => 'Especialistas obtenidos correctamente',
                'especialistas' => $especialistas
            ));
        } else {
            $response = json_encode(array(
                'success' => false,
                'message' => 'No se encontraron especialistas'
            ));
        }

        // Imprime la respuesta JSON
        echo $response;
        exit();
    }


}

