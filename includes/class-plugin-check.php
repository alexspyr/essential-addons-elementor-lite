<?php

/**
 * 
 */
class EA_Plugin_Check {

    public $wpforms_available;

    public function __construct() {
        // add_action( 'plugins_loaded', [$this, 'check_wpforms_available']);
        // add_action( 'wp_loaded', [$this, 'check_wpforms_available']);
        add_action( 'init', [$this, 'check_wpforms_available']);
        // $this->check_wpforms_available();
    }


    public function check_wpforms_available() {
        // if( class_exists( '\WPForms\WPForms' ) || class_exists( 'WPForms' ) ) {
        if(true) {
            
        }else {
            $this->wpforms_available = false;
        }

        // var_dump('Hello World!');
    }

}

