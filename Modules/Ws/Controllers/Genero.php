<?php

namespace Modules\Ws\Controllers;


use App\Controllers\BaseController;
use Modules\Ws\Models\Ws_model;

use stdClass;
use Exception;
use DateTime;

class Genero extends BaseController
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

    function obtenerGeneros() {
        try{
            $generos = $this->ws_model->obtenerGeneros();

            if(count($generos) > 0){
            
                $response = json_encode(array(
                    'success' => true,
                    'message' => 'Generos obtenidos correctamente',
                    'generos' => $generos
                ));
            }
            else{
                $response = json_encode(array(
                    'success' => false,
                    'message' => 'No se encontraron generos'
                ));
            }
    
        echo $response;
        exit();
    }
    catch (Exception $e) {
        // Manejo de errores
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => $e->getMessage()]);
    }
    }
}