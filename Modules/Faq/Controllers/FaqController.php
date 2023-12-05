<?php

namespace Modules\Faq\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Database\Query;
use Modules\Faq\Models\FaqModel;
use stdClass;

class FaqController extends BaseController
{
	private $faqModel;
	private $db;

	public function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->faqModel = new FaqModel();
		
		
	}
	
    public function index()
    {
        $data['datalibrary'] = array(
			
			'titulo' => "Faq",
			'vista' => array('\Modules\Faq\Views\index', '\Modules\Faq\Views\modals'),
			'libjs' => array('\Modules\Faq\Views\libjs'),
			'libcss' => array('\Modules\Faq\Views\libcss'),
		);
		return view("\Modules\Menu_principal\Views\body", $data);
    }

	public function getFaq() {

		$columnas = ['ID', 'TITULO', 'DESCRIPCION'];
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


		if($query = $this->faqModel->getFaq($where, $clauses, $ORDER_BY, $LIMIT)){

			foreach ($query->getResult() as $datos) {

                // print_r($datos->ID);
                // die();

				//Datos que seran mostrados en la vista
				$row = new stdClass();
				$row->id = $datos->ID;
				$row->titulo = $datos->TITULO;
				$row->descripcion = $datos->DESCRIPCION;
				$row->opciones = '<div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a class="dropdown-item cacv-editar" href="#" data-id="' . $row->id . '" data-titulo="' . $row->titulo . '" data-descripcion="' . $row->descripcion . '"><i class="fa fa-pencil"></i> Editar</a>
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

	

	public function actualizarRegistro()
{
    $id = $this->request->getPost('id');
    $titulo = $this->request->getPost('titulo');
    $descripcion = $this->request->getPost('descripcion');

	$datos_faq = [
		'titulo' => $titulo,
		'descripcion' => $descripcion
	];

    // Llamando al modelo para realizar la actualización del registro
    $this->faqModel->actualizarRegistro('faq', 'id', $datos_faq, $id);

    echo json_encode(['result' => true]);
}

	
}