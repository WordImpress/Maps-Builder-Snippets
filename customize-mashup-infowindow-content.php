<?php
/**
 * Customize the Mashup Marker Infowindow
 *
 * @param $response
 * @param $marker_data
 * @param $post_id
 */
function my_custom_mashup_infowindow_content( $response, $marker_data, $post_id ) {

//Set Vars
	$post_object      = get_post( $post_id );
	$marker_title     = $post_object->post_title;
	$marker_content   = wp_trim_words( $post_object->post_excerpt, 55 );
	$marker_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'large' );
	$response         = '';

	$response['infowindow'] = '<div id="infobubble-content" class="main-place-infobubble-content">';

	if ( ! empty( $marker_thumbnail[0] ) ) {
		$response['infowindow'] .= '<div class="place-thumb"><img src="' . $marker_thumbnail[0] . '" alt="' . $marker_title . '"></div>';
	}
	if ( ! empty( $marker_title ) ) {
		$response['infowindow'] .= '<p class="place-title">' . $marker_title . '</p>';
	}

	if ( ! empty( $marker_content ) ) {
		$response['infowindow'] .= '<p class="place-description">' . $marker_content . '</p>';
	}

	$response['infowindow'] .= '<a href="' . get_permalink( $post_id ) . '" title="' . $marker_title . '" class="gmb-mashup-single-link">' . __( 'Read More &raquo;' ) . '</a>';

	$response['infowindow'] .= '</div>';
	echo json_encode( $response );
	wp_die();

}

add_filter( 'gmb_mashup_infowindow_content', 'my_custom_mashup_infowindow_content', 10, 3 );