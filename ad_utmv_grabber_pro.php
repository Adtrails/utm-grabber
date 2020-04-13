<?php

/**
 * Plugin Name: AdTrails UTM Grabber Pro
 * Plugin URI: https://www.adtrails.com/pricing/
 * Description: AdTrails UTM Grabber Premium pushes UTM/GCLID info CF7, Gravity Forms, WP Forms, and Ninja Forms.
 * Version: 1.1.0
 * Author:  Actuate Media
 * Author URI: https://www.adtrails.com
 * Text Domain: ad_utmv_grabber_pro
 * Domain Path: /i18n/languages/
 *
 * @package ad_utmv_grabber_pro
 */

if ( ! function_exists( '_is_adtrail_installed' ) ) {
	function _is_adtrail_installed() {
		$file_path = 'ad_utmv_grabber/ad_utmv_grabber.php';
		$installed_plugins = get_plugins();
		return isset( $installed_plugins[ $file_path ] );
	}
}
if (!class_exists('UtmvGrabber')) {
	add_action( 'admin_notices', 'ad_error_notice' );
	return false;
}

function ad_error_notice() { 
	?>
    <div class="error notice">		
		<?php
			$plugin = 'ad_utmv_grabber/ad_utmv_grabber.php';
			if ( _is_adtrail_installed() ) {
				if ( ! current_user_can( 'activate_plugins' ) ) {
					return;
				}

				$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
				$message =  '<p>' .  __( 'AdTrails UTM Grabber Pro is not working because you need to activate the AdTrails UTM Grabber FREE plugin. <br/> <a' ). '</p>';
				$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate AdTrails UTM Grabber FREE Now', 'ad_utmv_grabber_pro' ) ) . '</p>';
				echo $message;
			} else {
				if ( ! current_user_can( 'install_plugins' ) ) {
					return;
				}

				/* Installed from wordpress.org */				
				//$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=handl-utm-grabber' ), 'install-plugin_handl-utm-grabber' );
				$install_url = "https://www.adtrails.com/wp-content/uploads/2020/04/ad_utmv_grabber.zip";
				
				$message = '<p>' . __( 'AdTrails UTM Grabber Pro is not working because you need to install the AdTrails UTM Grabber FREE plugin.', 'ad_utmv_grabber_pro' ) . '</p>';
				$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install AdTrails UTM Grabber FREE Now', 'ad_utmv_grabber_pro' ) ) . '</p>';
				echo $message;
			}
		?>		
    </div><?php 
}

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define UTMV_PLUGIN_FILE PRO.
if (!defined('UTMV_GRABBER_PRO_PLUGIN_FILE')) {
    define('UTMV_GRABBER_PRO_PLUGIN_FILE', __FILE__);
}



// Include the main ClassUtmvGrabberPro class.
if (!class_exists('ClassUtmvGrabberPro')) {
    include_once dirname(__FILE__) . '/inc/ClassUtmvGrabber.php';
}

/**
 * Main instance of UtmvGrabberPro.
 *
 * Returns the main instance of UtmvGrabberPro.
 *
 * @since  1.0.0
 * @return UtmvGrabberPro
 */
function UtmvGrabberPro() {
    return UtmvGrabberPro::instance();
}
//setting link by Arif Uddin
function ad_utmv_grabber_pro_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=UtmvGrabber_options">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = basename(__DIR__).'/ad_utmv_grabber_pro.php';
add_filter( "plugin_action_links_$plugin", 'ad_utmv_grabber_pro_settings_link' );
/* --------------end ----------------*/

// Global for backwards compatibility.
$GLOBALS['utmv_grabber_pro'] = UtmvGrabberPro();
