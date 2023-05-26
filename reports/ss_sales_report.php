<?php
require 'ss_sales_report_details.php';
$monday = strtotime("last monday");
$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
$this_week_sd = date("Y-m-d",$monday);
$this_week_ed = date("Y-m-d",$sunday);
$first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
$last_day_this_month  = date('Y-m-t');
?>
<div class="container-fluid ss-sales-report">
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 heading">
        <h3>Dashboard - Sales & Customer Summary</h3>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <h6 class="mb-0 text-secondary">Total Sales</h6>
                            <h5 class="my-1 text-info"><?php echo wc_price($reportClass->sh_sales_total_report_());?>
                            </h5>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i
                                class="fa fa-money" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <h6 class="mb-0 text-secondary">This Week Sales</h6>
                            <h5 class="my-1 text-danger">
                                <?php echo wc_price($reportClass->sh_sales_total_report_('WEEK'));?></h5>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i
                                class="fa fa-money" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <h6 class="mb-0 text-secondary">This Month Sales</h6>
                            <h5 class="my-1 text-success">
                                <?php echo wc_price($reportClass->sh_sales_total_report_('MONTH'));?></h5>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i
                                class="fa fa-money" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0 text-secondary">This Year Sales</h6>
                            <h5 class="my-1 text-warning">
                                <?php echo wc_price($reportClass->sh_sales_total_report_('YEAR'));?></h5>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto"><i
                                class="fa fa-money" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <h6 class="mb-0 text-secondary">Total Customer</h6>
                            <h5 class="my-1 text-info"><?php echo ($reportClass->get_customers());?></h5>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i
                                class="fa fa-users" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <h6 class="mb-0 text-secondary">Total Guest Customer</h6>
                            <h5 class="my-1 text-danger"><?php echo ($reportClass->get_customers('GUEST'));?></h5>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i
                                class="fa fa-users" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <h6 class="mb-0 text-secondary">This Week Customers</h6>
                            <h5 class="my-1 text-success">
                                <?php echo ($reportClass->get_customers('ALL',$this_week_sd,$this_week_ed ));?></h5>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i
                                class="fa fa-users" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 heading">
        <h3>Recent Orders</h3>
    </div>
    <div class="row w-auto border-start border-0 border-3 border-success">
        <div class="col">
                <table class="table table-striped table-hover" style="width: 1280px;">
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>First Name</th>
                        <th>Billing Email</th>
                        <th>Country</th>
                        <th>Order Status</th>
                        <th>Currency</th>
                        <th>Order Total</th>
                    </tr>
                    <?php
                $orders=$reportClass->sh_sales_recent_orders_();
                if ( $orders->have_posts() ) {
                    while ( $orders->have_posts() ) {
                        $orders->the_post();
                        $order = wc_get_order( $orders->post->ID );              
                    $date=$order->get_date_created();               
                        ?>
                    <tr>
                        <td><?php echo $order->get_id() ;?></td>
                        <td><?php echo  date_format($date,"Y/m/d ");?></td>
                        <td><?php echo $order->get_billing_first_name() ;?></td>
                        <td><?php echo $order->get_billing_email() ;?></td>
                        <td><?php echo WC()->countries->countries[ $order->get_billing_country()] ;?></td>
                        <td><?php echo ucfirst($order->get_status());?></td>
                        <td><?php echo $order->get_currency() ;?></td>
                        <td><?php echo $order->get_total() ;?></td>
                    </tr>
                    <?php
                    }
                    wp_reset_postdata();
                        } else {
                            echo '<tr><td colspan="8">No orders found.</td></tr>';
                        }
                    ?>
                </table>
            </div>
    </div>
</div>