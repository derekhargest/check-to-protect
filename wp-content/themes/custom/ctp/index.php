<?php
$disclaimer = get_field( 'disclaimer', get_option('page_for_posts') );
?>

<section class="news">
	<div class="content">
		<h1 class="page-heading"><?= single_post_title(); ?></h1>

		<ol class="news__list">
			<?php while( have_posts() ) : the_post(); ?>
				<?php mg_get_template_part('partials', 'loop-post'); ?>
			<?php endwhile; ?>
		</ol>

		<?php mg_get_template_part('partials', 'pagination'); ?>

		<p class="news__disclaimer"><?= $disclaimer; ?></p>
	</div>
</section>
