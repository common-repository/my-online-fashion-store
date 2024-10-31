<?php
/**
 * My Order Page
 * @package My Online Fashion Store\My Order Page
 */
defined( 'ABSPATH' ) || exit;
/**
 * MYOFS_My_Order Class
 *
 * Provides a order list which is buy inventory products.
 */
class MYOFS_My_Order  extends MYOFS_API{
	 
	public function getMyOrderSearchData(){
		global $wpdb;
		$posts          = $wpdb->prefix . "posts";
        $posts_meta     = $wpdb->prefix . "postmeta";
        $users          = $wpdb->prefix . "users";
        $order_item     = $wpdb->prefix . "woocommerce_order_items";
        $order_itemmeta = $wpdb->prefix . "woocommerce_order_itemmeta";
        $inventorypro   = $wpdb->prefix . MYOFS_DB_TABLE;
		$result  = MYOFS_API::GetMyInventoryIds(); 
		if(array_key_exists(200,$result['status_code'])){
			$wooData = $result['data']['data'];
			$create_table = "CREATE TABLE IF NOT EXISTS `".$inventorypro."` (
				`id` INT( 20 ) NOT NULL AUTO_INCREMENT,
				`wc_product_id` INT( 20 ),			
				`status` INT( 20 ),
				 PRIMARY KEY ( `id` )
			) ENGINE = MYISAM";
			foreach($wooData as $wdval){
				$wc_product_id = $wdval['wix_product_id'];
				$checkId = $wpdb->get_results( "SELECT * FROM $inventorypro WHERE wc_product_id ='". $wc_product_id ."'",ARRAY_A );
				if (empty($checkId)) {
					$wpdb->insert($inventorypro, array(
					    'wc_product_id' => $wc_product_id,
					    'status' => 1,
					));
				}
			}
		}
		$getpage = sanitize_text_field($_GET['page']);
		if (isset($getpage) && !empty($getpage)){ $page = $getpage; }else{ $page = 1; }
		$per_page       = 10;
		$previous_btn   = true;
		$next_btn       = true;
		$first_btn      = true;
		$last_btn       = true;
		$start          = ($page - 1) * $per_page;
		$showpagi       = 0;
        $uids           = array();        
        $left           = '';
        $qr             = '';
        $order_html = $ordritem_html  = '';
        $search =  sanitize_text_field($_GET['search']);        		
        if (isset( $search ) && !empty( $search )) {
        	if (DateTime::createFromFormat('F j, Y, g:i a', $search) !== false ){
        		$date = date('Y-m-d H:i',strtotime($search));
       			$qr .= " AND pt.`post_date` LIKE '%$date%'"; 
        	}elseif(DateTime::createFromFormat('F', $search) !== false ){
        		$date = date('m',strtotime($search));
       			$qr .= " AND pt.`post_date` LIKE '%$date%'"; 
        	}elseif(DateTime::createFromFormat('j', $search) !== false ){
        		$date = date('d',strtotime($search));
       			$qr .= " AND pt.`post_date` LIKE '%$date%'";
        	}elseif(DateTime::createFromFormat('Y', $search) !== false && strlen($search) > 3  ){
        		$date = date('Y',strtotime($search));
       			$qr .= " AND pt.`post_date` LIKE '%$date%'";
        	}elseif(DateTime::createFromFormat('g:i', $search) !== false || DateTime::createFromFormat('g:i a', $search) !== false  ){
        		$date = date('H:i',strtotime($search));
       			$qr .= " AND pt.`post_date` LIKE '%$date%'";
        	}elseif(DateTime::createFromFormat('F j, Y', $search) !== false ){
        		$date = date('Y-m-d',strtotime($search));
       			$qr .= " AND pt.`post_date` LIKE '%$date%'";
        	}elseif(DateTime::createFromFormat('F j', $search) !== false || DateTime::createFromFormat('F j,', $search) !== false){
        		$date = date('m-d',strtotime($search));
       			$qr .= " AND pt.`post_date` LIKE '%$date%'";
        	}elseif(DateTime::createFromFormat('F Y', $search) !== false || DateTime::createFromFormat('F, Y', $search) !== false) {
        		$date = date('Y-m',strtotime($search));
       			$qr .= " AND pt.`post_date` LIKE '%$date%'";
			}else{       		      			
       			$user_res = $wpdb->get_results("SELECT ID FROM $users WHERE user_email LIKE '%$search%' ORDER BY ID ASC",ARRAY_A);
       			for ($ii=0; $ii <count($user_res) ; $ii++) { 
       				array_push($uids, $user_res[$ii]['ID']);
       			}
       			if (isset($uids) && !empty($uids)) {
	       			$ids = implode(",",$uids);
	       			$qr .= " AND (psm.`meta_key` = '_customer_user' AND psm.`meta_value` IN($ids))";
       				
       			}else{
       				$qr .= " AND pt.`ID` = $search";
       			}
			}			
        }        

