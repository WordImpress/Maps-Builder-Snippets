<?php

/**
 * Function to translate or add custom "Read More" text in Mashups.
 * Change 'YOUR TEXT HERE' to your desired text.
 */
function my_custom_readmore_text() {
	// Add your custom text here.
	return __( 'YOUR TEXT HERE', 'google-maps-builder' );
}
add_filter( 'gmb_mashup_infowindow_content_readmore', 'my_custom_readmore_text' );
