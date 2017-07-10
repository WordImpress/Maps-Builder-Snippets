<?php
/**
 * Centers the map based on current post's latitude and longitude.
 *
 * In cases where the same map is used across several posts, it may be helpful
 * to center the map on the location of the current post. This function updates
 * the lat/lng coordinates of the map to use the coordinates of the post in
 * which the map is displayed. Must be used within The Loop.
 *
 * @param array $map_data Localized JSON data used to construct the map.
 * @return array
 */
function my_custom_map_center( $map_data ) {
	// Get the current post ID.
	$post_id = get_the_ID();

	// If using custom meta_keys, replace _gmb_lat and _gmb_lng.
	$post_lat = get_post_meta( $post_id, '_gmb_lat', true );
	$post_lng = get_post_meta( $post_id, '_gmb_lng', true );

	if ( empty( $post_lat ) || empty( $post_lng ) ) {
		// Do not modify map if current post's lat/lng is empty.
		return $map_data;
	}

	// Get map ID of the first map on the page.
	reset( $map_data );
	$map_id = key( $map_data );

	// Update map center to current post's lat/lng.
	$map_data[ $map_id ]['map_params']['latitude'] = $post_lat;
	$map_data[ $map_id ]['map_params']['longitude'] = $post_lng;

	return $map_data;
}
add_filter( 'gmb_localized_data', 'my_custom_map_center' );
