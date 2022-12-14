<?php

require_once( PENCI_AMP_DIR . '/includes/utils/class-amp-dom-utils.php' );
require_once( PENCI_AMP_DIR . '/includes/utils/class-amp-html-utils.php' );
require_once( PENCI_AMP_DIR . '/includes/utils/class-amp-string-utils.php' );
require_once( PENCI_AMP_DIR . '/includes/utils/class-amp-wp-utils.php' );

require_once( PENCI_AMP_DIR . '/includes/class-amp-content.php' );

require_once( PENCI_AMP_DIR . '/includes/sanitizers/class-amp-style-sanitizer.php' );
require_once( PENCI_AMP_DIR . '/includes/sanitizers/class-amp-blacklist-sanitizer.php' );
require_once( PENCI_AMP_DIR . '/includes/sanitizers/class-amp-tag-and-attribute-sanitizer.php' );
require_once( PENCI_AMP_DIR . '/includes/sanitizers/class-amp-img-sanitizer.php' );
require_once( PENCI_AMP_DIR . '/includes/sanitizers/class-amp-video-sanitizer.php' );
require_once( PENCI_AMP_DIR . '/includes/sanitizers/class-amp-iframe-sanitizer.php' );
require_once( PENCI_AMP_DIR . '/includes/sanitizers/class-amp-audio-sanitizer.php' );
require_once( PENCI_AMP_DIR . '/includes/sanitizers/class-amp-playbuzz-sanitizer.php' );
require_once( PENCI_AMP_DIR . '/includes/sanitizers/class-amp-form-sanitizer.php' );
require_once( PENCI_AMP_DIR . '/includes/sanitizers/class-amp-rule-spec.php' );

require_once( PENCI_AMP_DIR . '/includes/embeds/class-amp-twitter-embed.php' );
require_once( PENCI_AMP_DIR . '/includes/embeds/class-amp-youtube-embed.php' );
require_once( PENCI_AMP_DIR . '/includes/embeds/class-amp-dailymotion-embed.php' );
require_once( PENCI_AMP_DIR . '/includes/embeds/class-amp-vimeo-embed.php' );
require_once( PENCI_AMP_DIR . '/includes/embeds/class-amp-soundcloud-embed.php' );
require_once( PENCI_AMP_DIR . '/includes/embeds/class-amp-gallery-embed.php' );
require_once( PENCI_AMP_DIR . '/includes/embeds/class-amp-instagram-embed.php' );
require_once( PENCI_AMP_DIR . '/includes/embeds/class-amp-vine-embed.php' );
require_once( PENCI_AMP_DIR . '/includes/embeds/class-amp-facebook-embed.php' );
require_once( PENCI_AMP_DIR . '/includes/embeds/class-amp-pinterest-embed.php' );

class Penci_AMP_Post_Template {
	const SITE_ICON_SIZE = 32;
	const CONTENT_MAX_WIDTH = 600;

	// Needed for 0.3 back-compat
	const DEFAULT_NAVBAR_BACKGROUND = '#0a89c0';
	const DEFAULT_NAVBAR_COLOR = '#fff';

	private $template_dir;
	private $data;

