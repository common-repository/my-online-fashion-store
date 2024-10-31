<?php
$MYOFS_Api   = new MYOFS_API();
$marketing   = $MYOFS_Api->GetMarketingMaterial();
$marketingdata = $marketing['data'];
$callimgmethod =  $MYOFS_Api->DynamicImagesApi();
$getimage = $callimgmethod['data']['image']['marketing']; 
?>
<div class="wrap">	
	<!-- page sidebar  -->
		<?php load_template(MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-sidebar-page.php');?>
	<!-- end sidebar -->
	<div class="page-content-wrapper">	
		<div class="page-content">	
		    <div class="page-bar">
				<h1 class="wp-heading-inline" id="dynamic_heading_title"><?php esc_html_e('Marketing Material','my-online-fashion-store');?></h1>
				<p class="header_img">
					<img src="<?php echo esc_url($getimage['free_banner']); ?>">
				</p>
			</div>	
			<div class="row" id="product_data">
				<?php if($marketingdata['status'] == 1){ ?>
					<ul class="marketing_material">
						<?php 						
						foreach($marketingdata['data'] as $marketing_s)
						{ 
							printf(
								wp_kses(
									__('<li><a href="%1$s" download="%2$s" target="_blank" ><span>%3$s</span>%4$s</a></li>','my-online-fashion-store'),
									array(
										'li' => array(),
										'a' => array(
											'href' => array(),
											'class' => array(),
											'download'=>array(), 
											'target'  =>array(), 
										),
										'span' => array()
									)
								),
								$marketingdata['url'].$marketing_s['banner_file'],
								$marketing_s['banner_file'],
								strtoupper($marketing_s['b_name']),
								'DOWNLOAD FILE'
							);
						} ?>
					</ul>	
				<?php } ?>
				<div class="marketing_info">
					<?php if (isset($getimage['marketing_info']) && !empty($getimage['marketing_info'])) {
						foreach($getimage['marketing_info'] as $key => $banner){ ?>
							<div class="check_banner">
								<?php if(isset($banner['link']) && !empty($banner['link'])){?>
									<a href="<?php echo esc_url($banner['link']);?>" target="_blank">
								<?php }?>
									<img src="<?php echo esc_url($banner['url']);?>">
								<?php if(isset($banner['link']) && !empty($banner['link'])){?>
									</a> 
								<?php }?>					
							</div>
						<?php }
					}?>
					<h3>
						<a href="<?php echo (MYOFS_MARKETINGMATERIAL_BANNER_PATH.'&utm_source=71310&utm_medium=cx_affiliate&utm_campaign=&cxd_token=71310_2868713&show_join=true');?>" target="_blank"><?php esc_html_e('Find Freelance Services For Your Business Marketing Needs Today!');?></a>
					</h3>
					<p>
						<?php esc_html_e ('Access','my-online-fashion-store');?> 	<a href="<?php echo esc_url(MYOFS_MARKETINGMATERIAL_BANNER_PATH.'&utm_source=71310&utm_medium=cx_affiliate&utm_campaign=&cxd_token=71310_2868720&show_join=true');?>" target="_blank"><?php esc_html_e ('FIVERR.com','my-online-fashion-store');?></a>
						<?php esc_html_e (' for an assortment of marketing services including SEO, SOCIAL MEDIA MARKETING, SOCIAL MEDIA ADVERTISING, CONTENT MARKETING, SEM, VIDEO MARKETING, EMAIL MARKETING, E-COMMERCE MARKETING, WEB TRAFFIC, INFLUENCER MARKETING, MOBILE MARKETING & ADVERTISING and much more.','my-online-fashion-store');?></p>
					<h4>
						<?php esc_html_e('Find a qualified freelances no matter what your budget is,', 'my-online-fashion-store');?> 
						<a href="<?php echo esc_url(MYOFS_MARKETINGMATERIAL_BANNER_PATH.'&utm_source=71310&utm_medium=cx_affiliate&utm_campaign=&cxd_token=71310_2868720&show_join=true');?>" target="_blank"><?php esc_html_e('CLICK HERE FOR MORE INFO!', 'my-online-fashion-store');?></a>
					</h4>
					<?php if (isset($getimage['banners']) && !empty($getimage['banners'])) {
						?>
						<ul>
							<?php foreach($getimage['banners'] as $key => $banner){ ?>
								<li>
									<a href="<?php echo esc_url($banner['link']);?>" target="_blank">
										<img src="<?php echo esc_url($banner['url']);?>">
									</a>						
								</li>
							<?php }?>
						</ul>
						<?php
					}?>
						
				</div>
			</div>
		</div>
	</div>	
</div>
<div class="myofs_plugin_version">
	<span><?php esc_html_e(MYOFS_FOOTER); ?></span>
</div>