<?php
/** Pie y botón de WhatsApp. */
$d = b3d_datos();
?>
<footer class="pie">
	<div class="contenedor pie__fila">
		<p class="pie__nombre"><?php echo esc_html( $d['nombre'] ); ?></p>
		<p><?php echo esc_html( $d['direccion'] ); ?></p>
		<p class="pie__enlaces">
			<a href="tel:<?php echo esc_attr( b3d_tel() ); ?>"><?php echo esc_html( $d['telefono'] ); ?></a>
			<a href="mailto:<?php echo esc_attr( $d['email'] ); ?>"><?php echo esc_html( $d['email'] ); ?></a>
			<a href="<?php echo esc_url( $d['instagram'] ); ?>" target="_blank" rel="noopener">Instagram</a>
		</p>
		<p class="pie__legal">© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( $d['nombre'] ); ?>. Web de KizaryDev.</p>
	</div>
</footer>

<a class="whatsapp" href="https://wa.me/<?php echo esc_attr( $d['whatsapp'] ); ?>" target="_blank" rel="noopener" aria-label="Escribir por WhatsApp">
	<svg viewBox="0 0 24 24" width="26" height="26" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.4.8 3.2.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.5-.3Z"/></svg>
</a>

<?php wp_footer(); ?>
</body>
</html>
