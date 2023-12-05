<?php

namespace Modules\Ws\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Database\Query;
use CodeIgniter\HTTP\Message;
use Modules\Ws\Models\Ws_model;
use Transbank\Webpay\WebpayPlus\Transaction;
use stdClass;

class Reservas extends BaseController
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


    public function getHorarios() {
        $postdata = json_decode(file_get_contents('php://input'));
        //$hola = $this->wsmodel->gethorario(4);
        //echo json_encode($hola->getResult());

        

        if ($postdata && isset($postdata->id) ) {   

            $this->eliminarReservasExpiradas();

            $horariosreservados = $this->consultarHorariosOcupados();

            $id = $postdata->id;
             if ($this->wsmodel->getHorario($id)) {
                $datos = $this->wsmodel->getHorario($id)->getResult();
                $response = [
                    'success' => true,
                    'message' => $datos,
                    'horariosReservados' => $horariosreservados
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

    public function getHorarios2() {
        $postdata = json_decode(file_get_contents('php://input'));
        //$hola = $this->wsmodel->gethorario(4);
        //echo json_encode($hola->getResult());

        

        if ($postdata && isset($postdata->id) ) {   

            $this->eliminarReservasExpiradas();

            $horariosreservados = $this->consultarHorariosOcupados();

            $id = $postdata->id;
             if ($this->wsmodel->getHorario($id)) {
                $datos = $this->wsmodel->getHorario($id)->getResult();
                $response = [
                    'success' => true,
                    'message' => $datos,
                    'horariosReservados' => $horariosreservados
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

    public function eliminarReservasExpiradas() {
        $fecha_actual = date('Y-m-d H:i:s');
    
        $this->wsmodel->borrar($fecha_actual);

    }

    public function consultarHorariosOcupados() {
        // Obtén la fecha y hora actual
        $fecha_actual = date('Y-m-d H:i:s');
    
        // Realiza la consulta para obtener los horarios disponibles
        // Asume que la estructura de la tabla es "horarios_disponibles" con campos "hora_inicio" y "hora_fin"

        if ($this->wsmodel->consultarHorariosOcupados($fecha_actual)) {
            $datos = $this->wsmodel->consultarHorariosOcupados($fecha_actual)->getResult();
            
        } else {
            $datos = null;
        }
        

        return $datos;

        //echo $datos;
        

    }



    public function reservaTemporal() {
        $postdata = json_decode(file_get_contents('php://input'));

        $fecha_actual = date('Y-m-d H:i:s');

        

        if ($postdata && isset($postdata->horario) ) {   
            $horario = $postdata->horario;

            $hora = $horario->hora;
            $tipo = $horario->tipo;
            $disponibilidad = $horario->disponible;
            $id = $postdata->id;
            $fecha = $postdata->selectedDate;

            $expiracion = date('Y-m-d H:i:s', strtotime($fecha_actual . ' +5 minutes'));


            if ($this->wsmodel->consultarReservaTomada($fecha,$hora,$fecha_actual)) {
               

                $message = "ocupado";
                $response = [
                    'success' => false,
                    'message' => $message
                ];
                echo json_encode($response);
            } else {
                $datosReserva = [
                    'fecha' => $fecha,
                    'hora' => $hora,
                    'reserva_iniciada' => $fecha_actual,
                    'reserva_expiracion' => $expiracion
                ];
                
                $this->wsmodel->insertar('reserva_temporal', $datosReserva);

                $response = [
                    'success' => true,
                    'message' => 'Reserva_temporal insertada correctamente'
                ];

                echo json_encode($response);
                
                


            }


            

            

            // $datos = $this->wsmodel->getHorario($id)->getResult();
            
            // $horarioArray = json_decode($datos[0]->horario, true);
            // if (isset($horarioArray[$fecha])) {
            //     $horasDelDia = &$horarioArray[$fecha]; // Accedemos por referencia para actualizar el original
            
            //     // Busca la hora y el tipo en el arreglo
            //     foreach ($horasDelDia as &$horaInfo) {
            //         if ($horaInfo['hora'] === $hora && $horaInfo['tipo'] === $tipo) {
            //             // Verifica el valor de disponibilidad
            //             if ($horaInfo['disponible'] == 0) {
            //                 $message = "ocupado";
            //                 $response = [
            //                     'success' => false,
            //                     'message' => $message
            //                 ];
            //                 echo json_encode($response);
            //                 exit();
            //             }
            //             // Cambia la disponibilidad
            //             $horaInfo['disponible'] = 0; // Cambia a no disponible
            //         }
            //     }
            
            //     // Si llegas a este punto, significa que se realizaron los cambios con éxito
                
            // } else {
            //     // Fecha no encontrada en el arreglo
            //     $response = [
            //         'success' => false,
            //         'message' => 'Fecha no encontrada'
            //     ];
            //     echo json_encode($response);
            // }
            // // Convierte el horario actualizado en JSON
            // $horarioJson = json_encode($horarioArray);

            // // Actualiza el horario en la base de datos
            // $tabla = "especialista";
            // $comparar = "id_especialista";
            // $data = ['horario' => $horarioJson];

            // $this->wsmodel->actualizarReserva($tabla, $comparar, $data, $id);

            

            // $message = "reservado";

            // $response = [
            //     'success' => true,
            //     'message' => $message
            // ];
            // echo json_encode($response); 

        } else {
            // Si los datos no están presentes o son nulos
            echo json_encode(array(
                'success' => false,
                'message' => 'No se han recibido datos válidos',
            ));
        }

        exit();
    }


    public function guardarReserva() {
        $postdata = json_decode(file_get_contents('php://input'));

        if ($postdata && isset($postdata->id_usuario) ) {   
            

            $datosReserva = [
                'id_usuario' => $postdata->id_usuario,
                'id_especialista' => $postdata->id_especialista,
                'fecha_reserva' => $postdata->fecha,
                'hora_reserva' => $postdata->hora,
                'id_estado' => $postdata->id_estado,
                'id_tipo_consulta' => $postdata->tipo_consulta
            ];

            $this->wsmodel->insertar('reserva', $datosReserva);

            if ($postdata->tipo_consulta == 1) {
                $postdata->tipo_consulta = 'online';
            } else {
                $postdata->tipo_consulta = 'presencial';
            }
            

            $id = $postdata->id_especialista;
            $fecha = $postdata->fecha;
            $hora = $postdata->hora;
            $tipo = $postdata->tipo_consulta;
            

            
            $datos = $this->wsmodel->getHorario($id)->getResult();
            

            $horarioArray = json_decode($datos[0]->horario, true);
            if (isset($horarioArray[$fecha])) {
                $horasDelDia = &$horarioArray[$fecha]; // Accedemos por referencia para actualizar el original
            
                // Busca la hora y el tipo en el arreglo
                foreach ($horasDelDia as &$horaInfo) {
                    if ($horaInfo['hora'] === $hora && $horaInfo['tipo'] === $tipo) {
                        // Verifica el valor de disponibilidad
                        if ($horaInfo['disponible'] == 0) {
                            $message = "ocupado";
                            $response = [
                                'success' => false,
                                'message' => $message
                            ];
                            echo json_encode($response);
                            exit();
                        }
                        // Cambia la disponibilidad
                        $horaInfo['disponible'] = 0; // Cambia a no disponible
                    }
                }
            
                // Si llegas a este punto, significa que se realizaron los cambios con éxito
                
            } else {
                // Fecha no encontrada en el arreglo
                $response = [
                    'success' => false,
                    'message' => 'Fecha no encontrada'
                ];
                echo json_encode($response);
            }
            // Convierte el horario actualizado en JSON
            $horarioJson = json_encode($horarioArray);

            // Actualiza el horario en la base de datos
            $tabla = "especialista";
            $comparar = "id_especialista";
            $data = ['horario' => $horarioJson];

            $this->wsmodel->actualizarReserva($tabla, $comparar, $data, $id);

            $this->wsmodel->borrarReservaConfirmada($hora,$fecha);



            $response = [
                'success' => true,
                'message' => 'Datos insertados correctamente'
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




    public function actualizarhorario() {
        $postdata = json_decode(file_get_contents('php://input'));
        //$hola = $this->wsmodel->gethorario(4);
        //echo json_encode($hola->getResult());

        

        if ($postdata && isset($postdata->id) ) {   

            $id = $postdata->id;
            $fecha = $postdata->fecha;
            $hora = $postdata->hora;
            $disponibilidad = $postdata->disponibilidad;
            $tipo = $postdata->tiposesion;

            if ($disponibilidad == 'disponible') {
                $disponibilidad = 1;
            } else {
                $disponibilidad = 0;
            }
            

            

            
            $datos = $this->wsmodel->getHorario($id)->getResult();

            $this->eliminarReservasExpiradas();


            $horarioArray = json_decode($datos[0]->horario, true);
            if (isset($horarioArray[$fecha])) {
                $horasDelDia = &$horarioArray[$fecha]; // Accedemos por referencia para actualizar el original
            
                // Busca la hora y el tipo en el arreglo
                foreach ($horasDelDia as &$horaInfo) {
                    if ($horaInfo['hora'] === $hora) {
                        // Verifica el valor de disponibilidad
                        $horaInfo['disponible'] = $disponibilidad;
                        $horaInfo['tipo'] = $tipo;
                    }
                }
            
                // Si llegas a este punto, significa que se realizaron los cambios con éxito
                
            } else {
                // Fecha no encontrada en el arreglo
                $response = [
                    'success' => false,
                    'message' => 'Fecha no encontrada'
                ];
                echo json_encode($response);
                exit();
            }
            // Convierte el horario actualizado en JSON
            $horarioJson = json_encode($horarioArray);

            // Actualiza el horario en la base de datos
            $tabla = "especialista";
            $comparar = "id_especialista";
            $data = ['horario' => $horarioJson];

            $this->wsmodel->actualizarReserva($tabla, $comparar, $data, $id);

            $response = [
                'success' => true,
                'message' => 'Datos actualizados correctamente'
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


    public function crearTransaccion() {

        $postdata = json_decode(file_get_contents('php://input'));


        if ($postdata && isset($postdata->id) ) {   

            $id = $postdata->id;
            $session_id = $postdata->id;
            $amount = $postdata->tarifa;
            $buy_order = $postdata->orden_compra;
            $return_url = 'http://192.168.2.100/sense/ws/estadoDelPago';

            if ($this->respuesta = (new Transaction)->create($buy_order, $session_id, $amount, $return_url)) {
                
                


                echo json_encode($this->respuesta); 

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
                'message' => 'Fallo la transaccion',
            ));
        }
    
        exit();

        

        
    }

    public function estadoDelPago() {

        $fecha = date('Y-m-d H:i:s');


        $TBK_TOKEN = $_GET['TBK_TOKEN'] ?? null;


        $token = $_GET['token_ws'] ?? $_POST['token_ws'] ?? null;


        if ($TBK_TOKEN !== null) {
            // $TBK_TOKEN no es null, tiene un valor
            // Realiza la acción que deseas aquí

            $datosTransaccion = [
                'response_code' => -1,
                
            ];
            
            

            return view("\Modules\Ws\Views\pagos\pagocancelado");
        }


        if ($token !== null) {
            

            $response = (new Transaction)->commit($token); // ó cualquiera de los métodos detallados en el ejemplo anterior del método create.
            if ($response->isApproved()) {
            // Transacción Aprobada

            $datosTransaccion = [
                'monto' => $response->getAmount(),
                'estado' => $response->getStatus(),
                'orden_compra' => $response->getBuyOrder(),
                'id_especialista' => $response->getSessionId(),
                'numero_tarjeta' => $response->getCardNumber(),
                'fecha' => $fecha,
                'codigo_autorizacion' => $response->getAuthorizationCode(),
                'tipo_pago' => $response->getPaymentTypeCode(),
                'codigo_respuesta' => $response->getResponseCode(),
            ];
            
            $this->wsmodel->insertar('boleta', $datosTransaccion);

 
            return view("\Modules\Ws\Views\pagos\pagocompletado");

            } else {

                // Transacción rechazada
            

            $datosTransaccion = [
                'codigo_respuesta' => $response->getResponseCode(),
                'id_especialista' => $response->getSessionId(),
                'orden_compra' => $response->getBuyOrder(),
            ];
            
            $this->wsmodel->insertar('boleta', $datosTransaccion);


            return view("\Modules\Ws\Views\pagos\pagocancelado");

            
            }
    
        }

        exit();

    }

    


    // public function pagoCompletado() {

    //     $postdata = json_decode(file_get_contents('php://input'));
        
        

    //     $where = $postdata->id . ' AND orden_compra = ' . $postdata->orden_compra;



        

    //    // $where = '4 and orden_compra = 4614';


    //     if ($this->wsmodel->getBoleta($where)) {
    //         $datos = $this->wsmodel->getBoleta($where)->getResult();
    //         $response = [
    //             'success' => true,
    //             'message' => $datos
    //         ];
    //         echo json_encode($response); 
    //     } else {
    //         echo json_encode(array(
    //             'success' => false,
    //             'message' => 'no se ha encontrado el horario.',
    //         ));
    //     }

       

    //     // $datosTransaccion = $this->session->get('datos_transaccion'); // Obtén los datos de la sesión

    //     // //if ($datosTransaccion && isset($datosTransaccion['response_code'])) {
    //     //     $responseCode = $datosTransaccion['response_code'];

    //     //     if ($responseCode == 0) {
    //     //         // Realiza acciones si el response_code es igual a 0

    //     //         header("HTTP/1.1 200 OK");
    //     //         echo json_encode(array(
    //     //             'success' => true,
    //     //             'monto' => $datosTransaccion['amount'],
    //     //             'estado' => $datosTransaccion['status'],
    //     //             'orde_compra' => $datosTransaccion['buy_order'],
    //     //             'session_id' => $datosTransaccion['session_id'],
    //     //             'detalle_tarjeta' => $datosTransaccion['card_detail'],
    //     //             'numero_tarjeta' => $datosTransaccion['card_number'],
    //     //             'fecha_transaccion' => $datosTransaccion['transaction_date'],
    //     //             'codigo_transaccion' => $datosTransaccion['authorization_code'],
    //     //             'tipo_pago' => $datosTransaccion['payment_type_code'],
    //     //         ));
    //     //     } else {

                

    //     //         $responseCode = $datosTransaccion['response_code'];

    //     //         if ($responseCode == -1) {
    //     //             $mensaje = 'Rechazo - Posible error en el ingreso de datos de la transacción';
    //     //         } elseif ($responseCode == -2) {
    //     //             $mensaje = 'Rechazo - Se produjo fallo al procesar la transacción, este mensaje de rechazo se encuentra relacionado a parámetros de la tarjeta y/o su cuenta asociada';
    //     //         } elseif ($responseCode == -3) {
    //     //             $mensaje = 'Rechazo - Error en Transacción';
    //     //         } elseif ($responseCode == -4) {
    //     //             $mensaje = 'Rechazo - Rechazada por parte del emisor';
    //     //         } elseif ($responseCode == -5) {
    //     //             $mensaje = 'Rechazo - Transacción con riesgo de posible fraude';
    //     //         } else {
    //     //             $mensaje = 'indefinido';
    //     //         }

    //     //         header("HTTP/1.1 200 OK");

    //     //         echo json_encode(array(
    //     //             'success' => false,
    //     //             'message' => $mensaje
                    
    //     //         ));
    //     //     }
    //     // // } else {
    //     // //     echo json_encode(array(
    //     // //         'success' => false,
    //     // //         'message' => 'no se recuperaon datos de la sesion.'
                
    //     // //     ));
    //     // // }

        
        
    //     exit();
        
    // }



    public function pagoCompletado2() {

        $postdata = json_decode(file_get_contents('php://input'));



        if ($postdata && isset($postdata->id) ) {   

             $where = $postdata->id . ' AND orden_compra = ' . $postdata->orden_compra;


             if ($this->wsmodel->getBoleta($where)) {
                $datos = $this->wsmodel->getBoleta($where)->getResult();
                $response = [
                    'success' => true,
                    'message' => $datos
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
                'message' => 'Fallo la transaccion',
            ));
        }
    
        exit();
        
    }



    public function guardarHorario() {
        $postdata = json_decode(file_get_contents('php://input'));

        if ($postdata && isset($postdata->id_esp) ) {   

            $id = $postdata->id_esp;


            $tabla = "especialista";
            $comparar = "id_especialista";
            


            foreach ($postdata->horario as $date => $sessions) {
                $newSessions = [];
            
                // Recorrer las sesiones de cada día
                foreach ($sessions as $session) {
                    // Construir un nuevo arreglo con el formato deseado
                    $newSessions[] = [
                        "hora" => $session->hora,
                        "tipo" => $session->tipo,
                        "disponible" => $session->disponible,
                    ];
                }
            
                // Agregar las sesiones al nuevo arreglo bajo la fecha correspondiente
                $newArray[$date] = $newSessions;
            }
            
            // Convertir el nuevo arreglo a formato JSON
            $jsonResult = json_encode($newArray, JSON_PRETTY_PRINT);

            

            $data = ['horario' => $jsonResult];

            $this->wsmodel->actualizarReserva($tabla, $comparar, $data, $id);

            $response = [
                'success' => true,
                'message' => 'Datos insertados correctamente'
            ];
            echo json_encode($response); 





        } else {
            // Si los datos no están presentes o son nulos
            echo json_encode(array(
                'success' => false,
                'message' => 'Horario no guardado',
            ));
        }
    
        exit();

    }


    function verificarUser() {

        $postdata = json_decode(file_get_contents('php://input'));

        if ($postdata && isset($postdata->id) ) { 
            $id = $postdata->id;

            if ($this->wsmodel->verificarUsuario($id)) {

                $datos = $this->wsmodel->verificarUsuario($id);

                $response = [
                    'success' => true,
                    'message' => $datos
                ];
                echo json_encode($response); 
            } else {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'no se ha encontrado al especialista.',
                ));
            }





        } else {
            // Si los datos no están presentes o son nulos
            echo json_encode(array(
                'success' => false,
                'message' => 'No se encontro la verificacion',
            ));
        }
    
        exit();


    }

   
    

}