<?php
/**
 *  Maintenance page
 *
 * @package My Online Fashion Store\ Maintenance
 */
defined( 'ABSPATH' ) || exit;
/**
 * MYOFS_MYOFS_Maintenance Class
 *
 */
class MYOFS_Maintenance extends MYOFS_API{

	function __construct() {
		add_action('myofs_maintenance_mode', array( &$this, 'GetMaintenanceData' ), 10,3);
	}
    public function GetMaintenanceData(){
        $result = MYOFS_API::MaintenanceMode();
        if(array_key_exists(200,$result['status_code'])){
            $data = $result['data']['response']; 
            $check_maintenance = $data['mode'];
            $image_url = $data['img'];
            if ($check_maintenance == 1) {
                ?>
                <div class="maintenance_cont">
                    <div class="mtn_left">
                        <img src="<?php echo esc_url($image_url);?>">
                    </div>
                    <div class="mtn_right">
                        <h1><?php echo esc_html_e( 'We’ll be back soon!', 'my-online-fashion-store' ); ?></h1>
                        <p><?php echo esc_html_e( "Sorry for the inconvenience but we're performing some maintenance at the moment. If you need to you can always", 'my-online-fashion-store' ); ?> <a href="mailto:info@ccwholesaleclothing.com"><?php echo esc_html_e( "contact us", 'my-online-fashion-store' ); ?> </a>, <?php echo esc_html_e( "otherwise we'll be back online shortly!", 'my-online-fashion-store' ); ?></p>
                        <p>— <a href="mailto:info@ccwholesaleclothing.com"><?php echo esc_html_e( "CCWholesaleclothing Team", 'my-online-fashion-store' ); ?></a></p>
                    </div>
                </div>
                <?php
            }
        }
    }
}