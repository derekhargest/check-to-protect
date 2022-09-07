<?php
/**
 * Form Vin Search
 * This needs refactoring into the mg-carfax plugin.
 *
 * @package  WordPress
 */

mg_get_template_part( 'partials', 'modal-loading' );
$recaptcha_site_key = get_global_option( 'recaptcha_site_key' );

$user_ip        = $_SERVER['REMOTE_ADDR']; //phpcs:ignore
$transient_name = '_transient_IP_' . $user_ip;
$sub_count      = 0;
$lang           = $_GET['lang']; //phpcs:ignore

if ( false === ( $submissions = get_transient( $transient_name ) ) ) { //phpcs:ignore
} else {
	$submissions = get_transient( $transient_name );
	$sub_count   = $submissions['count'];
}

$vin          = $_POST['vin']; //phpcs:ignore
$plate_number = $_POST['plate_number']; //phpcs:ignore
$state_id     = $_POST['state_id']; //phpcs:ignore
$vin_look_up  = get_field( 'home_vin_lookup' );
$vin_text     = $vin_look_up['placeholder_text'] ? $vin_look_up['placeholder_text'] : 'Enter 17-character VIN (Vehicle Identification Number)';
$plate_text   = 'Enter License Plate Number';
$state_text   = 'State';
$subtext      = 'All recall repairs are FREE at your local dealer';

if ( 'es' == $lang ) {
	$vin_text   = ! is_front_page() ? 'Ingresa el VIN de 17 caracteres' : 'Ingresa el VIN de 17 caracteres';
	$subtext    = 'Todas las reparaciones de llamados a revisión son gratis en su concesionario local';
	$plate_text = 'Ingresa el Número de Placa';
	$state_text = 'Estado';
}

$form_action = get_the_permalink( mg_get_post_by_template( 'page-template-results.php' ) );

// VIN validation messages.
$validation_messages  = get_field( 'vin_validator', 'option' );
$validation_title     = 'es' == $lang ? 'VIN incorrecto:' : 'Invalid VIN:';
$e_required           = $validation_messages['error_required'];
$e_count              = $validation_messages['error_count'];
$e_special_chars      = $validation_messages['error_special_chars'];
$e_restricted_letters = $validation_messages['error_restricted_letters'];

// Powered by content.
$powered_by      = get_field( 'powered_by_carfax_messaging', 'option' );
$powered_by_text = $powered_by['text'];
$powered_by_logo = $powered_by['logo'];

// Social Share Content.
$social_text  = 'es' == $lang ? 'Recuérdale a tus seres queridos<br /> que se protejan visitando Check To Protect' : 'Remind friends and family to Check To Protect';
$twitter_text = 'es' == $lang ? '53 millones de vehículos en los Estados Unidos tienen recalls pendientes. Toma un minuto de tu día para ver si el tuyo es uno de ellos.' : '53 million vehicles in the US have open safety recalls. Take a minute to see if yours is one.';