	public function __construct( $post_id ) {
		$this->template_dir = apply_filters( 'penci_amp_post_template_dir', PENCI_AMP_DIR . '/templates' );

		$this->ID   = $post_id;
		$this->post = get_post( $post_id );

		$_font_urls = penci_amp_get_font_urls();

		$content_max_width = self::CONTENT_MAX_WIDTH;
		if ( isset( $GLOBALS['content_width'] ) && $GLOBALS['content_width'] > 0 ) {
			$content_max_width = $GLOBALS['content_width'];
		}
		$content_max_width = apply_filters( 'penci_amp_content_max_width', $content_max_width );

		$this->data = array(
			'content_max_width' => $content_max_width,

			'document_title' => function_exists( 'wp_get_document_title' ) ? wp_get_document_title() : wp_title( '', false ), // back-compat with 4.3
			'canonical_url'  => penci_amp_get_canonical_url(),
			'home_url'       => home_url(),
			'blog_name'      => get_bloginfo( 'name' ),

			'html_tag_attributes' => array(),
			'body_class'          => 'penci-amp-body',

			'site_icon_url'         => penci_amp_site_icon_url( self::SITE_ICON_SIZE ),
			'placeholder_image_url' => penci_amp_get_asset_url( 'images/placeholder-icon.png' ),

			'featured_image'     => false,
			'comments_link_url'  => false,
			'comments_link_text' => false,

			'penci_amp_runtime_script'    => 'https://cdn.ampproject.org/v0.js',
			'penci_amp_component_scripts' => array(),

			'customizer_settings' => array(),

			'font_urls' => $_font_urls,

			'post_penci_amp_styles' => array(
				
			),

			/**
			 * Add amp-analytics tags.
			 *
			 * This filter allows you to easily insert any amp-analytics tags without needing much heavy lifting.
			 *
			 * @since 0.4
			 *.
			 *
			 * @param    array $analytics An associative array of the analytics entries we want to output. Each array entry must have a unique key, and the value should be an array with the following keys: `type`, `attributes`, `script_data`. See readme for more details.
			 * @param    object $post The current post.
			 */
			'penci_amp_analytics'   => apply_filters( 'amp_post_template_analytics', array(), $this->post ),
		);


			$this->build_post_content();
			$this->build_post_data();
			$this->build_customizer_settings();
			$this->build_html_tag_attributes();

		$this_data = apply_filters( 'penci_amp_post_template_data', $this->data, $this->post );
		$this_data = apply_filters( 'amp_post_template_data', $this_data, $this->post );

		$this->data = $this_data;
	}

	public function get( $property, $default = null ) {
		if ( isset( $this->data[ $property ] ) ) {
			return $this->data[ $property ];
		} else {
			_doing_it_wrong( __METHOD__, sprintf( esc_html__( 'Called for non-existant key ("%s").', 'penci-amp' ), esc_html( $property ) ), '0.1' );
		}

		return $default;
	}

	public function get_customizer_setting( $name, $default = null ) {
		$settings = $this->get( 'customizer_settings' );
		if ( ! empty( $settings[ $name ] ) ) {
			return $settings[ $name ];
		}

		return $default;
	}

	public function load( $template ) {
		$this->load_parts( array( $template ) );
	}

	public function load_parts( $templates ) {
		foreach ( $templates as $template ) {
			$file = $this->get_template_path( $template );
			$this->verify_and_include( $file, $template );
		}
	}

	private function get_template_path( $template ) {
		return sprintf( '%s/%s.php', $this->template_dir, $template );
	}

	private function add_data( $data ) {
		$this->data = array_merge( $this->data, $data );
	}

	private function add_data_by_key( $key, $value ) {
		$this->data[ $key ] = $value;
	}

	private function merge_data_for_key( $key, $value ) {
		if ( is_array( $this->data[ $key ] ) ) {
			$this->data[ $key ] = array_merge( $this->data[ $key ], $value );
		} else {
			$this->add_data_by_key( $key, $value );
		}
	}

	private function build_post_data() {

		$post_title              = get_the_title( $this->ID );
		$post_publish_timestamp  = get_the_date( 'U', $this->ID );
		$post_modified_timestamp = get_post_modified_time( 'U', false, $this->post );


		$post_author   = isset( $this->post->post_author ) ? get_userdata( $this->post->post_author ) : '';

		$this->add_data( array(
			'post'                    => $this->post,
			'post_id'                 => $this->ID,
			'post_title'              => $post_title,
			'post_publish_timestamp'  => $post_publish_timestamp,
			'post_modified_timestamp' => $post_modified_timestamp,
			'post_author'             => $post_author,
		) );

		$metadata = array(
			'@context'         => 'http://schema.org',
			'@type'            => 'WebSite',
			'mainEntityOfPage' => $this->get( 'canonical_url' ),
			'publisher'        => array(
				'@type' => 'Organization',
				'name'  => $this->get( 'blog_name' ),

			),
			'headline'         => $post_title,
			'datePublished'    => date( 'c', $post_publish_timestamp ),
			'dateModified'     => date( 'c', $post_modified_timestamp ),
			'author'           => array(
				'@type' => 'Person',
				'name'  => isset( $post_author->display_name ) ? $post_author->display_name : '',
			),
		);

		$site_icon_url = $this->get( 'site_icon_url' );
		if ( $site_icon_url ) {
			$metadata['publisher']['logo'] = array(
				'@type'  => 'ImageObject',
				'url'    => $site_icon_url,
				'height' => self::SITE_ICON_SIZE,
				'width'  => self::SITE_ICON_SIZE,
			);
		}

		$image_metadata = $this->get_post_image_metadata();
		if ( $image_metadata ) {
			$metadata['image'] = $image_metadata;
		}

		$ftmetadata = apply_filters( 'penci_amp_post_template_metadata', $metadata, $this->post );
		$ftmetadata = apply_filters( 'amp_post_template_metadata', $ftmetadata, $this->post );

		$this->add_data_by_key( 'metadata', $ftmetadata );

		$this->build_post_featured_image();
		$this->build_post_commments_data();
	}

