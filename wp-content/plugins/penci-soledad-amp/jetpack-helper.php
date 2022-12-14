<?php

// Jetpack bits.

add_action( 'pre_penci_amp_render_post', 'penci_amp_jetpack_mods' );

/**
 * Disable Jetpack features that are not compatible with AMP.
 *
 **/
function penci_amp_jetpack_mods() {
	if ( Jetpack::is_module_active( 'stats' ) ) {
		add_action( 'penci_amp_post_template_footer', 'jetpack_penci_amp_add_stats_pixel' );
	}
	penci_amp_jetpack_disable_sharing();
	penci_amp_jetpack_disable_related_posts();
}

function penci_amp_jetpack_disable_sharing() {
	add_filter( 'sharing_show', '__return_false', 100 );
}

/**
 * Remove the Related Posts placeholder and headline that gets hooked into the_content
 *
 * That placeholder is useless since we can't ouput, and don't want to output Related Posts in AMP.
 *
 **/
function penci_amp_jetpack_disable_related_posts() {
	if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
		$jprp = Jetpack_RelatedPosts::init();
		remove_filter( 'the_content', array( $jprp, 'filter_add_target_to_dom' ), 40 );
	}
}

function jetpack_penci_amp_add_stats_pixel( $penci_amp_template ) {
	if ( ! has_action( 'wp_footer', 'stats_footer' ) ) {
		return;
	}
	?>
	<amp-pixel src="<?php echo esc_url( jetpack_penci_amp_build_stats_pixel_url() ); ?>"></amp-pixel>
	<?php
}

/**
 * Generate the stats pixel.
 *
 * Looks something like:
 *     https://pixel.wp.com/g.gif?v=ext&j=1%3A3.9.1&blog=1234&post=5678&tz=-4&srv=example.com&host=example.com&ref=&rand=0.4107963021218808
 */
function jetpack_penci_amp_build_stats_pixel_url() {
	global $wp_the_query;
	if ( function_exists( 'stats_build_view_data' ) ) { // added in https://github.com/Automattic/jetpack/pull/3445
		$data = stats_build_view_data();
	} else {
		$blog = Jetpack_Options::get_option( 'id' );
		$tz = get_option( 'gmt_offset' );
		$v = 'ext';
		$blog_url = Penci_AMP_WP_Utils::parse_url( site_url() );
		$srv = $blog_url['host'];
		$j = sprintf( '%s:%s', JETPACK__API_VERSION, JETPACK__VERSION );
		$post = $wp_the_query->get_queried_object_id();
		$data = compact( 'v', 'j', 'blog', 'post', 'tz', 'srv' );
	}

	$data['host'] = isset( $_SERVER['HTTP_HOST'] ) ? rawurlencode( $_SERVER['HTTP_HOST'] ) : ''; // input var ok
	$data['rand'] = 'RANDOM'; // amp placeholder
	$data['ref'] = 'DOCUMENT_REFERRER'; // amp placeholder
	$data = array_map( 'rawurlencode' , $data );
	return add_query_arg( $data, 'https://pixel.wp.com/g.gif' );
}
