<?php
/*
 * ClassUpdater.php
 * 
 * Copyright 2020 AdTrails <contact@adtrails.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */
class UtmvGrabber_Updater {

	public static $access_token = 'ea63d211c73740aa644c7916c359e006c4f4d60e';
	public static $endpoint = 'https://api.github.com/repos/Adtrails/utm-grabber/releases/latest';
	public static $plugin_dir; 

	public static $plugin_file;
	/**
	 * pseudo-constructor
	 *
	 * @since   1.0.2
	 */
	public static function instance() {
		new self();
	}

	public function __construct() {	
	
		self::$plugin_dir = '/ad_utmv_grabber_paid/';
		self::$plugin_file = UTMV_GRABBER_PRO_BASENAME;
		
		add_action( 'init', array( &$this, 'init' ) );
		add_filter( 'plugins_api', array( &$this, 'get_plugin_info' ), 10, 3 );
		add_filter( 'upgrader_post_install', array( &$this, 'upgrader_post_install' ), 10, 3 );
		add_action( 'admin_init', array( __CLASS__, 'utmvgrabber_check_update' ) );

	}

	/**
	 * Plugin init
	 *
	 * @since 1.0.1
	 * @return string|null 
	 */
	public function init() {

		// Version compare
		if( ! version_compare( UTMV_GRABBER_PRO_VERSION, get_option( 'utmvgrabber_newest_version' ), '<') ) {
			return;
		}
		if ( is_admin() && current_user_can( 'install_plugins' ) ) {
			add_action( 'site_transient_update_plugins', array( __CLASS__, 'utmvgrabber_filter_update' ), 10, 2 );
			add_action( 'transient_update_plugins', array( __CLASS__, 'utmvgrabber_filter_update' ), 10, 2 );
			add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'api_check' ) );
			
		}
	}

	/**
	 * Check update version
	 *
	 * @since 1.0.1
	 * @return null
	 */

	public static function utmvgrabber_check_update() {
		
		$endpoint = self::$endpoint;
		$access_token = self::$access_token;
		$url = $endpoint . '?access_token=' . $access_token;
		$request = wp_remote_get( $url, array(
			'timeout' => 120
		));
		if ( ! is_wp_error( $request ) ) {
			
			$response = json_decode($request['body'], true);
			if(!isset($response['error']) && isset($response['tag_name'])){
				$tag_name = $response['tag_name'];
				$newest_version = ltrim( $tag_name, 'v');
				update_option( 'utmvgrabber_newest_version', $newest_version );
				update_option( 'utmvgrabber_last_updated', '' );
				update_option( 'utmvgrabber_zip_url', $response['zipball_url'] );
			}
		}
	}
	public function utmvgrabber_plugin_info()
	{
		return "test";	
	}
	/**
	 * Set update notif
	 * 
	 * @param  object $update_plugins
	 * @return null
	 */
	public static function utmvgrabber_filter_update($update_plugins) {
		if ( ! is_object( $update_plugins ) )
			return $update_plugins;

		if ( ! isset( $update_plugins->response ) || ! is_array( $update_plugins->response ) )
			$update_plugins->response = array();

		if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'plugin-information' && $_GET['plugin'] == 'utmvgrabber' ) {
			echo '<iframe width="100%" height="100%" border="0" style="border:none;" src="https://www.adtrails.com/pricing/"></iframe>';
			exit;
		}

		$update_plugins->response[self::$plugin_file] = (object)array(
			'slug'         => 'utmvgrabber',
			'new_version'  => get_option( 'utmvgrabber_newest_version' ), // The newest version
			'url'          => 'https://www.adtrails.com', // Informational
			'package'      => get_option( 'utmvgrabber_zip_url' ) . '?access_token=' . self::$access_token 
		);
		return $update_plugins;
	}

	/**
	 * Get Plugin info
	 *
	 * @since 1.0.2
	 * @param bool    $false  always false
	 * @param string  $action the API function being performed
	 * @param object  $args   plugin arguments
	 * @return object $response the plugin info
	 */
	public function get_plugin_info( $false, $action, $response ) {
		// Check if this call API is for the right plugin
		if ( !isset( $response->slug ) || $response->slug )
			return false;
		$response->slug = self::$plugin_file;
		$response->plugin_name  = 'AdTrails UTM Grabber Pro';
		$response->version = get_option( 'utmvgrabber_newest_version' );
		$response->author = ' Actuate Media';
		$response->homepage = 'https://www.adtrails.com/pricing/';
		$response->requires = '4.0';
		$response->tested = '4.3';
		$response->downloaded   = 0;
		$response->last_updated = '';
		$response->sections = array( 'description' => 'Test' );
		$response->download_link = get_option( 'utmvgrabber_zip_url' ) . '?access_token=' . self::$access_token;
		return $response;
	}

	/**
	 * Hook into the plugin update check and connect to GitHub
	 *
	 * @since 1.0.2
	 * @param object  $transient the plugin data transient
	 * @return object $transient updated plugin data transient
	 */
	public function api_check( $transient ) {
		// Check if the transient contains the 'checked' information
		// If not, just return its value without hacking it
		if ( empty( $transient->checked ) )
			return $transient;

		// check the version and decide if it's new
		$update = version_compare( get_option( 'utmvgrabber_newest_version' ), UTMV_GRABBER_PRO_VERSION );
		if ( 1 === $update ) {
			$response = new stdClass;
			$response->new_version = get_option( 'utmvgrabber_newest_version' );
			$response->slug = '/'.self::$plugin_file;
			$response->url = add_query_arg( array( 'access_token' => self::$access_token ), 'https://github.com/Adtrails/utm-grabber' );
			$response->package = get_option( 'utmvgrabber_zip_url' );

			// If response is false, don't alter the transient
			if ( false !== $response )
				$transient->response[ '/'.self::$plugin_file] = $response;
		}
		return $transient;
	}

	public function upgrader_post_install($true, $hook_extra, $result) {
		global $wp_filesystem;
		// Move & Activate
		$proper_destination = WP_PLUGIN_DIR.self::$plugin_dir;
		$wp_filesystem->move( $result['destination'], $proper_destination );
		$result['destination'] = $proper_destination;
		$activate = activate_plugin( WP_PLUGIN_DIR.'/'.self::$plugin_file );

		// Output the update message
		$fail  = __( 'The plugin has been updated, but could not be reactivated. Please reactivate it manually.', 'AdTrails' );
		$success = __( 'Plugin reactivated successfully.', 'AdTrails' );
		echo is_wp_error( $activate ) ? $fail : $success;
		return $result;
	}
}