<?php 
/**
 *  Registers a Shortcode to display a map with the mashup metabox location on the post.
 *
 */

/**
 * Displays the map
 *
 * @access      private
 * @since       1.0
 * @return      void
*/
function gmbsimple_map_shortcode( $atts ) {
	global $post;

	$atts = shortcode_atts(
		array(
			'postid'			=> $post->ID,
			'address'           => false,
			'width'             => '100%',
			'height'            => '400px',
			'enablescrollwheel' => 'true',
			'zoom'              => 15,
			'disablecontrols'   => 'false',
			'map_theme'           => array(
				'map_type'       => ! empty( $all_meta['gmb_type'][0] ) ? $all_meta['gmb_type'][0] : 'RoadMap',
				'map_theme_json' => ! empty( $all_meta['gmb_theme_json'][0] ) ? $all_meta['gmb_theme_json'][0] : 'none',
			),
		),
		$atts
	);
		$lat = get_post_meta($atts['postid'], '_gmb_lat');
		$long = get_post_meta($atts['postid'], '_gmb_lng');

		$map_id = uniqid( 'gmbsimple_map_' ); // generate a unique ID for this map

		if ( empty($lat) || empty($long) ) :
			return;
		endif;

		ob_start();

	?>
		<div class="gmbsimple_map_canvas" id="<?php echo esc_attr( $map_id ); ?>" style="height: <?php echo esc_attr( $atts['height'] ); ?>; width: <?php echo esc_attr( $atts['width'] ); ?>"></div>
		<script type="text/javascript">
			var map_<?php echo $map_id; ?>;
			function gmbsimple_run_map_<?php echo $map_id ; ?>(){
				var location = new google.maps.LatLng("<?php echo $lat[0]; ?>", "<?php echo $long[0]; ?>");

				// Create a new StyledMapType object, passing it an array of styles,
				// and the name to be displayed on the map type control.
				var styledMapType = new google.maps.StyledMapType(
					[{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#d3d3d3"},{"visibility":"on"}]}],
					{name: 'Styled Map'});


				var map_options = {
					zoom: <?php echo $atts['zoom']; ?>,
					center: location,
					scrollwheel: <?php echo 'true' === strtolower( $atts['enablescrollwheel'] ) ? '1' : '0'; ?>,
					disableDefaultUI: <?php echo 'true' === strtolower( $atts['disablecontrols'] ) ? '1' : '0'; ?>,
					mapTypeControlOptions: {
						mapTypeIds: ['roadmap', 'styled_map']
					}
				}
				map_<?php echo $map_id ; ?> = new google.maps.Map(document.getElementById("<?php echo $map_id ; ?>"), map_options);
				var marker = new google.maps.Marker({
				position: location,
				map: map_<?php echo $map_id ; ?>
				});

				//Associate the styled map with the MapTypeId and set it to display.
				map_<?php echo $map_id ; ?>.mapTypes.set('styled_map', styledMapType);
				map_<?php echo $map_id ; ?>.setMapTypeId('styled_map');
			}
			gmbsimple_run_map_<?php echo $map_id ; ?>();
		</script>
		<?php
		return ob_get_clean();

}
add_shortcode( 'gmbsimple_map', 'gmbsimple_map_shortcode' );

/**
 * Fixes a problem with responsive themes
 *
 * @access      private
 * @since       1.0.1
 * @return      void
*/

function gmbsimple_map_css() {
	echo '<style type="text/css">/* =Responsive Map fix
-------------------------------------------------------------- */
.gmbsimple_map_canvas img {
	max-width: none;
}</style>';

}
add_action( 'wp_head', 'gmbsimple_map_css' );


function cmb2_before_google_maps_mashup_metabox( $post_id, $cmb ) {
	?>
	<h4>Location Shortcode</h4>
	<p><em>If you want to display this location on a simple Google Map, copy this shortcode and paste anywhere on your site:</em></p>
	<input type="text" value="<?php echo '[gmbsimple postid=' . $post_id . ']';?>" class="field left" onclick="select()" readonly style="width:100%">
	<?php
}
add_action( 'cmb2_before_post_form_google_maps_mashup_metabox', 'cmb2_before_google_maps_mashup_metabox', 10, 2 );
