<?php
$vin_lookup     = get_field( 'results_vin_lookup' );
$header_text	= get_field( 'results_header' );
$subtext		= get_field( 'results_subtext' );
$tablet_layout	= get_field('tablet_layout');

// If on the tablet layout and $_POST['vin'] OR $_POST['plate_number'] is not set or is empty, show the search form.
if ( false === $tablet_layout || ( $tablet_layout && ( empty( $_POST['vin'] ) && empty( $_POST['plate_number'] ) ) ) ) :
?>

<section class="results-vin-look-up <?php if ($tablet_layout) echo 'tablet'; ?>">
	<div class="content">
		<h1><?= $header_text; ?></h1>
	</div>
	<div class="vin-search">
		<?php mg_get_template_part( 'partials', 'form-vin-search-protected' ); ?>
	</div>
	<div class="content">
		<h2><?= $subtext; ?></h2>
	</div>
</section>
<?php
endif;
