<?php
/*
 * UtmvGrabberPro Core funtions
 * @package UtmvGrabber
 * @since   1.0.0
 */

class UtmvGrabberCorePro {


    /**
     * class constructor
     * @global type $UtmvGrabberSetting
     */
    function __construct() {

    }

    /**
     * get the content from view file
     * @param String $viewname view file name
     * @param Array $data Data to send into view file
     * @throws ApiException on a non 2xx response
     * @return HTML
     */
    public function getView($viewname, array $data = []) {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $$key = $value;
            }
        }
        /* default variables in view */
        global $UtmvGrabberProSetting;

        ob_start();
        $viewpath = UTMV_GRABBER_PRO_ABSPATH . "views/{$viewname}.php";
        if (!file_exists($viewpath)) {
            $viewpath = UTMV_GRABBER_ABSPATH . "views/{$viewname}.php";
        }
        require($viewpath);
        $html = ob_get_clean();
        return $html;
    }

    /**
     * get value from array/object if set
     *
     * @param String $key
     * @param Mixed $Data
     *
     * return Mixed
     */
    public function getValue($key, $Data, $print = true) {
        if (is_array($Data)) {
            if (array_key_exists($key, $Data)) {
                if ($print) {
                    echo $Data[$key];
                } else {
                    return $Data[$key];
                }
            }
        }

        if (isset($Data->$key)) {
            if ($print) {
                echo $Data->$key;
            } else {
                return $Data->$key;
            }
        }

        return false;
    }
}
?>