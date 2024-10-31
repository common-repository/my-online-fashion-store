<?php
$expire = get_option('myofs_opt_expire'); 
?>
<div class="wrap"> 
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <?php if ($expire != FALSE) { ?>
    <div class="warning notice is-dismissible">
    	<p><?php esc_html_e( $expire, 'my-online-fashion-store' ); ?>
    	<a href="<?php echo esc_url(MYOFS_PLAN_URL);?>" target="_blank"><?php esc_html_e( 'CLICK HERE TO UPGRADE PLAN', 'my-online-fashion-store' ); ?></a>
    </p>
    </div>
    <?php } ?>
	<div class="myofs-layout" id="myofs-layout__notice-list"></div>
    <form method="POST" action="" name="license_key" id="myofs-activate-keys">
	    <div id="myofs-license-container">
            <h2><?php esc_html_e( 'Active License', 'my-online-fashion-store' ); ?></h2> 
        	<table class="form-table">  
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Email', 'my-online-fashion-store' ); ?></th>						
						<td><input type="text" name="activation_email" value=""></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'License Key', 'my-online-fashion-store' ); ?></th>						
						<td><input type="text" name="activation_key" value=""></td>
					</tr>
					<tr>
						<th scope="row" colspan="3">
							<input type="hidden" name="action" value="myofs_activate_keys">
							<input type="submit" name="submit" value="Activate" class="button button-primary">
							<div class="spin_main" style="display: none">
								<div class="spin"></div>
							</div>
						</th>
					</tr>
					<?php if ($expire == FALSE) { ?>
						<tr>
							<th scope="row" colspan="3">
								<a href="<?php echo esc_url(MYOFS_PLAN_URL);?>" target="_blank"><?php esc_html_e( 'CLICK HERE TO JOIN THE PROGRAM AND RECEIVE YOUR ACTIVATION KEY ( $29/month with 14 day free trial )', 'my-online-fashion-store' ); ?></a>
							</th>
						</tr>
					<?php } ?>
				</tbody>
			</table>
	    </div>
    </form>
	<input value="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" id="admin-url" class="admin-url" type="hidden" />

</div><!-- .wrap -->
<div class="myofs_plugin_version">
	<span><?php esc_html_e(MYOFS_FOOTER); ?></span>
</div>