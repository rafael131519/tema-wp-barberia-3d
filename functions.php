<?php
/**
 * Funciones del tema Barbería Filo 3D.
 * Usa los mismos datos que la versión clásica (inc/datos.php).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Datos del negocio (inc/datos.php). */
function b3d_datos() {
	static $datos = null;
	if ( null === $datos ) {
		$datos = require get_template_directory() . '/inc/datos.php';
	}
	return $datos;
}

/** Enlace de reserva: URL de reservas o WhatsApp con mensaje escrito. */
function b3d_enlace_reserva() {
	$d = b3d_datos();
	if ( ! empty( $d['reserva_url'] ) ) {
		return $d['reserva_url'];
	}
	return 'https://wa.me/' . $d['whatsapp'] . '?text=' . rawurlencode( 'Hola, quiero reservar cita en ' . $d['nombre'] . '.' );
}

/** URL de una imagen de assets/img si existe; '' si no. */
function b3d_imagen( $archivo ) {
	$ruta = get_template_directory() . '/assets/img/' . $archivo;
	return file_exists( $ruta ) ? get_template_directory_uri() . '/assets/img/' . $archivo : '';
}

/** Teléfono sin espacios para enlaces tel: */
function b3d_tel() {
	return preg_replace( '/\s+/', '', b3d_datos()['telefono'] );
}

function b3d_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'b3d_setup' );

/** CSS, fuentes, Three.js (motor 3D) y nuestros scripts. */
function b3d_assets() {
	$v = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'b3d-fuentes', 'https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@600;800;900&family=Instrument+Sans:wght@400;500;600&display=swap', array(), null );
	wp_enqueue_style( 'b3d-estilos', get_stylesheet_uri(), array( 'b3d-fuentes' ), $v );

	// Three.js: la librería que dibuja el poste en 3D.
	wp_enqueue_script( 'three', 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js', array(), null, true );
	wp_enqueue_script( 'b3d-escena', get_template_directory_uri() . '/assets/js/escena.js', array( 'three' ), $v, true );
	wp_enqueue_script( 'b3d-main', get_template_directory_uri() . '/assets/js/main.js', array(), $v, true );
}
add_action( 'wp_enqueue_scripts', 'b3d_assets' );

/** Fuera estilos de bloques: la portada no los usa. */
function b3d_quitar_bloques() {
	if ( is_front_page() ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'global-styles' );
		wp_dequeue_style( 'classic-theme-styles' );
	}
}
add_action( 'wp_enqueue_scripts', 'b3d_quitar_bloques', 100 );
