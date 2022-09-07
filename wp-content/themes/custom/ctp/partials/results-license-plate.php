<?php
$social_text = $_GET['lang'] == 'es' ? 'Recuérdale a tus seres queridos<br /> que se protejan visitando Check To Protect' : 'Remind friends and family to Check To Protect';
$twitter_text = $_GET['lang'] == 'es' ? '53 millones de vehículos en los Estados Unidos tienen recalls pendientes. Toma un minuto de tu día para ver si el tuyo es uno de ellos.' : '53 million vehicles in the US have open safety recalls. Take a minute to see if yours is one.';
?>

<div class="recalls__default">
	<p><?= get_field( 'tab7_message' ); ?></p>
	<div class="recalls__share">
		<p><?= $social_text; ?></p>
		<div class="social-shares">
			<a class="social-share social-share--facebook" href="#" data-share-channel="facebook" data-title="53 million vehicles in the US have open safety recalls. Take a minute to see if yours is one. https://CheckToProtect.org Stay safe. Check your vehicle for open safety recalls." data-u="https://www.checktoprotect.org/">
				<span class="social-share__cta">Share</span>
				<i class="social-share__icon fa fa-facebook" aria-hidden="true"></i>
			</a>
			<a class="social-share social-share--twitter" href="#" data-share-channel="twitter" data-text="<?= $twitter_text; ?>" data-url="https://CheckToProtect.org">
				<span class="social-share__cta">Tweet</span>
				<i class="social-share__icon fa fa-twitter" aria-hidden="true"></i>
			</a>
		</div>
	</div>
</div>
