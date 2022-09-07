<?php
// Template Name: VIN Check Protected

require_once 'vendor/autoload.php';

the_post();

if (post_password_required()) : ?>
	<?php echo get_the_password_form(); ?>
<?php else : ?>
	<?php
	// Easier to set here than remove logic below
	$is_spam              = false;

	// for location details from IP via ipinfo.io
	$user_ip 	    = $_SERVER['REMOTE_ADDR'];
	$ip_details		= json_decode(file_get_contents("http://ipinfo.io/{$user_ip}/json"));
	$ip_city		= $ip_details->city;
	$ip_region		= $ip_details->region;
	$ip_country		= $ip_details->country;

	$plate_number 	= $_POST['plate_number'];
	$state_id		= $_POST['state_id'];

	if ($plate_number || $state_id) {
		$plate_failure = false;
		$xml_url = 'https://quickvin.carfax.com/1';

		$plate_request=<<<XML
		<carfax-request>
		<license-plate></license-plate>
		<state></state>
		<product-data-id>7F2D05CDBD014DB8</product-data-id>
		<location-id>CARFAX</location-id>
		</carfax-request>
		XML;

		$xml_request = new SimpleXMLElement($plate_request);
		$xml_request->{'license-plate'} = $plate_number;
		$xml_request->state 			= $state_id;

		$xml_args = array(
		'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'headers' => array(
				'Content-Type' => 'application/xml'
			),
			'body' => $xml_request->asXML(),
			'sslverify' => false
		);

		// Send request and store raw response
		$xml_response_raw = wp_remote_post( $xml_url, $xml_args);
		// Create new SimpleXML object from only XML of response
		$xml_response = simplexml_load_string($xml_response_raw["body"]);
		// Get only the value of the VIN from response data and trim
		$vin_from_plate = trim($xml_response->quickvinplus->{'vin-info'}->vin);
		// Set $vin if a value was returned
		if ($vin_from_plate) {
			$vin = $vin_from_plate;
			$response = $is_spam ? null : MG_Carfax()->get_recall_data( $vin );
		} else {
			$plate_failure = true;
		}
	} else {
		$vin = $_POST['vin'];
		$response = $is_spam ? null : MG_Carfax()->get_recall_data( $vin );
	}

	$search_date	= date("Y-m-d H:i:s");
	$vehicle        = $is_spam ? null : $response;
	$recalls        = $is_spam ? [] : $response->recalls;
	$recall_total   = strval( count($recalls) );
	$recalls_found  = $recall_total > 0;
	$recall_vehicle = get_field( 'tab2_vehicle_info' );
	$vehicle_model  = $is_spam ? '' : sprintf( '<h2 class="recalls__model">%s %s %s</h2>', $vehicle->year, $vehicle->make, $vehicle->model );
	$total_recall 	= "";
	if( $recalls ) {
		foreach( $recalls as $recall ) {
			$total_recall .= $recall->campaign . " ";
			$total_recall .= $recall->description . " ";
			$total_recall .= $recall->safetyRisk . " ";
			$total_recall .= $recall->remedy . " ";
			$total_recall .= $recall->campaignNumber . " ";
			$total_recall .= "| ";
		}
	}

	// for tablet mode only
	$back_button_text = get_field('back_button_text');
	$tablet_layout	= get_field('tablet_layout');

	// recalls fueron encontrados en
	$recall_text_prefix = $_GET['lang'] == 'es' ? 'recall' : 'Open Recall';
	$recall_text_suffix = $_GET['lang'] == 'es' ? 'fueron<br /> encontrados en' : 'for';

	if ($_GET['lang'] == 'es' && $recalls_found) {
		$vehicle_model .= '<p>Lo sentimos, no pudimos traducir los resultados. Por favor, contacte a su concesionario.</p>';
	}

	mg_get_template_part( 'partials', 'results-vin-look-up-protected' );

	$social_text = $_GET['lang'] == 'es' ? 'Recuérdale a tus seres queridos<br /> que se protejan visitando Check To Protect' : 'Remind friends and family to Check To Protect';
	$twitter_text = $_GET['lang'] == 'es' ? '53 millones de vehículos en los Estados Unidos tienen recalls pendientes. Toma un minuto de tu día para ver si el tuyo es uno de ellos.' : '53 million vehicles in the US have open safety recalls. Take a minute to see if yours is one.';
	$find_dealer = $_GET['lang'] == 'es' ? 'Encuentra a tu concesionario' : 'Find Dealer';
	?>

	<section class="recalls">
		<div class="content">
			<header class="recalls__header">
				<?php
				# -------------------------------------------------------------------
				# 1FAHP26W49G252740 = valid VIN, no recalls                         ;
				# JH4DB1660LS017594 = valid VIN, 1 recall                           ;
				# -------------------------------------------------------------------
				# JTMKF4DV5B5309254 = invalid VIN, not due to characters entered    ;
				# 2ITRX07W53C371582 = invalid VIN due to usage of "I, Q, O"         ;
				# JTM*$&AJKYT382!2$ = invalid VIN due to special characters         ;
				# -------------------------------------------------------------------
				?>
				<div class="recalls__header--row">
					<h1 class="recalls__heading<?php if( $recalls_found ) { echo ' dealer print'; } ?>">
						<?php
						# -------------------------------------------------------------------
						# 1FAHP26W49G252740 = valid VIN, no recalls                         ;
						# JH4DB1660LS017594 = valid VIN, 1 recall                           ;
						# -------------------------------------------------------------------
						# JTMKF4DV5B5309254 = invalid VIN, not due to characters entered    ;
						# 2ITRX07W53C371582 = invalid VIN due to usage of "I, Q, O"         ;
						# JTM*$&AJKYT382!2$ = invalid VIN due to special characters         ;
						# -------------------------------------------------------------------
						?>
						<?php if( $is_spam ) : ?>
							<?= get_field( 'tab6_heading' ) ?>
						<?php elseif ($plate_failure) : ?>
							<?= get_field( 'tab7_heading' ) ?>
						<?php elseif( $recalls_found ) : ?>
							<span class="recalls__quantity alert"><?= $recall_total; ?></span> <?= $recall_text_prefix; ?><?php if($recall_total != 1) echo 's'; ?> <?= $recall_text_suffix;?>:
						<?php else :
							switch( $response->recallAvailability ) {
								case 'InvalidVin':
									echo get_field( 'tab1_heading' );
									break;
								case 'NoRecallOEMProvidesData':
									echo '<span class="recalls__quantity clear">0 ';
									echo get_field( 'tab2_heading' );
									echo '</span>';
									break;
								case 'NoRecallOEMDoesNotProvideData':
									echo get_field( 'tab3_heading' );
									break;
								case 'NoRecallOEMUnknown':
									echo get_field( 'tab4_heading' );
									break;
								default:
									if ( $response->errors['500'] ) {
										echo get_field( 'tab5_heading' );
									} else {
										echo '<span class="recalls__quantity clear">';
										if(isset($_GET['q'])){
											if($_GET['q'] == 1) echo 'Your 3-Month Reminder has been added';
										} else{
											echo get_field( 'tab6_heading' );
										}
										echo '</span>';
									}
							}
						endif; ?>
					</h1>
					<?php if( $recalls_found ) : ?>
						<div class="recalls__buttons">
							<?php if( $link = MG_Carfax()->get_make_dealer_link( $vehicle->make ) ) : ?>
								<a href="<?= $link; ?>" class="button yellow" target="_blank" rel="noopener" title="Click here to find dealers for your vehicle recall" style="white-space: nowrap;"><?= $find_dealer; ?></a>
							<?php endif; ?>
							<a href="javascript:;" class="button yellow print js-print" title="Click here to print all the available recalls for your vehicle">
								<i class="ico-print" aria-hidden="true">
									<span class="sr-only">Print Icon</span>
								</i>
							</a>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( !$tablet_layout ) : ?>
					<div class="recalls__share">
						<p><small><em><?= $social_text; ?></em></small></p>
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
				<?php endif; ?>

				<?php if( $recalls_found ) {
					echo $vehicle_model;
				} else {
					switch ( $response->recallAvailability ) {
						case 'NoRecallOEMProvidesData':
							if ( get_field('tab2_vehicle_info') ) {
								echo $vehicle_model;
							}
							break;
						case 'NoRecallOEMDoesNotProvideData':
							if ( get_field('tab3_vehicle_info') ) {
								echo $vehicle_model;
							}
							break;
						default:
							break;
					}
				} ?>
			</header>
			<?php if( $is_spam ) {
				// for spam!
				mg_get_template_part( 'partials', 'results-default' );
			} elseif ( $plate_failure ) {
				// if License Plate search returned no VIN
				mg_get_template_part( 'partials', 'results-license-plate' );
			} elseif ( $response->errors['500'] ) {
				// example vin: 1a1a1a1a1a1a1a1a1
				mg_get_template_part( 'partials', 'results-error' );
			} else {
				switch( $response->recallAvailability ) {
					// example vin: 11111111111111111
					case 'InvalidVin':
						mg_get_template_part( 'partials', 'results-invalid-vin' );
						break;
					// example vin: 1HGEJ8246YL006494
					case 'NoRecallOEMProvidesData':
						mg_get_template_part( 'partials', 'results-no-recall' );
						break;
					// example vin: 5YJSA1DP8DFP16168
					case 'NoRecallOEMDoesNotProvideData':
						mg_get_template_part( 'partials', 'results-no-data', true, array( 'response' => $response ) );
						break;
					// example vin: 1GNFK16282J138280
					case 'NoRecallOEMUnknown':
						mg_get_template_part( 'partials', 'results-unknown' );
						break;
					// example vin: 1D4HD48286F110145
					case 'Available':
						mg_get_template_part( 'partials', 'results-available', true, array( 'response' => $response ) );
						break;
					// empty search
					default:
						mg_get_template_part( 'partials', 'results-default' );
				}
			} ?>
		</div>
		<?php if( get_field('include_back_button') && ( $vin || $plate_number ) ) : ?>
			<div class="back-btn-container">
				<a class="back-btn btn form-toggle" href="<?php echo esc_url( get_permalink()); ?>"><?php echo $back_button_text; ?></a>
			</div>
		<?php endif; ?>
	</section>
	<?php
	if ( $tablet_layout ) {
		// Send results to google sheet via zapier
		$endpoint = 'https://hooks.zapier.com/hooks/catch/7343953/btepnzc/';
		$body = [
			'search_date' 	=> $search_date,
			'vin' 			=> $vin,
			'make' 			=> $vehicle->make,
			'model' 		=> $vehicle->model,
			'num_recalls' 	=> $recall_total,
			'details'		=> $total_recall,
			'ip_address'	=> $user_ip,
			'city'			=> $ip_city,
			'state'			=> $ip_region,
			'country'		=> $ip_country,
		];
		$body = wp_json_encode( $body );
		$options = [
		    'body'        => $body,
		    'headers'     => [
		        'Content-Type' => 'application/json',
		    ],
		    'timeout'     => 60,
		    'redirection' => 5,
		    'blocking'    => true,
		    'httpversion' => '1.0',
		    'sslverify'   => false,
		    'data_format' => 'body',
		];

		if( $recalls_found ) {
			wp_remote_post( $endpoint, $options );
		} else {
			switch( $response->recallAvailability ) {
				// example vin: 1HGEJ8246YL006494
				case 'NoRecallOEMProvidesData':
					wp_remote_post( $endpoint, $options );
					break;
				// example vin: 5YJSA1DP8DFP16168
				case 'NoRecallOEMDoesNotProvideData':
					$details = "OEM does not provide data";
					$recall_total = "";
					$body = [
						'search_date' 	=> $search_date,
						'vin' 			=> $vin,
						'make' 			=> $vehicle->make,
						'model' 		=> $vehicle->model,
						'num_recalls' 	=> $recall_total,
						'details'		=> $details,
						'ip_address'	=> $user_ip,
						'city'			=> $ip_city,
						'state'			=> $ip_region,
						'country'		=> $ip_country,
					];
					$body = wp_json_encode( $body );
					$options = [
					    'body'        => $body,
					    'headers'     => [
					        'Content-Type' => 'application/json',
					    ],
					    'timeout'     => 60,
					    'redirection' => 5,
					    'blocking'    => true,
					    'httpversion' => '1.0',
					    'sslverify'   => false,
					    'data_format' => 'body',
					];
					wp_remote_post( $endpoint, $options );
					break;
				// example vin: 1GNFK16282J138280
				case 'NoRecallOEMUnknown':
					$details = "OEM unknown";
					$recall_total = "";
					$body = [
						'search_date' 	=> $search_date,
						'vin' 			=> $vin,
						'make' 			=> $vehicle->make,
						'model' 		=> $vehicle->model,
						'num_recalls' 	=> $recall_total,
						'details'		=> $details,
						'ip_address'	=> $user_ip,
						'city'			=> $ip_city,
						'state'			=> $ip_region,
						'country'		=> $ip_country,
					];
					$body = wp_json_encode( $body );
					$options = [
					    'body'        => $body,
					    'headers'     => [
					        'Content-Type' => 'application/json',
					    ],
					    'timeout'     => 60,
					    'redirection' => 5,
					    'blocking'    => true,
					    'httpversion' => '1.0',
					    'sslverify'   => false,
					    'data_format' => 'body',
					];
					wp_remote_post( $endpoint, $options );
					break;
				// empty search
				default:
			}
		}
	} ?>
<?php endif; ?>
