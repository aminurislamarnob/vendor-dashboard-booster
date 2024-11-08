<?php
/*
Plugin Name: Customer Management for Dokan
Plugin URI:  https://wordpress.org/plugins/dokan-customers/
Description: Enable customer management feature on the Dokan vendor dashboard.
Version: 1.3.0
Author: Aminur Islam
Author URI: https://github.com/aminurislamarnob
Text Domain: dokan-customers
WC requires at least: 5.0.0
Domain Path: /languages
License: GPLv2 or later
*/
use PluginizeLab\DokanCustomers\DokanCustomers;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'DOKAN_CUSTOMERS_FILE' ) ) {
    define( 'DOKAN_CUSTOMERS_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load pluginizelab_dokan_customers Plugin when all plugins loaded
 *
 * @return \PluginizeLab\DokanCustomers\DokanCustomers;
 */
function pluginizelab_dokan_customers() {
    return DokanCustomers::init();
}

// Lets Go....
pluginizelab_dokan_customers();
