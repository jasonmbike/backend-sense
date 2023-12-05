<?php

namespace Modules\Ws\Controllers;


use App\Controllers\BaseController;
use Modules\Ws\Models\Ws_model;

use stdClass;
use Exception;
use DateTime;

class Faq extends BaseController
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


    function obtenerFaq() {
        // Llama a la función del modelo para obtener las faq
        $faq = $this->ws_model->obtenerFaq();
    
        if ($faq) {
            // Codifica los datos en JSON con el campo 'faqs'
            $response = json_encode(array(
                'success' => true,
                'message' => 'Faq obtenidos correctamente',
                'faqs' => $faq  // Modificado para 'faqs' en lugar de 'especialistas'
            ));
        } else {
            $response = json_encode(array(
                'success' => false,
                'message' => 'No se encontraron faq'
            ));
        }
    
        // Imprime la respuesta JSON
        echo $response;
        exit();
    }
    
}






