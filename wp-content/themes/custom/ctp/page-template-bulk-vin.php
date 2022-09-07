<?php
// Template Name: Bulk VIN Check
the_post();

global $post;

mg_get_template_part( 'partials', 'hero' );
?>

<section class="default page-<?= $post->post_name ?>">
	<div class="content wysiwyg">
		<?php
		$intro_text     = get_field('intro_text');

		$mobile_section = get_field('mobile_repair_section');
		$m_title        = $mobile_section['section_title'];
		$m_intro_text   = $mobile_section['intro_text'];
		$m_after_text   = $mobile_section['after_dropdown_text'];

		$bulk_section   = get_field('bulk_check_section');
		$b_title        = $bulk_section['section_title'];
		$b_intro_text   = $bulk_section['section_text'];
		$b_cta_url      = $bulk_section['cta']['url'];
		$b_cta_title    = $bulk_section['cta']['title'];
		$b_cta_target   = $bulk_section['cta']['target'] ? $bulk_section['cta']['target'] : '_self';

		$airbag_section = get_field('airbag_check_section');
		$a_title        = $airbag_section['section_title'];
		$a_intro_text   = $airbag_section['section_text'];
		$a_cta_url      = $airbag_section['cta']['url'];
		$a_cta_title    = $airbag_section['cta']['title'];
		$a_cta_target   = $airbag_section['cta']['target'] ? $airbag_section['cta']['target'] : '_self';
		?>

		<?php if ( $intro_text ) : ?>
			<?php echo $intro_text; ?>
			<span class="divider"></span>
		<?php endif; ?>

		<?php if ( $mobile_section ) : ?>
			<h2><?php echo $m_title; ?></h2>
			<?php echo $m_intro_text; ?>

			<?php



			?>
			<div class="custom-select">

				<label for="repair-location-select">Select a city</label>
				<select id="repair-location-select" class="repair-location-select">
					<option value="0">Location</option>
					<?php

					$city_array = get_repair_locations('selectList');

					foreach($city_array as $city) {
						$name_no_space 			= str_replace(' ','',$city);
						$name_option_value		= strtolower(str_replace(',','-',$name_no_space));

						?>
						<option value="<?php echo esc_html($name_option_value); ?>"><?php echo esc_html($city) ?></option>
						<?php
					}

					?>
				</select>
			</div>

			<div class="location-content">
				<h2 class="location-title">Location</h2>
				<div class="locations">
					<!-- AJAX Repair Locations will appear here -->
				</div>
				<div class="disclaimer">
					<p>Make sure you have your 17-character Vehicle Identification Number (VIN) on hand.</p>
					<p>Don’t see an option for your vehicle? Call your local dealer to see if they offer mobile repair services in your area.</p>
				</div>
			</div>
			<?php echo $m_after_text; ?>
			<span class="divider"></span>
		<?php endif; ?>

		<?php if ( $bulk_section ) : ?>
			<h2><?php echo $b_title; ?></h2>
			<?php echo $b_intro_text; ?>
			<?php if ( $b_cta_url ) : ?>
				<a class="button yellow" href="<?php echo esc_url( $b_cta_url ); ?>" target="<?php echo esc_attr( $b_cta_target ); ?>" rel="noopener noreferrer"><?php echo esc_html( $b_cta_title ); ?></a>
			<?php endif; ?>
			<span class="divider"></span>
		<?php endif; ?>

		<?php if ( $airbag_section ) : ?>
			<h2><?php echo $a_title; ?></h2>
			<?php echo $a_intro_text; ?>
			<?php if ( $a_cta_url ) : ?>
				<a class="button yellow" href="<?php echo esc_url( $a_cta_url ); ?>" target="<?php echo esc_attr( $a_cta_target ); ?>" rel="noopener noreferrer"><?php echo esc_html( $a_cta_title ); ?></a>
			<?php endif; ?>
		<?php endif; ?>

		<?php
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
