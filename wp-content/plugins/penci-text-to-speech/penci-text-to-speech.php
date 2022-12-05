<?php
/*
Plugin Name: Penci Text To Speech
Plugin URI: https://pencidesign.net/
Description: A plugin to help you converts text into human-like speech. The Plugin uses the latest technology of machine learning and artificial intelligence to play a high-quality human voice.
Version: 1.1
Author: PenciDesign
Author URI: http://themeforest.net/user/pencidesign?ref=pencidesign
*/

namespace PenciDesign;

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/** Include plugin autoloader for additional classes. */
require __DIR__ . '/inc/autoload.php';

/** Includes the autoloader for libraries installed with Composer. */
require __DIR__ . '/vendor/autoload.php';

require plugin_dir_path( __FILE__ ) . 'helper.php';

use PenciDesign\MetaBox;
use PenciDesign\RSS;
use PenciDesign\Shortcodes;
use PenciDesign\AdminStyles;
use PenciDesign\FrontStyles;
use PenciDesign\AdminScripts;
use PenciDesign\FrontScripts;
use PenciDesign\Elementor;
use PenciDesign\WPBakery;
use PenciDesign\SpeechCaster;

/**
 * SINGLETON: Core class used to instantiate and control a PenciTextToSpeech plugin.
 *
 * @since 1.0.0
 **/
final class PenciTextToSpeech {

	/**
	 * Plugin version.
	 *
	 * @string version
	 * @since 1.0.0
	 **/
	public static $version;

	/**
	 * Plugin name.
	 *
	 * @string version
	 * @since 3.0.4
	 **/
	public static $name;

	/**
	 * Use minified libraries if SCRIPT_DEBUG is turned off.
	 *
	 * @since 1.0.0
	 **/
	public static $suffix;

	/**
	 * URL (with trailing slash) to plugin folder.
	 *
	 * @var string
	 * @since 1.0.0
	 **/
	public static $url;

	/**
	 * PATH to plugin folder.
	 *
	 * @var string
	 * @since 1.0.0
	 **/
	public static $path;

	/**
	 * Plugin base name.
	 *
	 * @var string
	 * @since 1.0.0
	 **/
	public static $basename;

	/**
	 * Plugin admin menu base.
	 *
	 * @var string
	 * @since 3.0.0
	 **/
	public static $menu_base;

	/**
	 * Plugin slug base.
	 *
	 * @var string
	 * @since 3.0.5
	 **/
	public static $slug;

	/**
	 * Full path to main plugin file.
	 *
	 * @var string
	 * @since 3.0.5
	 **/
	public static $plugin_file;

	/**
	 * The one true PenciTextToSpeech.
	 *
	 * @var PenciTextToSpeech
	 * @since 1.0.0
	 **/
	private static $instance;

	/**
	 * Sets up a new plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 **/
	private function __construct() {

		/** Initialize main variables. */
		$this->initialization();

	}

	/**
	 * Setup the plugin.
	 *
	 * @return void
	 **@since 3.0.0
	 * @access public
	 *
	 */
	public function setup() {

		add_filter( 'https_ssl_verify', '__return_false' );

		/** Define hooks that runs on both the front-end as well as the dashboard. */
		$this->both_hooks();

		/** Define public hooks. */
		$this->public_hooks();

		/** Define admin hooks. */
		$this->admin_hooks();

	}

	/**
	 * Initialize main variables.
	 *
	 * @return void
	 **@since 1.0.0
	 * @access public
	 *
	 */
	public function initialization() {

		/** Get Plugin version. */
		self::$version = $this->get_plugin_data( 'Version' );

		/** Plugin slug. */
		self::$slug = $this->get_plugin_data( 'TextDomain' );

		/** Get Plugin name. */
		self::$name = $this->get_plugin_data( 'Name' );

		/** Gets the plugin URL (with trailing slash). */
		self::$url = plugin_dir_url( __FILE__ ) . 'assets/';

		/** Gets the plugin PATH. */
		self::$path = plugin_dir_path( __FILE__ );

		/** Use minified libraries if SCRIPT_DEBUG is turned off. */
		self::$suffix = '';

		/** Set plugin basename. */
		self::$basename = plugin_basename( __FILE__ );

		/** Plugin settings page base. */
		self::$menu_base = 'toplevel_page_penci_texttospeech_settings';

		/** Full path to main plugin file. */
		self::$plugin_file = __FILE__;

		add_action( 'init', function () {
			if ( class_exists( 'SoledadFW\Customizer\CustomizerOptionAbstract' ) ) {
				require_once( plugin_dir_path( __FILE__ ) . 'customizer/panel.php' );
				require_once( plugin_dir_path( __FILE__ ) . 'customizer/settings.php' );
				\SoledadFW\PenciTextToSpeechCustomizer::getInstance();
			}
		} );

		PenciTextToSpeechUtilities::get_instance();

	}

