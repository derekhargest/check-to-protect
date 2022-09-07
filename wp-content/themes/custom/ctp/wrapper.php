<?php get_template_part('partials/head'); ?>
<body <?php body_class(); ?>>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5MS6S3G" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<?php
		do_action('get_header');
		mg_get_header();
	?>

	<main class="main">
		<?php mg_get_template(); ?>
	</main>

	<?php
		do_action('get_footer');
		mg_get_footer();
	?>
</body>
</html>
