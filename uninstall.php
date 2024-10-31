<?php
 /**
 * My Online Fashion Store uninstall
 *
 * Uninstalling My Online Fashion Store deletes table,options data and removed store data from the third party server.
 *
 * @link       https://www.ccwholesaleclothing.com/
 * @version    1.1.3
 * @package    My Online Fashion Store\Uninstaller 
 */

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();
global $wpdb;

$getoptdata  =  get_option( 'myofs_opt_data' );
if ($getoptdata != FALSE) {
    if (isset($getoptdata) && !empty($getoptdata)) {
    	$getoption  = maybe_unserialize($getoptdata);
	    $token      = base64_decode(sanitize_option('myofs_opt_data',$getoption['auth_token']));
	    $email      = sanitize_email($getoption['email']);
	    $keysencode = base64_encode($token.':'.get_site_url());
	    $returnauth = array('Authorization' => 'Basic '.$keysencode);
		
		$arg_arr = array( 'method' => 'POST','body' => array('email' => $email),'timeout' => 45,'httpversion' => '1.1','headers' => $returnauth );
		$response = wp_remote_request( 'https://wp.ccdemostore.com/app_api/Account/uninstallappupdate', $arg_arr);
	    $result = json_decode($response['body'], true);
	    if ($result['status'] != 1) {
	        $class   = 'notice notice-error';
	        $message = 'Something went to wrong.Please try again';
	        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
	        exit();
	    } 
    }
}
$table_name = $wpdb->prefix .'myofs_products';
$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
$description = 'My Online Fashion Store';
$wpdb->delete( $wpdb->prefix . 'woocommerce_api_keys', array( 'description' => $description ), array( '%d' ) );    
delete_option( 'myofs_db_version' );
delete_option( 'myofs_activation_redirect' );
delete_option( 'myofs_opt_data' );
update_option( 'myofs_db_installed', 0 );
