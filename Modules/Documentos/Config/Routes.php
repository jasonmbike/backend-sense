<?php
  
$routes->group("documentos", ["namespace" => "\Modules\Documentos\Controllers"], function ($routes) {

	// // welcome page - URL: /student
	$routes->get("/", "DocumentosController::index",['filter' => 'session']);
	// // Ruta para obtener datos de la tabla Documentos
    $routes->post("getDocumentos", "DocumentosController::getDocumentos");
	$routes->post("actualizarEstadoDocumento", "DocumentosController::actualizarEstadoDocumento");
	// // Ruta para obtener datos de la tabla FAQ
    // $routes->post("actualizarRegistro", "FaqController::actualizarRegistro");
	

});