<?php

$vin            = $_POST['vin'];
$plate_number 	= $_POST['plate_number'];
$state_id		= $_POST['state_id'];
$vin_look_up    = get_field( 'home_vin_lookup' );
$vin_text       = $vin_look_up['placeholder_text'] ? $vin_look_up['placeholder_text'] : "Enter 17-character VIN (Vehicle Identification Number)";
$plate_text		= "Enter License Plate Number";
$state_text		= "State";
$subtext		= 'All recall repairs are FREE at your local dealer';

if ($_GET['lang'] == 'es') {
	$vin_text   = !is_front_page() ? 'Ingresa el VIN de 17 caracteres' : 'Ingresa el VIN ( número de identificación de vehículo ) de 17 caracteres';
	$subtext    = 'Todas las reparaciones de llamados a revisión son gratis en su concesionario local';
	$plate_text		= "Ingresa el Número de Placa";
	$state_text		= "Estado";
}

$form_action    = get_the_permalink();

// VIN validation messages
$validation_messages    = get_field('vin_validator', 'option');
$validation_title       = $_GET['lang'] == 'es' ? 'VIN incorrecto:' : 'Invalid VIN:';
$e_required             = $validation_messages['error_required'];
$e_count                = $validation_messages['error_count'];
$e_special_chars        = $validation_messages['error_special_chars'];
$e_restricted_letters   = $validation_messages['error_restricted_letters'];

// Powered by content
$powered_by 		= get_field('powered_by_carfax_messaging', 'option');
$powered_by_text 	= $powered_by['text'];
$powered_by_logo	= $powered_by['logo'];
?>

<?php if ($plate_number || $state_id) : ?>
	<style>
		#formsubmit input.vin {
			display: none;
		}
	</style>
	<?php $method_vin = false; ?>
<?php else : ?>
	<style>
		#formsubmit #state-select, #formsubmit input.plate {
			display: none;
		}
	</style>
	<?php $method_vin = true; ?>
<?php endif; ?>
<?php if ($_GET['lang'] == 'es') : ?>
	<div id="formtoggle">
		<p>Busca tu vehículo por:</p>
		<button id="btn-vin" class="btn form-toggle <?php if($method_vin){ echo "active";} ?>">VIN</button>
		<button id="btn-plate" class="btn form-toggle <?php if(!$method_vin){ echo "active";} ?>">Placa</button>
	</div>
<?php else : ?>
	<div id="formtoggle">
		<p>Look up vehicle by:</p>
		<button id="btn-vin" class="btn form-toggle <?php if($method_vin){ echo "active";} ?>">VIN</button>
		<button id="btn-plate" class="btn form-toggle <?php if(!$method_vin){ echo "active";} ?>">License Plate</button>
	</div>
<?php endif; ?>

<form id="formsubmit" class="form vin-search__form vin-form <?php if($method_vin){ echo "method_vin";} else {echo "method_plate";} ?>" method="post" action="<?= $form_action; ?>">
    <div id="state-select" class="custom-select" data-state-id-post="<?php if($state_id){ echo $state_id;} ?>">
    	<select
    		id="license-state-select"
    		class="repair-location-select"
    		name="state_id"
    		aria-label="<?php echo $state_text; ?>">
    		<option value=""><?php echo $state_text; ?></option>
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
		name="plate_number" <?php if( $plate_number ) : ?>
		value="<?= $plate_number; ?>"<?php endif; ?>
		placeholder="<?= $plate_text; ?>"
		maxlength="8"
		class="vin-search__input plate"
		id="js-vin-input"
		aria-label="<?= $plate_text; ?>"
		 />
	<input
		type="text"
		name="vin" <?php if( $vin ) : ?>
		value="<?= $vin; ?>"<?php endif; ?>
		placeholder="<?= $vin_text; ?>"
		maxlength="17"
		class="vin-search__input vin"
		id="js-vin-input"
		aria-label="<?= $vin_text; ?>"
		<?php # the order the [data-validation] values depict the priority ?>
		data-validation="required special restricted quantity" />
	<button
		id="js-vin-btn"
		class="vin-search__btn"
		type="button"
		aria-label="Search Button Icon">
		<i class="ico-search" aria-hidden="true">
			<span class="sr-only">Search Icon</span>
		</i>
	</button>
</form>

<?php if($powered_by) : ?>
	<div class="powered-by">
		<?php if($powered_by_text) {
			echo ( '<p>' . $powered_by_text . '</p>' );
		} ?>
		<?php if($powered_by_logo) {
			$size = 'large';
		    echo wp_get_attachment_image( $powered_by_logo, $size );
		} ?>
	</div>
<?php endif; ?>

<div id="js-vin-alert" class="vin-search__error" data-validation-error="false" role="alert">
	<p class="vin-search__error-title"><?= $validation_title; ?></p>
	<ul class="vin-search__error-list">
		<li class="vin-search__error-list-item" data-error-type="error-required" data-validation-error="false">
			<p class="vin-search__error-list-item--description"><?= $e_required['message']; ?></p>
		</li>
		<li class="vin-search__error-list-item" data-error-type="error-special" data-validation-error="false">
			<p class="vin-search__error-list-item--description"><?= $e_special_chars['message']; ?></p>
		</li>
		<li class="vin-search__error-list-item" data-error-type="error-restricted" data-validation-error="false">
			<p class="vin-search__error-list-item--description"><?= $e_restricted_letters['message']; ?></p>
		</li>
		<li class="vin-search__error-list-item" data-error-type="error-quantity" data-validation-error="false">
			<p class="vin-search__error-list-item--description"><?= $e_count['message']; ?></p>
		</li>
	</ul>
	<button id="js-vin-dismiss" type="button" class="vin-search__close" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>