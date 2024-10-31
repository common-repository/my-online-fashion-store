<?php
/**
 * My Online Fashion Store Admin Helper API
 *
 * @package my-online-fashion-store\myofs-api
 */
defined('ABSPATH') || exit;

/**
 * MYOFS_API Class
 *
 * Provides a communication interface with the My Online Fashion Store Helper API.
 */
class MYOFS_API {
     /**
      * API Curl Request Method
      *
      * @return array
      */
     private function callAPI($method, $path, $data, $auth = '') {

          $resarr = $arg_arr = array();
          switch ($method) {
               case "POST":
                    if ($data)
                         $url = sprintf("%s?%s", MYOFS_API_PATH . $path, http_build_query($data));
                    $arg_arr = array('method' => 'POST', 'body' => $data);
                    break;
               case "PUT":
                    if ($data)
                         $url = sprintf("%s", MYOFS_API_PATH . $path);
                    $arg_arr = array('method' => 'PUT', 'body' => $data);
                    break;
               case "DELETE":
                    if ($data)
                         $url = sprintf("%s", MYOFS_API_PATH . $path);
                    $arg_arr = array('method' => 'PUT', 'body' => $data);
                    break;
               default:
                    if ($data == 1) {
                         $url = sprintf("%s", MYOFS_API_PATH . $path);
                    } else {
                         $url = sprintf("%s?%s", MYOFS_API_PATH . $path, http_build_query($data));
                    }
                    $arg_arr = array('method' => 'GET');
          }
          $arg_arr['timeout'] = 45;
          $arg_arr['httpversion'] = '1.1';
          $arg_arr['headers'] = $auth;
          $response      = wp_remote_request($url, $arg_arr);
          $response_code = wp_remote_retrieve_response_code($response);
          // Check the HTTP Status code
          switch ($response_code) {
               case 200:
                    $status_code = array(200 => esc_html("Request was successful"));
                    break;
               case 404:
                    $status_code = array(404 => esc_html("API Not found"));
                    break;
               case 500:
                    $status_code = array(500 => esc_html("servers replied with an error."));
                    break;
               case 502:
                    $status_code = array(502 => esc_html("servers may be down or being upgraded. Hopefully they'll be OK soon!"));
                    break;
               case 503:
                    $status_code = array(503 => esc_html("service unavailable. Hopefully they'll be OK soon!"));
                    break;
               default:
                    $status_code = array(204 => esc_html("Undocumented error"));
                    break;
          }
          
          if (is_wp_error($response)) {
               $error_message = $response->get_error_message();
               $resarr['error'] = $error_message;
          } else {
               $ret_json = json_decode($response['body'], true);
               $resarr['data'] = $ret_json;
          }
          $resarr['status_code'] = $status_code;
          return $resarr;
     }

     /**
      * Get Activation Plan
      *
      * @return array
      */
     public function GetActivationPlan($qry_str) {
          $get_data = $this->callAPI('POST', 'Purchase/gettoken', $qry_str);
          return $get_data;
     }

     /**
      * Return the authentication
      *
      * @return array
      */
     private function authentication() {
          $getoption = MYOFS_OPTDATA;
          $token = base64_decode(sanitize_option(MYOFS_OPT_NAME, $getoption['auth_token']));
          $url = MYOFS_SITE_URL;
          $keysencode = base64_encode($token . ':' . $url);
          $returnauth = array('Authorization' => 'Basic ' . $keysencode);
          return $returnauth;
     }

     /**
      * Return the check plan
      *
      * @return array
      */
     private function recurringchk() {
          $auth = $this->authentication();
          $recchk_data = $this->callAPI('GET', 'Payement_recuring', true, $auth);
          if (isset($recchk_data['data']) && !empty($recchk_data['data'])) {
               if ($recchk_data['data']['status'] == 1) {
                    return $recchk_data['data']['status'];
               } else {
                    update_option('myofs_opt_expire', $recchk_data['data']['message']);
                    delete_option('myofs_opt_data');
                    return $recchk_data['data']['status'];
               }
          }
     }

     /**
      * Maintenance Mode
      *
      * @return array
      */
     public function MaintenanceMode() {
          $auth = $this->authentication();
          $get_data = $this->callAPI('GET', 'Maintenance', true, $auth);
          return $get_data;
     }

     /**
      * Check plugin version
      *
      * @return array
      */
     public function CheckPluginVersion() {
          $auth = $this->authentication();
          $get_data = $this->callAPI('GET', 'Maintenance/version_release', array('version' => MYOFS_VERSION), $auth);
          return $get_data;
     }

