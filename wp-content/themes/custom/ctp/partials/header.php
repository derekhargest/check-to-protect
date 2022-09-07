<?php
$header         = get_global_option( 'header_section' );
$header_logo    = $header['logo']['url'];
$tablet_layout	= get_field('tablet_layout');

$gtm_event_snippet = get_field('event_snippet');

$footer_section  = get_global_option( 'footer_section' );
$powered_by_logo = $footer_section['footer_image'];
$powered_by_lede = $footer_section['footer_content'];
?>

<?= $gtm_event_snippet; ?>

<header class="header">
	<div class="header__container">
		<div class="header__container__col">
			<h1 class="header__logo">
				<a href="<?php echo bloginfo('url'); ?>" title="Click here to return to the Check To Protect homepage">
					<?php if( $header_logo ) : ?>
						<img src="<?= $header_logo; ?>" alt="<?php bloginfo('name'); echo ' logo'; ?>" />
					<?php endif; ?>
				</a>
			</h1>
			<?php if ( $powered_by_lede || $powered_by_logo ) : ?>
				<div class="header__pb">
					<?php if ( $powered_by_lede ) : ?>
						<div><?php echo $powered_by_lede; ?></div>
					<?php endif; ?>
					<?php if( $powered_by_logo ) : ?>
						<a href="https://www.nsc.org/road/safety-topics/check-to-protect">
							<img src="<?= $powered_by_logo['url']; ?>" class="header__pb" alt="<?= $powered_by_logo['alt']; ?>" />
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="header__container__col">
			<nav class="header__nav" id="header-nav">
				<div class="header__menu">
					<?php wp_nav_menu( array(
						'container'         => false,
						'menu_class'        => 'menu menu--header',
						'theme_location'    => 'header',
					)); ?>
				</div>
			</nav>
		</div>
	</div>
</header>
