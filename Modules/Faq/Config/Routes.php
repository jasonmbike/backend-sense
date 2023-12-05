<?php
  
$routes->group("faq", ["namespace" => "\Modules\Faq\Controllers"], function ($routes) {

	// welcome page - URL: /student
	$routes->get("/", "FaqController::index",['filter' => 'session']);
	// Ruta para obtener datos de la tabla FAQ
    $routes->post("getFaq", "FaqController::getFaq");
	// Ruta para obtener datos de la tabla FAQ
    $routes->post("actualizarRegistro", "FaqController::actualizarRegistro");
	

});