     /**
      * Get Subscription Plane Information 
      *
      * @return array
      */
     public function GetMyAccount() {
          $auth = $this->authentication();
          $rchk = $this->recurringchk();
          if (isset($rchk) && !empty($rchk) && $rchk == 1) {
               $get_data = $this->callAPI('GET', 'Account', true, $auth);
               return $get_data;
          }
     }

     /**
      * Get Inventory All Products Details
      *
      * @return array
      */
     public function GetAllProducts($qry_str) {
          $auth = $this->authentication();
          $rchk = $this->recurringchk();
          if (isset($rchk) && !empty($rchk) && $rchk == 1) {
               $chk_keys = $this->CheckWcRestApiKeys();
               $queryStrData = array_filter($qry_str);
               $get_data = $this->callAPI('GET', 'products', $queryStrData, $auth);
               return $get_data;
          }
     }

     /**
      * Get Inventory All Added Products
      *
      * @return array
      */
     public function GetAddedProducts($qry_str) {
          $queryStrData = array_filter($qry_str);
          $auth = $this->authentication();
          $get_data = $this->callAPI('GET', 'products/itemaddedlabel', $queryStrData, $auth);
          return $get_data;
     }

     /**
      * Get My Inventory Products Details
      *
      * @return array
      */
     public function GetMyInventory($qry_str) {
          $auth = $this->authentication();
          $rchk = $this->recurringchk();
          if (isset($rchk) && !empty($rchk) && $rchk == 1) {
               $queryStrData = array_filter($qry_str);
               $get_data = $this->callAPI('GET', 'products/getMyProducts', $queryStrData, $auth);
               return $get_data;
          }
     }
     /**
      * Get Added Products Ids
      *
      * @return array
      */
     public function GetMyInventoryIds() {
          $auth = $this->authentication();
          $rchk = $this->recurringchk();
          if (isset($rchk) && !empty($rchk) && $rchk == 1) { 
               
               $get_data = $this->callAPI('GET', 'products/getMyProductsIds', true, $auth);
               return $get_data;
          }
     }

     /**
      * Get Inventory Single Product Details 
      *
      * @return array
      */
     public function GetSingleProduct($product_id) {
          $auth = $this->authentication();
          $rchk = $this->recurringchk();
          if (isset($rchk) && !empty($rchk) && $rchk == 1) {
               $queryStr = array('product_id' => $product_id);
               $get_data = $this->callAPI('GET', 'products/getProduct', $queryStr, $auth);
               return $get_data;
          }
     }

     /**
      * Get Inventory Categories
      *
      * @return array
      */
     public function GetAllCategories() {
          $auth = $this->authentication();
          $rchk = $this->recurringchk();
          if (isset($rchk) && !empty($rchk) && $rchk == 1) {
               $get_data = $this->callAPI('GET', 'categories', true, $auth);
               return $get_data;
          }
     }

     /**
      * Get Marketing Material Banner Details
      *
      * @return array
      */
     public function GetMarketingMaterial() {
          $auth = $this->authentication();
          $rchk = $this->recurringchk();
          if (isset($rchk) && !empty($rchk) && $rchk == 1) {
               $get_data = $this->callAPI('GET', 'marketing', true, $auth);
               return $get_data;
          }
     }

     /**
      * get woocommerce store categories
      *
      * @return array
      */
     public function GetStoreCategories() {
          $auth = $this->authentication();
          $rchk = $this->recurringchk();
          if (isset($rchk) && !empty($rchk) && $rchk == 1) {
               $chk_keys = $this->CheckWcRestApiKeys();
               $get_data = $this->callAPI('GET', 'WcStoreCategories', true, $auth);
                
               return $get_data;
          }
     }

     /**
      * Create Single/Bulk Product on the woocommerce
      *
      * @return array
      */
     public function AddProducttoStore($qry_str) {
          $auth = $this->authentication();
          $rchk = $this->recurringchk();
          if (isset($rchk) && !empty($rchk) && $rchk == 1) {
               $chk_keys = $this->CheckWcRestApiKeys();
               $get_data = $this->callAPI('POST', 'products/item_addtowix', $qry_str, $auth);
              
               return $get_data;
          }
     }

     /**
      * Create Single/Bulk Product on the woocommerce
      *
      * @return array
      */
     public function StoreMyinventoryProductIds() {
          $auth = $this->authentication();
          $rchk = $this->recurringchk();
          if (isset($rchk) && !empty($rchk) && $rchk == 1) {
               $get_data = $this->callAPI('GET', 'products/item_myinventory', true, $auth);
               return $get_data;
          }
     }

     /*
      * Remove Single/Bulk Product on the woocommerce
      *
      * @return array
      */

