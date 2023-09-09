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

        // flash rewrite rules
        dokan()->rewrite->register_rule();
        flush_rewrite_rules();

        $this->get_customers();
    }

    /**
     * Get account formatted address.
     *
     * @param  string $address_type Address type.
     *                              Accepts: 'billing' or 'shipping'.
     *                              Default to 'billing'.
     * @param  int    $customer_id  Customer ID.
     *                              Default to 0.
     * @return string
     */
	public static function get_formatted_address( $address_type = 'billing', $customer_id = 0 ) {
        $getter  = "get_{$address_type}";
		$address = array();

		if ( 0 === $customer_id ) {
			$customer_id = get_current_user_id();
		}

		$customer = new \WC_Customer( $customer_id );

		if ( is_callable( array( $customer, $getter ) ) ) {
			$address = $customer->$getter();
			unset( $address['first_name'], $address['last_name'], $address['company'], $address['email'], $address['tel'] );
		}

		return WC()->countries->get_formatted_address( $address, ', ' );
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
        $urls['customers'] = array(
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
    public static function get_vendor_orders() {
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
    public static function get_vendor_customers() {
        global $wpdb;
        $comma_separated_order_ids = implode( ',', self::get_vendor_orders() );
		$customer_ids = $wpdb->get_col( "SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = '_customer_user' AND meta_value > 0 AND post_id IN ({$comma_separated_order_ids})" );
        return $customer_ids;
    }

    /**
     * Get total spend of a customer by customer & seller id
     *
     * @param int $customer_id
     * @param int $seller_id
     * @return string
     */
    public static function get_total_spend_by_seller_customer( $customer_id, $seller_id ) {
        $order_ids = dokan_get_customer_orders_by_seller( $customer_id, $seller_id );
        $total_spend = 0;
        if ( is_array( $order_ids ) && count( $order_ids ) > 0 ) {
			foreach ( $order_ids as $order_id ) {
				$order = wc_get_order( $order_id );
				$total_spend += $order->get_total();
			}
		}
        return wc_price( $total_spend );
    }
}
