<?php

namespace WpEnhancers\VendorDashboardBooster;

class ManageCustomers {
    /**
     * The constructor.
     */
    public function __construct() {
        add_filter( 'dokan_query_var_filter', [ $this, 'dokan_load_document_menu' ] );
        add_filter( 'dokan_get_dashboard_nav', [ $this, 'dokan_add_customers_menu' ] );
        add_action( 'dokan_load_custom_template', [ $this, 'dokan_load_customers_template' ] );
        $this->flush();
    }

    /**
     * Flush rewrite rules
     *
     * @return void
     */
    public function flush() {
        dokan()->rewrite->register_rule();
        flush_rewrite_rules();
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
            'title' => __( 'Customers', 'vendor-dashboard-boosters' ),
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
            require_once VENDOR_DASHBOARD_BOOSTER_TEMPLATE_DIR . '/customers.php';
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
            'limit'     => -1,
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
        $vendor_orders = self::get_vendor_orders();
        $customers = [];
        if ( ! empty( $vendor_orders ) ) {
            foreach ( $vendor_orders as $order_id ) {
                $order = wc_get_order( $order_id );
                $customers[] = $order->get_customer_id();
            }
        }
        $customers = array_unique( $customers );
        return $customers;
    }

    /**
     * Get total spend of a customer by customer & seller id
     *
     * @param int $customer_id
     * @param int $seller_id
     * @return string
     */
    public static function get_total_spend_by_seller_customer( $customer_id, $seller_id ) {
        $order_ids = dokan()->order->get_customer_order_ids_by_seller( $customer_id, $seller_id );
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