     public function RemoveProducttoStore($qry_str) {
          $auth = $this->authentication();
          $rchk = $this->recurringchk();
          if (isset($rchk) && !empty($rchk) && $rchk == 1) {
               $chk_keys = $this->CheckWcRestApiKeys();
               $get_data = $this->callAPI('POST', 'products/itemremove', $qry_str, $auth);
               return $get_data;
          }
     }

     /*
      * Create Combine Product on the woocommerce
      *
      * @return array
      */

     public function CombineProducts($qry_str) {
          $auth = $this->authentication();
          $rchk = $this->recurringchk();
          if (isset($rchk) && !empty($rchk) && $rchk == 1) {
               $chk_keys = $this->CheckWcRestApiKeys();
               $get_data = $this->callAPI('POST', 'products/checkCombineProduct', $qry_str, $auth);
               return $get_data;
          }
     }

     /**
      * Submit Return Page form
      *
      * @return array
      */
     public function ReturnFormSubmit($qry_str) {
          $auth = $this->authentication();
          $rchk = $this->recurringchk();
          if (isset($rchk) && !empty($rchk) && $rchk == 1) {
               $get_data = $this->callAPI('POST', 'Returnemail', $qry_str, $auth);
               return $get_data;
          }
     }

     /**
      * Store Generated woocommerce Rest API Keys
      *
      * @return array
      */
     public function StoreGeneratedWcRestApiKeys($authkey) {
          $url = MYOFS_SITE_URL;
          $token = base64_decode($authkey);
          $keysencode = base64_encode($token . ':' . $url);
          $auth = array('Authorization' => 'Basic ' . $keysencode);
          $qry_str = $this->GenerateWcRestApiKeys();
          $get_data = $this->callAPI('POST', 'WcRestApiKeys', $qry_str, $auth);
          return $get_data;
     }

     /**
      * Generate woocommerce Rest API Keys
      *
      * @return array
      */
     private function GenerateWcRestApiKeys() {
          global $wpdb;
          /* genrate API keys */
          update_option('woocommerce_api_enabled', 'yes', true);
          $consumer_key = 'ck_' . wc_rand_hash();
          $consumer_secret = 'cs_' . wc_rand_hash();
          $user_id = get_current_user_id();
          $store_url = MYOFS_SITE_URL;
          $description = MYOFS_PLUGIN_NAME;
          $permissions = 'read_write';
          $wckey_table = $wpdb->prefix . 'woocommerce_api_keys';
          $getid = $wpdb->get_row(
                  $wpdb->prepare(
                          "SELECT key_id
				FROM $wckey_table
				WHERE description = %d",
                          $description
                  ),
                  ARRAY_A
          );
          if (isset($getid['key_id']) && !empty($getid['key_id'])) {
               $keyid = $getid['key_id'];
               $data = array(
                   'consumer_key' => hash_hmac('sha256', $consumer_key, 'wc-api'),
                   'consumer_secret' => $consumer_secret,
                   'truncated_key' => substr($consumer_key, -7),
               );

               $wpdb->update(
                       $wckey_table,
                       $data,
                       array('key_id' => $keyid),
                       array('%s', '%s', '%s'),
                       array('%d')
               );
          } else {

               $data = array(
                   'user_id' => $user_id,
                   'description' => $description,
                   'permissions' => $permissions,
                   'consumer_key' => hash_hmac('sha256', $consumer_key, 'wc-api'),
                   'consumer_secret' => $consumer_secret,
                   'truncated_key' => substr($consumer_key, -7),
               );
               $wpdb->insert(
                       $wckey_table,
                       $data,
                       array('%d', '%s', '%s', '%s', '%s', '%s')
               );
               $insertid = $wpdb->insert_id;
          }
          //product delete webhook
          $deletewhdata = array(
              'status' => 'active',
              'name' => MYOFS_PLUGIN_NAME . ' - Delete Product',
              'user_id' => get_current_user_id(),
              'delivery_url' => MYOFS_WEBHOOK_PATH . 'deletehook',
              'secret' => $consumer_secret,
              'topic' => 'product.deleted',
              'date_created' => current_time('mysql'),
              'date_created_gmt' => current_time('mysql', 1),
              'api_version' => 3,
              'failure_count' => 0,
              'pending_delivery' => 0
          );
          $wpdb->insert($wpdb->prefix . 'wc_webhooks', $deletewhdata);

          $response = array();
          $response['consumer_key'] = $consumer_key;
          $response['consumer_secret'] = $consumer_secret;
          $response['store_url'] = $store_url;

          return $response;
     }

     /*
      *
      * Check woocommerce rest API read/write permission or not 
      *
      */

