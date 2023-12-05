<?php

//...  
  
$routes->group("menu_principal", ["namespace" => "\Modules\Menu_principal\Controllers"], function ($routes) {

	// welcome page - URL: /student
	$routes->get("/", "MenuPrincipalController::index", ['filter' => 'session']);
  
    // other page - URL: /student/other-method
	$routes->get("other-method", "MenuPrincipalController::otherMethod");

});