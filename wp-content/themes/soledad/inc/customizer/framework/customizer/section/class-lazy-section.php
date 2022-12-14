<?php
/**
 * @author      SoledadFWtheme
 * @license     https://opensource.org/licenses/MIT
 */

namespace SoledadFW\Customizer\Section;

/*****
 * Lazy section
 */
class Lazy_Section extends Default_Section {
	const TYPE = 'lazy';

	/**
	 * Type of control, used by JS.
	 *
	 * @access public
	 * @var string
	 */
	public $type = self::TYPE;

	public $ajax_call;

	public $dependency;

	/**
	 * Render the panel's JS templates.
	 *
	 * This function is only run for panel types that have been registered with
	 * WP_Customize_Manager::register_panel_type().
	 *
	 * @see WP_Customize_Manager::register_panel_type()
	 */
	public function print_template() {
		?>
		<script type="text/html" id="tmpl-customize-lazy-section-loading">
			<li class="customize-lazy-section-loading">
				<div class="loader">{{ data.loading }}</div>
			</li>
		</script>
		<?php
		parent::print_template();
	}

	/**
	 * Export data to JS.
	 *
	 * @return array
	 */
	public function json() {
		$data               = parent::json();
		$data['ajax_call']  = $this->ajax_call;
		$data['dependency'] = $this->dependency;

		return $data;
	}
}
