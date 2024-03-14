<?php
/*
Plugin Name: Vendor Dashboard Booster For Dokan
Plugin URI:  https://wordpress.org/plugins/vendor-dashboard-booster/
Description: A plugin which enhance vendor dashboard feature for Dokan multivendor plugin.
Version: 1.0.0
Author: Aminur Islam
Author URI: https://github.com/aminurislamarnob
Text Domain: vendor-dashboard-booster
WC requires at least: 5.0.0
Domain Path: /languages
License: GPLv2 or later
 */
use WeLabs\VendorDashboardBooster\VendorDashboardBooster;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'VENDOR_DASHBOARD_BOOSTER_FILE' ) ) {
    define( 'VENDOR_DASHBOARD_BOOSTER_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load VENDOR_DASHBOARD_BOOSTER Plugin when all plugins loaded
 *
 * @return \WeLabs\VendorDashboardBooster\VendorDashboardBooster;
 */
function welabs_vendor_dashboard_booster() {
    return VendorDashboardBooster::init();
}

// Lets Go....
welabs_vendor_dashboard_booster();
