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
			add_submenu_page( 
				Elementor\Settings::PAGE_ID, 
				__( 'Granular Controls', 'granular-controls-for-elementor' ), 
				__( 'Granular Controls', 'granular-controls-for-elementor' ), 
				'delete_posts', 
				'granular_controls', 
				array($this, 'granular_settings_page' ) );
		}

		function get_settings_sections() {
			$sections = array(
				array(
					'id'    => 'granular_general_settings',
					'title' => __( 'General Controls', 'granular-controls-for-elementor' )
				),
				array(
					'id'    => 'granular_editor_settings',
					'title' => __( 'Editor Options', 'granular-controls-for-elementor' )
				),
				array(
					'id'    => 'granular_advanced_settings',
					'title' => __( 'Advanced Settings', 'granular-controls-for-elementor' )
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
				'' => '— ' . __( 'Select', 'granular-controls-for-elementor' ) . ' —',
			];
			foreach ( $templates as $template ) {
				$options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			}
			$settings_fields = array(
				'granular_general_settings' => array(
					array(
						'name'    => 'granular_accordion_off',
						'label'   => __( 'Accordions Closed?', 'granular-controls-for-elementor' ),
						'desc'    => __( 'Set all accordions\' first tab to be closed on page load.', 'granular-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_dashboard_widget_off',
						'label'   => __( 'Remove Dashboard Widget', 'granular-controls-for-elementor' ),
						'desc'    => __( 'Remove the Elementor\'s dashboard widget.', 'granular-controls-for-elementor' ),
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
						'label'   => __( 'Change Editor Color', 'granular-controls-for-elementor' ),
						'desc'    => __( 'Apply a custom color skin to the editor panel. Dark skin is by <a target="_blank" href="https://www.facebook.com/AlexIschenko2016">Alex Ischenko</a>', 'granular-controls-for-elementor' ),
						'type'    => 'select',
						'default' => '',
						'options' => array(
							'' 			=> __( 'Default', 'granular-controls-for-elementor' ),
							'dark' 		=> __( 'Dark', 'granular-controls-for-elementor' ),
							'lgrunge' 	=> __( 'Light Grunge', 'granular-controls-for-elementor' ),
							'dgrunge' 	=> __( 'Dark Grunge', 'granular-controls-for-elementor' ),
							'blue' 		=> __( 'Deep Blue', 'granular-controls-for-elementor' ),
							'purple' 	=> __( 'Deep Purple', 'granular-controls-for-elementor' ),
							'red' 		=> __( 'Red', 'granular-controls-for-elementor' ),
							'gred' 		=> __( 'Grunge Red', 'granular-controls-for-elementor' )
						),
					),
					array(
						'name'    => 'granular_editor_hack_2',
						'label'   => __( 'Elementor UI Hack Widget Panel', 'granular-controls-for-elementor' ),
						'desc'    => __( 'Elementor - Hacking away at UI Frustrations #2 - Widget Panel By <a target="_blank" href="https://www.facebook.com/profile.php?id=100011054383197">David Beckwith</a>.', 'granular-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					//array(
					//	'name'    => 'granular_onion_skin_on',
					//	'label'   => __( 'Elementor Onion Skin Hack', 'granular-controls-for-elementor' ),
					//	'desc'    => __( 'Elementor - Apply the Onion Skin Hack By <a target="_blank" href="https://www.facebook.com/profile.php?id=100011054383197">David Beckwith</a>.', 'granular-controls-for-elementor' ),
					//	'type'    => 'radio',
					//	'default' => 'no',
					//	'options' => array(
					//		'yes' => 'Yes',
					//		'no'  => 'No'
					//	)
					//),
					array(
						'name'    => 'granular_editor_parallax_on',
						'label'   => __( 'Enable Parallax', 'granular-controls-for-elementor' ),
						'desc'    => __( 'Choose to load the Parallax scripts and it\'s controls or not!', 'granular-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_editor_particles_on',
						'label'   => __( 'Enable Particles', 'granular-controls-for-elementor' ),
						'desc'    => __( 'Choose to load the Particles scripts and it\'s controls or not!', 'granular-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_editor_exit_on',
						'label'   => __( 'Enable Exit Bar', 'granular-controls-for-elementor' ),
						'desc'    => __( 'Don\'t like having to go through too many hoops in order to exit the editor? There\'s a control for that - just enable to get a 1 exit option bar!', 'granular-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_editor_exit_point',
						'label'   => __( 'Exit Point', 'granular-controls-for-elementor' ),
						'desc'    => __( 'Select where to land when the Exit To Dashboard buttons is clicked - Default is the current post/page edit screen', 'granular-controls-for-elementor' ),
						'type'    => 'select',
						'default' => '',
						'options' => array(
							'editor' 		=> __( 'Edit Screen', 'granular-controls-for-elementor' ),
							'type_pages'	=> __( 'Pages List', 'granular-controls-for-elementor' ),
							'type_posts'	=> __( 'Posts List', 'granular-controls-for-elementor' ),
							'type_lib'		=> __( 'Library List', 'granular-controls-for-elementor' ),
							'dashboard' 	=> __( 'Admin Dashboard', 'granular-controls-for-elementor' ),
							'live' 			=> __( 'Site\'s Home Page', 'granular-controls-for-elementor' )
						),
					),
					array(
						'name'    => 'granular_editor_exit_target',
						'label'   => __( 'Exit Target', 'granular-controls-for-elementor' ),
						'desc'    => __( 'Select How the exit happens. Sometimes you might want to quickly pop into the Admin area without leaving the editor<br /> then setting the Exit Point to a new tab might be ideal for your work flow :)', 'granular-controls-for-elementor' ),
						'type'    => 'select',
						'default' => '',
						'options' => array(
							'' 			=> __( 'Same Tab/Window', 'granular-controls-for-elementor' ),
							'_blank'	=> __( 'New Tab/Window', 'granular-controls-for-elementor' )
						),
					),
					array(
						'name'    => 'granular_editor_exit_name',
						'label'   => __( 'Exit Name', 'granular-controls-for-elementor' ),
						'desc'    => __( 'If you\'ve changed the default exit point it might be worth changing the button text too so that you know where you\'ll land on exit :) ', 'granular-controls-for-elementor' ),
						'type'    => 'text',
						'default' => __( 'Exit To Dashboard', 'granular-controls-for-elementor' ),
					),
					array(
						'name'    => 'granular_editor_live_view_name',
						'label'   => __( 'Live View Name', 'granular-controls-for-elementor' ),
						'desc'    => __( 'Change the Live View text or leave empty to only show the icon :) ', 'granular-controls-for-elementor' ),
						'type'    => 'text',
						'default' => __( 'View Live Page', 'granular-controls-for-elementor' ),
					),
				),
				'granular_advanced_settings' => array(
					array(
						'name'    => 'granular_elementor_dashboard_on',
						'label'   => __( 'Elementor In Dashboard', 'granular-controls-for-elementor' ),
						'desc'    => __( 'Enable use of Elementor content in the Admin Dashboard - below options will not function correctly with this setting turned off!.', 'granular-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_welcome_on',
						'label'   => __( 'Welcome Panel', 'granular-controls-for-elementor' ),
						'desc'    => __( 'Enable the custom Granular Welcome Panel in the Admin Dashboard.', 'granular-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_welcome_template_id',
						'label'   => __( 'Panel Template ID', 'granular-controls-for-elementor' ),
						'desc'    => __( 'Select the template you\'d like to be used as the Welcome Panel in the Admin Dashboard.', 'granular-controls-for-elementor' ),
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