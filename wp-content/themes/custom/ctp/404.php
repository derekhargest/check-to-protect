<?php
$global_array   = get_field( 'page_not_found', 'option' );
$heading        = $global_array['heading'];
$body           = $global_array['body'];
?>

<section>
	<div class="content wysiwyg bulk-lookup">
		<header class="page-header">
			<h1 class="page-header__title">
				<?php if( $heading ) : echo $heading; else : ?>
					Page Not Found
				<?php endif; ?>
			</h1>
		</header>
		<?php if( $body ) : echo $body; else : ?>
			<p>Looks like the page you're looking for isn't here anymore.</p>
		<?php endif; ?>
	</div>
</section>
