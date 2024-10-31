<div class="wrap">	
	<div class="myofs-layout" id="myofs-layout__notice-list"></div>
	<div class="apploader"><div class="loader"></div></div>
	<!-- page sidebar  -->
		<?php load_template(MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-sidebar-page.php');?>
	<!-- end sidebar -->
	<div class="page-content-wrapper">
		<div id="firstt"><div id="snackbar"></div></div>
		<div class="page-content">
			<div class="page-bar">
				<h1 class="wp-heading-inline" id="dynamic_heading_title"><?php esc_html_e('My Inventory');?></h1>
			</div>
			<div class="myofs_inventory" id="myofs_myinvproductdta">
				<?php do_action('myofs_my_inventory_products');?>
			</div>
		</div>
	</div>
	
</div>
<div class="myofs_plugin_version">
	<span><?php esc_html_e(MYOFS_FOOTER); ?></span>
</div>