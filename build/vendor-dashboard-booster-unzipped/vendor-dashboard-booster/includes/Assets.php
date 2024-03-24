<?php

namespace WpEnhancers\VendorDashboardBooster;

class Assets {
    /**
     * The constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_all_scripts' ], 10 );

        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ], 10 );
        } else {
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_front_scripts' ] );
        }
    }

    /**
     * Register all plugin scripts and styles.
     *
     * @return void
     */
    public function register_all_scripts() {
        $this->register_styles();
        $this->register_scripts();
    }

    /**
     * Register scripts.
     *
     * @param array $scripts
     *
     * @return void
     */
    public function register_scripts() {
        $admin_script       = VENDOR_DASHBOARD_BOOSTER_PLUGIN_ASSET . '/admin/script.js';
        $frontend_script    = VENDOR_DASHBOARD_BOOSTER_PLUGIN_ASSET . '/frontend/script.js';

        wp_register_script( 'VENDOR_DASHBOARD_BOOSTER_admin_script', $admin_script, [], VENDOR_DASHBOARD_BOOSTER_PLUGIN_VERSION, true );
        wp_register_script( 'VENDOR_DASHBOARD_BOOSTER_script', $frontend_script, [], VENDOR_DASHBOARD_BOOSTER_PLUGIN_VERSION, true );
    }

    /**
     * Register styles.
     *
     * @return void
     */
    public function register_styles() {
        $admin_style       = VENDOR_DASHBOARD_BOOSTER_PLUGIN_ASSET . '/admin/style.css';
        $frontend_style    = VENDOR_DASHBOARD_BOOSTER_PLUGIN_ASSET . '/frontend/style.css';

        wp_register_style( 'vendor_dashboard_booster_admin_style', $admin_style, [], VENDOR_DASHBOARD_BOOSTER_PLUGIN_VERSION );
        wp_register_style( 'vendor_dashboard_booster_style', $frontend_style, [], VENDOR_DASHBOARD_BOOSTER_PLUGIN_VERSION );
    }

    /**
     * Enqueue admin scripts.
     *
     * @return void
     */
    public function enqueue_admin_scripts() {
        wp_enqueue_script( 'vendor_dashboard_booster_admin_script' );
        wp_localize_script(
            'vendor_dashboard_booster_admin_script', 'Vendor_Dashboard_Booster_Admin', []
        );
    }

    /**
     * Enqueue front-end scripts.
     *
     * @return void
     */
    public function enqueue_front_scripts() {
        wp_enqueue_style( 'vendor_dashboard_booster_style' );
        // wp_enqueue_script( 'VENDOR_DASHBOARD_BOOSTER_script' );
        // wp_localize_script(
        //     'VENDOR_DASHBOARD_BOOSTER_script', 'VENDOR_DASHBOARD_BOOSTER', []
        // );
    }
}
