<?php
/**
 * DATOS DEL NEGOCIO
 * ------------------------------------------------------------
 * Este es el ÚNICO archivo que tienes que cambiar para adaptar
 * la demo a otra barbería. Cambia los textos entre comillas,
 * guarda y recarga la web.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(

	// Identidad
	'nombre'      => 'Barbería Filo',
	'eslogan'     => 'Corte clásico, oficio de barrio.',
	'intro'       => 'Cortes a tijera y máquina, barbas y afeitado con toalla caliente en el corazón de Russafa. Sin prisas y sin sorpresas en el precio.',

	// Contacto
	'direccion'   => 'Carrer de Cádiz, 42, 46006 València',
	'barrio'      => 'Russafa, Valencia',
	'telefono'    => '+34 600 000 000',
	'whatsapp'    => '34600000000',          // Solo números, con el 34 delante
	'email'       => 'hola@barberiafilo.es',
	'instagram'   => 'https://www.instagram.com/',

	// Reservas: si pones un enlace (Booksy, Treatwell...), el botón irá ahí.
	// Si lo dejas vacío, el botón abre WhatsApp con un mensaje ya escrito.
	'reserva_url' => '',

	// Horario
	'horario'     => array(
		'Lunes'            => 'Cerrado',
		'Martes a viernes' => '10:00 – 14:00 · 16:30 – 20:30',
		'Sábado'           => '09:30 – 14:30',
		'Domingo'          => 'Cerrado',
	),

	// Servicios y precios
	'servicios'   => array(
		array(
			'nombre'   => 'Corte',
			'desc'     => 'Lavado, corte a tijera o máquina y peinado.',
			'precio'   => '15 €',
			'duracion' => '30 min',
		),
		array(
			'nombre'   => 'Degradado',
			'desc'     => 'Fade bajo, medio o alto con acabado a navaja.',
			'precio'   => '17 €',
			'duracion' => '40 min',
		),
		array(
			'nombre'   => 'Corte + barba',
			'desc'     => 'El servicio completo. Sales listo para todo.',
			'precio'   => '22 €',
			'duracion' => '50 min',
		),
		array(
			'nombre'   => 'Arreglo de barba',
			'desc'     => 'Perfilado, rebaje y aceite hidratante.',
			'precio'   => '10 €',
			'duracion' => '20 min',
		),
		array(
			'nombre'   => 'Afeitado clásico',
			'desc'     => 'Toalla caliente, navaja y bálsamo.',
			'precio'   => '16 €',
			'duracion' => '30 min',
		),
		array(
			'nombre'   => 'Corte infantil',
			'desc'     => 'Hasta 12 años.',
			'precio'   => '12 €',
			'duracion' => '25 min',
		),
	),

	// Bloque "Cómo trabajamos" (3 puntos)
	'puntos'      => array(
		array(
			'titulo' => 'Cita a tu hora',
			'texto'  => 'Reservas en 30 segundos y entras cuando te toca. Sin esperas en la puerta.',
		),
		array(
			'titulo' => 'Precio cerrado',
			'texto'  => 'Lo que ves en la carta es lo que pagas. Sin suplementos.',
		),
		array(
			'titulo' => 'Producto profesional',
			'texto'  => 'Trabajamos con ceras, aceites y bálsamos que también puedes llevarte a casa.',
		),
	),
);
