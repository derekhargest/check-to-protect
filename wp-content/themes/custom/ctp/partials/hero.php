<?php
$hero_boolean   = get_field( 'hero_boolean' );
$hero_image     = get_field( 'hero_image' );
$hero_text      = get_field( 'hero_text' );
$hero_position  = get_field( 'hero_position' );
?>

<?php if( $hero_boolean ) : ?>
	<section class="hero">
		<div class="overlay">
			<div class="overlay__color"></div>
			<div class="overlay__image" style="background-image: url('<?= $hero_image['url']; ?>');<?= ' background-position: ' . $hero_position . ';'; ?>"></div>
		</div>
		<div class="hero__content">
			<h1><?= $hero_text; ?></h1>
		</div>
	</section>
<?php endif; ?>
