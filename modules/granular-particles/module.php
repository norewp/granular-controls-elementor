<?php
namespace ElementorControls\Modules\GranularParticles;

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
		$particles_on = granular_get_options( 'granular_editor_particles_on', 'granular_editor_settings', 'no' );
		if ( 'yes' === $particles_on ) {
			$this->add_actions();
		}
	}

	public function get_name() {
		return 'granular-particles';
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
			'section_particles_on',
			[
				'label' => __( 'Enable Particles', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'Yes',
				'label_off' => 'No',
				'return_value' => 'yes',
				'description' => __( 'Switch on to enable & access Particles options! Note that currently particles are not visible in edit/preview mode & can only be viewed on the frontend.', 'granular-controls-for-elementor' ),
			]
		);
		
		$element->add_responsive_control(
			'particles_custom_height',
			[
				'label' => __( 'Height', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'condition' => [
					'section_particles_on' => 'yes',
				],
				'description' => __( 'Set this equal to the set Minimum Height of your section - default is 400px!', 'granular-controls-for-elementor' ),
			]
		);
		
		$element->add_control(
			'section_particles_js',
			[
				'label' => __( 'Particles JSON', 'granular-controls-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'condition' => [
					'section_particles_on' => 'yes',
				],
				'description' => __( 'Paste your particles JSON code here - Generate it from <a href="http://vincentgarreau.com/particles.js/#default" target="_blank">Here!</a>', 'granular-controls-for-elementor' ),
				'default' => '',
			]
		);
		
	}
	
	protected function add_actions() {
		add_action( 'elementor/element/before_section_end', [ $this, 'register_controls' ], 10, 3 );		
		add_action( 'elementor/frontend/element/before_render', [ $this, 'before_render'], 10, 1 );
		add_action( 'elementor/frontend/element/after_render', [ $this, 'after_render'], 10, 1 );
		
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}
	
	public function before_render($element) {    		
		$settings = $element->get_settings();
		if( $element->get_settings( 'section_particles_on' ) == 'yes' ) {
			
			if ( empty( $settings['particles_custom_height'] ) ) {
				$height = '400';
			} else {
				$height = $settings['particles_custom_height'];
			}		

			$element->add_render_attribute( '_wrapper', 'id', 'granule-particles-' . $element->get_id() ); ?>				
			<style>#granule-particles-<?php echo $element->get_id(); ?> > canvas{height: <?php echo $height ?>px !important;position: absolute;top:0;}</style>
			<?php	
		}
	}
	
	public function after_render($element) {
		$settings = $element->get_settings();		
		if( $element->get_settings( 'section_particles_on' ) == 'yes' ) {		
			if ( ! empty( $settings['section_particles_js'] ) ) { ?>
				<script type="text/javascript">
					particlesJS("granule-particles-<?php echo $element->get_id(); ?>", <?php echo $settings['section_particles_js']; ?> );
				</script>
			<?php } else { $this->default_particles_render($element); }
		}
	}
	
	protected function default_particles_render($element) { ?>
		<script type="text/javascript">
			/* ---- particles.js config ---- */
			particlesJS("granule-particles-<?php echo $element->get_id(); ?>", {
				"particles": {
				"number": {"value": 80,"density": {"enable": true,"value_area": 400}},"color": {"value": "#ffffff"},"shape": {"type": "circle","stroke": {"width": 0,"color": "#000000"},"polygon": {"nb_sides": 5},
				"image": {"src": "img/github.svg","width": 100,"height": 50}},"opacity": {"value": 0.5,"random": false,"anim": {"enable": false,"speed": 1,"opacity_min": 0.1,"sync": false}},
				"size": {"value": 3,"random": true,"anim": {"enable": false,"speed": 40,"size_min": 0.1,"sync": false}},"line_linked": {"enable": true,"distance": 150,"color": "#ffffff","opacity": 0.4,"width": 1},
				"move": {"enable": true,"speed": 6,"direction": "none","random": false,"straight": false,"out_mode": "out","bounce": false,"attract": {"enable": false,"rotateX": 600,"rotateY": 1200}}},
				"interactivity": {"detect_on": "canvas","events": { "onhover": {"enable": true,"mode": "grab"},"onclick": {"enable": true,"mode": "push"},"resize": true},
				"modes": {"grab": {"distance": 140,"line_linked": {"opacity": 1}},"bubble": {"distance": 400,"size": 40,"duration": 2,"opacity": 8,"speed": 3},
				"repulse": {"distance": 200,"duration": 0.4},"push": {"particles_nb": 4},"remove": {"particles_nb": 2}}},"retina_detect": true
			});
		</script>		
	<?php
	}

	public function enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'granule-particles-js',
			ELEMENTOR_CONTROLS_URL . 'assets/js/particles' . $suffix . '.js',
			[
				'jquery',
			],
			ELEMENTOR_CONTROLS_VERSION,
			false
		);
	}
}