        $where = " order_item_type = 'line_item' AND p.order_item_id = pm.order_item_id AND (pm.meta_key = '_product_id' AND mp.wc_product_id = pm.meta_value) AND pt.`post_status` IN ('wc-pending','wc-processing','wc-on-hold','wc-completed','wc-cancelled','wc-refunded','wc-failed') AND pt.`post_type` = 'shop_order'".$qr;        
        $query = "SELECT pt.ID as order_id ,pt.post_status,pt.post_date FROM $order_item as p,$order_itemmeta as pm,$inventorypro as mp,".$posts." as pt LEFT JOIN $posts_meta as psm ON pt.ID = psm.post_id WHERE ".$where;
    
        $count     = $wpdb->get_results($query." GROUP BY pt.ID");
        $total_row = count($count);
        $orderdata = $wpdb->get_results($query." GROUP BY pt.ID ORDER BY pt.ID DESC LIMIT $start, $per_page");

        $invpro = $wpdb->get_results("SELECT wc_product_id FROM $inventorypro",ARRAY_A);
        $invproduct     = array();
        for ($ip=0; $ip <count($invpro); $ip++) { 
        	array_push($invproduct, $invpro[$ip]['wc_product_id']);
        }
        $invpids = array_unique($invproduct);
        $proids  = array_filter($invpids);

		if (isset($orderdata) && !empty($orderdata)) {
			$orderarray = $products = array();
			$shipping_address = '';
			foreach ($orderdata as $ordervalue) {
		        $order        = wc_get_order($ordervalue->order_id);
		        $created_date = $order->get_date_created()->format ('F j, Y, g:i a');
				$user_data    = $order->get_user();
					
		        switch($ordervalue->post_status) :      
					case 'wc-pending':
						$status = 'Pending payment';
						break;
					case 'wc-on-hold':
						$status = 'On hold';
						break;
					case 'wc-completed':
						$status = 'Completed';
						break;
					case 'wc-cancelled':						
						$status = 'Cancelled';
						break;
					case 'wc-refunded':						
						$status = 'Refunded';
						break;
					case 'wc-failed':						
						$status = 'Failed';
						break;
				  	default:
						$status = 'Processing';
					
						break;
				endswitch; 
				$totalcal = 0;
				foreach ( $order->get_items() as $item_id => $item ) {
					$product_id = $item->get_product_id();
					$product    = $item->get_product();
					if (in_array($product_id,$proids) ) {
						$itemprice  = ($item->get_subtotal()/$item->get_quantity());
						$products[$ordervalue->order_id][] = array(
							'item_name'  => $item->get_name(),
							'item_price' => number_format($itemprice,2),
							'item_qty'   => $item->get_quantity(),
						);					
						$totalcal = $totalcal +($itemprice*$item->get_quantity());					
					}
				}
				$total = number_format($totalcal, 2);
				if ($total > 0) {
					$shipping_info = array();
					if ($order->get_shipping_address_1() != '') {
						$shipping_address = $order->get_shipping_address_1().' '.$order->get_shipping_address_2().',<br>'.$order->get_shipping_city().','.$order->get_shipping_state().',<br>'.$order->get_shipping_country().'-'.$order->get_shipping_postcode();
						$shipadd = wp_kses( $shipping_address,array('br' => array()) );
						$shipping_info = array(
							'name' => $order->get_shipping_first_name().' '.$order->get_shipping_last_name(),
							'company' => $order->get_shipping_company(),
							'address' => $shipadd,
						);
					}
					$billing_address = $order->get_billing_address_1().' '.$order->get_billing_address_2().',<br>'.$order->get_billing_city().','.$order->get_billing_state().',<br>'.$order->get_billing_country().'-'.$order->get_billing_postcode();	
					$billadd = wp_kses( $billing_address,array('br' => array()) );					
					$orderarray[] = array(
						'ID' => $ordervalue->order_id,
						'date'=> $created_date,
						'user_email'=> $user_data->data->user_email,
						'status'=> $status,
						'items' => $products,
						'total' => $total,
						'currency' => $order->get_currency(),
						'payment'  => $order->get_payment_method_title(),
						'shipping_info'  => $shipping_info,
						'billing_info'  => array(
							'name' => $order->get_billing_first_name().' '.$order->get_billing_last_name(),
							'company' => $order->get_billing_company(),
							'address' => $billadd,
							'phone_no' => $order->get_billing_phone(),
						),
					);
				}
				
			}

			$response['status'] = 1;
			$response['data']   = $orderarray;       	
		}else{     
			$showpagi = 1;
			$response['status'] = 0;
		}
		if ($showpagi == 0 && $total_row > $per_page) {
	        $no_of_paginations = ceil($total_row / $per_page);
	        /* Pagination */
	        $pagination = array(
	            "cur_page" => $page,
	            "no_of_paginations" => $no_of_paginations,
	            "first_btn" => $first_btn,
	            "previous_btn" => $previous_btn,
	            "last_btn" => $last_btn,
	            "next_btn" => $next_btn
	        );
	       //sprintf( '<div class="error">%s</div>', wpautop( $message ) );
		   $response['pagination'] = MYOFS_API::Pagination($pagination,'order-list');
	    } else {
	        $response['pagination'] = "";
	    }
	    echo json_encode($response);
	    exit;

	}	

}
?>