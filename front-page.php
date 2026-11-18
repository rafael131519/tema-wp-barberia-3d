<?php
/**
 * Portada inmersiva. El poste 3D está en un lienzo fijo detrás (header.php)
 * y escena.js lo mueve según el scroll: grande en la portada,
 * a un lado en los precios, y queda tapado por las secciones de fondo sólido.
 */
$d       = b3d_datos();
$reserva = b3d_enlace_reserva();
$zona    = trim( explode( ',', $d['barrio'] )[0] );

get_header();
?>

<main>

	<!-- PORTADA -->
	<section class="hero" data-tramo="hero">
		<div class="contenedor hero__rejilla">
			<div class="hero__texto">
				<h1 class="hero__titulo"><?php echo esc_html( $d['nombre'] ); ?></h1>
				<p class="hero__eslogan"><?php echo esc_html( $d['eslogan'] ); ?></p>
				<p class="hero__intro"><?php echo esc_html( $d['intro'] ); ?></p>
				<div class="acciones">
					<a class="boton boton--rojo" href="<?php echo esc_url( $reserva ); ?>" target="_blank" rel="noopener">Reservar cita</a>
					<a class="boton boton--linea" href="#servicios">Ver precios</a>
				</div>
			</div>
			<!-- Poste de respaldo en CSS: solo se ve si el 3D no puede cargar -->
			<div class="poste-css" aria-hidden="true"><span></span></div>
		</div>
		<p class="hero__pista" aria-hidden="true">Desliza</p>
	</section>

	<!-- PRECIOS: el poste se queda a la derecha y gira con el scroll -->
	<section class="precios" id="servicios" data-tramo="precios">
		<div class="contenedor">
			<h2 class="titulo-seccion">Precios</h2>
			<p class="precios__nota">Pasa el ratón por un servicio.</p>
			<ul class="carta">
				<?php foreach ( $d['servicios'] as $servicio ) : ?>
					<li class="carta__fila" data-servicio>
						<div>
							<h3><?php echo esc_html( $servicio['nombre'] ); ?></h3>
							<p><?php echo esc_html( $servicio['desc'] ); ?> <span class="carta__min"><?php echo esc_html( $servicio['duracion'] ); ?></span></p>
						</div>
						<span class="carta__precio"><?php echo esc_html( $servicio['precio'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<a class="boton boton--rojo" href="<?php echo esc_url( $reserva ); ?>" target="_blank" rel="noopener">Reservar cita</a>
		</div>
	</section>

	<!-- CÓMO TRABAJAMOS (fondo sólido: tapa el 3D) -->
	<section class="estudio" id="estudio">
		<div class="contenedor">
			<h2 class="titulo-seccion">Cómo trabajamos</h2>
			<div class="estudio__columnas">
				<?php foreach ( $d['puntos'] as $punto ) : ?>
					<div class="estudio__punto">
						<h3><?php echo esc_html( $punto['titulo'] ); ?></h3>
						<p><?php echo esc_html( $punto['texto'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- TRABAJOS: tarjetas que se inclinan en 3D con el ratón -->
	<section class="galeria" id="galeria">
		<div class="contenedor">
			<h2 class="titulo-seccion">Trabajos recientes</h2>
			<div class="galeria__rejilla">
				<?php
				$textos = array( 'Degradado medio', 'Barba perfilada', 'Corte a tijera', 'Afeitado clásico', 'Texturizado', 'Corte y barba' );
				foreach ( $textos as $i => $texto ) :
					$foto = b3d_imagen( 'galeria-' . ( $i + 1 ) . '.jpg' );
					?>
					<figure class="tarjeta" data-inclinar>
						<div class="tarjeta__cara">
							<?php if ( $foto ) : ?>
								<img src="<?php echo esc_url( $foto ); ?>" alt="<?php echo esc_attr( $texto ); ?>" loading="lazy">
							<?php else : ?>
								<div class="tarjeta__vacia" aria-hidden="true"></div>
							<?php endif; ?>
							<figcaption><?php echo esc_html( $texto ); ?></figcaption>
						</div>
					</figure>
				<?php endforeach; ?>
			</div>
			<p class="galeria__mas"><a href="<?php echo esc_url( $d['instagram'] ); ?>" target="_blank" rel="noopener">Ver más en Instagram</a></p>
		</div>
	</section>

	<!-- DÓNDE ESTAMOS -->
	<section class="visitanos" id="visitanos">
		<div class="contenedor visitanos__rejilla">
			<div>
				<h2 class="titulo-seccion">Estamos en <?php echo esc_html( $zona ); ?></h2>
				<p class="visitanos__direccion"><?php echo esc_html( $d['direccion'] ); ?></p>
				<table class="horario">
					<?php foreach ( $d['horario'] as $dia => $horas ) : ?>
						<tr<?php echo ( 'Cerrado' === $horas ) ? ' class="horario__cerrado"' : ''; ?>>
							<th scope="row"><?php echo esc_html( $dia ); ?></th>
							<td><?php echo esc_html( $horas ); ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
				<div class="acciones">
					<a class="boton boton--rojo" href="<?php echo esc_url( $reserva ); ?>" target="_blank" rel="noopener">Reservar cita</a>
					<a class="boton boton--linea-oscura" href="tel:<?php echo esc_attr( b3d_tel() ); ?>">Llamar</a>
				</div>
			</div>
			<div class="mapa">
				<iframe title="Mapa de <?php echo esc_attr( $d['nombre'] ); ?>" src="https://maps.google.com/maps?q=<?php echo rawurlencode( $d['direccion'] ); ?>&z=16&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
