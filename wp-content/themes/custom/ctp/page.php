<?php
the_post();

global $post;

mg_get_template_part( 'partials', 'hero' );
?>

<section class="default page-<?= $post->post_name ?>">
	<div class="content wysiwyg">
		<?php
		the_content();

		$partner_enable = get_field('partnership_enable');
		$partner_title  = get_field('partnership_title');
		$partner_logos  = get_field('partnership_logos');

		if ( $partner_enable && $partner_title ) : ?>
			<section class="partner-logos">
				<h1><?= $partner_title; ?></h1>
				<div class="logos">
					<?php foreach ( $partner_logos as $logo ) : ?>
						<img src="<?= $logo['logo']['url']; ?>" alt="<?= $logo['logo']['alt']; ?>" />
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>
	</div>
</section>
