<?php
/**
 * Home CTAs
 *
 * @package  WordPress
 */

$cta_container = get_field( 'home_call_to_actions' );
$ctas          = $cta_container['call_to_action'];
$icon          = get_field( 'icon' );
?>

<section class="call-to-actions">
	<div class="overlay" style="background: url('<?= esc_url( $cta_container['background']['url'] ); ?>') no-repeat center/cover transparent;"></div>
	<div class="content">
		<h1><?= esc_html( $cta_container['heading'] ); ?></h1>
		<?php if ( $ctas ) : ?>
			<ul class="callouts">
				<?php foreach ( $ctas as $cta ) { ?>
					<li class="callout">
						<article class="callout__article">
							<i class="ico-<?= esc_attr( $cta['icon']['value'] ); ?> callout__icon"><span class="sr-only">Icon of a <?= esc_html( $cta['icon']['label'] ); ?></span></i>
							<h1 class="callout__heading"><?= $cta['heading']; //phpcs:ignore ?></h1>
							<p class="callout__content"><?= $cta['content']; //phpcs:ignore ?></p>
						</article>
					</li>
				<?php } ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
