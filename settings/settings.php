<?php
namespace ElementorControls;

use Elementor;
/**
 * WordPress settings API: Granular Controls For Elementor
 *
 * @author Zulfikar Nore
 */
if ( !class_exists('Granular_Controls_Settings_API' ) ) {
	class Granular_Controls_Settings_API {

		private $settings_api;

		function __construct() {
			$this->settings_api = new Granular_Settings_API;

			add_action( 'admin_init', array($this, 'admin_init') );
			add_action( 'admin_menu', array($this, 'add_admin_menu'), 503 );
		}

		function admin_init() {

			//set the settings
			$this->settings_api->set_sections( $this->get_settings_sections() );
			$this->settings_api->set_fields( $this->get_settings_fields() );

			//initialize settings
			$this->settings_api->admin_init();
		}

		function add_admin_menu() {
			add_submenu_page( Elementor\Settings::PAGE_ID, 'Granular Controls', 'Granular Controls', 'delete_posts', 'granular_controls', array($this, 'granular_settings_page' ) );
		}

		function get_settings_sections() {
			$sections = array(
				array(
					'id'    => 'granular_general_settings',
					'title' => __( 'General Controls', 'elementor-controls' )
				),
				array(
					'id'    => 'granular_editor_settings',
					'title' => __( 'Editor Options', 'elementor-controls' )
				),
				array(
					'id'    => 'granular_advanced_settings',
					'title' => __( 'Advanced Settings', 'elementor-controls' )
				)
			);
			return $sections;
		}

		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		function get_settings_fields() {

			$templates = $this->get_templates();
			$options = [
				'' => '— ' . __( 'Select', 'elementor-controls' ) . ' —',
			];
			foreach ( $templates as $template ) {
				$options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			}
			$settings_fields = array(
				'granular_general_settings' => array(
					array(
						'name'    => 'granular_accordion_off',
						'label'   => __( 'Accordions Closed?', 'elementor-controls' ),
						'desc'    => __( 'Set all accordions\' first tab to be closed on page load.', 'elementor-controls' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_dashboard_widget_off',
						'label'   => __( 'Remove Dashboard Widget', 'elementor-controls' ),
						'desc'    => __( 'Remove the Elementor\'s dashboard widget.', 'elementor-controls' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					)
				),
				'granular_editor_settings' => array(
					array(
						'name'    => 'granular_editor_skin',
						'label'   => __( 'Change Editor Color', 'elementor-controls' ),
						'desc'    => __( 'Apply a custom color skin to the editor panel. Dark skin is by <a target="_blank" href="https://www.facebook.com/AlexIschenko2016">Alex Ischenko</a>', 'elementor-controls' ),
						'type'    => 'select',
						'default' => '',
						'options' => array(
							'' 			=> __( 'Default', 'elementor-controls' ),
							'dark' 		=> __( 'Dark', 'elementor-controls' ),
							'lgrunge' 	=> __( 'Light Grunge', 'elementor-controls' ),
							'dgrunge' 	=> __( 'Dark Grunge', 'elementor-controls' ),
							'blue' 		=> __( 'Deep Blue', 'elementor-controls' ),
							'purple' 	=> __( 'Deep Purple', 'elementor-controls' ),
							'red' 		=> __( 'Red', 'elementor-controls' ),
							'gred' 		=> __( 'Grunge Red', 'elementor-controls' )
						),
					),
					array(
						'name'    => 'granular_editor_hack_2',
						'label'   => __( 'Elementor UI Hack Widget Panel', 'elementor-controls' ),
						'desc'    => __( 'Elementor - Hacking away at UI Frustrations #2 - Widget Panel By <a target="_blank" href="https://www.facebook.com/profile.php?id=100011054383197">David Beckwith</a>.', 'elementor-controls' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					)
				),
				'granular_advanced_settings' => array(
					array(
						'name'    => 'granular_elementor_dashboard_on',
						'label'   => __( 'Elementor In Dashboard', 'elementor-controls' ),
						'desc'    => __( 'Enable use of Elementor content in the Admin Dashboard - below options will not function correctly with this setting turned off!.', 'elementor-controls' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_welcome_on',
						'label'   => __( 'Welcome Panel', 'elementor-controls' ),
						'desc'    => __( 'Enable the custom Granular Welcome Panel in the Admin Dashboard.', 'elementor-controls' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_welcome_template_id',
						'label'   => __( 'Panel Template ID', 'elementor-controls' ),
						'desc'    => __( 'Select the template you\'d like to be used as the Welcome Panel in the Admin Dashboard.', 'elementor-controls' ),
						'type'    => 'select',
						'default' => '',
						'options' => $options,
					),
				)
			);

			return $settings_fields;
		}

		function granular_settings_page() {
			echo '<div class="wrap">';
				$this->settings_api->show_navigation();
				$this->settings_api->show_forms();
			echo '</div>';
		}

		/**
		 * Get all the pages
		 *
		 * @return array page names with key value pairs
		 */
		function get_pages() {
			$pages = get_pages();
			$pages_options = array();
			if ( $pages ) {
				foreach ($pages as $page) {
					$pages_options[$page->ID] = $page->post_title;
				}
			}

			return $pages_options;
		}
		
		public static function get_templates() {
			return Plugin::elementor()->templates_manager->get_source( 'local' )->get_items();
		}

	}
}
