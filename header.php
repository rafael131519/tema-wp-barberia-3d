<?php
/** Cabecera. */
$d = b3d_datos();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php echo esc_attr( $d['nombre'] . '. ' . $d['eslogan'] . ' ' . $d['barrio'] . '.' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Lienzo 3D: el poste vive aquí, detrás del contenido -->
<canvas class="escena" data-escena aria-hidden="true"></canvas>

<header class="cabecera" data-cabecera>
	<div class="contenedor cabecera__fila">
		<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $d['nombre'] ); ?></a>

		<button class="menu-boton" type="button" aria-expanded="false" aria-controls="menu" data-menu-boton>
			<span></span><span></span>
			<span class="sr">Abrir menú</span>
		</button>

		<nav class="menu" id="menu" data-menu>
			<a href="#servicios">Precios</a>
			<a href="#estudio">Cómo trabajamos</a>
			<a href="#galeria">Trabajos</a>
			<a href="#visitanos">Dónde estamos</a>
			<a class="boton boton--rojo" href="<?php echo esc_url( b3d_enlace_reserva() ); ?>" target="_blank" rel="noopener">Reservar cita</a>
		</nav>
	</div>
</header>
