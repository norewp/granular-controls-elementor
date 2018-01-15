<?php
namespace ElementorControls\Modules\GranularParallax;

use Elementor;
use Elementor\Elementor_Base;
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Widget_Base;
use ElementorControls\Base\Module_Base;
use ElementorControls\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'granular-parallax';
	}

	/**
	 * @param $element Widget_Base
	 * @param $section_id string
	 * @param $args array
	 */
	public function register_controls( $element, $section_id, $args ) {
		static $sections = [
			'section_background', /* Section */
		];

		if ( ! in_array( $section_id, $sections ) ) {
			return;
		}

		$element->add_control(
			'granules_parallax_particles_notice',
			[
				'raw' => __( 'NOTICE: Please note that using both Parallax & Particles together on the same section may have side effects - use with care!', 'elementor-granules' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);
		
		$element->add_control(
			'section_parallax_on',
			[
				'label' => __( 'Enable parallax', 'extend-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'Yes',
				'label_off' => 'No',
				'return_value' => 'yes',
				'description' => __( 'Currently there are no configurable options - this may change in the future!.', 'extend-elements' ),
			]
		);
		
	}
	
	protected function add_actions() {
		add_action( 'elementor/element/before_section_end', [ $this, 'register_controls' ], 10, 3 );
		add_action( 'elementor/frontend/element/after_render', [ $this, 'after_render'], 10, 1 );
		
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}
	
	public function after_render($element) {
		if( $element->get_settings( 'section_parallax_on' ) == 'yes' ) { ?>			
			<script type="text/javascript">
				(function($) {
					 $('.elementor-element-<?php echo $element->get_id(); ?>').simpleParallax({orientation: 'down'});
				})(jQuery);
			</script>
		<?php }
	}
	
	public function enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'granule-parallax-js',
			ELEMENTOR_CONTROLS_URL . 'assets/js/simpleParallax' . $suffix . '.js',
			[
				'jquery',
			],
			ELEMENTOR_CONTROLS_VERSION,
			false
		);
	}
}
