<?php
namespace ElementorControls;

use Elementor;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main class plugin
 */
class Plugin_Functions {
	
	private static $_instance;
	
	public function elementor_accordion_off() { ?>
		<script>
			jQuery(document).ready(function() {
				jQuery( '.elementor-accordion .elementor-tab-title' ).removeClass( 'elementor-active' );
				jQuery( '.elementor-accordion .elementor-tab-content' ).css( 'display', 'none' );
			});
		</script>
	<?php
	}
	
	public function granular_welcome_panel() {
		$screen = get_current_screen();
		if( $screen->base == 'dashboard' ) { 
		$panel_id = granular_get_options( 'granular_welcome_template_id', 'granular_advanced_settings', '' ); ?>
		<div class="granular-dashboard">
			<?php do_action( 'granular_before_dashboard_title' ); ?>
			<h2><?php _e( 'Dashboard', 'elementor-controls' ); ?></h2>
			<div id="welcome-panel" class="welcome-panel">
				<?php wp_nonce_field( 'welcome-panel-nonce', 'granularwelcomepanelnonce', false ); ?>
				<?php do_action( 'granular_before_welcome_content' ); ?>
				<div class="welcome-panel-content">
					<?php echo Plugin::elementor()->frontend->get_builder_content_for_display( $panel_id );?>
				</div>
				<?php do_action( 'granular_after_welcome_content' ); ?>
			</div>		
		<?php do_action( 'granular_welcome_panel_footer' ); ?>
		</div>
		<?php }
	}
		
	public function disable_elementor_dashboard_overview_widget() {
		remove_meta_box( 'e-dashboard-overview', 'dashboard', 'normal' );
	}
	
	public function db_ui_hack_2() {
		echo '<style type="text/css">
			.elementor-panel .panel-elements-category-items{display: flex; flex-wrap: wrap; justify-content: flex-start;}.elementor-panel .elementor-element-wrapper{flex: 1 1 100px;}
		</style>';	
	}
	
	public function enqueue_editor_skin_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$color = granular_get_options( 'granular_editor_skin', 'granular_editor_settings', '' );
		
		wp_enqueue_style(
			'elementor-editor-skin',
			ELEMENTOR_CONTROLS_ASSETS_URL . 'css/elementor-' . $color . '-skin.css',
			[],
			ELEMENTOR_CONTROLS_VERSION
		);

	}
	
	public function enqueue_welcome_panel_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$panel_id = granular_get_options( 'granular_welcome_template_id', 'granular_advanced_settings', '' );		
		wp_enqueue_style( 'granular-dashboard-page', esc_url( site_url().'/wp-content/uploads/elementor/css/post-' . $panel_id . '.css', false, '1.1', 'all' ) );
	}
	
	private function functions_setup_hooks() {
		
		$accord_closed = granular_get_options( 'granular_accordion_off', 'granular_general_settings', 'no' );
		if ( 'yes' === $accord_closed ) {
			add_action( 'wp_footer', [ $this, 'elementor_accordion_off' ], 99 );
		}
		
		$dash_widget_off = granular_get_options( 'granular_dashboard_widget_off', 'granular_general_settings', 'no' );
		if ( 'yes' === $dash_widget_off ) {
			add_action( 'wp_dashboard_setup', [ $this, 'disable_elementor_dashboard_overview_widget' ], 40 );
		}
		
		$skin = granular_get_options( 'granular_editor_skin', 'granular_editor_settings', 'default' );
		if ( ! empty ( $skin ) ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_skin_styles' ] );
		}
		
		$editor_hack_2 = granular_get_options( 'granular_editor_hack_2', 'granular_editor_settings', 'no' );
		if ( 'yes' === $editor_hack_2 ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'db_ui_hack_2' ] );
		}
		
		/* 
		 * Advanced Options
		 */
		$custom_panel = granular_get_options( 'granular_welcome_on', 'granular_advanced_settings', 'no' );
		if ( 'yes' === $custom_panel ) {
			add_action( 'admin_notices', [ $this, 'granular_welcome_panel' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_welcome_panel_styles' ] );
		}
	}
	
	public function __construct() {
		$this->functions_setup_hooks();
		
	}
}