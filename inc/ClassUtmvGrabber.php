<?php

/*
 * UtmvGrabberPro Main class
 * @package ad_utmv_grabber_pro/inc
 * @since   1.0.0
 */

class UtmvGrabberPro {

    /**
     * UtmvGrabber version.
     *
     * @var string
     */
    public $version = '1.1.0';

    /**
     * The single instance of the class.
     *
     * @var UtmvGrabber
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * UtmvGrabber core functions
     *
     * @var engine
     * @since 1.0.0
     */
    public $engine;

    /**
     * Main UtmvGrabber Instance.
     *
     * Ensures only one instance of IsLayouts is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return UtmvGrabber.
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * NeonMaker Constructor.
     *
     * @global Array $UtmvGrabberSetting
     *
     */
    function __construct() {
        global $UtmvGrabberProSetting;

        $UtmvGrabberProSetting = get_option('UtmvGrabberPro_options', true);
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
        $this->engine = new UtmvGrabberCorePro();
    }

    /**
     * Hook into actions and filters.
     *
     * @since 1.0.0
     */
    public function init_hooks() {
        register_activation_hook(UTMV_GRABBER_PRO_PLUGIN_FILE, array($this, 'UtmvGrabberPro_plugin_install'));
        add_action('init', array($this, 'init'), 0);

		/* loader */
		/* $edd_utmv_grabber_license_status = get_option('edd_utmv_grabber_license_status', true);
		if($edd_utmv_grabber_license_status == 'valid') {
			add_action('plugins_loaded',array('UtmvGrabber_Updater', 'instance'));
		} */
        /* register front end scripts */
        add_action('wp_enqueue_scripts', array($this, 'UtmvGrabberProScripts'), 0);

        /* register admin scripts */
        add_action('admin_enqueue_scripts', array($this, 'UtmvGrabberProAdminScripts'), 0);
    }

    /*
     * UtmvGrabberPro installation hook
     */

    public function UtmvGrabberPro_plugin_install() {
        global $wpdb;
        $prefix=$wpdb->prefix;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    }

    /**
     * Init plugin when WordPress Initialises.
     */
    public function init() {
    }
    /**
     * Define UtmvGrabberPro Constants.
     */
    private function define_constants() {
        $this->define('UTMV_GRABBER_PRO_PLUGIN_DIR', basename(__DIR__));
        $this->define('UTMV_GRABBER_PRO_ABSPATH', dirname(UTMV_GRABBER_PRO_PLUGIN_FILE) . '/');
        $this->define('UTMV_GRABBER_PRO_BASENAME', plugin_basename(UTMV_GRABBER_PRO_PLUGIN_FILE));
        $this->define('UTMV_GRABBER_PRO_URL', plugins_url(basename(UTMV_GRABBER_PRO_ABSPATH)));
        $this->define('UTMV_GRABBER_PRO_VERSION', $this->version);
        $this->define('UTMV_GRABBER_PRO_CURRENCY', 'USD');
        $this->define('UTMV_EDD_SAMPLE_ITEM_NAME', 'AdTrails UTM Grabber Plugin');
        $this->define('UTMV_SIMPLE_ADDON_EDD_SL_STORE_URL', 'https://www.adtrails.com');
        $this->define('UTMV_SL_ITEM_ID', 83);
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {
        include_once UTMV_GRABBER_PRO_ABSPATH . '/inc/ClassUtmvGrabberCore.php';
		include_once UTMV_GRABBER_PRO_ABSPATH . '/inc/ClassActions.php';
        include_once UTMV_GRABBER_PRO_ABSPATH . '/inc/ClassShortcodes.php';
        include_once UTMV_GRABBER_PRO_ABSPATH . '/inc/ClassZapier.php';
        //include_once UTMV_GRABBER_PRO_ABSPATH . '/inc/ClassUpdater.php';
        if (is_admin()) {
            include_once UTMV_GRABBER_PRO_ABSPATH . '/inc/ClassAdminOptions.php';
        } 		
    }

    /**
     * register and enque front end styles and scripts.
     *
     * @since 1.0.0
     */
    public function UtmvGrabberProScripts() {
        global $post;
        global $UtmvGrabberProSetting;

        wp_enqueue_script('UtmvGrabberPro_script', UTMV_GRABBER_PRO_URL . "/assets/js/utmv_grabber.js", array('jquery'), UTMV_GRABBER_PRO_VERSION);
        wp_enqueue_style('UtmvGrabberPro_style', UTMV_GRABBER_PRO_URL . '/assets/css/utmv_grabber.css', array(), UTMV_GRABBER_PRO_VERSION);

        wp_localize_script('UtmvGrabberPro_script', 'UtmvGrabberPro_localize', array(
                'ajax_url' => admin_url('admin-ajax.php')                
            )
        );
    }

    public function UtmvGrabberProAdminScripts() {
        wp_enqueue_script('UtmvGrabberPro_admin_script', UTMV_GRABBER_PRO_URL . '/assets/js/utmv_grabber_admin.js', array('jquery'), UTMV_GRABBER_PRO_VERSION);
        wp_enqueue_style('UtmvGrabberPro_admin_style', UTMV_GRABBER_PRO_URL . '/assets/css/utmv_grabber_admin.css', array(), UTMV_GRABBER_PRO_VERSION);	
		
		wp_localize_script('UtmvGrabberPro_admin_script', 'UtmvGrabberProAdmin_localize', array(
                'ajax_url' => admin_url('admin-ajax.php')                
            )
        );		
    }

    /**
     * Define constant if not already set.
     *
     * @param string      $name  Constant name.
     * @param string|bool $value Constant value.
     */
    private function define($name, $value) {
        if (!defined($name)) {
            define($name, $value);
        }
    }	 
}
?>