<?php
/**
 * Results Vin Lookup
 *
 * @package  WordPress
 */

$vin_lookup  = get_field( 'results_vin_lookup' );
$header_text = get_field( 'results_header' );
$subtext     = get_field( 'results_subtext' );
?>
<section class="results-vin-look-up">
	<div class="content">
		<h1><?= esc_html( $header_text ); ?></h1>
		<h2><?= esc_html( $subtext ); ?></h2>
	</div>
	<div class="vin-search">
		<?php mg_get_template_part( 'partials', 'form-vin-search' ); ?>
	</div>
</section>