	private function build_post_commments_data() {
		if( ! isset( $this->post->post_type ) ) {
			return;
		}

		if ( ! post_type_supports( $this->post->post_type, 'comments' ) ) {
			return;
		}

		$comments_open = comments_open( $this->ID );

		// Don't show link if close and no comments
		if ( ! $comments_open
		     && ! $this->post->comment_count ) {
			return;
		}

		$comments_link_url  = get_comments_link( $this->ID );
		$comments_link_text = $comments_open
			? __( 'Leave a Comment', 'penci-amp' )
			: __( 'View Comments', 'penci-amp' );

		$this->add_data( array(
			'comments_link_url'  => $comments_link_url,
			'comments_link_text' => $comments_link_text,
		) );
	}

	private function build_post_content() {

		if( ! isset( $this->post->post_content ) ) {
			return;
		}
		$penci_amp_content = new Penci_AMP_Content( $this->post->post_content,
			apply_filters( 'penci_amp_content_embed_handlers', array(
				'Penci_AMP_Twitter_Embed_Handler'     => array(),
				'Penci_AMP_YouTube_Embed_Handler'     => array(),
				'Penci_AMP_DailyMotion_Embed_Handler' => array(),
				'Penci_AMP_Vimeo_Embed_Handler'       => array(),
				'Penci_AMP_SoundCloud_Embed_Handler'  => array(),
				'Penci_AMP_Instagram_Embed_Handler'   => array(),
				'Penci_AMP_Vine_Embed_Handler'        => array(),
				'Penci_AMP_Facebook_Embed_Handler'    => array(),
				'Penci_AMP_Pinterest_Embed_Handler'   => array(),
				'Penci_AMP_Gallery_Embed_Handler'     => array(),
			), $this->post ),
			apply_filters( 'amp_content_sanitizers', array(
				'Penci_AMP_Style_Sanitizer'             => array(),
				// 'Penci_AMP_Blacklist_Sanitizer' => array(),
				'Penci_AMP_Img_Sanitizer'               => array(),
				'Penci_AMP_Video_Sanitizer'             => array(),
				'Penci_AMP_Form_Sanitizer'              => array(),
				'Penci_AMP_Audio_Sanitizer'             => array(),
				'Penci_AMP_Playbuzz_Sanitizer'          => array(),
				'Penci_AMP_Iframe_Sanitizer'            => array(
					'add_placeholder' => true,
				),
				'AMP_Tag_And_Attribute_Sanitizer' => array(),
			), $this->post ),
			array(
				'content_max_width' => $this->get( 'content_max_width' ),
			)
		);

		$this->add_data_by_key( 'post_penci_amp_content', $penci_amp_content->get_penci_amp_content() );
		$this->add_data_by_key( 'archive_penci_amp_description', $penci_amp_content->get_archive_amp_description() );
		$this->merge_data_for_key( 'penci_amp_component_scripts', $penci_amp_content->get_penci_amp_scripts() );
		$this->merge_data_for_key( 'post_penci_amp_styles', $penci_amp_content->get_penci_amp_styles() );
	}

