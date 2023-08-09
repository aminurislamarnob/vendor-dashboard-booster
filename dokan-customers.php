<?php
/**
 * Plugin Name: Dokan Customers
 * Plugin URI:  https://wordpress.org/plugins/dokan-customers
 * Description: A dokan addon plugin which enable vendor wise customers features on each vendor panel.
 * Version: 0.0.1
 * Author: Aminur Islam Arnob
 * Author URI: https://wordpress.org/plugins/dokan-customers
 * Text Domain: dokan-customers
 * WC requires at least: 5.0.0
 * Domain Path: /languages/
 * License: GPLv2 or later
 */
use WeLabs\DokanCustomers\DokanCustomers;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'DOKAN_CUSTOMERS_FILE' ) ) {
    define( 'DOKAN_CUSTOMERS_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load Dokan_Customers Plugin when all plugins loaded
 *
 * @return \WeLabs\DokanCustomers\DokanCustomers;
 */
function welabs_dokan_customers() {
    return DokanCustomers::init();
}

// Lets Go....
welabs_dokan_customers();
