<?php
global $post;
$post_fields = get_fields();
?>

<li class="news__item" id="news-<?php the_ID(); ?>">
	<a href="<?= $post_fields['external_link']; ?>" title="Click here to learn more about <?= the_title(); ?>" class="news__link-wrapper" target="_blank">
		<article class="news__container">
			<h1 class="news__heading"><?= the_title(); ?></h1>
			<h2 class="news__subhead"><?= $post_fields['publication']; ?></h2>
			<?php if( $post_fields['content'] ) : ?>
				<p class="news__content"><?= $post_fields['content']; ?></p>
			<?php endif; ?>
		</article>
	</a>
	<a href="<?= $post_fields['external_link']; ?>" title="Click here to learn more about <?= the_title(); ?>" class="news__link" target="_blank" rel="noopener">Read More</a>
</li>
