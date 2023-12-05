<?php

namespace Modules\Menu_principal\Controllers;

use App\Controllers\BaseController;

class MenuPrincipalController extends BaseController
{
    public function index()
	{
		// echo "This is simple from Student Module";

        $data['datalibrary'] = array(
			
			'titulo' => "Menu principal",
			'vista' => array(),
			'libjs' => array(),
			'libcss' => array(),
		);


		return view("\Modules\Menu_principal\Views\body", $data);
	}
  
    public function otherMethod()
	{
		echo "This is other method from Student Module";
	}
}