	/**
	 * Define hooks that runs on both the front-end as well as the dashboard.
	 *
	 * @return void
	 **@since 3.0.0
	 * @access private
	 *
	 */
	private function both_hooks() {

		/** Load the plugin text domain for translation. */
		load_plugin_textdomain( 'penci-text-to-speech', false, dirname( PenciTextToSpeech::$basename ) . '/languages/' );

		/** Adds all the necessary shortcodes. */
		Shortcodes::get_instance();

		/** Register Elementor Widgets. */
		$this->register_elementor_widgets();

		/** Register WPBakery Widgets. */
		$this->register_wpbakery_elements();

	}

	/**
	 * Register all the hooks related to the public-facing functionality.
	 *
	 * @return void
	 **@since 3.0.0
	 * @access private
	 *
	 */
	private function public_hooks() {

		/** Work only on frontend area. */
		if ( is_admin() ) {
			return;
		}

		/** Load CSS for Frontend Area. */
		FrontStyles::get_instance();

		/** Load JavaScripts for Frontend Area. */
		FrontScripts::get_instance();

		/** Add player code to page. */
		SpeechCaster::get_instance()->add_player();

		add_filter( 'template_include', [ SpeechCaster::class, 'penci_tts_page_template' ], PHP_INT_MAX );

		/** Add Schema markup */
		add_action( 'wp_head', [ SpeechCaster::get_instance(), 'structured_data' ] );

		/** Add RSS feeds */
		RSS::get_instance();

	}

	/**
	 * Register all the hooks related to the admin area functionality.
	 *
	 * @return void
	 **@since 3.0.0
	 * @access private
	 *
	 */
	private function admin_hooks() {

		/** Work only in admin area. */
		if ( ! is_admin() ) {
			return;
		}

		/** Create folder for audio files. */
		wp_mkdir_p( trailingslashit( wp_upload_dir()['basedir'] ) . 'penci-text-to-speech' );

		/** Add Ajax handlers and before_delete_post action. */
		SpeechCaster::get_instance()->add_actions();

		/** Add Meta Box for selected post types. */
		MetaBox::get_instance();

		/** Add admin styles. */
		AdminStyles::get_instance();

		/** Add admin javascript. */
		AdminScripts::get_instance();

	}

	public function register_elementor_widgets() {

		/** Initialize Elementor widgets. */
		Elementor::get_instance();

	}

	/**
	 * Registers a WPBakery element.
	 *
	 * @return void
	 * @since 3.0.0
	 * @access public
	 **/
	public function register_wpbakery_elements() {

		/** Initialize WPBakery Element. */
		WPBakery::get_instance();

	}

	/**
	 * Return current plugin metadata.
	 *
	 * @param string $field - Field name from plugin data.
	 *
	 * @return array {
	 *     Plugin data. Values will be empty if not supplied by the plugin.
	 *
	 * @type string $Name Name of the plugin. Should be unique.
	 * @type string $Title Title of the plugin and link to the plugin's site (if set).
	 * @type string $Description Plugin description.
	 * @type string $Author Author's name.
	 * @type string $AuthorURI Author's website address (if set).
	 * @type string $Version Plugin version.
	 * @type string $TextDomain Plugin textdomain.
	 * @type string $DomainPath Plugins relative directory path to .mo files.
	 * @type bool $Network Whether the plugin can only be activated network-wide.
	 * @type string $RequiresWP Minimum required version of WordPress.
	 * @type string $RequiresPHP Minimum required version of PHP.
	 * }
	 **@since 3.0.0
	 * @access public
	 *
	 */
	public function get_plugin_data( $field ) {

		static $plugin_data;

		/** We already have $plugin_data. */
		if ( $plugin_data !== null ) {
			return $plugin_data[ $field ];
		}

		/** This is first time call of method, so prepare $plugin_data. */
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		$plugin_data = get_plugin_data( __FILE__ );

		return $plugin_data[ $field ];

	}

	/**
	 * Return plugin version.
	 *
	 * @return string
	 **@since 2.0.2
	 * @access public
	 *
	 */
	public function get_version() {
		return self::$version;
	}

	/**
	 * Main PenciTextToSpeech Instance.
	 *
	 * Insures that only one instance of PenciTextToSpeech exists in memory at any one time.
	 *
	 * @static
	 * @return PenciTextToSpeech
	 **@since 1.0.0
	 *
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {

			self::$instance = new self;

		}

		return self::$instance;

	}

} // End Class PenciTextToSpeech.

/** Run PenciTextToSpeech class once after activated plugins have loaded. */
add_action( 'plugins_loaded', [ PenciTextToSpeech::get_instance(), 'setup' ] );