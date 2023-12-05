<?php

namespace Modules\Ws\Controllers;


use App\Controllers\BaseController;
use Modules\Ws\Models\Ws_model;

use stdClass;
use Exception;
use DateTime;

class Comuna extends BaseController
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

    public function obtenerComunas() {
        $postdata = json_decode(file_get_contents('php://input'));
    
        if ($postdata) {
            $id_region = $postdata->id_region;
            $Comunas = $this->ws_model->obtenerComunas($id_region);
    
            if ($Comunas !== null) {
                // Verificamos si $Comunas no es nulo antes de contar
                $numComunas = is_array($Comunas) ? count($Comunas) : 0;
    
                if ($numComunas > 0) {
                    $response = json_encode(array(
                        'success' => true,
                        'message' => 'Comunas obtenidas correctamente',
                        'Comunas' => $Comunas
                    ));
                } else {
                    $response = json_encode(array(
                        'success' => false,
                        'message' => 'No se encontraron comunas'
                    ));
                }
            } else {
                // Manejo de error si $Comunas es nulo
                $response = json_encode(array(
                    'success' => false,
                    'message' => 'Error al obtener comunas'
                ));
            }
    
            echo $response;
            exit();
        } else {
            // Si los datos no están presentes o son nulos
            echo json_encode(array(
                'success' => false,
                'message' => 'Fallo la transaccion',
            ));
            exit();
        }
    }
    
   
}
