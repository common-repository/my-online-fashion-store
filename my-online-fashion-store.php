<?php
/**
 * My Online Fashion Store
 *
 * @package       	My Online Fashion Store
 * @author        	CCwholesaleclothing Team
 * @copyright     	2021 CCdemowholesaleclothing
 * @license       	GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:   	My Online Fashion Store
 * Plugin URI:    	https://www.ccwholesaleclothing.com/
 * Description:   	Online fashion store addon helps you product store in woocommerce. 
 * Version:       	1.1.3
 * Author:        	CCwholesaleclothing Team
 * Text Domain:   	my-online-fashion-store
 * License:       	GPL v2 or later
 * License URI:   	http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:     /languages
 *
 * Requires at least: 5.6
 * Requires PHP:  	7.2
 *
 * WC requires at least: 5.2.0
 * WC tested up to: 6.1.1
 */

// If this file is called directly, abort.
if(!defined( 'ABSPATH' )) exit;
/*
* config.php file load all the define plugin constants 
*/
require_once(plugin_dir_path( __FILE__ ) . 'config.php');

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		/*Check woocommerce plugin activated or not*/
        if (!function_exists('is_woocommerce_active')){
			function is_woocommerce_active(){
			    $active_plugins = (array) get_option('active_plugins', array());
			    if(is_multisite()){
				   $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
			    }
			    return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins) || class_exists('WooCommerce');
			}
		}
		/*Get woocmmerce current activated version*/
		if (!function_exists('myofs_get_woocommerce_version')){
			function myofs_get_woocommerce_version() {			     
				if ( ! function_exists( 'get_plugins' ) ){
					require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				}
		
				$plugin_folder = get_plugins( '/' . 'woocommerce' );
				$plugin_file = 'woocommerce.php';
			
				if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
					return $plugin_folder[$plugin_file]['Version'];
				} else {
					return NULL;
				}
			}
		}
		
		if(is_woocommerce_active()) {
			$woo_version = myofs_get_woocommerce_version();
			if ( $woo_version <= '5.2.0' ) {				
				add_action( 'admin_notices', 'admin_version_notice_error' );
			     
			}else{

				/**
				* The code that runs during plugin activation.
				* This action is documented in includes/class-myofs-activator.php
				*/
				require_once MYOFS_PLUGIN_INCLUDE_PATH . 'class-myofs-activator.php';
				register_activation_hook( __FILE__, array( 'MYOFS_Activator', 'activate' ) );

				/**
				* The code that runs during plugin deactivation.
				* This action is documented in includes/class-myofs-deactivator.php
				*/
				require_once MYOFS_PLUGIN_INCLUDE_PATH . 'class-myofs-deactivator.php';
				register_deactivation_hook( __FILE__, array('MYOFS_Deactivator','deactivate') );
				/*
				* Redirects the user after plugin activation
				*/
				add_action( 'admin_init', 'MYOFS_after_activation_redirect');
				function MYOFS_after_activation_redirect() {		
					if (is_user_logged_in() &&  intval( get_option( 'myofs_activation_redirect', false ) ) === wp_get_current_user()->ID ) {
						delete_option( 'myofs_activation_redirect' );
						wp_safe_redirect( admin_url( MYOFS_PLUGIN_URL ) );
						exit;
					}
				}	
				
				/**
				 * The core plugin class that is used to define internationalization,
				 * admin-specific hooks, and public-facing site hooks.
				 */
				if ( ! class_exists( 'my_online_fashion_store', false ) ) {

					require MYOFS_PLUGIN_INCLUDE_PATH . 'class-myofs-core-functions.php';
				}

				/**
				 * Begins execution of the plugin.
				 *
				 * Since everything within the plugin is registered via hooks,
				 * then kicking off the plugin from this point in the file does
				 * not affect the page life cycle.
				 *
				 * @since    1.1.3
				 */
				function run_my_online_fashion_store() {

					$plugin = new my_online_fashion_store();
					$plugin->run();

				}
				run_my_online_fashion_store();
			}
		}else{
			add_action( 'admin_notices', 'admin_notice__error' );
		} 
}else{	
	add_action( 'admin_notices', 'admin_notice__error' );
}
myofs_plugin_structure();
function admin_version_notice_error() {
    $class   = 'notice notice-error';
    $message = 'WooCommerce version not comfortable with My Online Fashion Store plugin please install/update  woocommerce plugin version 5.2.0 or above';
 
    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}
function admin_notice__error() {
    $class = 'notice notice-error';
    $message = 'woocommerce plugin is required for my online fashion store plugin.so please install/active WooCommerce plugin';	 
    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}
function myofs_plugin_structure(){
	global $wpdb;	
	$table_name   = $wpdb->prefix .MYOFS_DB_TABLE;
	$create_table = "CREATE TABLE IF NOT EXISTS `".$table_name."` (
			`id` INT( 20 ) NOT NULL AUTO_INCREMENT,
			`wc_product_id` INT( 20 ),			
			`status` INT( 20 ),
			 PRIMARY KEY ( `id` )
		) ENGINE = MYISAM";
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($create_table);
	}
	$charset_collate = $wpdb->get_charset_collate();

	if (get_option( MYOFS_OPT_NAME ) == FALSE) {
		add_option( MYOFS_OPT_NAME );		
		add_option( 'myofs_db_version', MYOFS_VERSION );
		update_option( 'myofs_db_installed', 1);	
	}

}
?>