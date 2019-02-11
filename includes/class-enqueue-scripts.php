<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Scripts and styles
 */
class EA_Scripts {

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
        add_action( 'wp_enqueue_scripts', array($this, 'essential_addons_el_enqueue') );
        add_action( 'elementor/editor/before_enqueue_scripts', array($this, 'ea_load_el_editor_scripts'));
    }

    
    /**
     * Load module's scripts and styles if any module is active.
     *
     * @since v1.0.0
     */
    public function essential_addons_el_enqueue(){
        $is_component_active = Essential_Addons::eael_activated_modules();

        wp_enqueue_style(
            'essential_addons_elementor-css',
            ESSENTIAL_ADDONS_EL_URL.'assets/css/essential-addons-elementor.css'
        );

        wp_enqueue_script(
            'eael-scripts',
            ESSENTIAL_ADDONS_EL_URL.'assets/js/eael-scripts.js',
            array('jquery'),'1.0', true
        );

        if ( class_exists( 'GFCommon' ) ) {
            foreach( eael_select_gravity_form() as $form_id => $form_name ){
                if ( $form_id != '0' ) {
                    gravity_form_enqueue_scripts( $form_id );
                }
            };
        }

        if ( function_exists( 'wpforms' ) ) {
            wpforms()->frontend->assets_css();
        }

        if( $is_component_active['fancy-text'] ) {
            wp_enqueue_script(
                'essential_addons_elementor-fancy-text-js',
                ESSENTIAL_ADDONS_EL_URL.'assets/js/fancy-text.js',
                array('jquery'),'1.0', true
            );
        }

        if( $is_component_active['count-down'] ) {
            wp_enqueue_script(
                'essential_addons_elementor-countdown-js',
                ESSENTIAL_ADDONS_EL_URL.'assets/js/countdown.min.js',
                array('jquery'),'1.0', true
            );
        }

        if(
            $is_component_active['post-grid']
            || $is_component_active['twitter-feed']
            || $is_component_active['facebook-feed']
        ) {
            wp_enqueue_script(
                'essential_addons_elementor-masonry-js',
                ESSENTIAL_ADDONS_EL_URL.'assets/js/masonry.min.js',
                array('jquery'),'1.0', true
            );
        }

        if(
            $is_component_active['post-grid']
            || $is_component_active['post-timeline']
        ) {
            wp_enqueue_script(
                'essential_addons_elementor-load-more-js',
                ESSENTIAL_ADDONS_EL_URL.'assets/js/load-more.js',
                array('jquery'),'1.0', true
            );

            $eael_js_settings = array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            );
            wp_localize_script(
                'essential_addons_elementor-load-more-js',
                'eaelPostGrid', $eael_js_settings
            );
        }

        if( $is_component_active['twitter-feed']) {
            wp_enqueue_script(
                'essential_addons_elementor-codebird-js',
                ESSENTIAL_ADDONS_EL_URL.'assets/social-feeds/codebird.js',
                array('jquery'),'1.0', true
            );
        }

        if(
            $is_component_active['twitter-feed']
            || $is_component_active['facebook-feed']
        ) {
            wp_enqueue_script(
                'essential_addons_elementor-doT-js',
                ESSENTIAL_ADDONS_EL_URL.'assets/social-feeds/doT.min.js',
                array('jquery'),'1.0', true
            );

            wp_enqueue_script(
                'essential_addons_elementor-moment-js',
                ESSENTIAL_ADDONS_EL_URL.'assets/social-feeds/moment.js',
                array('jquery'),'1.0', true
            );

            wp_enqueue_script(
                'essential_addons_elementor-socialfeed-js',
                ESSENTIAL_ADDONS_EL_URL.'assets/social-feeds/jquery.socialfeed.js',
                array('jquery'),'1.0', true
            );
        }

        if( $is_component_active['filter-gallery'] ) {
            wp_enqueue_script(
                'essential_addons_mixitup-js',
                ESSENTIAL_ADDONS_EL_URL.'assets/js/mixitup.min.js',
                array('jquery'),'1.0', true
            );

            wp_enqueue_script(
                'essential_addons_magnific-popup-js',
                ESSENTIAL_ADDONS_EL_URL.'assets/js/jquery.magnific-popup.min.js',
                array('jquery'),'1.0', true
            );

            wp_register_script(
                'essential_addons_isotope-js',
                ESSENTIAL_ADDONS_EL_URL.'assets/js/isotope.pkgd.min.js',
                array('jquery'),'1.0', true
            );

            wp_register_script(
                'jquery-resize',
                ESSENTIAL_ADDONS_EL_URL.'assets/js/jquery.resize.min.js',
                array('jquery'), '1.0', true
            );
        }

        if( $is_component_active['price-table'] ) {
            wp_enqueue_style(
                'essential_addons_elementor-tooltipster',
                ESSENTIAL_ADDONS_EL_URL.'assets/css/tooltipster.bundle.min.css'
            );
            wp_enqueue_script(
                'essential_addons_elementor-tooltipster-js',
                ESSENTIAL_ADDONS_EL_URL.'assets/js/tooltipster.bundle.min.js',
                array('jquery'),'1.0', true
            );
        }
        
        if( $is_component_active['progress-bar'] ) {
            wp_enqueue_script(
                'essential_addons_elementor-progress-bar',
                ESSENTIAL_ADDONS_EL_URL.'assets/js/loading-bar.min.js',
                array('jquery'),'1.0', true
            );
        }

        if( $is_component_active['section-particles'] ) {
            wp_enqueue_script(
                'particles-js',
                ESSENTIAL_ADDONS_EL_URL.'assets/js/particles.js',
                ['jquery'], '1.0', true
            );

            $preset_themes = require ESSENTIAL_ADDONS_EL_PATH.'extensions/eael-particle-section/particle-themes.php';
            wp_localize_script( 'particles-js', 'ParticleThemesData', $preset_themes );
        }

    }

    /**
     * Editor Css
     */
    public function ea_load_el_editor_scripts() {
        wp_register_style(
            'essential_addons_elementor_editor-css',
            ESSENTIAL_ADDONS_EL_URL.'assets/css/essential-addons-editor.css'
        );
        wp_enqueue_style('essential_addons_elementor_editor-css');
    }
}

EA_Scripts::get_instance();