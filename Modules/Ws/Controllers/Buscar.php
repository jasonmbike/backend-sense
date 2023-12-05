<?php

namespace Modules\Ws\Controllers;

use App\Controllers\BaseController;
use Modules\Ws\Models\Ws_model;


class Buscar extends BaseController
{

    private $wsmodel;
	private $db;
    public $respuesta;

    public function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->wsmodel = new Ws_model();
        
        
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


    public function getPersonalidadesYHobbies() {
        $postdata = json_decode(file_get_contents('php://input'));
        //$hola = $this->wsmodel->gethorario(4);
        //echo json_encode($hola->getResult());

        

        if ($postdata) {   

            
             if ($this->wsmodel->getPersonalidades()) {
                $datos = $this->wsmodel->getPersonalidades()->getResult();
                $datos2 = $this->wsmodel->getHobbies()->getResult();


                $response = [
                    'success' => true,
                    'message' => $datos,
                    'message2' => $datos2
                ];
                echo json_encode($response); 
            } else {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'no se ha encontrado el horario.',
                ));
            }
        } else {
            // Si los datos no están presentes o son nulos
            echo json_encode(array(
                'success' => false,
                'message' => 'No se han recibido datos válidos',
            ));
        }
    
        exit();
    }






    public function getespecialista() {
        $postdata = json_decode(file_get_contents('php://input'));
        //$hola = $this->wsmodel->gethorario(4);
        //echo json_encode($hola->getResult());

        

        if ($postdata) { 

        
            
            //$tiposesion = $postdata->tiposesion[0];
            $tiposesion = !empty($postdata->tiposesion[0]) ? $postdata->tiposesion[0] : '';
            //$tipogenero = $postdata->tipogenero[0];
            $tipogenero = !empty($postdata->tipogenero[0]) ? $postdata->tipogenero[0] : '';
            $personalidadesSeleccionadas = $postdata->personalidadesSeleccionadas;
            $hobbiesSeleccionados = $postdata->hobbiesSeleccionados;

            // print_r($tiposesion[0]);
            // print_r($tipogenero);
            // print_r($personalidadesSeleccionadas);
            // print_r($hobbiesSeleccionados);
            // die();


            
             if ($this->wsmodel->getespecialista($tiposesion,$tipogenero,$personalidadesSeleccionadas,$hobbiesSeleccionados)) {
                $datos = $this->wsmodel->getespecialista($tiposesion,$tipogenero,$personalidadesSeleccionadas,$hobbiesSeleccionados)->getResult();
                


                $response = [
                    'success' => true,
                    'message' => $datos,
                ];
                echo json_encode($response); 
            } else {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'no se ha encontrado ningun especialista',
                ));
            }
        } else {
            // Si los datos no están presentes o son nulos
            echo json_encode(array(
                'success' => false,
                'message' => 'No se han recibido datos válidos',
            ));
        }
    
        exit();
    }

}
