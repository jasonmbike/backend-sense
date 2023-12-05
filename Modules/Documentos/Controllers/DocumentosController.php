<?php

namespace Modules\Documentos\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Database\Query;
use Modules\Documentos\Models\DocumentosModel;
use stdClass;

class DocumentosController extends BaseController
{
	private $DocumentosModel;
	private $db;

	public function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->DocumentosModel = new DocumentosModel();
		
		
	}
	
    public function index()
    {
        $data['datalibrary'] = array(
			
			'titulo' => "Documentos",
			'vista' => array('\Modules\Documentos\Views\index', '\Modules\Documentos\Views\modals'),
			'libjs' => array('\Modules\Documentos\Views\libjs'),
			'libcss' => array('\Modules\Documentos\Views\libcss'),
		);
		return view("\Modules\Menu_principal\Views\body", $data);
    }


	public function getDocumentos() {

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


		if($query = $this->DocumentosModel->getDocumentos($where, $clauses, $ORDER_BY, $LIMIT)){

			foreach ($query->getResult() as $datos) {

                // print_r($datos->ID);
                // die();

				//Datos que seran mostrados en la vista
				$row = new stdClass();
				$row->id_documento = $datos->ID_DOCUMENTO;
				$row->email = $datos->EMAIL;
				$row->ruta_archivo = '<a href="' . base_url($datos->RUTA_ARCHIVO) . '" target="_blank">Ver archivo</a>';
				$row->estado = $datos->ESTADO;
				$row->nombre = $datos->NOMBRE;
				$row->opciones = '<div class="dropdown">
					<button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
					<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
						<a class="dropdown-item cacv-activar" href="#" data-id=' . $row->id_documento . '><i class="fa fa-check"></i> Activar</a>
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
		exit();
	}

	public function actualizarEstadoDocumento() {
		$id_documento = $this->request->getPost('id_documento');
		$result = $this->DocumentosModel->actualizarEstadoDocumento($id_documento);
		
		if ($result) {
			echo json_encode(["success" => true]); // Otra lógica de respuesta si es necesario
		} else {
			echo json_encode(["success" => false, "message" => "Error al actualizar el estado"]); // Otra lógica de respuesta si es necesario
		}
	}
	
	
	
}