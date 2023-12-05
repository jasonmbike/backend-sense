<?php

//...  
  
$routes->group("mantenedor", ["namespace" => "\Modules\Mantenedor\Controllers"], function ($routes) {

	// welcome page - URL: /student
	$routes->get("/", "MantenedorController::index",['filter' => 'session']);
  
    // other page - URL: /student/other-method
	$routes->get("getEspecialistas", "MantenedorController::getEspecialistas");
	$routes->post("getEspecialistas", "MantenedorController::getEspecialistas");
	$routes->post("getUsuarios", "MantenedorController::getUsuarios");
	$routes->get("getEstado", "MantenedorController::getEstado");
	$routes->post("getDatosPorID", "MantenedorController::getDatosPorID");
	$routes->post("procesar_especialista", "MantenedorController::procesar_especialista");
	$routes->post("procesar_cliente", "MantenedorController::procesar_cliente");

});