<?php

//...  
  
$routes->group("ws", ["namespace" => "\Modules\Ws\Controllers"], function ($routes) {

	// welcome page - URL: /student
	$routes->add("/", "Ws::index");
	$routes->get("getHorarios", "Reservas::getHorarios");
	$routes->get("crearTransaccion", "Reservas::crearTransaccion");
	$routes->get("pagocompletado", "Reservas::pagoCompletado");
	$routes->get("estadoDelPago", "Reservas::estadoDelPago");
	$routes->get("pagoCompletado", "Reservas::pagoCompletado");
	$routes->post("pagoCompletado2", "Reservas::pagoCompletado2");
	$routes->get("sesion", "Reservas::sesion");
	$routes->get("recoger", "Reservas::recoger");
	$routes->get("reservaTemporal", "Reservas::reservaTemporal");
	$routes->get("eliminarReservasExpiradas", "Reservas::eliminarReservasExpiradas");
	$routes->get("consultarHorariosOcupados", "Reservas::consultarHorariosOcupados");
	$routes->get("guardarReserva", "Reservas::guardarReserva");
	

});