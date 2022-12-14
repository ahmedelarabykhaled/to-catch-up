<?php
$block = penci_get_builder_mod( 'penci_header_pb_block_2_select' );
if ( ! empty( $block ) ) {
	?>
    <div class="penci-header-block penci-header-block-2">
		<?php
		$block_id = '';
		$block_oj = get_page_by_path( $block, OBJECT, 'penci-block' );
		if ( $block_oj ) {
			$block_id = $block_oj->ID;
		}
		if ( $block_id ) {
			if ( did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->documents->get( $block_id )->is_built_with_elementor()  ) {
				echo penci_get_elementor_content( $block_id );
			} else {
				$block_content = get_post( $block_id );
				echo do_shortcode( $block_content->post_content );

				$shortcodes_custom_css = get_post_meta( $block_id, '_wpb_shortcodes_custom_css', true );

				echo '<style data-type="vc_shortcodes-custom-css">';
				if ( ! empty( $shortcodes_custom_css ) ) {
					echo $shortcodes_custom_css;
				}
				echo '</style>';
			}
		} ?>
    </div>
	<?php
}
