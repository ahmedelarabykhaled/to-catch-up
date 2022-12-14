<?php


namespace PenciDesign;

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

use DOMElement;
use DOMDocument;

/**
 * SINGLETON: Class used to implement work with HTML and XML files.
 *
 * @since 2.0.0
 *
 **/
final class XMLHelper {

	/**
	 * The one true XMLHelper.
	 *
	 * @var XMLHelper
	 * @since 2.0.0
	 **/
	private static $instance;

	/**
	 * The easiest way to get inner HTML of the node.
	 *
	 * @param DOMElement $node - Return inner html.
	 *
	 * @return string
	 * @since 2.0.0
	 * @access public
	 */
	public function get_inner_html( $node ) {

		$innerHTML = '';
		$children  = $node->childNodes;

		foreach ( $children as $child ) {
			$innerHTML .= $child->ownerDocument->saveXML( $child );
		}

		return $innerHTML;

	}

	/**
	 * Prepare HTML for Google TTS. Remove unnecessary html tags.
	 *
	 * @param $post_content - Post/Page content.
	 *
	 * @return string|string[]|null
	 * @since 2.0.0
	 * @access public
	 **/
	public function clean_html( $post_content ) {

		/** Strip Tags except contents tags and SSML. */
		$post_content = strip_tags( $post_content, '<div><p><h1><h2><h3><h4><h5><h6><pre><ul><ol><li><table><span><i><b><strong><em><code><break><say-as><sub><emphasis><prosody><voice>' );

		/** Remove inline styles. */
		$post_content = preg_replace( '/(<[^>]+) style=".*?"/i', '$1', $post_content );

		/** Decoding HTML entities. */
		$post_content = html_entity_decode( $post_content );

		/** Remove empty tags. */
		$pattern      = "/<[^\/>]*>([\s]?)*<\/[^>]*>/"; // Pattern to remove any empty tag.
		$post_content = preg_replace( $pattern, '', $post_content );

		/** Remove spaces, tabs, newlines. */
		$post_content = preg_replace( '~>\s+<~', '> <', $post_content );

		return $post_content;

	}

	/**
	 * Repair HTML with DOMDocument.
	 *
	 * @param $html - HTML content to be repaired.
	 *
	 * @return string
	 * @since 2.0.0
	 * @access public
	 **/
	public function repair_html( $html ) {

		/** Hide DOM parsing errors. */
		libxml_use_internal_errors( true );
		libxml_clear_errors();

		/** Load the possibly malformed HTML into a DOMDocument. */
		$dom          = new DOMDocument();
		$dom->recover = true;
		//$dom->loadHTML( '<?xml encoding="UTF-8"><body id="repair">' . $html . '</body>' ); // input UTF-8.
		$dom->loadHTML( '<?xml encoding="UTF-8"><!DOCTYPE html><html lang=""><head><title>R</title></head><body id="repair">' . $html . '</body></html>' );

		/** Copy the document content into a new document. */
		$doc = new DOMDocument();
		foreach ( $dom->getElementById( 'repair' )->childNodes as $child ) {
			$doc->appendChild( $doc->importNode( $child, true ) );
		}

		/** Output the new document as HTML. */
		$doc->encoding     = 'UTF-8'; // output UTF-8.
		$doc->formatOutput = false;

		return trim( $doc->saveHTML() );
	}

	/**
	 * Get language code and name from <voice> tag, or use default.
	 *
	 * @param string $html HTML content to be voiced.
	 *
	 * @return array First element - Language Code, Second element - Language Name.
	 * @since 2.0.0
	 **/
	public function get_lang_params_from_tag( $html ) {

		/** Get name attribute, from tag voice, if we have one. */
		$array = [];
		preg_match( '/voice name="([^"]*)"/i', $html, $array );

		$language_name = get_theme_mod( 'penci_texttospeech_language_name', 'en-US-Standard-D' );
		$language_code = explode( '-', $language_name );
		$language_code = is_array( $language_code ) ? $language_code[0] . '-' . $language_code[1] : 'en-US';

		/** Return default values, if nothing was found. */
		if ( ! isset( $array[1] ) ) {
			return [
				$language_code,
				$language_name,
			];
		}

		/** Voice name ex. "en-GB-Wavenet-C", from tag <voice>. */
		$voice_name = $array[1];

		/** Get Lang code. */
		$pieces    = explode( '-', $voice_name );
		$lang_code = $pieces[0] . '-' . $pieces[1];

		return [ $lang_code, $voice_name ];
	}

	/**
	 * Main XMLHelper Instance.
	 *
	 * Insures that only one instance of XMLHelper exists in memory at any one time.
	 *
	 * @static
	 * @return XMLHelper
	 * @since 2.0.0
	 **/
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof XMLHelper ) ) {
			self::$instance = new XMLHelper;
		}

		return self::$instance;
	}

} // End Class XMLHelper.
