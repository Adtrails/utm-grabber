<?php

/*
 * UtmvGrabber Shortcodes
 * @package UtmvGrabber
 * @since   1.0.0
 */

class UtmvGrabberProShortcodes {
    function __construct() {
        /**
         * Shortcode to display the list of utm variables in front end through shortcodes
         * [utm_varibles]
         */		
		$utmFields = UTMV_GRABBER_UTM_FIELDS;
		$this->utmWpFormShortcodes($utmFields);		
    }

    /**
     * display utm variables from a provider URLs
     *
     * @return HTML
     */
    private function utmWpFormShortcodes($utmFields) {
		$cfield = '';
        foreach ($utmFields as $id=>$utmfield){
			if (isset($_GET[$utmfield]) && $_GET[$utmfield] != '')
				$cfield = htmlspecialchars($_GET[$utmfield],ENT_QUOTES, 'UTF-8');
			elseif(isset($_COOKIE[$utmfield]) && $_COOKIE[$utmfield] != ''){ 
				$cfield = $_COOKIE[$utmfield];
			}else{
				$cfield = '';
			}		
			$_COOKIE[$utmfield] = $cfield;
			
			/* add shortcodes for ninja forms */
			add_shortcode('nj_'.$utmfield, function() use ($utmfield) {return urldecode($_COOKIE[$utmfield]);});	
			
			/* display utm filed value in gravity forms mail */
			add_filter( 'gform_field_value_'.$utmfield, function() use ($utmfield) {return urldecode($_COOKIE[$utmfield]); });			
		}
    }
}
return new UtmvGrabberProShortcodes();
?>