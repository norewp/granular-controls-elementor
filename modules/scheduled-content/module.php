<?php
namespace ElementorControls\Modules\ScheduledContent;

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
		return 'scheduled-controls';
	}

	/**
	 * @param $element Widget_Base
	 * @param $section_id string
	 * @param $args array
	 */
	public function register_controls( $element, $section_id, $args ) {
		static $sections = [
			'layout', /* Column */
			'section_layout', /* Section */
		];

		if ( ! in_array( $section_id, $sections ) ) {
			return;
		}

		$element->start_controls_section(
			'section_scheduled_controls',
			[
				'label' => __( 'Schedule Content', 'granular-controls-for-elementor' ),
				'tab' => Controls_Manager::TAB_LAYOUT,
			]
		);

		$element->add_control(
			'schedule_content_description',
			[
				'raw' => __( 'These controls only affect the Column|Section they are attached to!', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);
		
		$element->add_control(
			'scheduled_content_on',
			[
				'label' => __( 'Schedule Content?', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'Yes',
				'label_off' => 'No',
				'return_value' => 'yes',
				'description' => __( 'Switch on to schedule the contents of this column|section!.', 'granular-controls-for-elementor' ),
			]
		);
		
		$element->add_control(
			'schedule_start_date',
			[
				'label' => __( 'Start Date', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::DATE_TIME,
				'default' => '01/01/2018 00:00:00',
				'condition' => [
					'scheduled_content_on' => 'yes',
				],
				'description' => __( 'Set content display schedule start date!', 'granular-controls-for-elementor' ),
			]
		);
		
		$element->add_control(
			'schedule_end_date',
			[
				'label' => __( 'End Date', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::DATE_TIME,
				'default' => '02/01/2018 00:00:00',
				'condition' => [
					'scheduled_content_on' => 'yes',
				],
				'description' => __( 'Set content display schedule end date!', 'granular-controls-for-elementor' ),
			]
		);

		$element->end_controls_section();
		
	}
	
	public function before_render($element) {
		$settings = $element->get_settings();		
		if( $element->get_settings( 'scheduled_content_on' ) == 'yes' ) {			
			$element->add_render_attribute( '_wrapper', 'class', 'scheduled-content-' . $element->get_id() );	
		}
	}
	
	public function after_render($element) {
		$settings = $element->get_settings();
		if( $element->get_settings( 'scheduled_content_on' ) == 'yes' ) {
		$start = $settings['schedule_start_date'];
		$end = $settings['schedule_end_date']; ?>
			<script type="text/javascript">
				(function($) {
					$.fn.scheduleContent = function(options) {
						var settings = $.extend({
							start: '01/01/2018 00:00:00',
							end: '12/31/2999 00:00:00'
						}, options );
						var startDate = new Date(settings.start);
						var endDate = new Date(settings.end);
						var now = new Date();
						if((now >= startDate) && (now <= endDate)){
							$(this).show();
						}
						else {
							$(this).hide();
						}
					};
					$('.scheduled-content-<?php echo $element->get_id(); ?>').scheduleContent({
						start:'<?php echo $start; ?>',
						end:'<?php echo $end; ?>'
					});
				})(jQuery);	
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
