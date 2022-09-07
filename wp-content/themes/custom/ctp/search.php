<?php global $wp_query; ?>

<section class="content container container--padded container--max-width">
	<header class="page-header">
		<h1 class="page-header__title"><?php echo $wp_query->found_posts; ?> result<?php if( $wp_query->found_posts != 1 ) echo 's'; ?> for "<?php the_search_query(); ?>"</h1>
	</header>

	<ol class="posts">
		<?php while( have_posts() ) : the_post(); ?>
			<?php mg_get_template_part('partials', 'loop-post'); ?>
		<?php endwhile; ?>
	</ol>

	<?php mg_get_template_part('partials', 'pagination'); ?>
</section>