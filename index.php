<?php
/** Plantilla de respaldo para páginas que no son la portada. */
get_header();
?>
<main class="pagina">
	<div class="contenedor contenedor--estrecho">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class(); ?>>
					<h1><?php the_title(); ?></h1>
					<div class="pagina__contenido"><?php the_content(); ?></div>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<h1>Esta página no existe</h1>
			<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Volver al inicio</a></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
