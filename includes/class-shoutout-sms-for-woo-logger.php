<?php

/**
 * @class      ShoutOUT_SMS_For_Woo_Logger
 * @since      1.0.0
 * @package    ShoutOUT_Sms_For_Woo
 * @subpackage ShoutOUT_Sms_For_Woo/includes
 * @author     shoutoutlabs
 */
class ShoutOUT_SMS_For_Woo_Logger {

    private $_handles;

    /**
     * Constructor for the logger.    
     */
    public function __construct() {
        $this->_handles = array();
    }

    /**
     * Destructor.    
     */
    public function __destruct() {
        foreach ($this->_handles as $handle) {
            @fclose($handle);
        }
    }

    /**
     * Open log file for writing.
     *    
     */
    private function open($handle) {
        if (isset($this->_handles[$handle])) {
            return true;
        }

        if ($this->_handles[$handle] = @fopen($this->shoutout_sms_for_woo_get_log_file_path($handle), 'a')) {
            return true;
        }

        return false;
    }

    /**
     * Add a log entry to chosen file.    
     */
    public function add($handle, $message) {
        if ($this->open($handle) && is_resource($this->_handles[$handle])) {
            $time = date_i18n('m-d-Y @ H:i:s -'); // Grab Time
            @fwrite($this->_handles[$handle], $time . " " . $message . "\n");
        }
    }

    /**
     * Clear entries from chosen file.    
     */
    public function clear($handle) {
        if ($this->open($handle) && is_resource($this->_handles[$handle])) {
            @ftruncate($this->_handles[$handle], 0);
        }
    }

    /**
     * Get file path    
     */
    public function shoutout_sms_for_woo_get_log_file_path($handle) {
        return trailingslashit(SHOUTOUT_SMS_FOR_WOO_LOG_DIR) . $handle . '-' . sanitize_file_name(wp_hash($handle)) . '.log';
    }

}