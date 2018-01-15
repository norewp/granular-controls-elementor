<?php
namespace ElementorControls;

use Elementor;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main class plugin
 */
class Plugin {

	/**
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * @var Manager
	 */
	private $_modules_manager;

	/**
	 * @deprecated
	 *
	 * @return string
	 */
	public function get_version() {
		return ELEMENTOR_CONTROLS_VERSION;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'elementor-controls' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'elementor-controls' ), '1.0.0' );
	}

	/**
	 * @return \Elementor\Plugin
	 */

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function _includes() {
		require ELEMENTOR_CONTROLS_PATH . 'includes/modules-manager.php';
		require ELEMENTOR_CONTROLS_PATH . 'includes/granular-functions.php';
		
		if ( is_admin() ) {
			require( ELEMENTOR_CONTROLS_PATH . 'settings/class.settings-api.php' );
			require( ELEMENTOR_CONTROLS_PATH . 'settings/settings.php' );
		}
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$filename = strtolower(
			preg_replace(
				[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
				[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
				$class
			)
		);
		$filename = ELEMENTOR_CONTROLS_PATH . $filename . '.php';

		if ( is_readable( $filename ) ) {
			include( $filename );
		}
	}

	public function enqueue_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$direction_suffix = is_rtl() ? '-rtl' : '';

		
	}

	public function enqueue_frontend_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	}

	public function enqueue_editor_scripts() {
		$suffix = Utils::is_script_debug() ? '' : '.min';
	
	}

	public function register_frontend_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	}

	public function enqueue_editor_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

	public function elementor_controls_init() {
		$this->_modules_manager = new Manager();

		$elementor = Elementor\Plugin::$instance;

		// Add element category in panel
		$elementor->elements_manager->add_category(
			'elementor-controls-elements',
			[
				'title' => __( 'Elementor Controls Elements', 'elementor-controls' ),
				'icon' => 'font',
			],
			1
		);

		do_action( 'elementor_controls/init' );
	}

	private function setup_hooks() {
		add_action( 'elementor/init', [ $this, 'elementor_controls_init' ] );
		
		//add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		$this->_includes();

		$this->setup_hooks();
		
		if ( is_admin() ) {
			new Granular_Controls_Settings_API();
		}
		
		new Plugin_Functions();
	}
}

if ( ! defined( 'ELEMENTOR_CONTROLS_TESTS' ) ) {
	// In tests we run the instance manually.
	Plugin::instance();
}