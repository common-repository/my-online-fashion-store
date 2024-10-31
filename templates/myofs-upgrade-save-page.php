<?php 
$MYOFS_Api   = new MYOFS_API();
$callimgmethod =  $MYOFS_Api->DynamicImagesApi();
$getimage = $callimgmethod['data']['image']['upgarde']; 
?>
<div class="myofs-layout" id="myofs-layout__notice-list">
</div>
<div class="wrap">	
	<!-- page sidebar  -->
		<?php load_template(MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-sidebar-page.php');?>
	<!-- end sidebar -->
	<div class="page-content-wrapper">	
	    <div class="page-content plan_info">	
		    <div class="page-bar">
				<h1 class="wp-heading-inline" id="dynamic_heading_title"><?php esc_html_e('Upgrade & Save','my-online-fashion-store');?></h1>
			</div>	
			<a class="upgradesave_bnnr_img" href="<?php echo esc_url(MYOFS_PLAN_URL);?>" target="_blank">
				<img src="<?php echo esc_url($getimage['upgarde_image']); ?>" alt="UPGRADE SAVE PLAN">
			</a>	
			<h1><?php esc_html_e('BONUS #1','my-online-fashion-store');?></h1>
			<p><?php esc_html_e('UPGRADE TO ANNUAL PLAN AND ENJOY THE FOLLOWING BENEFITS','my-online-fashion-store');?></p>
			<p><?php esc_html_e('SAVE $240 ON TOTAL MONTHLY MEMBERSHIP FEES ( REGULAR UNLIMITED MONTH TO MONTH PLAN COST 12 X $29 = $348 ) YOU PAY ONE TIME ONLY $108.00','my-online-fashion-store');?></p>
			<p><?php esc_html_e('ENJOY 20% DISCOUNT ON ALL PRODUCTS IN OUR INVENTORY FOR THE ENTIRE YEAR INCLUDING ALL ITEMS FOR SALE CATEGORY','my-online-fashion-store');?></p>

			
			<h1><?php esc_html_e('BONUS #2','my-online-fashion-store');?></h1>
			<p><?php esc_html_e('UPGRADE TO ANNUAL PLAN PLAN AND ENJOY AN ADDITIONAL DISCOUNT ON ALL PRODUCTS IN OUR INVENTORY. INCREASE YOUR PROFIT MARGIN BY 20%','my-online-fashion-store');?></p>
			<p><?php esc_html_e('ANNUAL PLAN - ENJOY 20% DISCOUNT ON ALL PRODUCTS IN OUR INVENTORY','my-online-fashion-store');?></p>
			<p><?php esc_html_e('EXAMPLE. REG COST PRODUCT X = $20, YOUR COST WILL BE $16','my-online-fashion-store');?></p>

			<h1 class="more_info_plan">
				<a href="<?php echo esc_url(MYOFS_PLAN_URL);?>" target="_blank"><?php esc_html_e('<< Click For More Info >>','my-online-fashion-store');?></a>
			</h1>
	    </div>
  	</div>
</div>
<div class="myofs_plugin_version">
	<span><?php esc_html_e(MYOFS_FOOTER,'my-online-fashion-store'); ?></span>
</div>	