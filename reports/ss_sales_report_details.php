<?php
if( !class_exists( 'SHReports' ) ) {
	class SHReports
    {
        function sh_sales_total_report_($timeframe="ALL",$start_date=NULL,$end_date=NULL){
			global $wpdb;
			$today_date = date_i18n("Y-m-d");	
			$query = "SELECT
					SUM(order_total.meta_value)as 'total_sales'
					FROM {$wpdb->prefix}posts as posts			
					LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID 
					
					WHERE 1=1
					AND posts.post_type ='shop_order' 
					AND order_total.meta_key='_order_total' ";
					
			$query .= " AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed')
						
						";
			
			if ($timeframe =="WEEK"){
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND 
      WEEK(date_format( posts.post_date, '%Y-%m-%d')) = WEEK(CURRENT_DATE()) ";
			}
			if ($timeframe =="MONTH"){
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND 
      MONTH(date_format( posts.post_date, '%Y-%m-%d')) = MONTH(CURRENT_DATE()) ";
			}
			if ($timeframe =="YEAR"){
				$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
			}
			if ($timeframe =="YESTERDAY"){
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') = DATE_SUB('$today_date', INTERVAL 1 DAY) "; 
			}
			if ($timeframe =="DAY"){		
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') = '{$today_date}' "; 
				$query .= " GROUP BY  date_format( posts.post_date, '%Y-%m-%d') ";
			
			
			}
		
			$query .= " AND  posts.post_status NOT IN ('trash')";			
			
			$results = $wpdb->get_var($query);
			$results = isset($results) ? $results : "0";
			return $results;
		}


		function get_customers($customer_type='ALL',$start_date =NULL, $end_date=NULL){
			global $wpdb;	
			 $query = "";
			 $query .= " SELECT COUNT(customer_user.meta_value) as count ";
			 
			 $query .= "	 FROM {$wpdb->prefix}posts as posts		";
			 $query .= "	LEFT JOIN  {$wpdb->prefix}postmeta as customer_user ON customer_user.post_id=posts.ID ";
			 $query .= "	WHERE 1=1 ";
			 $query .= " AND posts.post_type ='shop_order'  ";
			 $query .= " AND customer_user.meta_key ='_customer_user' ";	
			 
			 if ($start_date && $end_date){
				$query .= " AND date_format( posts.post_date, '%Y-%m-%d') BETWEEN  '{$start_date}' AND '{$end_date}'";	 
			  }
			 
			  if($customer_type=='ALL')
			 	$query .= " AND customer_user.meta_value >0 ";
			  else
			  $query .= " AND customer_user.meta_value=0 ";

			$query .= " AND  posts.post_status NOT IN ('trash')";
			 
			 	
			$row = $wpdb->get_var($query);	
			return $row;
		}


		function sh_sales_recent_orders_($limit=10){
			global $wpdb;
			$args = array(
				'post_type'      => 'shop_order',
				'post_status'    => array( 'wc-pending','wc-processing','wc-on-hold', 'wc-completed'),
				'posts_per_page' => $limit,
				'orderby'        => 'date',
				'order'          => 'DESC'
			);
			
			$orders = new WP_Query( $args );
			
			
			return $orders;
		}


    }
}

$reportClass=new SHReports();

?>