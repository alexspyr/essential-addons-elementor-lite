<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('EA_Elements') ) {

    class EA_Elements {

        /**
         * The single instance of the class.
         *
         * @var self
         * @since 1.0.0
         */
        private static $_instance = null;

        /**
         * Allows for accessing single instance of class. Class should only be constructed once per call.
         *
         * @since 1.0.0
         * @static
         * @return self Main instance.
         */
        public static function get_instance() {
            if ( ! self::$_instance ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }


        public function __construct() {
            add_action( 'elementor/widgets/widgets_registered', array($this, 'add_eael_elements') );
        }

        public function elements_path( $file ) {
            $file = ltrim( $file, '/' );
            
            return ESSENTIAL_ADDONS_EL_PATH . 'elements/' . $file;
        }

        /**
         * Acivate or Deactivate Modules
         *
         * @since v1.0.0
         */
        public function add_eael_elements() {

            $is_component_active = Essential_Addons::eael_activated_modules();

            $elements = [
                [
                    'name'   => 'post-grid',
                    'source' => $this->elements_path('post-grid/post-grid.php')
                ],
                [
                    'name'   => 'post-timeline',
                    'source' => $this->elements_path('post-timeline/post-timeline.php')
                ],
                [
                    'name'   => 'fancy-text',
                    'source' => $this->elements_path('fancy-text/fancy-text.php'),
                ],
                [
                    'name'   => 'creative-btn',
                    'source' => $this->elements_path('creative-button/creative-button.php'),
                ],
                [
                    'name'   => 'count-down',
                    'source' => $this->elements_path('countdown/countdown.php'),
                ],
                [
                    'name'   => 'team-members',
                    'source' => $this->elements_path('team-members/team-members.php'),
                ],
                [
                    'name'   => 'testimonials',
                    'source' => $this->elements_path('testimonials/testimonials.php'),
                ],
                [
                    'name'      => 'product-grid',
                    'source'    => $this->elements_path('product-grid/product-grid.php'),
                    'condition' => [ 'function_exists', 'WC' ]
                ],
                [
                    'name'      => 'contact-form-7',
                    'source'    => $this->elements_path('contact-form-7/contact-form-7.php'),
                    'condition' => [ 'function_exists', 'wpcf7' ]
                ],
                [
                    'name'      => 'weforms',
                    'source'    => $this->elements_path('weforms/weforms.php'),
                    'condition' => [ 'function_exists', 'WeForms' ]
                ],
                [
                    'name'   => 'info-box',
                    'source' => $this->elements_path('infobox/infobox.php')
                ],
                [
                    'name'   => 'call-to-action',
                    'source' => $this->elements_path('call-to-action/call-to-action.php')
                ],
                [
                    'name'   => 'dual-header',
                    'source' => $this->elements_path('dual-color-header/dual-color-header.php')
                ],
                [
                    'name'   => 'price-table',
                    'source' => $this->elements_path('pricing-table/pricing-table.php')
                ],
                [
                    'name'      => 'ninja-form',
                    'source'    => $this->elements_path('ninja-form/ninja-form.php'),
                    'condition' => [ 'function_exists', 'Ninja_Forms' ]
                ],
                [
                    'name'      => 'gravity-form',
                    'source'    => $this->elements_path('gravity-form/gravity-form.php'),
                    'condition' => [ 'class_exists', 'GFForms' ]
                ],
                [
                    'name'      => 'caldera-form',
                    'source'    => $this->elements_path('caldera-forms/caldera-forms.php'),
                    'condition' => [ 'class_exists', 'Caldera_Forms' ]
                ],
                [
                    'name'      => 'wpforms',
                    'source'    => $this->elements_path('wpforms/wpforms.php'),
                    'condition' => [ 'class_exists', '\WPForms\WPForms' ]
                ],
                [
                    'name'   => 'twitter-feed',
                    'source' => $this->elements_path('twitter-feed/twitter-feed.php')
                ],
                [
                    'name'   => 'facebook-feed',
                    'source' => $this->elements_path('facebook-feed/facebook-feed.php')
                ],
                [
                    'name'   => 'data-table',
                    'source' => $this->elements_path('data-table/data-table.php')
                ],
                [
                    'name'   => 'filter-gallery',
                    'source' => $this->elements_path('filterable-gallery/filterable-gallery.php')
                ],
                [
                    'name'   => 'image-accordion',
                    'source' => $this->elements_path('image-accordion/image-accordion.php')
                ],
                [
                    'name'   => 'content-ticker',
                    'source' => $this->elements_path('content-ticker/content-ticker.php')
                ],
                [
                    'name'   => 'tooltip',
                    'source' => $this->elements_path('tooltip/tooltip.php')
                ],
                [
                    'name'   => 'adv-accordion',
                    'source' => $this->elements_path('advance-accordion/advance-accordion.php')
                ],
                [
                    'name'   => 'adv-tabs',
                    'source' => $this->elements_path('advance-tabs/advance-tabs.php')
                ],
                [
                    'name'   => 'progress-bar',
                    'source' => $this->elements_path('progress-bar/progress-bar.php')
                ]
            ];

            $elements = apply_filters( 'eael_elements', $elements );

            foreach( $elements as $element ) {
                
                if(isset($element['condition'])) {
                    if( $element['condition'][0]($element['condition'][1]) && $is_component_active[$element['name']] ) {
                        require_once $element['source'];
                    }
                }else {
                    if( $is_component_active[$element['name']] ) {
                        require_once $element['source'];
                    }
                }
            }

        }
        
    }

}