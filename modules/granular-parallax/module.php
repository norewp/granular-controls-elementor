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
		$parallax_on = granular_get_options( 'granular_editor_parallax_on', 'granular_editor_settings', 'no' );
		if ( 'yes' === $parallax_on ) {
			$this->add_actions();
		}
	}

	public function get_name() {
		return 'granular-parallax';
	}
	
	public function get_script_depends() {
		return [ 'granule-parallax-js' ];
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
				'raw' => __( 'NOTICE: Please note that using both Parallax & Particles together on the same section may have side effects - use with care!', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);
		
		$element->add_control(
			'section_parallax_on',
			[
				'label' => __( 'Enable Parallax', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'Yes',
				'label_off' => 'No',
				'return_value' => 'yes',
				'description' => __( 'Enable to access extra controls.', 'granular-controls-for-elementor' ),
			]
		);
		
		$element->add_responsive_control(
			'parallax_type',
			[
				'label' => __( 'Type', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'scroll',
				'options' => [
					'scroll' 			=> __( 'Scroll', 'granular-controls-for-elementor' ),
					'scroll-opacity' 	=> __( 'Scroll + Opacity', 'granular-controls-for-elementor' ),
					'opacity' 			=> __( 'Opacity', 'granular-controls-for-elementor' ),
					'scale' 			=> __( 'Scale', 'granular-controls-for-elementor' ),
					'scale-opacity' 	=> __( 'Scale + Opacity', 'granular-controls-for-elementor' ),
				],
				'condition' => [
					'section_parallax_on' => 'yes',
				],
				'description' => __( 'Set the Parallax type needed - default is Scroll effect.', 'granular-controls-for-elementor' ),
			]
		);
		
		$element->add_control(
			'granules_parallax_speed_notice',
			[
				'raw' => __( 'NOTICE: Speed has some caveats - the higher the speed the greater the zoom on the image. Negative speed values will also switch the direction of the movement on scroll!', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'condition' => [
					'section_parallax_on' => 'yes',
				],
			]
		);
		
		$element->add_control(
			'parallax_speed',
			[
				'label' => __( 'Speed', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1.2,
				'description' => __( 'This should be set between -1 to a max of 2 - Decimal points must be used for fine controls.', 'granular-controls-for-elementor' ),
				'condition' => [
					'section_parallax_on' => 'yes',
				],
			]
		);
		
		$element->add_control(
			'granules_parallax_mobile_notice',
			[
				'raw' => __( 'NOTICE: These options are untested and I would love to hear your feedback on them once you have tried them!', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'condition' => [
					'section_parallax_on' => 'yes',
				],
			]
		);
		
		$element->add_control(
			'android_support',
			[
				'label' => __( 'Android Support', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'false',
				'options' => [
					'false' => __( 'Enable', 'granular-controls-for-elementor' ),
					'true'  => __( 'Disable', 'granular-controls-for-elementor' ),
				],
				'condition' => [
					'section_parallax_on' => 'yes',
				],
				'description' => __( 'Enable support on Android devices.', 'granular-controls-for-elementor' ),
			]
		);
		
		$element->add_control(
			'ios_support',
			[
				'label' => __( 'iOS Support', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'false',
				'options' => [
					'false' => __( 'Enable', 'granular-controls-for-elementor' ),
					'true'  => __( 'Disable', 'granular-controls-for-elementor' ),
				],
				'condition' => [
					'section_parallax_on' => 'yes',
				],
				'description' => __( 'Enable support on iOs devices.', 'granular-controls-for-elementor' ),
			]
		);
		
	}
	
	protected function add_actions() {
		add_action( 'elementor/element/before_section_end', [ $this, 'register_controls' ], 10, 3 );
		add_action( 'elementor/frontend/element/after_render', [ $this, 'after_render'], 10, 1 );
		
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}
		
	public function after_render($element) {
		$settings 		= $element->get_settings(); 		
		if( $element->get_settings( 'section_parallax_on' ) == 'yes' ) { 
		$type 			= $settings['parallax_type'];
		$and_support 	= $settings['android_support'];
		$ios_support 	= $settings['ios_support'];
		$speed 			= $settings['parallax_speed'];
		
		?>			
			<script type="text/javascript">	
				( function( $ ) {
					"use strict";
					var granularParallaxElementorFront = {
						init: function() {
							elementorFrontend.hooks.addAction('frontend/element_ready/global', granularParallaxElementorFront.initWidget);
						},
						initWidget: function( $scope ) {
							$('.elementor-element-<?php echo $element->get_id(); ?>').jarallax({
								type: '<?php echo $type; ?>',
								speed: <?php echo $speed; ?>,
								keepImg: true,
								imgSize: 'cover',
								imgPosition: '50% 0%',
								noAndroid: <?php echo $and_support; ?>,
								noIos: <?php echo $ios_support; ?>
							});
						}
					};
					$(window).on('elementor/frontend/init', granularParallaxElementorFront.init);
				}( jQuery ) );
			</script>
			
		<?php }
	}
	
	public function enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'granule-parallax-js',
			ELEMENTOR_CONTROLS_URL . 'assets/js/jarallax.js',
			[
				'jquery',
			],
			ELEMENTOR_CONTROLS_VERSION,
			false
		);
	}
}
