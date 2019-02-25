<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('EA_Extensions') ) {
    class EA_Extensions {

    /**
      * Instance of this class
      * 
      * @access protected
      */
      protected static $_instance = null;

      /**
       * Get instinstancance of this class
       * 
       * @return Essential_Addons
       */
        public static function get_instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        public function __construct() {
            $this->add_eael_extensions();
        }


        /**
         * Load acivate or deactivate Modules
         *
         * @since v1.0.0
         */
        function add_eael_extensions() {
            $is_component_active = Essential_Addons::eael_activated_modules();

            if( $is_component_active['section-particles'] ) {
                require_once ESSENTIAL_ADDONS_EL_PATH .'extensions/eael-particle-section/eael-particle-section.php';
            }
        }

    }
}
