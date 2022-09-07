<?php
/**
 * Vin Lookup
 *
 * @package  WordPress
 */

$vin_lookup     = get_field( 'home_vin_lookup' );
$video_desktop  = $vin_lookup['video']['url'];
$mobile_support = $vin_lookup['video_mobile_boolean'];
$video_mobile   = $vin_lookup['video_mobile']['url'];
$header         = get_field( 'header_section', 'option' );
$header_banner  = $header['optional_homepage_banner'];
$banner_link    = $header['banner_link'];
$is_banner      = $header['display_banner'];
?>

<?php 
if ( $header_banner && $is_banner ) :
	if ( $banner_link ) {
		$link_url    = $banner_link['url'];
		$link_target = $banner_link['target'] ? $banner_link['target'] : '_self';
		echo '<a href="' . esc_url( $link_url ) . '" target="' . esc_attr( $link_target ) . '">';
	}
	?>
		<section class="banner">
			<p><?php echo esc_html( $header_banner ); ?></p>
		</section>
	<?php
	if ( $banner_link ) {
		echo '</a>';
	}
endif;
?>

<section class="vin-look-up">
	<div class="overlay">
		<div class="overlay__color"></div>
		<div class="overlay__video">
			<video playsinline autoplay muted loop poster="<?= esc_url( $vin_lookup['video_fallback']['url'] ); ?>" 
			class="video" style="background-image: url('<?= esc_url( $vin_lookup['video_fallback']['url'] ); ?>');">
				<?php if ( $mobile_support ) : ?>
					<source src="<?= esc_url( $video_mobile ); ?>" type="video/webm">
				<?php endif; ?>
				<source src="<?= esc_url( $video_desktop ); ?>" type="video/mp4">
			</video>
		</div>
	</div>
	<div class="content">
		<h1><?= $vin_lookup['heading']; //phpcs:ignore ?></h1>
		<p><?= $vin_lookup['content']; //phpcs:ignore ?></p>
	</div>
	<div class="vin-search">
		<?php mg_get_template_part( 'partials', 'form-vin-search' ); ?>
	</div>
	<div style="color:white;text-decoration: underline;padding-top:20px;padding-bottom:30px;"><a href="/bulk-vin-check/">Click here for bulk VIN lookup</a></div>
</section>
