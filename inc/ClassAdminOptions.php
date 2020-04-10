<?php
class UtmvGrabberProOptions {

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct() {
        //add_action('admin_menu', array($this, 'UtmvGrabber__plugin_page'));
		$this->options = get_option('UtmvGrabber_options');
        add_action('ad_utmgrabber_pro_settings', array($this, 'UtmvGrabber_options_page'));
        add_action('admin_init', array($this, 'page_init'));
		//add_action('admin_init', [$this, 'edd_adtrails_activate_license']);
    }
	
    /**
     * Add options page
     */
    public function UtmvGrabber__plugin_page() {
       
    }
    /**
     * Options page callback
     */
    public function UtmvGrabber_options_page() {
		
        echo '<div class="ad_pro_key_wrap">';
		echo wp_sprintf('<h2>%s</h2>', __('Licence Key Section', 'ad_utmv_grabber'));
        $this->licence_key_api();
        echo '</div>';
    }
   
    /**
     * Register and add settings
     */
    public function page_init() {
		register_setting(
                'UtmvGrabber_option_group', // Option group
                'UtmvGrabber_options', // Option name
                [$this, 'sanitize'] // Sanitize
        );
        add_settings_section(
                'UtmvGrabber_general', // ID
                '', // Title
                [$this, 'general_setting'], // Callback
                'UtmvGrabber_options' // Page
        );
		add_settings_field(
                'ad_zapier_webhook_url', __('Webhook URL', 'utmvgrabber'), [$this, 'adZapierWebhookURL'], 'UtmvGrabber_options', 'UtmvGrabber_general'
        );		
		/* add_settings_field(
                'licence_key_api', __('Authorization Key', 'utmvgrabber'), [$this, 'licence_key_api'], 'UtmvGrabber_options', 'UtmvGrabber_general'
        );  */        
			
		/* add a filters for ninja form */
		add_filter( 'ad_utmgrabber_wpforms', [$this, 'ad_utmgrabber_wpforms_func'] );	
		
		/* add a filters for gravity form */
		add_filter( 'ad_utmgrabber_gravity', [$this, 'ad_utmgrabber_gravity_func']);
		
		/* add a filters for zapier form */
		add_filter( 'ad_utmgrabber_zapier', [$this, 'ad_utmgrabber_zapier_func'] );
		
		/* add a filters for ninja form */
		add_filter( 'ad_utmgrabber_ninja', [$this, 'ad_utmgrabber_ninja_func'] );	
		
		/* add a filters for premium plugin heading */
		add_filter( 'ad_utmgrabber_main_heading', [$this, 'ad_utmgrabber_main_heading_func']);
		
		/* remove active button & enable others form tabs */
		add_filter( 'ad_utmgrabber_tabs', [$this, 'ad_utmgrabber_tabs_func']);
		
    }
	/*
 	 * define functions all forms 
	 */
	public function ad_utmgrabber_wpforms_func() {
        print UtmvGrabberPro()->engine->getView('_wp_forms', []); 
    }
	public function ad_utmgrabber_gravity_func() {
        print UtmvGrabberPro()->engine->getView('_gravity_form', []); 
    }
	public function ad_utmgrabber_zapier_func() {
        print UtmvGrabberPro()->engine->getView('_zapier', []); 
    }
	public function ad_utmgrabber_ninja_func() {
        print UtmvGrabberPro()->engine->getView('_ninja', []); 
    }
	/*  end */
	
	/* 
	 * define plugin main content heading 
	 */
	public function ad_utmgrabber_main_heading_func()
	{
		print _e("AdTrails UTM Grabber - Premium");
	}
	
	/* 
	 * override tabs of paid plugin 
	 */
	public function ad_utmgrabber_tabs_func()
	{
		print UtmvGrabberPro()->engine->getView('_tabs_menu', []); 
	}
    public function adZapierWebhookURL() {
        ?>
        <input type='text' style="width:100%; min-width:300px;" id="ad_zapier_webhook_url" name='UtmvGrabber_options[ad_zapier_webhook_url]' value='<?php $this->displayValue('ad_zapier_webhook_url'); ?>' >		
        <?php
    }    
	public function licence_key_api() {
        ?>	<label>Licence Key : </label>	
        <input type='text' style="width:70%; min-width:300px;" id="ad_api_key" name='utmv_grabber_paid_key' value='<?php print get_option('utmv_grabber_paid_key'); ?>' required>
		<?php //wp_nonce_field( 'edd_sample_nonce', 'edd_sample_nonce' ); ?>
		<input type='button' id="ad_licence_key" name='authorized_api_key' onclick="UtmvGrabberProAdmin.authorizedKey();" class="button button-primary" value='Authorized'>
		<h3 class="keyAuthorizeMessage"></h3>
        <?php
    }
    
    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input) {

        return $input;
    }

    /**
     * display value from array
     * @param String $key
     * @param bolean $return
     *
     * @return String value from options
     */
    public function displayValue($key, $return = false) {	
        if (array_key_exists($key, $this->options)) {
            if ($return) {
                return $this->options[$key];
            }
            print $this->options[$key];
        }
    }
    /**
     * Print the UtmvGrabber api information
     */
    public function general_setting() {
        print sprintf('');
    }   
	
	/**
	 * edd_sample_activate_license
	 *
	 */
	public function edd_adtrails_activate_license() {
		
		$license = "287b8baa86d14db454ae17802eeb58348";
		
		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'    => UTMV_SL_ITEM_ID, // The ID of the item in EDD
			'url'        => home_url()
		);
		
		// Call the custom API.
		$response = wp_remote_post( UTMV_SIMPLE_ADDON_EDD_SL_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
		
		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			$message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {

				switch( $license_data->error ) {

					case 'expired' :

						$message = sprintf(
							__( 'Your license key expired on %s.' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'revoked' :

						$message = __( 'Your license key has been disabled.' );
						break;

					case 'missing' :

						$message = __( 'Invalid license.' );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$message = __( 'Your license is not active for this URL.' );
						break;

					case 'item_name_mismatch' :

						$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), UTMV_EDD_SAMPLE_ITEM_NAME );
						break;

					case 'no_activations_left':

						$message = __( 'Your license key has reached its activation limit.' );
						break;

					default :

						$message = __( 'An error occurred, please try again.' );
						break;
				}

			}
		}
		echo "<pre>";
		print_r($license_data);
		echo "</pre>";
		die;		
	}
	
}
return new UtmvGrabberProOptions();
?>