	private function build_post_featured_image() {
		$post_id       = $this->ID;
		$featured_html = get_the_post_thumbnail( $post_id, 'large' );

		// Skip featured image if no featured image is available.
		if ( ! $featured_html ) {
			return;
		}

		$featured_id = get_post_thumbnail_id( $post_id );

		// If an image with the same ID as the featured image exists in the content, skip the featured image markup.
		// Prevents duplicate images, which is especially problematic for photo blogs.
		// A bit crude but it's fast and should cover most cases.
		$post_content = $this->post->post_content;
		if ( false !== strpos( $post_content, 'wp-image-' . $featured_id )
		     || false !== strpos( $post_content, 'attachment_' . $featured_id ) ) {
			return;
		}

		$featured_image = get_post( $featured_id );

		list( $sanitized_html, $featured_scripts, $featured_styles ) = Penci_AMP_Content_Sanitizer::sanitize(
			$featured_html,
			array( 'Penci_AMP_Img_Sanitizer' => array() ),
			array(
				'content_max_width' => $this->get( 'content_max_width' ),
			)
		);

		$this->add_data_by_key( 'featured_image', array(
			'penci_amp_html' => $sanitized_html,
			'caption'        => $featured_image->post_excerpt,
		) );

		if ( $featured_scripts ) {
			$this->merge_data_for_key( 'penci_amp_component_scripts', $featured_scripts );
		}

		if ( $featured_styles ) {
			$this->merge_data_for_key( 'post_penci_amp_styles', $featured_styles );
		}
	}

	private function build_customizer_settings() {
		$settings = Penci_AMP_Customizer_Settings::get_settings();

		/**
		 * Filter AMP Customizer settings.
		 *
		 * Inject your Customizer settings here to make them accessible via the getter in your custom style.php template.
		 *
		 * Example:
		 *
		 *     echo esc_html( $this->get_customizer_setting( 'your_setting_key', 'your_default_value' ) );
		 *
		 * @since 0.4
		 *
		 * @param array $settings Array of AMP Customizer settings.
		 * @param WP_Post $post Current post object.
		 */
		$this->add_data_by_key( 'customizer_settings', apply_filters( 'penci_amp_post_template_customizer_settings', $settings, $this->post ) );
	}

	/**
	 * Grabs featured image or the first attached image for the post
	 *
	 * TODO: move to a utils class?
	 */
	private function get_post_image_metadata() {
		$post_image_meta = null;
		$post_image_id   = false;

		if ( has_post_thumbnail( $this->ID ) ) {
			$post_image_id = get_post_thumbnail_id( $this->ID );
		} else {
			$attached_image_ids = get_posts( array(
				'post_parent'      => $this->ID,
				'post_type'        => 'attachment',
				'post_mime_type'   => 'image',
				'posts_per_page'   => 1,
				'orderby'          => 'menu_order',
				'order'            => 'ASC',
				'fields'           => 'ids',
				'suppress_filters' => false,
			) );

			if ( ! empty( $attached_image_ids ) ) {
				$post_image_id = array_shift( $attached_image_ids );
			}
		}

		if ( ! $post_image_id ) {
			return false;
		}

		$post_image_src = wp_get_attachment_image_src( $post_image_id, 'full' );

		if ( is_array( $post_image_src ) ) {
			$post_image_meta = array(
				'@type'  => 'ImageObject',
				'url'    => $post_image_src[0],
				'width'  => $post_image_src[1],
				'height' => $post_image_src[2],
			);
		}

		return $post_image_meta;
	}

	private function build_html_tag_attributes() {
		$attributes = array();

		if ( function_exists( 'is_rtl' ) && is_rtl() ) {
			$attributes['dir'] = 'rtl';
		}

		$lang = get_bloginfo( 'language' );
		if ( $lang ) {
			$attributes['lang'] = $lang;
		}

		$this->add_data_by_key( 'html_tag_attributes', $attributes );
	}

	private function verify_and_include( $file, $template_type ) {
		$located_file = $this->locate_template( $file );
		if ( $located_file ) {
			$file = $located_file;
		}

		$file = apply_filters( 'penci_amp_post_template_file', $file, $template_type, $this->post );
		if ( ! $this->is_valid_template( $file ) ) {
			_doing_it_wrong( __METHOD__, sprintf( esc_html__( 'Path validation for template (%s) failed. Path cannot traverse and must be located in `%s`.', 'penci-amp' ), esc_html( $file ), 'WP_CONTENT_DIR' ), '0.1' );

			return;
		}

		do_action( 'penci_amp_post_template_include_' . $template_type, $this );
		include( $file );
	}


	private function locate_template( $file ) {
		$search_file = sprintf( 'amp/%s', basename( $file ) );

		return locate_template( array( $search_file ), false );
	}

	private function is_valid_template( $template ) {
		if ( false !== strpos( $template, '..' ) ) {
			return false;
		}

		if ( false !== strpos( $template, './' ) ) {
			return false;
		}

		if ( ! file_exists( $template ) ) {
			return false;
		}

		return true;
	}
}