if ( 300 >= $sub_count ) : ?>
	<?php $method_vin = false; ?>
	<style>
		#formsubmit input.vin {
			display: none;
		}
	</style>
	<div class="grid_wrapper">
		<form id="formsubmit" method="post" action="<?= esc_url( $form_action ); ?>" enctype="multipart/form-data" class="<?= ! $method_vin ? 'method_vin' : 'method_plate'; ?>">
			<div class="grid__col">
				<div id="formtoggle">
					<?php if ( $_GET['plate_error'] ): //phpcs:ignore ?>
						<p class="error">There was a problem with your plate photo, try another image or enter your license manually.</p>
					<?php endif; ?>
					<?php if ( $_GET['no_results'] ): //phpcs:ignore 
						$plate_number = $_GET['plate_number'] ? strtoupper( $_GET['plate_number'] ) : ''; //phpcs:ignore 
						$state_id     = $_GET['state_id'] ? strtoupper( $_GET['state_id'] ) : ''; //phpcs:ignore 
						$vin          = $_GET['vin'] ? strtoupper( $_GET['vin'] ) : ''; //phpcs:ignore 
						$for          = $vin ? 'VIN: ' . $vin : 'plate: ' . $state_id . '-' . $plate_number;
						?>
						<p class="error"><?= esc_html( "We found no results for $for." ) ?><br /> Please check and try again.</p>
					<?php endif; ?>
					<p><?= 'es' == $lang ? 'Busca tu vehículo por:' : 'Look up vehicle by:'?></p>
					<button id="btn-plate" class="btn form-toggle <?= ! $method_vin ? 'active' : ''; ?>">
						<i class="ico-plate"></i><span><?= 'es' == $lang ? 'Place' : 'License Plate' ?></span>
					</button>
					<button id="btn-vin" class="btn form-toggle <?= $method_vin ? 'active' : ''; ?>">
						<i class="ico-pound"></i><span>VIN</span>
					</button>
					<label class="btn -alt file-upload">
						<input type="file" id="plate_photo" name="plate_photo" accept="image/*,.pdf" />
						<span><i class="ico-camera"></i><span>Photo of your license plate</span></span>
					</button>
				</div>
			</div>
			<div class="grid__col">
				<div class="form vin-search__form--home vin-search__form vin-form">
					<div class="g-recaptcha"
						data-sitekey="<?= esc_attr( $recaptcha_site_key ) ?>"
						data-callback="recaptchaCallback"
						data-size="invisible">
					</div>
					<div id="state-select" class="custom-select <?= ! $method_vin ? 'active' : ''; ?>" data-state-id-post="<?= $state_id ? esc_attr( $state_id ) : ''; ?>">
						<select
							id="license-state-select"
							class="repair-location-select"
							name="state_id"
							aria-label="<?= esc_attr( $state_text ); ?>">
							<option value=""><?= esc_html( $state_text ); ?></option>
							<option value="AL">AL</option>
							<option value="AK">AK</option>
							<option value="AR">AR</option>
							<option value="AZ">AZ</option>
							<option value="CA">CA</option>
							<option value="CO">CO</option>
							<option value="CT">CT</option>
							<option value="DC">DC</option>
							<option value="DE">DE</option>
							<option value="FL">FL</option>
							<option value="GA">GA</option>
							<option value="HI">HI</option>
							<option value="IA">IA</option>
							<option value="ID">ID</option>
							<option value="IL">IL</option>
							<option value="IN">IN</option>
							<option value="KS">KS</option>
							<option value="KY">KY</option>
							<option value="LA">LA</option>
							<option value="MA">MA</option>
							<option value="MD">MD</option>
							<option value="ME">ME</option>
							<option value="MI">MI</option>
							<option value="MN">MN</option>
							<option value="MO">MO</option>
							<option value="MS">MS</option>
							<option value="MT">MT</option>
							<option value="NC">NC</option>
							<option value="NE">NE</option>
							<option value="NH">NH</option>
							<option value="NJ">NJ</option>
							<option value="NM">NM</option>
							<option value="NV">NV</option>
							<option value="NY">NY</option>
							<option value="ND">ND</option>
							<option value="OH">OH</option>
							<option value="OK">OK</option>
							<option value="OR">OR</option>
							<option value="PA">PA</option>
							<option value="RI">RI</option>
							<option value="SC">SC</option>
							<option value="SD">SD</option>
							<option value="TN">TN</option>
							<option value="TX">TX</option>
							<option value="UT">UT</option>
							<option value="VT">VT</option>
							<option value="VA">VA</option>
							<option value="WA">WA</option>
							<option value="WI">WI</option>
							<option value="WV">WV</option>
							<option value="WY">WY</option>
						</select>
					</div>
					<input
						type="text"
						name="plate_number" 
						<?= $plate_number ? 'value="' . esc_attr( $plate_number ) . '"' : ''; ?>
						placeholder="<?= esc_attr( $plate_text ); ?>"
						maxlength="8"
						class="vin-search__input plate <?= ! $method_vin ? 'active' : ''; ?>"
						id="js-vin-input"
						aria-label="<?= esc_attr( $plate_text ); ?>"
					/>
					<input
						type="text"
						name="vin" 
						<?= $vin ? 'value="' . esc_attr( $vin ) . '' : ''; ?>
						placeholder="<?= esc_attr( $vin_text ); ?>"
						maxlength="17"
						class="vin-search__input vin <?= $method_vin ? 'active' : ''; ?>"
						id="js-vin-input"
						aria-label="<?= esc_attr( $vin_text ); ?>"
						<?php // the order the [data-validation] values depict the priority. ?>
						data-validation="required special restricted quantity"
					/>
					<button id="js-vin-btn" class="btn vin-search__btn vin-search__btn--home" type="button">
						Search
					</button>
				</div>
			</div>
		</form>
		<?php if ( $powered_by ) : ?>
			<div class="powered-by">
				<?php 
				if ( $powered_by_text ) {
					echo ( '<p>' . esc_html( $powered_by_text ) . '</p>' );
				} 
				if ( $powered_by_logo ) {
					$size = 'large';
					echo wp_get_attachment_image( $powered_by_logo, $size );
				} 
				?>
			</div>
		<?php endif; ?>
	<div class="recalls__share">
		<p><?= esc_html( $social_text ); ?></p>
		<div class="social-shares">
			<a class="social-share social-share--facebook" href="#" data-share-channel="facebook" data-title="53 million vehicles in the US have open safety recalls. Take a minute to see if yours is one. https://CheckToProtect.org Stay safe. Check your vehicle for open safety recalls." data-u="https://www.checktoprotect.org/">
				<span class="social-share__cta">Share</span>
				<i class="social-share__icon fa fa-facebook" aria-hidden="true"></i>
			</a>
			<a class="social-share social-share--twitter" href="#" data-share-channel="twitter" data-text="<?= esc_attr( $twitter_text ); ?>" data-url="https://CheckToProtect.org">
				<span class="social-share__cta">Tweet</span>
				<i class="social-share__icon fa fa-twitter" aria-hidden="true"></i>
			</a>
		</div>
	</div>

	<div id="js-vin-alert" class="vin-search__error" data-validation-error="false" role="alert">
		<p class="vin-search__error-title"><?= esc_html( $validation_title ); ?></p>
		<ul class="vin-search__error-list">
			<li class="vin-search__error-list-item" data-error-type="error-required" data-validation-error="false">
				<p class="vin-search__error-list-item--description"><?= esc_html( $e_required['message'] ); ?></p>
			</li>
			<li class="vin-search__error-list-item" data-error-type="error-special" data-validation-error="false">
				<p class="vin-search__error-list-item--description"><?= esc_html( $e_special_chars['message'] ); ?></p>
			</li>
			<li class="vin-search__error-list-item" data-error-type="error-restricted" data-validation-error="false">
				<p class="vin-search__error-list-item--description"><?= esc_html( $e_restricted_letters['message'] ); ?></p>
			</li>
			<li class="vin-search__error-list-item" data-error-type="error-quantity" data-validation-error="false">
				<p class="vin-search__error-list-item--description"><?= esc_html( $e_count['message'] ); ?></p>
			</li>
		</ul>
		<button id="js-vin-dismiss" type="button" class="vin-search__close" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php else : ?>
	<div class="ip-limit-reached">
		<p>It looks like you have a large number of VINs to search. We encourage you to request access to <a href="https://www.carfax.com/recall/" target="_blank">CARFAX's Bulk VIN search tool</a>, which allows you to search up to 10,000 VINs at a time. If you have trouble, please contact <a href="ChecktoProtect@nsc.org">ChecktoProtect@nsc.org</a></p>
	</div>
<?php endif; ?>
