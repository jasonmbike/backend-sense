<?php

namespace Modules\Ws\Controllers;


use App\Controllers\BaseController;
use Modules\Ws\Models\Ws_model;

use stdClass;
use Exception;
use DateTime;

class Perfil extends BaseController
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

    function obtenerUsuario() {
        // Llama a la función del modelo para obtener los perfiles

        
        $postdata = json_decode(file_get_contents('php://input'));

        if ($postdata && isset($postdata->id_usuario) ) {   

        $id_usuario = $postdata->id_usuario;
        $tipo_usuario = $postdata->tipo_usuario;


        if ($tipo_usuario == 1) {
            $tipo_usuario = 'especialista';

        } else {
            $tipo_usuario = 'usuario';
        }

        $usuario = $this->ws_model->obtenerUsuario($tipo_usuario,$id_usuario);
    
        if ($usuario) {
            // Codifica los datos en JSON con el campo 'perfiles'
            $response = json_encode(array(
                'success' => true,
                'message' => 'Perfil obtenido correctamente',
                'usuarios' => $usuario
            ));
        } else {
            $response = json_encode(array(
                'success' => false,
                'message' => 'No se encontraron perfiles'
            ));
        }
    
        // Imprime la respuesta JSON
        echo $response;
        exit();

    } else {
        // Si los datos no están presentes o son nulos
        echo json_encode(array(
            'success' => false,
            'message' => 'Fallo la transaccion',
        ));
    }

    exit();
    }

    function actualizarUsuario() {
        // Obtener datos JSON de la solicitud POST
        $postdata = json_decode(file_get_contents('php://input'));
    
        if ($postdata && isset($postdata->usuario)) {
            $id_tipo_usuario = $postdata->tipo_user;
    
            // Determinar el tipo de usuario y el campo de comparación
            if ($id_tipo_usuario == 1) {
                $id_tipo_usuario = "especialista";
                $comparar = "id_especialista";
            } else {
                $id_tipo_usuario = "usuario";
                $comparar = "id_usuario";
            };
    
            // Obtener datos del objeto JSON
            $datos = [
                'nombre' => $postdata->datos->nombre,
                'ap_paterno' => $postdata->datos->ap_paterno,
                'ap_materno' => $postdata->datos->ap_materno,
                'email' => $postdata->datos->email,
                'nacionalidad' => $postdata->datos->nacionalidad,
                'direccion' => $postdata->datos->direccion,
                'rut' => $postdata->datos->rut,
                'celular' => $postdata->datos->celular,
                'id_region' => $postdata->datos->id_region,
                'id_comuna' => $postdata->datos->id_comuna,
                'id_genero' => $postdata->datos->id_genero,
                'imagen_perfil' => $postdata->datos->imagen_perfil
            ];
    
            // Verificar si la imagen es una cadena base64
            if (strpos($datos['imagen_perfil'], 'data:image') === 0) {
                // Decodificar la imagen base64 y guardarla en el servidor
                $imagen_perfil_base64 = $datos['imagen_perfil'];
                $imagen_perfil_bin = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagen_perfil_base64));
    
                // Crear una carpeta con el ID del usuario
                $carpeta_usuario = 'public/fotoperfiles/' . $postdata->usuario;
                if (!file_exists($carpeta_usuario)) {
                    mkdir($carpeta_usuario, 0777, true);
                }
    
                // Ruta donde se guardará la imagen dentro de la carpeta del usuario
                $ruta_guardar = $carpeta_usuario . '/' . uniqid() . '.jpg';
    
                // Guardar la imagen en la ruta especificada
                file_put_contents($ruta_guardar, $imagen_perfil_bin);
    
                // Actualizar la ruta en los datos
                $datos['imagen_perfil'] = $ruta_guardar;
            }
    
            // Actualizar usuario en la base de datos
            $usuario = $this->ws_model->actualizarReserva($id_tipo_usuario, $comparar, $datos, $postdata->usuario);
    
            if ($usuario) {
                // Codificar la respuesta en JSON
                $response = json_encode(array(
                    'success' => true,
                    'message' => 'Usuario editado exitosamente',
                    'usuario' => $postdata->usuario
                ));
            } else {
                $response = json_encode(array(
                    'success' => false,
                    'message' => 'No se encontraron perfiles'
                ));
            }
    
            // Imprimir la respuesta JSON
            echo $response;
            exit();
        } else {
            // Si los datos no están presentes o son nulos
            echo json_encode(array(
                'success' => false,
                'message' => 'Fallo la transaccion',
            ));
        }
    
        exit();
    }
   

    public function getPersonalidadesYHobbies2() {
        $postdata = json_decode(file_get_contents('php://input'));
        //$hola = $this->wsmodel->gethorario(4);
        //echo json_encode($hola->getResult());

        

        if ($postdata) {   


            $id = $postdata->id;
            
            if ($this->ws_model->getPersonalidades2($id)) {
                $datos = $this->ws_model->getPersonalidades2($id)->getResult();
            }

            if ($this->ws_model->getHobbies2($id)) {
                $datos2 = $this->ws_model->getHobbies2($id)->getResult(); 
            } 



                $response = [
                    'success' => true,
                    'message' => $datos,
                    'message2' => $datos2
                ];
                echo json_encode($response); 




        } else {
            // Si los datos no están presentes o son nulos
            echo json_encode(array(
                'success' => false,
                'message' => 'No se han recibido datos válidos',
            ));
        }
    
        exit();
    }
    

    public function updatePersonalidadesYHobbies() {
        $postdata = json_decode(file_get_contents('php://input'));
        //$hola = $this->wsmodel->gethorario(4);
        //echo json_encode($hola->getResult());

        

        if ($postdata) { 

            $tipo = $postdata->tipo;
            $id = $postdata->id;
            $data = $postdata->data;

            $datosInsertar = [];

         

            if ($tipo == 1){

                $this->ws_model->borrarpersonalidades($id);

                foreach ($data as $item) {
                $datosInsertar[] = array(
                    'id_especialista' => $id,
                    'id_personalidad' => $item->id_personalidad
                );}
            } else {
                $this->ws_model->borrarhobbies($id);
                foreach ($data as $item) {
                $datosInsertar[] = array(
                    'id_especialista' => $id,
                    'id_hobbie' => $item->id_hobbie
                );}

            }
            
            

            $tabla = ($tipo == 1) ? 'especialista_personalidad' : 'especialista_hobbie';


            $resultado = $this->ws_model->insertarmultiple($tabla, $datosInsertar);

            if ($resultado) {
                $response = [
                    'success' => true,
                    'message' => 'Datos insertados correctamente',
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Error al insertar datos',
                ];
            }
    
            echo json_encode($response);
    


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


    





    
