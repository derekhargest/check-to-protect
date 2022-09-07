<?php
/**
 * Vin How To
 *
 * @package  WordPress
 */

$vin_how_to = get_field( 'home_vin_how_to' );
?>

<section class="vin-how-to">
	<div class="content">
		<div class="vin-how-to--image">
			<img src="<?= esc_url( $vin_how_to['image']['sizes']['vin_how_to_image'] ); ?>" alt="<?= esc_attr( $vin_how_to['image']['alt'] ); ?>">
		</div>
		<div class="vin-how-to--text wysiwyg">
			<?= $vin_how_to['text']; //phpcs:ignore ?>
		</div>
	</div>
</section>
