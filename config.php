<?php
/*
* Plugin constant data
*/
define( 'MYOFS_CODE', 'myofs' );
define( 'MYOFS_PLUGIN_NAME', 'My Online Fashion Store' );
define( 'MYOFS_VERSION', '1.1.3' );
define( 'MYOFS_DB_TABLE', 'myofs_products' );
define( 'MYOFS_PLUGIN_URL', 'admin.php?page=myofs-all-inventory' );
define( 'MYOFS_OPT_NAME', 'myofs_opt_data');
define( 'MYOFS_OPTDATA', maybe_unserialize( get_option( MYOFS_OPT_NAME ) ) );
define( 'MYOFS_SITE_URL', get_site_url()); 
define( 'MYOFS_FOOTER', 'My Online Fashion Store Version '.MYOFS_VERSION.'.
Powered by CCdemowholesaleclothing');

define( 'MYOFS_PLUGIN_FILE', __FILE__ );
define( 'MYOFS_PLUGIN_DIR_PATH', plugin_dir_path( MYOFS_PLUGIN_FILE ) );
define( 'MYOFS_URL', plugins_url( '/', __FILE__ ) );
define( 'MYOFS_PLUGIN_INCLUDE_PATH',MYOFS_PLUGIN_DIR_PATH. 'includes/' );
define( 'MYOFS_PLUGIN_TEMPLATE_PATH',MYOFS_PLUGIN_DIR_PATH. 'templates/' );
/*
* Assets folder
*/
define( 'MYOFS_ASSETS_URL',MYOFS_URL.'assets/' );
define( 'MYOFS_JS_PATH',  MYOFS_ASSETS_URL . 'js/');
define( 'MYOFS_CSS_PATH', MYOFS_ASSETS_URL . 'css/');
define( 'MYOFS_IMG_PATH', MYOFS_ASSETS_URL . 'images/');

define( 'MYOFS_PUBLIC_PATH',  MYOFS_ASSETS_URL . 'public/');
define( 'MYOFS_PUBLIC_JS_PATH',  MYOFS_ASSETS_URL . 'public/js/');
define( 'MYOFS_PUBLIC_CSS_PATH',  MYOFS_ASSETS_URL . 'public/css/');
define( 'MYOFS_PUBLIC_IMG_PATH', MYOFS_ASSETS_URL . 'public/images/');

define( 'MYOFS_SLICK_PATH', MYOFS_PUBLIC_PATH . 'slick/');

define( 'MYOFS_SELECT2_PATH', MYOFS_PUBLIC_PATH . 'select2/');
define( 'MYOFS_SELECT2_JS_PATH', MYOFS_SELECT2_PATH . 'js/');
define( 'MYOFS_SELECT2_CSS_PATH', MYOFS_SELECT2_PATH . 'css/');

define( 'MYOFS_PLAN_URL', 'https://subscription.ccdemostore.com/');

/*API Path*/
define( 'MYOFS_API_PATH', 'https://wp.ccdemostore.com/app_api/');
define( 'MYOFS_WEBHOOK_PATH', MYOFS_API_PATH.'productwebhook/');
define( 'MYOFS_API_ID', 'c3c84390-82f2-4ae3-9c3e-92eb2e0c1b7b');
/*Store Path*/
define('MYOFS_STORE_URL','https://www.ccwholesaleclothing.com');
define('MYOFS_SURL','https://www.myonlinefashionstore.com');
define('MYOFS_PRODUCT_PATH',MYOFS_SURL.'/products/');
define('MYOFS_PRODUCT_CFI',MYOFS_PRODUCT_PATH.'custom-flyer-insert');
define('MYOFS_PRODUCT_CL',MYOFS_PRODUCT_PATH.'custom-label');
define('MYOFS_PRODUCT_CHT',MYOFS_PRODUCT_PATH.'custom-hang-tag');
define('MYOFS_SHIPPING_PATH',MYOFS_SURL.'/pages/shipping-info');

define( 'MYOFS_PLANUPGRADE_PATH', admin_url( 'admin.php?page=myofs-all-inventory&tab=my-account' ) );
define( 'MYOFS_PERPROGRAM_PATH', MYOFS_PRODUCT_PATH.'pre-programmed-pre-designed-pre-loaded-with-our-items-ready-to-launch-website');
define( 'MYOFS_CUSTOMPACKEING_PATH', MYOFS_SURL.'/pages/custom-packaging');
define( 'MYOFS_MARKETINGMATERIAL_PATH', 'https://wp.ccdemostore.com/dashboard/application/uploads/marketing/');
define( 'MYOFS_MARKETINGMATERIAL_BANNER_PATH', 'https://www.fiverr.com/categories/online-marketing?source=category_tree');

?>