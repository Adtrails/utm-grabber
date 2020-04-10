<?php
/*
 * UtmvGrabber Actions
 * @package utmv_grabber/inc
 * @since   1.0.0
 */

class UtmvGrabberActionsPro {

    /**
     * UtmvGrabber Constructor.
     */
    function __construct() {
        foreach ($this->AjaxActions() as $key => $action) {
            add_action("wp_ajax_{$action['name']}", [$this, $action['callback']]);
            add_action("wp_ajax_nopriv_{$action['name']}", [$this, $action['callback']]);
        }
        add_action('init', [$this, 'UtmvGrabberActionsProInit']);
    }

    /*
     * UtmvGrabber ajax handlers
     *
     * @return Array
     */

    private function AjaxActions () {
        return [
            ['name' => 'ad_authorized_key', 'callback' => 'keyAdEDDLicenceCheck'],
            ['name' => 'ad_licence_key_insert', 'callback' => 'licenceKeySave']
        ];
    }    

    /**
     * actions init method
     */
    public function UtmvGrabberActionsProInit () {
    }
	
	/**
	 * method for check authorized Licence key 
	 */
	public function keyAdEDDLicenceCheck()
	{
		// listen for our activate button to be clicked
		if( isset( $_POST['formdata'] ) ) {
		
			// run a quick security check
			//if( ! check_admin_referer( 'edd_sample_nonce', 'edd_sample_nonce' ) )
				//return; // get out if we didn't click the Activate button

		$license = trim($_POST['formdata']);
		
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
        wp_send_json($license_data);
        return;	
		}
	}
	
	/**
	 * Insert Licence Key in option table
	 *
	 */
	public function licenceKeySave()
	{
		global $wpdb;
		$prefix = $wpdb->prefix;	
		update_option('edd_utmv_grabber_license_status', $_POST['l_status']);
		update_option('utmv_grabber_paid_key', $_POST['licence_key']);
	}
	
}
return new UtmvGrabberActionsPro();
?>