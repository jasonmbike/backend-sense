<?php



namespace Modules\Ws\Controllers;

use App\Controllers\BaseController;

use stdClass;

class Ws extends BaseController
{

	public function __construct()
	{
		
	}
	
    public function index()
    {
        $request = \Config\Services::request();


        if (!empty($request->getGet("m"))) {
            $direccion = $request->getGet("m");
            $direccion = strtolower($direccion);
            $check = method_exists($this, $direccion);
            if ($check) {
                call_user_func(array($this, $direccion));
            } else {
                // En caso de no encontrarse la función llamada, se obliga a actualizar
                $info = new stdClass();
                $info->estado = new stdClass();
                $info->estado->actualizar = 0;

                $info = json_encode($info);
                echo $info;
            }
        }
    }


   

    public function login()
    {
        $modulo = new Login();
        $modulo->index();
    }

    public function especialista()
    {
        $modulo = new Especialista();
        $modulo->index();
    }

    public function reservas(){
        $modulo = new Reservas();
        $modulo->index();
    }

    public function faq(){
        $modulo = new Faq();
        $modulo->index();
    }

    public function perfil(){
        $modulo = new Perfil();
        $modulo->index();
    }

    public function documentacion(){
        $modulo = new Documentacion();
        $modulo->index();
    }
    public function genero(){
        $modulo = new Genero();
        $modulo->index();
    }
    public function Region(){
        $modulo = new Region();
        $modulo->index();
    }
    public function Comuna(){
        $modulo = new Comuna();
        $modulo->index();
    }

    public function historial()
    {
        $modulo = new Historial();
        $modulo->index();
    }



    public function buscar()
    {
        $modulo = new Buscar();
        $modulo->index();
    }

}