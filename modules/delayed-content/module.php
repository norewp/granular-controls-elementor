<?php
namespace ElementorControls\Modules\DelayedContent;

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
		return 'delayed-content';
	}

	/**
	 * @param $element Widget_Base
	 * @param $section_id string
	 * @param $args array
	 */
	public function register_controls( $element, $section_id, $args ) {
		static $sections = [
			//'layout', /* Column */
			'section_layout', /* Section */
		];

		if ( ! in_array( $section_id, $sections ) ) {
			return;
		}

		$element->start_controls_section(
			'section_column_controls',
			[
				'label' => __( 'Delayed Content', 'granular-controls-for-elementor' ),
				'tab' => Controls_Manager::TAB_LAYOUT,
			]
		);

		$element->add_control(
			'column_controls_description',
			[
				'raw' => __( 'These controls only affect the Column/Section they are attached to!', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);
		
		$element->add_control(
			'section_delay_on',
			[
				'label' => __( 'Delay Content?', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'Yes',
				'label_off' => 'No',
				'return_value' => 'yes',
				'description' => __( 'Switch on to delay the contents of this column|section!.', 'granular-controls-for-elementor' ),
			]
		);
		
		$element->add_control(
			'content_delay_time',
			[
				'label' => __( 'Delay Time', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '0.50',
				'condition' => [
					'section_delay_on' => 'yes',
				],
				'description' => __( 'Set delay time in in minutes i.e 1 for 1 minute or 0.20 (60*0.2) for 12 seconds - default is 0.50 (30 seconds)!', 'granular-controls-for-elementor' ),
			]
		);

		$element->end_controls_section();
		
	}
	
	public function before_render($element) {
		$settings = $element->get_settings();
		
		if( $element->get_settings( 'section_delay_on' ) == 'yes' ) {			
			$element->add_render_attribute( '_wrapper', 'id', 'delayed-content-' . $element->get_id() ); 
			$element->add_render_attribute( '_wrapper', 'style', 'display:none' );
		}
	}
	
	public function after_render($element) {
		$settings = $element->get_settings();
		if( $element->get_settings( 'section_delay_on' ) == 'yes' ) { 		
		$time = $settings['content_delay_time'];
		?>
			<input type="hidden" id="timedelay-<?php echo $element->get_id(); ?>" value="<?php echo $time; ?>" />
			<script type="text/javascript">
				window.onload = function() {
					delay = ( document.getElementById("timedelay-<?php echo $element->get_id(); ?>").value * 60 ) * 1000;
					setTimeout(function(){showdiv()}, delay);
					
					function showdiv() {
						document.getElementById("delayed-content-<?php echo $element->get_id(); ?>").style.display = "block";
					}
				}
			</script>		
		<?php	
		}
		
	}
	
	protected function add_actions() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 3 );
		
		add_action( 'elementor/frontend/element/before_render', [ $this, 'before_render'], 10, 1 );
		add_action( 'elementor/frontend/element/after_render', [ $this, 'after_render'], 10, 1 );
	}

}
