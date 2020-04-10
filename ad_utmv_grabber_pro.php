<?php

/**
 * Plugin Name: AdTrails UTM Grabber Pro
 * Plugin URI: https://www.adtrails.com/pricing/
 * Description: AdTrails UTM Grabber Premium pushes UTM/GCLID info CF7, Gravity Forms, WP Forms, and Ninja Forms.
 * Version: 1.0.0
 * Author:  Actuate Media
 * Author URI: https://www.adtrails.com
 * Text Domain: ad_utmv_grabber_pro
 * Domain Path: /i18n/languages/
 *
 * @package ad_utmv_grabber_pro
 */
if (!class_exists('UtmvGrabber')) {
	add_action( 'admin_notices', 'ad_error_notice' );
	return false;
}

function ad_error_notice() {
?>
    <div class="error notice">
        <p><?php _e( 'AdTrails UTM Grabber Pro is not working because you need to activate the AdTrails UTM Grabber plugin' ); ?></p>
    </div>
<?php }
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

// Global for backwards compatibility.
$GLOBALS['utmv_grabber_pro'] = UtmvGrabberPro();
