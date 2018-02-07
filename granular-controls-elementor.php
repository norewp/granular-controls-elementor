<?php
/**
 * Plugin Name: Granular Controls Elementor
 * Description: Take control of your favourite page builder's elements to design better websites and landing pages and overall better UI/UX.
 * Plugin URI: https://github.com/norewp/granular-controls-elementor
 * Version: 1.0.4
 * Author: Zulfikar Nore
 * Author URI: https://granularcontrols.com/
 * Text Domain: granular-controls-for-elementor
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ELEMENTOR_CONTROLS_VERSION', '1.0.4' );
define( 'ELEMENTOR_CONTROLS_PREVIOUS_STABLE_VERSION', '1.0.2' );

define( 'ELEMENTOR_CONTROLS__FILE__', __FILE__ );
define( 'ELEMENTOR_CONTROLS_PLUGIN_BASE', plugin_basename( ELEMENTOR_CONTROLS__FILE__ ) );
define( 'ELEMENTOR_CONTROLS_PATH', plugin_dir_path( ELEMENTOR_CONTROLS__FILE__ ) );
define( 'ELEMENTOR_CONTROLS_MODULES_PATH', ELEMENTOR_CONTROLS_PATH . 'modules/' );
define( 'ELEMENTOR_CONTROLS_URL', plugins_url( '/', ELEMENTOR_CONTROLS__FILE__ ) );
define( 'ELEMENTOR_CONTROLS_ASSETS_URL', ELEMENTOR_CONTROLS_URL . 'assets/' );
define( 'ELEMENTOR_CONTROLS_MODULES_URL', ELEMENTOR_CONTROLS_URL . 'modules/' );

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function elementor_controls_load_plugin() {
	load_plugin_textdomain( 'granular-controls-for-elementor' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'elementor_controls_fail_load' );
		return;
	}

	$elementor_version_required = '1.4.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'elementor_controls_fail_load_out_of_date' );
		return;
	}

	$elementor_version_recommendation = '1.4.1';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_recommendation, '>=' ) ) {
		add_action( 'admin_notices', 'elementor_controls_admin_notice_upgrade_recommendation' );
	}

	require( ELEMENTOR_CONTROLS_PATH . 'plugin.php' );
}
add_action( 'plugins_loaded', 'elementor_controls_load_plugin' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function elementor_controls_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

		$message = '<p>' . __( 'Granular Controls not working because you need to activate the Elementor plugin.', 'granular-controls-for-elementor' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'granular-controls-for-elementor' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message = '<p>' . __( 'Granular Controls is not working because you need to install the Elementor plugin', 'granular-controls-for-elementor' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'granular-controls-for-elementor' ) ) . '</p>';
	}

	echo '<div class="error"><p>' . $message . '</p></div>';
}

function elementor_controls_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'Granular Controls not working because you are using an old version of Elementor.', 'granular-controls-for-elementor' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'granular-controls-for-elementor' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

function elementor_controls_admin_notice_upgrade_recommendation() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'A new version of Elementor is available. For better performance and compatibility of Elementor Custom Controls, we recommend updating to the latest version.', 'granular-controls-for-elementor' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'granular-controls-for-elementor' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}

/**
* Get the value of a settings field
*
* @param string $option settings field name
* @param string $section the section name this field belongs to
* @param string $default default text if it's not found
* @return mixed
*/
function granular_get_options( $option, $section, $default = '' ) {

    $options = get_option( $section );

    if ( isset( $options[$option] ) ) {
		return $options[$option];
    }

    return $default;
}

function elementor_dashboard_enqueue() {
    /* Get current page context */
	global $pagenow;
	
	if( 'index.php' != $pagenow ){
		return;
	}
	
	wp_enqueue_style( 'granular-dashboard-content', ELEMENTOR_CONTROLS_ASSETS_URL . 'css/granular-dashboard.min.css', false, '1.1', 'all' );
	
	global $wp_styles, $is_IE;
	wp_enqueue_style( 'granular-font-awesome', ELEMENTOR_CONTROLS_ASSETS_URL . 'font-awesome/css/font-awesome.min.css', array(), '4.7.0' );
	if ( $is_IE ) {
		wp_enqueue_style( 'granular-font-awesome-ie', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome-ie7.min.css', array('granular-font-awesome'), '4.7.0' );
		// Add IE conditional tags for IE 7 and older
		$wp_styles->add_data( 'granular-font-awesome-ie', 'conditional', 'lte IE 7' );
	}
	
	wp_enqueue_script( 'granular-dashboard-content-js', ELEMENTOR_CONTROLS_ASSETS_URL . 'js/granular-dashboard.min.js', array( 'jquery' ), time(), true );
}
$elementor_dash_on = granular_get_options( 'granular_elementor_dashboard_on', 'granular_advanced_settings', 'no' );
if ( 'yes' === $elementor_dash_on ) {
	remove_action( 'welcome_panel', 'wp_welcome_panel' );
	add_action( 'admin_enqueue_scripts', 'elementor_dashboard_enqueue' );
}

