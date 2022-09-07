<?php
/**
 * Loading Modal for Searches
 *
 * @package  WordPress
 */

$lang = $_GET['lang']; //phpcs:ignore
?>
<div id="loading-modal" class="modal">
	<div class="modal__content">
		<i class="ico-plate"></i>
		<p><?= 'es' == $lang ? 'Esperando...' : 'Loading...' ?></p>
	</div>
</div>