     private function CheckWcRestApiKeys() {
          global $wpdb;
          $response = array();
          $description = MYOFS_PLUGIN_NAME;
          $wckey_table = $wpdb->prefix . 'woocommerce_api_keys';
          $permissions = 'read_write';
          $getid = $wpdb->get_row(
                  $wpdb->prepare(
                          "SELECT key_id
				FROM $wckey_table
				WHERE description = %d",
                          $description
                  ),
                  ARRAY_A
          );
          if (isset($getid['key_id']) && !empty($getid['key_id'])) {
               $keyid = $getid['key_id'];
               $data = array('permissions' => $permissions);
               $wpdb->update(
                       $wckey_table,
                       $data,
                       array('key_id' => $keyid),
                       array('%s'),
                       array('%d')
               );
               $response['status'] = 0;
          } else {
               $consumer_key = 'ck_' . wc_rand_hash();
               $consumer_secret = 'cs_' . wc_rand_hash();
               $user_id = get_current_user_id();
               $data = array(
                   'user_id' => $user_id,
                   'description' => $description,
                   'permissions' => $permissions,
                   'consumer_key' => hash_hmac('sha256', $consumer_key, 'wc-api'),
                   'consumer_secret' => $consumer_secret,
                   'truncated_key' => substr($consumer_key, -7),
               );
               $wpdb->insert(
                       $wckey_table,
                       $data,
                       array('%d', '%s', '%s', '%s', '%s', '%s')
               );
               $insertid = $wpdb->insert_id;

               $url = MYOFS_SITE_URL;
               $auth = $this->authentication();

               $response['consumer_key'] = $consumer_key;
               $response['consumer_secret'] = $consumer_secret;
               $response['store_url'] = $url;
               $response['status'] = 1;

               $get_data = $this->callAPI('POST', 'WcRestApiKeys', $response, $auth);
          }

          return $response;
     }

     /**
      * Images API Keys
      *
      * @return array
      */
     public function DynamicImagesApi() {
          $get_data = $this->callAPI('GET', 'Imagedynamic', true, '');
          return $get_data;
     }

     /**
      * Pagination on the inventory
      *
      * @return array
      */
     public function Pagination($pagination,$page='') {
          extract($pagination);
          $pag_container = "";
          if ($cur_page >= 3) {
               $start_loop = $cur_page;
               if ($no_of_paginations > $cur_page + 2) {
                    $end_loop = $cur_page + 2;
               } else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 2) {
                    $start_loop = $no_of_paginations - 2;
                    $end_loop = $no_of_paginations;
               } else {
                    $end_loop = $no_of_paginations;
               }
          } else {
               $start_loop = 1;
               if ($no_of_paginations > 3) {

                    $end_loop = 3;
               } else {

                    $end_loop = $no_of_paginations;
               }
          }
          $rmvhtml = '';
          
          $rmvhtml .= '<div class="myofs-pagination">
               <ul>';
                    if ($first_btn && $cur_page > 1) { 
                         $rmvhtml .=  '<li p="1" class="active">« First</li>';
                    } else if ($first_btn) {                         
                         $rmvhtml .=  '<li p="1" class="inactive">« First</li>';
                    }
                    if ($previous_btn && $cur_page > 1) {
                         $pre = $cur_page - 1;
                         
                         $rmvhtml .=  '<li p="'.$pre.'" class="active previ">«</li>';
                    } else if ($previous_btn) {                         
                         $rmvhtml .=  '<li class="inactive previ">«</li>';
                    }

                    for ($i = $start_loop; $i <= $end_loop; $i++) {
                         $pclass = $cur_page == $i ? sanitize_html_class('selected') : sanitize_html_class('active');                         
                         $rmvhtml .=  '<li p="'.$i.'" class ='.$pclass.'>'.$i.'</li>';
                    }
                    if ($next_btn && $cur_page < $no_of_paginations) {
                         $nex = $cur_page + 1;               
                         $rmvhtml .=  '<li p="'.$nex.'" class="active next">»</li>';
                    } else if ($next_btn) {               
                         $rmvhtml .=  '<li class="inactive next">»</li>';
                    }
                    if ($last_btn && $cur_page < $no_of_paginations) {               
                         $rmvhtml .=  '<li p="'.$no_of_paginations.'" class="active">Last »</li>';
                    } else if ($last_btn) {
                         $rmvhtml .=  '<li p="'.$no_of_paginations.'" class="inactive">Last »</li>';
                    }
                    $rmvhtml .=  '</ul>
               </div>';
          if ($page == 'order-list') {
               return base64_encode($rmvhtml);
          }else{

               echo $rmvhtml;
          }
    }
}
?>