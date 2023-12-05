<?php

namespace Modules\Mantenedor\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Database\Query;
use Modules\Mantenedor\Models\MantenedorModel;
use stdClass;

class MantenedorController extends BaseController
{
	private $mantenedorModel;
	private $db;

	public function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->mantenedorModel = new MantenedorModel();
		
		
	}
	
    public function index()
    {
        $data['datalibrary'] = array(
			
			'titulo' => "Datos",
			'vista' => array('\Modules\Mantenedor\Views\index','\Modules\Mantenedor\Views\modals'),
			'libjs' => array('\Modules\Mantenedor\Views\libjs'),
			'libcss' => array('\Modules\Mantenedor\Views\libcss'),
		);
		return view("\Modules\Menu_principal\Views\body", $data);
    }



	public function getEspecialistas() {




		$columnas = ['CAMPO', 'CODIGO', 'CLIENTE_NOMBRE'];
        $where = 1;
		$clauses = [];
		$info = [];
		$tamanio = 0;
		$draw = 1;
		$end = $tamanio;
		$start = 0;
		$LIMIT = '';
		$ORDER_BY = '';
		$value = false;

		$recordsFiltered = $tamanio;


		if($query = $this->mantenedorModel->getEspecialistas($where, $clauses, $ORDER_BY, $LIMIT)){

			
           
            foreach ($query->getResult() as $datos) {

                /*print_r($datos->ID);
                die();*/

				//Datos que seran mostrados en la vista
				$row = new stdClass();
				$row->id = $datos->ID;
				$row->nombre = $datos->NOMBRE_COMPLETO;
				$row->edad = $datos->EDAD;
				$row->nacionalidad = $datos->NACIONALIDAD;
				$row->direccion = $datos->DIRECCION;
				$row->rut = $datos->RUT;
				$row->comuna = $datos->COMUNA;
				$row->region = $datos->REGION;
				$row->genero = $datos->GENERO;
				$row->educacion = $datos->EDUCACION;
				$row->celular = $datos->CELULAR;
				$row->puntuacion = $datos->PUNTUACION;
				$row->tipo_consulta = $datos->TIPO_DE_CONSULTA;
				$row->ubicacion_consulta = $datos->UBICACION_CONSULTA;
				$row->estado = $datos->ESTADO;
				$row->nestado = $datos->NESTADO;
				
				$row->opciones = '<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
										<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
											<a class="dropdown-item cacv-editar" href="#" data-id=' . $row->id . '><i class="fa fa-pencil"></i> Editar</a>
											
										</div>
								</div>';
				$info[] = $row;

                }
            
        }

		echo json_encode([
			'data' => $info, 
			'draw' => $draw, 'recordsFiltered' => $recordsFiltered, 'recordsTotal' => $tamanio,
			'allowed' => true, 
		]);

	}

	public function procesar_especialista()
	{

		$fecha = date('Y-m-d H:i:s');

		if ($_POST) {

			$info = new stdClass();
			$info->errores = [];
			
			$especialistas = new stdClass();
			$especialistas->id = null;
			$especialistas->estado = null;
			$result = false;


			if (!empty($this->request->getPost('id'))) {
				$especialistas->id = $this->request->getPost('id');
				$especialistas->id = $especialistas->id;
			}

			if (!empty($this->request->getPost('form-filtro-estado-modal'))) {
				$especialistas->estado = $this->request->getPost('form-filtro-estado-modal');
				$especialistas->estado = $especialistas->estado;

			} else {
				$info->errores[] = 'el estado es requerido.';
			}

			if (sizeof($info->errores) == 0) {

				$datos_especialistas = [
					'ID_ESTADO' => $especialistas->estado
				];

					if ($especialistas->id) { // Si existe el ID, significa que estoy editando

						$datos_especialistas = array_merge($datos_especialistas, ['FECHAMODIFICACION' => $fecha]);
						$this->mantenedorModel->actualizar('especialista', 'ID_ESPECIALISTA', $datos_especialistas, $especialistas->id);
					} else {  // Si no existe el ID significa que es un nuevo registro

						// $datos_especialistas = array_merge($datos_especialistas, ['FECHACREACION' => $fecha, 'ESTADO' => 1]);
						// if ($result = $this->mantenedorModel->insertar('USUARIO_JORNADA', $datos_especialistas)) {
						// 	$especialistas->id = $result;
						// }
					}

					if (is_numeric($especialistas->id) && sizeof($info->errores) == 0) {
						$result = true;
					
					} else {
							$result = false;
							//$info->errores[] = 'La Jornada no puede repetirse.';
						}
			}

			// $query = $this->db->getLastQuery();
			// echo (string) $query;

			echo json_encode(['result' => $result, 'errores' => $info->errores]);
		}
	}

	public function procesar_cliente()
	{

		$fecha = date('Y-m-d H:i:s');

		if ($_POST) {

			$info = new stdClass();
			$info->errores = [];
			
			$clientes = new stdClass();
			$clientes->id = null;
			$clientes->estado = null;
			$result = false;


			if (!empty($this->request->getPost('id'))) {
				$clientes->id = $this->request->getPost('id');
				$clientes->id = $clientes->id;
			}

			if (!empty($this->request->getPost('form-filtro-estado-modal'))) {
				$clientes->estado = $this->request->getPost('form-filtro-estado-modal');
				$clientes->estado = $clientes->estado;

			} else {
				$info->errores[] = 'el estado es requerido.';
			}

			if (sizeof($info->errores) == 0) {

				$datos_clientes = [
					'ID_ESTADO' => $clientes->estado
				];

					if ($clientes->id) { // Si existe el ID, significa que estoy editando

						$datos_clientes = array_merge($datos_clientes, ['FECHAMODIFICACION' => $fecha]);
						$this->mantenedorModel->actualizar('usuario', 'ID_USUARIO', $datos_clientes, $clientes->id);
					} else {  // Si no existe el ID significa que es un nuevo registro

						// $datos_especialistas = array_merge($datos_especialistas, ['FECHACREACION' => $fecha, 'ESTADO' => 1]);
						// if ($result = $this->mantenedorModel->insertar('USUARIO_JORNADA', $datos_especialistas)) {
						// 	$especialistas->id = $result;
						// }
					}

					if (is_numeric($clientes->id) && sizeof($info->errores) == 0) {
						$result = true;
					
					} else {
							$result = false;
							//$info->errores[] = 'La Jornada no puede repetirse.';
						}
			}

			// $query = $this->db->getLastQuery();
			// echo (string) $query;

			echo json_encode(['result' => $result, 'errores' => $info->errores]);
		}
	}


	public function getUsuarios() {




		$columnas = ['CAMPO', 'CODIGO', 'CLIENTE_NOMBRE'];
        $where = 1;
		$clauses = [];
		$info = [];
		$tamanio = 0;
		$draw = 1;
		$end = $tamanio;
		$start = 0;
		$LIMIT = '';
		$ORDER_BY = '';
		$value = false;

		$recordsFiltered = $tamanio;


		if($query = $this->mantenedorModel->getUsuarios($where, $clauses, $ORDER_BY, $LIMIT)){
           
            foreach ($query->getResult() as $datos) {

                /*print_r($datos->ID);
                die();*/

				//Datos que seran mostrados en la vista
				$row = new stdClass();
				$row->id = $datos->ID;
				$row->nombre = $datos->NOMBRE_COMPLETO;
				$row->edad = $datos->EDAD;
				$row->nacionalidad = $datos->NACIONALIDAD;
				$row->direccion = $datos->DIRECCION;
				$row->rut = $datos->RUT;
				$row->comuna = $datos->COMUNA;
				$row->region = $datos->REGION;
				$row->genero = $datos->GENERO;
				$row->celular = $datos->CELULAR;
				$row->estado = $datos->ESTADO;
				$row->nestado = $datos->NESTADO;
				$row->opciones = '<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
										<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; top: -46px; left: -74px; will-change: transform;">
											<a class="dropdown-item cacv-editar" href="#" data-id=' . $row->id . '><i class="fa fa-pencil"></i> Editar</a>
											
										</div>
								</div>';
				$info[] = $row;

                }
            
        }

		echo json_encode([
			'data' => $info, 
			'draw' => $draw, 'recordsFiltered' => $recordsFiltered, 'recordsTotal' => $tamanio,
			'allowed' => true, 
		]);

	}









	public function getDatosPorID(){



        $id = null;
		$data = null ;
		$tipousuario = $this->request->getPost('tipousuario');

		if ($tipousuario == 2) {
			if (!empty($this->request->getPost('id'))) {
				$id = $this->request->getPost('id');
	
				if (is_numeric($id)) {

					//if ($query = $this->Mantenedor_model->getAreas( "UA.ID_AREA=? AND UA.ID_CLdIENTE=?",array($id, $ID_CLIENTE)))
	
					if ($query = $this->mantenedorModel->getEspecialistas( "E.ID_ESPECIALISTA=?",array($id))) {
	
						$query = $query->getRow();
	
						$data = new \stdClass();
						$data->id = $query->ID;
						$data->nombre = $query->NOMBRE_COMPLETO;
						$data->estado = $query->ESTADO;
						
					}
				}
				echo json_encode($data);
			}


		} elseif ($tipousuario == 1) {
			if (!empty($this->request->getPost('id'))) {
				$id = $this->request->getPost('id');
	
				if (is_numeric($id)) {

	
					if ($query = $this->mantenedorModel->getUsuarios( "U.ID_USUARIO=?",array($id))) {
	
						$query = $query->getRow();
	
						$data = new \stdClass();
						$data->id = $query->ID;
						$data->nombre = $query->NOMBRE_COMPLETO;
						$data->estado = $query->ESTADO;
						
					}
				}
				echo json_encode($data);
			}}
		// } elseif ($tabla === "Cargos") {
		// 	if (!empty($this->input->post('id'))) {
		// 		$id = $this->security->xss_clean($this->input->post('id', true));
	
		// 		if (is_numeric($id)) {
	
		// 			if ($query = $this->Mantenedor_model->getCargos( "C.ID_CARGO=?",array($id))) {
	
		// 				$query = $query->row();
	
		// 				$data = new stdClass();
		// 				$data->id = $query->ID;
		// 				$data->nombre = $query->CARGO;
		// 				$data->codigo = $query->CODIGO;
		// 				$data->idcliente = $query->CLIENTE;
		// 			}
		// 		}
		// 		echo json_encode($data);
		// 	}
		// } elseif ($tabla === "Vicepresidencias") {
		// 	if (!empty($this->input->post('id'))) {
		// 		$id = $this->security->xss_clean($this->input->post('id', true));
	
		// 		if (is_numeric($id)) {
	
		// 			if ($query = $this->Mantenedor_model->getVicepresidencias( "V.ID_VICEPRESIDENCIA=?",array($id))) {
	
		// 				$query = $query->row();
	
		// 				$data = new stdClass();
		// 				$data->id = $query->ID;
		// 				$data->nombre = $query->VICEPRESI;
		// 				$data->codigo = $query->CODIGO;
		// 				$data->idcliente = $query->CLIENTE;
						
		// 			}
		// 		}
		// 		echo json_encode($data);
		// 	}
		// } elseif ($tabla === "Jornadas") {
		// 	if (!empty($this->input->post('id'))) {
		// 		$id = $this->security->xss_clean($this->input->post('id', true));
	
		// 		if (is_numeric($id)) {
	
		// 			if ($query = $this->Mantenedor_model->getJornadas( "J.ID_JORNADA=?",array($id))) {
	
		// 				$query = $query->row();
	
		// 				$data = new stdClass();
		// 				$data->id = $query->ID;
		// 				$data->nombre = $query->JORNADA;
		// 				$data->codigo = $query->CODIGO;
		// 				$data->idcliente = $query->CLIENTE;
						
		// 			}
		// 		}
		// 		echo json_encode($data);
		// 	}
		// } 
		//else {
			// Código para cuando no se reconozca ninguna categoría
		//}


       

        
    }




	public function getEstado(){

             
		if($query = $this->mantenedorModel->getEstado()){
			
			foreach ($query->getResult() as $datos) {

					$row = new \stdClass();
					
					$row->id = $datos->ID;
					$row->estado = $datos->ESTADO;
					
					$info[] = $row;

				}
			
			}

		echo json_encode([
			'data' => $info
		]);
    }

}