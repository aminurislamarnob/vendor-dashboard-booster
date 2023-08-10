<?php
/**
 *  Dokan Dashboard customers Template
 *
 *  Load customers related template
 *
 *  @package dokan
 */
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
        do_action( 'dokan_staffs_content_before' );

    ?>

    <div class="dokan-dashboard-content dokan-staffs-content">

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
             *  dokan_staffs_content_inside_before hook
             *
             *  @hooked show_seller_enable_message
             *
             *  @since 2.4
             */
            do_action( 'dokan_staffs_content_inside_before' );
        ?>


        <article class="dokan-staffs-area">

            <?php

                $seller_id    = get_current_user_id();
                $paged        = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
                $limit        = 10;
                $offset       = ( $paged - 1 ) * $limit;
                $staffs       = dokan_get_all_vendor_staffs(
                    array(
						'number' => $limit,
						'offset' => $offset,
                    )
                );

				if ( count( $staffs['staffs'] ) > 0 ) {
					?>
                    <table class="dokan-table dokan-table-striped vendor-staff-table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e( 'Name', 'dokan-customers' ); ?></th>
                                <th><?php esc_html_e( 'Email', 'dokan-customers' ); ?></th>
                                <th><?php esc_html_e( 'Phone', 'dokan-customers' ); ?></th>
                                <th><?php esc_html_e( 'Orders', 'dokan-customers' ); ?></th>
                                <th><?php esc_html_e( 'Total Spend', 'dokan-customers' ); ?></th>
                                <th><?php esc_html_e( 'Address', 'dokan-customers' ); ?></th>
                                <th><?php esc_html_e( 'Registered At', 'dokan-customers' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
						<?php
						foreach ( $staffs['staffs'] as $staff ) {
							?>
                                <tr >
                                    <td>
									<?php
										$edit_url = add_query_arg(
                                            array(
												'view' => 'add_staffs',
												'action' => 'edit',
												'staff_id' => $staff->ID,
                                            ), dokan_get_navigation_url( 'staffs' )
                                        );
									?>

									<?php printf( '<a href="%s">%s</a>', esc_url( $edit_url ), $staff->display_name ); ?>
                                    </td>
                                    <td><?php echo $staff->user_email; ?></td>
                                    <td><?php echo get_user_meta( $staff->ID, '_staff_phone', true ); ?></td>
                                    <td><?php echo dokan_format_datetime( $staff->user_registered ); ?></td>
                                </tr>
                            <?php } ?>

                        </tbody>

                    </table>

						<?php
						$user_count = $staffs['total_users'];
						$num_of_pages = ceil( $user_count / $limit );

						$base_url = dokan_get_navigation_url( 'staffs' );

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

        <style>
            table.vendor-staff-table tbody td a,
            table.vendor-staff-table tbody td .row-actions a {
                color: #6d6d6d;
            }

            table.vendor-staff-table tbody td a:hover,
            table.vendor-staff-table tbody td .row-actions a:hover {
                color: #000;
            }

            table.vendor-staff-table tbody td .row-actions .delete a:hover {
                color: #ff0000;
            }

            table.vendor-staff-table tbody .row-actions {
                font-size: 12px;
            }
        </style>


        <?php

            /**
             *  dokan_staffs_content_inside_after hook
             *
             *  @since 2.4
             */
            do_action( 'dokan_staffs_content_inside_after' );
        ?>

    </div> <!-- #primary .content-area -->

    <?php

        /**
         *  dokan_dashboard_content_after hook
         *  dokan_staffs_content_after hook
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_after' );
        do_action( 'dokan_staffs_content_after' );

    ?>

</div><!-- .dokan-dashboard-wrap -->

<?php do_action( 'dokan_dashboard_wrap_end' ); ?>

<style>
.vendor-staff-table tbody .row-actions {
    visibility: hidden;
    font-size: 12px;
    color: #ccc;
}

.vendor-staff-table tbody .row-actions .delete a {
    color: #A05;
}

.vendor-staff-table tbody .row-actions .delete a:hover {
    color: red;
}

.vendor-staff-table tbody tr:hover .row-actions {
    visibility: visible;
}
</style>