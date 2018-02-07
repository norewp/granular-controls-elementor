<?php
namespace ElementorControls\Modules\GranularBar;

use Elementor;
use ElementorUtils;
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
		$exitbar_on = granular_get_options( 'granular_editor_exit_on', 'granular_editor_settings', 'no' );
		if ( 'yes' === $exitbar_on ) {
			$this->editor_bar_actions();
		}
	}

	public function get_name() {
		return 'granular-bar';
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
		
	}
	
	protected function editor_bar_actions() {	
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_bar_styles' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_scripts' ] );		
		add_action( 'elementor/editor/footer', [ $this, 'granular_editor_bar'], 0 );
	}
	
	public function granular_editor_bar($element) {
		global $post;
		$exit_strategy 	= granular_get_options( 'granular_editor_exit_point', 'granular_editor_settings', 'editor' );
		$exit_target 	= granular_get_options( 'granular_editor_exit_target', 'granular_editor_settings', 'editor' );
		$exit_name 		= granular_get_options( 'granular_editor_exit_name', 'granular_editor_settings', __( 'Exit To Dashboard', 'granular-controls-for-elementor' ) );
		$id 			= $post->ID;
		$live_view 		= esc_url( get_permalink( $id ) );
		if ( 'dashboard' === $exit_strategy ) {
			$exit_point	= esc_url( admin_url() );
		} elseif ( 'editor' === $exit_strategy ) {
			$exit_point	= esc_url( get_edit_post_link( $id ) );
		} elseif ( 'type_pages' === $exit_strategy ) {
			$exit_point	= esc_url( admin_url( 'edit.php?post_type=page' ) );
		} elseif ( 'type_posts' === $exit_strategy ) {
			$exit_point	= esc_url( admin_url( 'edit.php' ) );
		} elseif ( 'type_lib' === $exit_strategy ) {
			$exit_point	= esc_url( admin_url( 'edit.php?post_type=elementor_library' ) );
		}
		
	?>
		<div id="granular-top-bar">		
			<div class="left-btn">
				<i class="elementor-icon eicon-animation"></i>
			</div>
			<a href="<?php echo $exit_point; ?>" target="<?php echo $exit_target; ?>" rel="noopener noreferrer">
				<div class="exit-to-dashboard">
					<i class="elementor-icon eicon-wordpress"></i>
					<?php echo esc_html( $exit_name ); ?>
				</div>
			</a>
			<a href="<?php echo $live_view; ?>" target="_blank" rel="noopener noreferrer">
				<div class="view-live-page">
					<?php _e( 'View Live Page', 'granular-controls-for-elementor' ); ?>
					<i class="elementor-icon eicon-editor-external-link"></i>
				</div>
			</a>
			<!--<div class="right-btn">
				<i class="elementor-icon eicon-settings"></i>
			</div>-->
		</div>
		<?php
	}
	
	public function enqueue_editor_bar_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		wp_enqueue_style(
			'granular-editor-bar',
			ELEMENTOR_CONTROLS_URL . 'assets/css/granular-editor-bar' . $suffix . '.css',
			[
				'elementor-editor',
			],
			ELEMENTOR_CONTROLS_VERSION
		);
	}
	
	public function enqueue_editor_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'granular-editor-bar-js',
			ELEMENTOR_CONTROLS_ASSETS_URL . 'js/granular-editor' . $suffix . '.js',
			[
				'jquery',
			],
			ELEMENTOR_CONTROLS_VERSION,
			false
		);
	}
}
