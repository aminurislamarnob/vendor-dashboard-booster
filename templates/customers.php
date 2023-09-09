<?php
/**
 *  Dokan Dashboard customers Template
 *
 *  Load customers related template
 *
 *  @package dokan
 */
use WeLabs\DokanCustomers\ManageCustomers;
?>

<?php do_action( 'dokan_dashboard_wrap_start' ); ?>

<div class="dokan-dashboard-wrap">

    <?php

        /**
         *  dokan_dashboard_content_before hook
         *
         *  @hooked get_dashboard_side_navigation
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_before' );
        do_action( 'dokan_customers_content_before' );

    ?>

    <div class="dokan-dashboard-content dokan-customers-content">

        <header class="dokan-dashboard-header">
            <span class="left-header-content">
                <h1 class="entry-title">
                    <?php esc_html_e( 'Customers', 'dokan-customers' ); ?>
                </h1>
            </span>
            <div class="dokan-clearfix"></div>
        </header><!-- .entry-header -->

        <?php

            /**
             *  dokan_customers_content_inside_before hook
             *
             *  @hooked show_seller_enable_message
             *
             *  @since 2.4
             */
            do_action( 'dokan_customers_content_inside_before' );
        ?>


        <article class="dokan-customers-area">

            <?php
                $vendor_customers = ManageCustomers::get_vendor_customers();


                $seller_id    = get_current_user_id();
                $paged        = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
                $limit        = 10;
                $offset       = ( $paged - 1 ) * $limit;
                $customers = array_slice( $vendor_customers, $offset, $limit );

			if ( count( $customers ) > 0 ) {
				?>
                    <table class="dokan-table dokan-table-striped vendor-customer-table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e( 'Name', 'dokan-customers' ); ?></th>
                                <th><?php esc_html_e( 'Email', 'dokan-customers' ); ?></th>
                                <th><?php esc_html_e( 'Phone', 'dokan-customers' ); ?></th>
                                <th><?php esc_html_e( 'Orders', 'dokan-customers' ); ?></th>
                                <th><?php esc_html_e( 'Total Spend', 'dokan-customers' ); ?></th>
                                <th><?php esc_html_e( 'Registered At', 'dokan-customers' ); ?></th>
                                <th><?php esc_html_e( 'Actions', 'dokan-customers' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
					<?php
					foreach ( $customers as $customer ) {
						$customer_info = get_userdata( $customer );
                        $customer_full_name = $customer_info->first_name . ' ' . $customer_info->last_name;
						?>
                                <tr>
                                    <td><?php echo esc_html( $customer_full_name ); ?></td>
                                    <td><?php echo esc_html( $customer_info->user_email ); ?></td>
                                    <td><?php echo esc_html( get_user_meta( $customer_info->ID, 'billing_phone', true ) ); ?></td>
                                    <td><?php echo esc_html( count( dokan_get_customer_orders_by_seller( $customer_info->ID, get_current_user_id() ) ) ); ?></td>
                                    <td><?php echo ManageCustomers::get_total_spend_by_seller_customer( $customer_info->ID, get_current_user_id() ); ?></td>
                                    <td><?php echo esc_html( dokan_format_datetime( $customer_info->user_registered ) ); ?></td>
                                    <td><?php printf( '<a class="dokan-btn dokan-btn-default dokan-btn-sm tips" href="%s" data-toggle="tooltip" data-placement="top" title="%s">%s</a> ', esc_url( dokan_get_navigation_url( 'orders' ) . '?customer_id=' . $customer_info->ID . '&seller_order_filter_nonce=' . wp_create_nonce( 'seller-order-filter-nonce' ) ), esc_attr( 'View Orders' ), '<i class="far fa-eye"></i>' ); ?></td>
                                </tr>
                            <?php } ?>

                        </tbody>

                    </table>

						<?php
						$user_count = count( $vendor_customers );
						$num_of_pages = ceil( $user_count / $limit );

						$base_url = dokan_get_navigation_url( 'customers' );

						if ( $num_of_pages > 1 ) {
							echo '<div class="pagination-wrap">';
							$page_links = paginate_links(
                                array(
									'current'   => $paged,
									'total'     => $num_of_pages,
									'base'      => $base_url . '%_%',
									'format'    => '?pagenum=%#%',
									'add_args'  => false,
									'type'      => 'array',
                                )
                            );

							echo "<ul class='pagination'>\n\t<li>";
							echo join( "</li>\n\t<li>", $page_links );
							echo "</li>\n</ul>\n";
							echo '</div>';
						}
						?>

					<?php } else { ?>

                    <div class="dokan-error">
                        <?php esc_html_e( 'No customer found', 'dokan-customers' ); ?>
                    </div>

                <?php } ?>

        </article>

        <?php

            /**
             *  dokan_customers_content_inside_after hook
             *
             *  @since 2.4
             */
            do_action( 'dokan_customers_content_inside_after' );
        ?>

    </div> <!-- #primary .content-area -->

    <?php

        /**
         *  dokan_dashboard_content_after hook
         *  dokan_customers_content_after hook
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_after' );
        do_action( 'dokan_customers_content_after' );

    ?>

</div><!-- .dokan-dashboard-wrap -->

<?php do_action( 'dokan_dashboard_wrap_end' ); ?>