<?php
	if ($_GET['lang'] == 'es') {
		$npl = 'Ver Artículos Viejos';
		$ppl = 'Ver Artículos Recientes';
	}
	else {
		$npl = 'View Older Articles';
		$ppl = 'View Newer Articles';
	}
?>

<div class="pagination">
	<div class="pagination__prev">
		<?php next_posts_link($npl) ?>
	</div>
	<div class="pagination__next">
		<?php previous_posts_link($ppl) ?>
	</div>
</div>
