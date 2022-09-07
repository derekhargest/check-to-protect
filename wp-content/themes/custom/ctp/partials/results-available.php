<?php
/**
 * Results Available
 *
 * @package  WordPress
 */

/**
 * Output for $response:
 *  - $response->vin                            Returns formatted VIN number
 *  - $response->model;                         Vehicle of VIN number
 *  - $response->make;                          Make of VIN number
 *  - $response->year;                          Year of VIN number
 *  - $response->recallAvailability;            Returns VIN recalls status
 *  - $response->serviceCampaignAvailability;   Returns VIN service campaign status
 *  - $response->recallAvailabilityMessage;     Returns VIN recall availability message
 *  - $response->recalls;                       Array
 *  - $response->serviceCampaigns;              Array
 */

/**
 * Output for $response->recalls:
 *  - $recall->date             Returns unformatted date of recall
 *  - $recall->nhtsaNumber;     NHTSA number
 *  - $recall->campaignNumber;  Recall number
 *  - $recall->campaign;        Title of recall
 *  - $recall->description;     Description of recall
 *  - $recall->safetyRisk;      Safety risk
 *  - $recall->status;          Status of recall
 *  - $recall->remedy;          Actionable remedy
 *  - $recall->expirationDate;  Returns unformatted date of expiration
 *  - $recall->high_priority;    Returns true or false
 */

$vehicle = $response;
$recalls = $response->recalls;

?>

<?php if ( $recalls ) : ?>
	<div class="recall-list">
		<?php foreach ( $recalls as $recall ) : ?>
			<article class="recall-list__recall <?= $recall->high_priority ? 'recall-list__recall--high-priority' : 'recall-list__recall--low-priority'; ?>" id="recall__<?= esc_attr( $recall->campaignNumber ); ?>">
				<header class="recall-list__heading" id="recall__<?= esc_attr( $recall->nhtsaNumber ); ?>">
					<button aria-label="Expand to view more details about <?= esc_attr( $recall->nhtsaNumber ); ?>" 
						data-toggle="collapse" 
						data-target="#recall__<?= esc_attr( $recall->nhtsaNumber ); ?>" 
						aria-expanded="false" 
						aria-controls="recall__<?= esc_html( $recall->nhtsaNumber ); ?>" 
						role="button" 
						class="recall-list__button collapsed">
					</button>
					<span class="recall-list__heading--date"><?php echo esc_html( gmdate( 'F d, Y', $recall->date / 1000 ) ); ?></span>
					<h1 class="recall-list__heading--title"><?= esc_html( $recall->campaign ); ?></h1>
					<p class="recall-list__heading--description">
						<span>Summary:</span>
						<?= esc_html( $recall->description ); ?>
					</p>
				</header>
				<div id="recall__<?= esc_attr( $recall->nhtsaNumber ); ?>" aria-labelledby="recall__<?= esc_attr( $recall->campaignNumber ); ?>" class="recall-list__content collapse show">
					<section class="recall-list__content--safety">
						<span>Safety Risk:</span>
						<p><?= esc_html( $recall->safetyRisk ); ?></p>
					</section>
					<section class="recall-list__content--remedy">
						<span>Remedy:</span>
						<?php 
						if ( $recall->status ) :
							$recall_status_class = '';
							$recall_status_text  = '';

							switch ( $recall->status ) {
								case 'RemedyAvailable':
									$recall_status_text  = strtoupper( 'Available' );
									$recall_status_class = 'available';
									break;
								case 'RemedyNotYetAvailable':
									$recall_status_text  = strtoupper( 'Not Yet Available' );
									$recall_status_class = 'not-available';
									break;
								default:
									$recall_status_text  = 'Unknown';
									$recall_status_class = 'unknown';
							}
							?>
							<span class="recall-list__content--remedy-status <?= esc_attr( $recall_status_class ); ?>"><?= esc_html( $recall_status_text ); ?></span>
							<p><?= esc_html( $recall->remedy ); ?></p>
						<?php endif; ?>
					</section>
					<section class="recall-list__content--campaign">
						<span>Campaign Number:</span> <?= esc_html( $recall->campaignNumber ); ?>
					</section>
					<section class="recall-list__contact--dealer">
						<p>Please contact your nearest dealer to schedule a service appointment.</p>
					</section>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
