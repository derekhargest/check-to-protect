<?php
/** Footer
 * 
 * @package WordPress
 */

$tablet_layout = get_field( 'tablet_layout' );
?>
<footer class="footer <?php echo $tablet_layout ? 'tablet' : ''; ?>">
	<div class="footer__container">
		<?php if ( ! $tablet_layout ) : ?>
			<section class="footer__right">
				<?php 
				wp_nav_menu( 
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'menu menu--footer',
					)
				);
				?>
			</section>
		<?php endif; ?>
	</div>
</footer>

<?php wp_footer(); ?>
