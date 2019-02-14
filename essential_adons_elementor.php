<?php
/**
 * Plugin Name: Essential Addons for Elementor
 * Description: The ultimate elements library for Elementor page builder plugin for WordPress.
 * Plugin URI: https://essential-addons.com/elementor/
 * Author: WPDeveloper
 * Version: 2.9.3
 * Author URI: https://wpdeveloper.net/
 *
 * Text Domain: essential-addons-elementor
 * Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('Essential_Addons') ) {

   final class Essential_Addons {

      /**
      * Instance of this class
      * 
      * @access protected
      */
      protected static $_instance = null;

   
      /**
       * Get instance of this class
       * 
       * @return Essential_Addons
       */
      public static function get_instance() {
         if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
         }

         return self::$_instance;
      }

      /**
       * Extensions
       */
      public $ea_extensions;

      /**
       * Elements
       */
      public $ea_elements;
      

      /**
       * Constract of this class
       */
      public function __construct() {
         $this->define_constants();
         $this->includes();
         $this->instantiate();
         $this->essential_addons_elementor_lite_start_plugin_tracking();

         add_action('admin_init', array($this, 'eael_redirect'));
         add_action( 'elementor/controls/controls_registered', array($this, 'eae_posts_register_control'));

         $plugin = plugin_basename( __FILE__ );
         add_filter( "plugin_action_links_$plugin", array($this, 'eael_add_settings_link'));

         if ( class_exists( 'Caldera_Forms' ) ) {
            add_filter( 'caldera_forms_force_enqueue_styles_early', '__return_true' );
         }
      
         if( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', array($this, 'eael_is_failed_to_load') );
         }
      }

      public function define_constants() {
         $this->define( 'ESSENTIAL_ADDONS_EL_URL', plugins_url( '/', __FILE__ ) );
         $this->define( 'ESSENTIAL_ADDONS_EL_PATH', plugin_dir_path( __FILE__ ) );
         $this->define( 'ESSENTIAL_ADDONS_EL_ROOT', __FILE__ );
         $this->define( 'ESSENTIAL_ADDONS_VERSION', '2.9.3' );
         $this->define( 'ESSENTIAL_ADDONS_STABLE_VERSION', '2.9.3' );
         $this->define( 'ESSENTIAL_ADDONS_BASENAME', plugin_basename( __FILE__ ) );
         $this->define( 'ESSENTIAL_ADDONS_INCLUDE_PATH', trailingslashit( plugin_dir_path( __FILE__ ) . 'includes' ) );
      }

      public function include_path( $file ) {
         $file = ltrim( $file, '/' );
         
         return ESSENTIAL_ADDONS_INCLUDE_PATH . $file;
      }

      public function define( $name, $value, $case_insensitive = false ) {
         if ( ! defined( $name ) ) {
            define( $name, $value, $case_insensitive );
         }
      }

      /**
       * This function will return true for all activated modules
      *
      * @since   v2.4.1
      */
      public static function eael_activated_modules() {

         $eael_default_keys = [
            'contact-form-7',
            'count-down',
            'creative-btn',
            'fancy-text',
            'img-comparison',
            'instagram-gallery',
            'interactive-promo',
            'lightbox',
            'post-block',
            'post-grid',
            'post-timeline',
            'product-grid',
            'team-members',
            'testimonial-slider',
            'testimonials',
            'testimonials',
            'weforms',
            'static-product',
            'call-to-action',
            'flip-box',
            'info-box',
            'dual-header',
            'price-table',
            'flip-carousel',
            'interactive-cards',
            'ninja-form',
            'gravity-form',
            'caldera-form',
            'wisdom_registered_setting',
            'twitter-feed',
            'facebook-feed',
            'data-table',
            'filter-gallery',
            'image-accordion',
            'content-ticker',
            'tooltip',
            'adv-accordion',
            'adv-tabs',
            'progress-bar',
            'section-particles'
         ];
         
         $eael_default_settings = array_fill_keys( $eael_default_keys, true );
         $eael_get_settings     = get_option( 'eael_save_settings', $eael_default_settings );
         $eael_new_settings     = array_diff_key( $eael_default_settings, $eael_get_settings );

         if( ! empty( $eael_new_settings ) ) {
            $eael_updated_settings = array_merge( $eael_get_settings, $eael_new_settings );
            update_option( 'eael_save_settings', $eael_updated_settings );
         }

         return $eael_get_settings = get_option( 'eael_save_settings', $eael_default_settings );
      }

      public function includes() {
         require_once $this->include_path('class-enqueue-scripts.php');
         require_once $this->include_path('elementor-helper.php');
         require_once $this->include_path('queries.php');
         require_once $this->include_path('class-plugin-usage-tracker.php');
         require_once $this->include_path('version-rollback.php');
         require_once $this->include_path('maintennance.php');
         require_once $this->include_path('eael-rollback.php');
         require_once ESSENTIAL_ADDONS_EL_PATH.'admin/settings.php';
         require_once $this->include_path('module-base.php');
         require_once $this->include_path('extensions.php');
         require_once $this->include_path('class-ea-elements.php');
         require_once $this->include_path('class-plugin-check.php');
         require_once dirname( __FILE__ ) . '/includes/class-wpdev-notices.php';
      }

      public function instantiate() {
         $this->ea_extensions = EA_Extensions::get_instance();
         $this->ea_elements   = EA_Elements::get_instance();
      }

      /**
       * Activation redirects
       *
       * @since v1.0.0
       */
      public static function eael_activate() {
         add_option('eael_do_activation_redirect', true);
      }

      /**
       * Redirect to options page
       *
       * @since v1.0.0
       */
      public function eael_redirect() {
         if (get_option('eael_do_activation_redirect', false)) {
            delete_option('eael_do_activation_redirect');
            if(!isset($_GET['activate-multi']))
            {
               wp_redirect("admin.php?page=eael-settings");
            }
         }
      }

      /**
       * Registering a Group Control for All Posts Element
       */
      public function eae_posts_register_control( $controls_manager ){
         include_once ESSENTIAL_ADDONS_EL_PATH . 'includes/eae-posts-group-control.php';
         $controls_manager->add_group_control( 'eaeposts', new Elementor\EAE_Posts_Group_Control() );
      }

      /**
       * Creates an Action Menu
       */
      public function eael_add_settings_link( $links ) {
         $settings_link = sprintf( '<a href="admin.php?page=eael-settings">' . __( 'Settings' ) . '</a>' );
         $go_pro_link = sprintf( '<a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank" style="color: #39b54a; font-weight: bold;">' . __( 'Go Pro' ) . '</a>' );
         array_push( $links, $settings_link, $go_pro_link );

         return $links;
      }

      /**
       * Optional usage tracker
       *
       * @since v1.0.0
       */
      public function essential_addons_elementor_lite_start_plugin_tracking() {
         if( ! class_exists( 'Eael_Plugin_Usage_Tracker') ) {
            require_once dirname( __FILE__ ) . '/includes/class-plugin-usage-tracker.php';
         }
   
           $wisdom = new Eael_Plugin_Usage_Tracker(
               __FILE__,
               'https://wpdeveloper.net',
               array(),
               true,
               true,
               1
           );
      }

      /**
       * Check if Elementor is Installed or not
      */
      public function eael_is_elementor_active() {
         if ( did_action( 'elementor/loaded' ) ) {
            return true;
         }

         return false;
      }

      /**
       * This notice will appear if Elementor is not installed or activated or both
       */
      public function eael_is_failed_to_load() {
         $elementor = 'elementor/elementor.php';

         if( $this->eael_is_elementor_active() ) {
            if( ! current_user_can( 'activate_plugins' ) ) {
               return;
            }
            $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor );
            $message = __( '<strong>Essential Addons for Elementor</strong> requires <strong>Elementor</strong> plugin to be active. Please activate Elementor to continue.', 'essential-addons-elementor' );
            $button_text = __( 'Activate Elementor', 'essential-addons-elementor' );
         } else {
            if( ! current_user_can( 'activate_plugins' ) ) {
               return;
            }
            $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
            $message = sprintf( __( '<strong>Essential Addons for Elementor</strong> requires <strong>Elementor</strong> plugin to be installed and activated. Please install Elementor to continue.', 'essential-addons-elementor' ), '<strong>', '</strong>' );
            $button_text = __( 'Install Elementor', 'essential-addons-elementor' );
         }
         $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
         printf( '<div class="error"><p>%1$s</p>%2$s</div>', __( $message ), $button );
      }

      
   }

}

function run_essential_addons() {
   return Essential_Addons::get_instance();
}
add_action( 'plugins_loaded', 'run_essential_addons', 25 );

register_activation_hook(__FILE__, 'Essential_Addons::eael_activate');


