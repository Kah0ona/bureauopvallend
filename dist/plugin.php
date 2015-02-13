<?php
/*
Plugin Name: Bureauopvallend Filter Plugin
Plugin URI: http://www.bureauopvallend.nl
Description: Filter plugin for filtering the games page
Version: 1.0
Author: Marten Sytema
Author URI: http://www.lokaalgevonden.nl
Author Email: marten@lokaalgevonden.nl
License:

  Copyright 2013 LokaalGevonden (marten@lokaalgevonden.nl)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
define('PLUGIN_PATH', plugin_dir_path(__FILE__) );

class BureauOpvallend {
	protected $options = null;

	function __construct() {
		// Load plugin text domain
		add_action('init', array( $this, 'plugin_textdomain' ) );
		add_action('init', array($this, 'load_options'));

		add_filter('plugins_loaded', array($this,'start_session')); //first code to be executed.

		// Register site styles and scripts
		add_action('wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		add_action('wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
	
		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );
				
		add_action('widgets_init', array($this, 'register_widgets' ));
		//this must be inside is_admin, see: http://codex.wordpress.org/AJAX_in_Plugins#Ajax_on_the_Viewer-Facing_Side	
		if(is_admin()){
			add_action('wp_ajax_nopriv_get_games',array($this,'fetch_games'));			
			add_action('wp_ajax_get_games',array($this,'fetch_games'));		
		}

	} 

	public function fetch_games() {
		include_once('models/FilterModel.php');
		$m = new FilterModel(null);
		$json = $m->getGames();
		header('Content-Type: application/json');
		echo $json;
		exit;
	}

	public function start_session(){
		session_start();
	}
	
	public function register_widgets(){
		include_once('widgets/FilterWidget.php');
		register_widget('FilterWidget');
	}
	
	public function load_options(){
		include_once('models/PluginOptions.php');
		$w = new PluginOptions();
		$w->loadOptions();
		$this->options = $w;
	}
	
	public function settings_menu(){
	}
	
	public function render_settings_menu(){
	}
	
	public function register_settings(){
	}
	
	/**
	 * Fired when the plugin is activated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function activate( $network_wide ) {
	
	} // end activate
	
	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function deactivate( $network_wide ) {

	} // end deactivate
	
	/**
	 * Fired when the plugin is uninstalled.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function uninstall( $network_wide ) {

	} // end uninstall

	/**
	 * Loads the plugin text domain for translation
	 */
	public function plugin_textdomain() {
		$domain = 'bureauopvallend-locale';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
        load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
        load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	} // end plugin_textdomain

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {
		wp_enqueue_style( 'bureauopvallend-admin-styles', plugins_url( '/bureauopvallend/css/admin.css' ) );
	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */	
	public function register_admin_scripts() {
		wp_enqueue_script( 'bureauopvallend-admin-script', plugins_url( '/bureauopvallend/js/admin.js' ) );
		if (isset($_GET['page']) && $_GET['page'] == 'bureauopvallend') {
	        wp_enqueue_media();
	        wp_register_script('file-upload-js', WP_PLUGIN_URL.'/bureauopvallend/js/file.upload.js', array('jquery'));
	        wp_enqueue_script('file-upload-js');
		}		
	} // end register_admin_scripts
	
	/**
	 * Registers and enqueues plugin-specific styles.
	 */
	public function register_plugin_styles() {
		wp_enqueue_style( 'bureauopvallend-plugin-styles', plugins_url( '/bureauopvallend/css/style.css' ) );
	} // end register_plugin_styles 
	
	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	public function register_plugin_scripts() {
		wp_enqueue_script('filterscript', plugins_url('/bureauopvallend/js/filter.js'), array('jquery') );		
	}
} // end class

$filterPlugin = new BureauOpvallend();
