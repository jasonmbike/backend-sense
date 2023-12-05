<?php

namespace Modules\Ws\Controllers;


use App\Controllers\BaseController;
use Modules\Ws\Models\Ws_model;

use stdClass;
use Exception;
use DateTime;

class Documentacion extends BaseController
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


    public function subirArchivo()
    {
        // Comprueba si la petición es de tipo POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit();
        }
    
        // Comprueba si se recibieron archivos en la solicitud
        // var_dump($_POST);
        // die();


        if (!empty($_FILES)) {
            $responses = [];
    
            $idEspecialista = $_POST['id_especialista'];
            $idEstado = 3; // ID del estado
    
            $ruta_destino = 'public/archivos/' . $idEspecialista . '/';
            if (!file_exists($ruta_destino)) {
                mkdir($ruta_destino, 0777, true);
            }
    
            $index = 1;
            foreach ($_FILES as $file) {
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $id_tipo_documento = $_POST['id_tipo_documento-' . $index];
                    $archivo_destino = $ruta_destino . basename($file['name']);
                    if (move_uploaded_file($file['tmp_name'], $archivo_destino)) {

                        $index++;

                        $this->ws_model->almacenarDocumentos($file['name'], $archivo_destino, $idEspecialista, $idEstado, $id_tipo_documento);

                        $responses[] = [
                            'success' => true,
                            'message' => 'Archivo ' . basename($archivo_destino) . ' subido correctamente',
                            'id_especialista' => $idEspecialista,
                            'id_estado' => $idEstado,
                            'id_tipo_documento' => $id_tipo_documento

                        ];
                    } else {
                        $responses[] = [
                            'success' => false,
                            'message' => 'Error al subir el archivo ' . basename($archivo_destino),
                        ];
                    }
                }
            }
    
            // Imprime la respuesta JSON
            echo json_encode($responses);
            exit();
        } else {
            // No se recibieron archivos
            $response = json_encode([
                'success' => false,
                'message' => 'No se encontraron archivos en la solicitud',
            ]);
    
            // Imprime la respuesta JSON
            echo $response;
            exit();
        }
    }
    

    
    
}   
