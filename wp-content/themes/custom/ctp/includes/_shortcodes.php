<?php

/**
 * Gallery Shortcode
 */
function mg_gallery_shortcode( $atts, $content = null ) {
	extract( $atts, EXTR_SKIP);

	if( $ids ) {
		$ids = explode(',', $ids );
		$output = '';

		$output = '<div class="gallery-slider-container flexslider-container">';
		$output .= '<div class="gallery-slider flexslider">';
		$output .= '<ul class="gallery-slides slides">';

		foreach( $ids as $id ) {
			$attachment = get_post( $id );
			$image = get_image_object( $attachment );

			$output .= '<li>';
				$output .= '<a href="'. $image['sizes']['large'] .'" class="lightbox">';
					$output .= '<img src="'. $image['sizes']['medium'] .'" alt="'. $image['title'] .'" />';
				$output .= '</a>';
			$output .= '</li>';
		}

		$output .= '</ul>';
		$output .= '</div>';
		$output .= '</div>';
	}

	return $output;
}
remove_shortcode('gallery', 'gallery_shortcode');
add_shortcode('gallery', 'mg_gallery_shortcode');