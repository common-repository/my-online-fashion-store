<?php
/**
 * Admin Dashboard - load all stylesheet,scripts,menus
 *
 * @link       https://www.ccwholesaleclothing.com/
 * @since      1.0.0
 *
 * @package    My Online Fashion Store
 * @subpackage my-online-fashion-store/MYOFS_Admin
 */
defined( 'ABSPATH' ) || exit;
class MYOFS_Admin {

	/**
	 * Register the stylesheets for the plugin.
	 * 
	 * The MYOFS_Loader will then create the relationship
	 * between the defined hooks and the functions defined in this
	 * class.
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook_suffix) {
		$page = sanitize_text_field( filter_input( INPUT_GET, 'page' ) );
		if ( isset($page) && $page == 'myofs-all-inventory') {
			
			wp_enqueue_style( MYOFS_CODE.'-simple-line-icons','https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.5.5/css/simple-line-icons.min.css', array(), MYOFS_VERSION, 'all' );
			wp_enqueue_style( MYOFS_CODE.'-slick-ui', MYOFS_SLICK_PATH.'slick.css', array(), MYOFS_VERSION, 'all' );

			wp_enqueue_style( MYOFS_CODE.'myofs-font-awesome-ui', MYOFS_PUBLIC_PATH.'font-awesome/css/font-awesome.min.css', array(), MYOFS_VERSION, 'all' );
					
			wp_enqueue_style( MYOFS_CODE.'-select2-ui', MYOFS_SELECT2_CSS_PATH.'select2.min.css', array(), MYOFS_VERSION, 'all' );

			wp_enqueue_style( MYOFS_CODE.'-styles-ui', MYOFS_CSS_PATH.'myofs-styles.css', array(), MYOFS_VERSION, 'all' );

			wp_enqueue_style( MYOFS_CODE.'-responsive-styles-ui', MYOFS_CSS_PATH.'myofs-responsive-styles.css', array(), MYOFS_VERSION, 'all' );
			wp_enqueue_style( MYOFS_CODE.'-sidebar-ui', MYOFS_CSS_PATH.'myofs-sidebar.css', array(), MYOFS_VERSION, 'all' );
		}

	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * The MYOFS_Loader will then create the relationship
	 * between the defined hooks and the functions defined in this
	 * class.
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$page = sanitize_text_field( filter_input( INPUT_GET, 'page' ) );
		if ( isset($page) && $page == 'myofs-all-inventory') {
	        wp_enqueue_script(MYOFS_CODE.'-js-slick', MYOFS_SLICK_PATH.'slick.js', array( 'jquery' ), MYOFS_VERSION, false);
	        
	        wp_enqueue_script(MYOFS_CODE.'-js-select2', MYOFS_SELECT2_JS_PATH.'select2.js', array( 'jquery' ), MYOFS_VERSION, false);
	        wp_enqueue_script(MYOFS_CODE.'-js-select2-min', MYOFS_SELECT2_JS_PATH.'select2.min.js', array( 'jquery' ), MYOFS_VERSION, false);        
	        
	        wp_enqueue_script(MYOFS_CODE.'-jquery-validate-js', MYOFS_PUBLIC_JS_PATH.'jquery.validate.js', array( 'jquery' ), MYOFS_VERSION, false);
	        wp_enqueue_script(MYOFS_CODE.'-jquery-validate-min-js', MYOFS_PUBLIC_JS_PATH.'jquery.validate.min.js', array( 'jquery' ), MYOFS_VERSION, false);
	        
	        wp_enqueue_script(MYOFS_CODE.'-all-inventory-js', MYOFS_JS_PATH.'myofs-all-inventory.js', array( 'jquery' ), false, true );        
	        wp_enqueue_script(MYOFS_CODE.'-admin-js', MYOFS_JS_PATH.'myofs-admin.js', array( 'jquery' ), MYOFS_VERSION, false);        
	        wp_enqueue_script(MYOFS_CODE.'-my-order-js', MYOFS_JS_PATH.'myofs-my-order.js', array( 'jquery' ), false, true );
	    }
		
	}
	/**
	 * Register a plugin custom menu page for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function myofs_admin_menu(){

		add_menu_page(
	        __( 'My Online Fashion Store', 'my-online-fashion-store' ),
	        __( 'My Online Fashion Store', 'my-online-fashion-store' ),	   
	        'manage_options',     
	        'myofs-all-inventory',
	        array( $this, 'productListPage' ),
			'dashicons-products',
	        10
	    );
	    $get_optdata = MYOFS_OPTDATA;
		if (isset($get_optdata['auth_token']) && !empty($get_optdata['auth_token'])) {
	        add_submenu_page( 
		    	'myofs-all-inventory',
				__( 'All Inventory', 'my-online-fashion-store' ),
		    	__( 'All Inventory', 'my-online-fashion-store' ),
		    	'manage_options',
		    	'myofs-all-inventory'		    	
		    );
		    add_submenu_page( 
		    	'myofs-all-inventory',
		    	__( 'My Inventory', 'my-online-fashion-store' ),
		    	__( 'My Inventory', 'my-online-fashion-store' ),
		    	'manage_options',
		    	'my-inventory',
	        	array( $this, 'menusQueryStringUrl' )     
		    	
		    );
		    add_submenu_page( 
		    	'myofs-all-inventory',
		    	__( 'My Orders', 'my-online-fashion-store' ),
		    	__( 'My Orders', 'my-online-fashion-store' ),
		    	'manage_options',
		    	'my-orders',
		        array( $this, 'menusQueryStringUrl' )
		    	
		    );
		    add_submenu_page( 
		    	'myofs-all-inventory',
		    	__( 'Marketing Material', 'my-online-fashion-store' ),
		    	__( 'Marketing Material', 'my-online-fashion-store' ),
		    	'manage_options',
		    	'marketing-marterial',
		        array( $this, 'menusQueryStringUrl' )
		    	
		    );
		    add_submenu_page( 
		    	'myofs-all-inventory',
		    	__( 'My Account', 'my-online-fashion-store' ),
		    	__( 'My Account', 'my-online-fashion-store' ),
		    	'manage_options',
		    	'my-account',
		        array( $this, 'menusQueryStringUrl' )
		    	
		    );
		     add_submenu_page( 
		    	'myofs-all-inventory',
		    	__( 'Help', 'my-online-fashion-store' ),
		    	__( 'Help', 'my-online-fashion-store' ),
		    	'manage_options',
		    	'help',
		        array( $this, 'menusQueryStringUrl' )
		    	
		    );
			add_submenu_page( 
		    	'myofs-all-inventory',
		    	__( 'Returns', 'my-online-fashion-store' ),
		    	__( 'Returns', 'my-online-fashion-store' ),
		    	'manage_options',
		    	'returns',
		        array( $this, 'menusQueryStringUrl' )
		    	
		    );
			add_submenu_page( 
		    	'myofs-all-inventory',
		    	__( 'Upgrade & Save', 'my-online-fashion-store' ),
		    	__( 'Upgrade & Save', 'my-online-fashion-store' ),		    	
		    	'manage_options',
		    	'upgrade-save',
		        array( $this, 'menusQueryStringUrl' )
		    	
		    );
		}
	}
	/**
	 * Register a plugin menus urls for the admin area.
	 *
	 * @since    1.0.0
	 */
	public static function menusQueryStringUrl() {
		$page = sanitize_text_field( filter_input( INPUT_GET, 'page' ) );
		switch($page) :      
			case 'my-inventory':
				wp_safe_redirect( add_query_arg( array( 'page' => 'myofs-all-inventory&tab=my-inventory' ) ) );
				break;
			case 'my-orders':
				wp_safe_redirect( add_query_arg( array( 'page' => 'myofs-all-inventory&tab=my-orders' ) ) );
				break;
			case 'marketing-marterial':
				wp_safe_redirect( add_query_arg( array( 'page' => 'myofs-all-inventory&tab=marketing-marterial' )));
				break;
			case 'my-account':
				wp_safe_redirect( add_query_arg( array( 'page' => 'myofs-all-inventory&tab=my-account' ) ) );
				break;
			case 'help':
				wp_safe_redirect( add_query_arg( array( 'page' => 'myofs-all-inventory&tab=help' ) ) );
				break;
			case 'returns':
				wp_safe_redirect( add_query_arg( array( 'page' => 'myofs-all-inventory&tab=returns' ) ) );
				break;
			case 'upgrade-save':
				wp_safe_redirect( add_query_arg( array( 'page' => 'myofs-all-inventory&tab=upgrade-save' ) ) );
				break;
		  	default:
				wp_safe_redirect( add_query_arg( array( 'page' => 'myofs-all-inventory' ) ) );				
				break;
		endswitch;
	}
	/*
	* Set Pages and Activation key for the plugin area.
	*/
	public static function productListPage() {
	 	$get_optdata = MYOFS_OPTDATA;
		if (isset($get_optdata['auth_token']) && !empty($get_optdata['auth_token'])) {
	       	if ( is_file(  MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-menus-page.php' ) )
	       	{
	        	include_once  MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-menus-page.php';            
	        }			
		}else{			
	        if ( is_file(  MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-activation-key-page.php' ) ) {
	        	include_once  MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-activation-key-page.php';      
	        }
		}
		
    }
	/**
	 * Set activation key page for the plugin area.
	 *
	 * @since    1.0.0
	 */
    public static function activationKeyPage() {
        if ( is_file(  MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-activation-key-page.php' ) ) {
        	include_once  MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-activation-key-page.php';      
        }
    }    

}
?>