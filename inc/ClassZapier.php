<?php
/*
 * UtmvGrabber Zapier
 * @package ad_utmv_grabber_pro/inc
 * @since   1.0.0
 */

class UtmvGrabberZapier {

    /**
     * UtmvGrabberZapier Constructor.
     */
    function __construct() {     	
		$options = get_option('UtmvGrabber_options');	
		$this->hooks_url = $options[ 'ad_zapier_webhook_url' ];

        add_action( 'wpcf7_submit', [$this, 'UtmvGrabber_contactForm_submit']); 
		add_action( 'gform_after_submission', [$this, 'UtmvGrabber_gravity_form_after_submission'], 10, 2 );
		add_action( 'ninja_forms_after_submission', [$this, 'UtmvGrabber_nj_forms_after_submission'] );
		add_action( 'wpforms_process', [$this, 'wpf_dev_process'], 10, 3 );
    }

    /*
     * Contact Form 7 Data Send to Zapier
     *
     */
    public function UtmvGrabber_contactForm_submit () {
		$submission   = WPCF7_Submission::get_instance();
		$cfdata = $submission->get_posted_data(); 					
		$this->post_form_data_to_zapier($this->hooks_url, $cfdata);
    }  
	
	/*
     * Gravity Form Data Send to Zapier
     *
     */
    public function UtmvGrabber_gravity_form_after_submission ( $entry, $form ) { 
		$gdata = array();
        foreach ( $form['fields'] as $field ) {
            $inputs = $field->get_entry_inputs();
            if ( is_array( $inputs ) ) {
                foreach ( $inputs as $input ) {
                    $value = rgar( $entry, (string) $input['id'] );
                    $label = isset($input['adminLabel']) && $input['adminLabel'] != '' ? $input['adminLabel'] : 'input_'.$input['id'];
                    $gdata[$label] = $value;
                }
            } else {
                $value = rgar( $entry, (string) $field->id );
                $label = isset($field->adminLabel) && $field->adminLabel != '' ? $field->adminLabel : 'input_'.$field->id;
                $gdata[$label] = $value;
            }
        }
        $this->post_form_data_to_zapier($this->hooks_url, $gdata);
    }  
	
	//Ninja Form data send to Zapier
	public function UtmvGrabber_nj_forms_after_submission( $form_data ){
		$njdata = array();
		foreach ($form_data['fields_by_key'] as $field){
			if (isset($field['key']))
				$njdata[$field['key']] = $field['value'];
		}
		$this->post_form_data_to_zapier($this->hooks_url, $njdata);
	}
	
	function wpf_dev_process( $fields, $entry, $form_data ) {
        $wfdata = array();
		foreach ($fields as $field){
            $wfdata[$field['name']] = $field['value'];
        }
		$this->post_form_data_to_zapier($this->hooks_url, $wfdata);
	}
	
	/* send data to zapier */ 	
	public function post_form_data_to_zapier($hooks_url, $form_data){
			$response = Requests::post( $hooks_url, array(), $form_data );			
			add_option( 'ad_zapier_webhook_log', $response, '', 'yes' ) or update_option('ad_zapier_webhook_log', $response); 
	}

}
return new UtmvGrabberZapier();
?>