<?php

namespace Modules\Ws\Controllers;


use App\Controllers\BaseController;
use Modules\Ws\Models\Ws_model;

use stdClass;
use Exception;
use DateTime;

class Login extends BaseController
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

   

    public function almacenarUsuario() {
        $postdata = json_decode(file_get_contents('php://input'));
    
        if ($postdata && isset($postdata->email) && isset($postdata->clave) && 
            isset($postdata->nombre) && isset($postdata->appaterno) && 
            isset($postdata->apmaterno) && isset($postdata->telefono) && isset($postdata->rut)) {
    
            $rut = $postdata->rut;    
            $nombre = $postdata->nombre;
            $appaterno = $postdata->appaterno;
            $apmaterno = $postdata->apmaterno;
            $telefono = $postdata->telefono;
            $email = $postdata->email;
            $clave = $postdata->clave;
    
            // Verificar si el correo ya está registrado
            if ($this->ws_model->consultarUsuario($email)) {
                // El correo ya está registrado, enviar mensaje de error
                echo json_encode(array(
                    'success' => false,
                    'message' => 'El correo electrónico ya está registrado'
                ));
                return;
            }
    
            $this->ws_model->almacenarUsuario( $rut, $nombre, $appaterno, $apmaterno, $telefono, $email, $clave);
    
            // Devolver una respuesta en formato JSON
            echo json_encode(array(
                'success' => true,
                'message' => 'Usuario almacenado correctamente',
                'email' => $email,
                'clave' => $clave,
                'rut' => $rut,
                'nombre' => $nombre,
                'apellidoPaterno' => $appaterno,
                'apellidoMaterno' => $apmaterno,
                'telefono' => $telefono
            ));
        } else {
            // Si los datos no están presentes o son nulos
            echo json_encode(array(
                'success' => false,
                'message' => 'No se han recibido datos válidos',
            ));
        }
    
        exit();
    }
    
    

    public function almacenarEspecialista() {
        $postdata = json_decode(file_get_contents('php://input'));
            
        if ($postdata && isset($postdata->email) && isset($postdata->clave) && 
            isset($postdata->nombre) && isset($postdata->appaterno) && 
            isset($postdata->apmaterno) && isset($postdata->telefono) && isset($postdata->rut)) {
    
            $rut = $postdata->rut;         
            $nombre = $postdata->nombre;
            $appaterno = $postdata->appaterno;
            $apmaterno = $postdata->apmaterno;
            $telefono = $postdata->telefono;
            $email = $postdata->email;
            $clave = $postdata->clave;
    
            // Verificar si el correo ya está registrado
            if ($this->ws_model->consultarUsuario($email)) {
                // El correo ya está registrado, enviar mensaje de error
                echo json_encode(array(
                    'success' => false,
                    'message' => 'El correo electrónico ya está registrado'
                ));
                return;
            }
    
            // Llama a la función del modelo para almacenar al especialista
            $this->ws_model->almacenarEspecialista($rut, $nombre, $appaterno, $apmaterno, $telefono, $email, $clave);
    
            // Devolver una respuesta en formato JSON
            echo json_encode(array(
                'success' => true,
                'message' => 'Especialista almacenado correctamente',
                'email' => $email,
                'clave' => $clave,
                'rut' => $rut,
                'nombre' => $nombre,
                'apellidoPaterno' => $appaterno,
                'apellidoMaterno' => $apmaterno,
                'telefono' => $telefono
            ));
        } else {
            // Si los datos no están presentes o son nulos
            echo json_encode(array(
                'success' => false,
                'message' => 'No se han recibido datos válidos',
            ));
        }
    
        exit();
    }
    
    
    
    public function iniciarSesion() {
        $postdata = json_decode(file_get_contents('php://input'));
        
        if ($postdata && isset($postdata->email)) {
            $email = $postdata->email;
           
      
            // Obtén las credenciales del usuario
            $credenciales = $this->ws_model->obtenerCredenciales($email);
      
            if ($credenciales) {
                $id_usuario = $credenciales['ID'];
                $tipo_usuario = $credenciales['ID_TIPO_USUARIO'];
                $email = $credenciales['EMAIL'];
                
                echo json_encode(array(
                    'success' => true,
                    'id_usuario' => $id_usuario,
                    'tipo_usuario' => $tipo_usuario,
                    'email' => $email
                ));
      
                
            } else {
                // Usuario no encontrado
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Correo electrónico o contraseña incorrectos'
                ));
            }
        } else {
            // Si los datos no están presentes o son nulos
            echo json_encode(array(
                'success' => false,
                'message' => 'No se han recibido datos válidos'
            ));
        }
      
        exit();
      }

    
      public function verificarCorreo() {
        $postdata = json_decode(file_get_contents('php://input'));
        if ($postdata && isset($postdata->email)) {
            $email = $postdata->email;
            // Verifica si el correo existe en la base de datos
            $correoExiste = $this->ws_model->correoExiste($email);
            echo json_encode(array(
                'correoExiste' => $correoExiste,  // Aquí enviamos el booleano
                'message' => $correoExiste ? 'Correo enviado correctamente' : 'El correo electrónico no está registrado'
            ));
        } else {
            // Si los datos no están presentes o son nulos
            echo json_encode(array(
                'success' => false,
                'message' => 'No se han recibido datos válidos'
            ));
        }
        exit();
    }
    
    


    // public function iniciarSesion() {
    //     $postdata = json_decode(file_get_contents('php://input'));
        
    //     if ($postdata && isset($postdata->email) && isset($postdata->clave)) {
    //         $email = $postdata->email;
    //         $claveEncriptada = $postdata->clave;
      
    //         // Obtén las credenciales del usuario
    //         $credenciales = $this->ws_model->obtenerCredenciales($email);
      
    //         if ($credenciales) {
    //             $hashed_clave = $credenciales['USUARIO_CLAVE']; // Obtén la clave almacenada en la base de datos
      
    //             // Aquí puedes desencriptar la clave encriptada del cliente y compararla
    //             // con la clave almacenada en la base de datos.
    //             if ($claveEncriptada === $hashed_clave) {
    //                 echo json_encode(array(
    //                     'success' => true,
    //                     'message' => 'Login correcto',
    //                     'email' => $email
    //                 ));
    //             } else {
    //                 echo json_encode(array(
    //                     'success' => false,
    //                     'message' => 'Correo electrónico o contraseña incorrectos'
    //                 ));
    //             }
    //         } else {
    //             // Usuario no encontrado
    //             echo json_encode(array(
    //                 'success' => false,
    //                 'message' => 'Correo electrónico o contraseña incorrectos'
    //             ));
    //         }
    //     } else {
    //         // Si los datos no están presentes o son nulos
    //         echo json_encode(array(
    //             'success' => false,
    //             'message' => 'No se han recibido datos válidos'
    //         ));
    //     }
      
    //     exit();
    //   }
    
    
    
    
        
 
}
