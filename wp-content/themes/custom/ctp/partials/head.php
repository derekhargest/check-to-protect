<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<?php wp_head(); ?>
	<?php if ( is_front_page() || is_page( 225 ) || is_page( 602 ) ): ?>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<?php endif; ?>
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:creator" content="@NSCsafety">
	<meta name="twitter:title" content="Check to Protect">
	<meta name="twitter:description" content="Find out if your vehicle has an open recall. It’s free to check and free to repair at a dealer.">
	<meta name="twitter:image" content="<?php echo get_template_directory_uri(); ?>/social-ad.jpg">
	<meta property="og:type" content="website" />
	<meta property="og:title" content="Check to Protect" />
	<meta property="og:description" content="Find out if your vehicle has an open recall. It’s free to check and free to repair at a dealer." />
	<meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/social-ad.jpg" />
	<meta property="og:image:width" content="900" />
	<meta property="og:image:height" content="900" />
	<meta property="og:url" content="<?php echo get_site_url(); ?>" />
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-5MS6S3G');</script>
	<!-- End Google Tag Manager -->
</head>
