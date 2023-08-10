<?php

namespace WeLabs\DokanCustomers;

class ManageCustomers {
    /**
     * The constructor.
     */
    public function __construct() {
        add_filter( 'dokan_query_var_filter', [ $this, 'dokan_load_document_menu' ] );
        add_filter( 'dokan_get_dashboard_nav', [ $this, 'dokan_add_customers_menu' ] );
        add_action( 'dokan_load_custom_template', [ $this, 'dokan_load_customers_template' ] );
        // add_action( 'init', [ $this, 'get_vendor_customers' ] );

        // flash rewrite rules
        dokan()->rewrite->register_rule();
        flush_rewrite_rules();
    }

    /**
     * Load dokan customer document menu
     *
     * @param array $query_vars
     * @return array
     */
    public function dokan_load_document_menu( $query_vars ) {
        $query_vars['customers'] = 'customers';
        return $query_vars;
    }

    /**
     * Add customers menu on dokan vendor dashboard
     *
     * @param array $urls
     * @return array
     */
    public function dokan_add_customers_menu( $urls ) {
        $urls['help'] = array(
            'title' => __( 'Customers', 'dokan-customers' ),
            'icon'  => '<i class="fas fa-users"></i>',
            'url'   => dokan_get_navigation_url( 'customers' ),
            'pos'   => 51,
        );
        return $urls;
    }

    /**
     * Load customers template for dokan dashboard
     *
     * @param array $query_vars
     * @return void
     */
    public function dokan_load_customers_template( $query_vars ) {
        if ( isset( $query_vars['customers'] ) ) {
            require_once DOKAN_CUSTOMERS_TEMPLATE_DIR . '/customers.php';
		}
    }

    /**
     * Get collections of current vendor order ids
     *
     * @return array
     */
    public function get_vendor_orders() {
        $query_args = [
            'seller_id' => dokan_get_current_user_id(),
            'limit'     => 10000000,
            'return'    => 'ids',
        ];
        return dokan()->order->all( $query_args );
    }

    /**
     * Get collection of customer ids by vendor orders
     *
     * @return array
     */
    public function get_vendor_customers() {
        global $wpdb;
        $comma_separated_order_ids = implode( ',', $this->get_vendor_orders() );
		$customer_ids = $wpdb->get_col( "SELECT DISTINCT meta_value  FROM $wpdb->postmeta WHERE meta_key = '_customer_user' AND meta_value > 0 AND post_id IN ({$comma_separated_order_ids})" );
        return $customer_ids;
    }
}
