<div class="wrap">	
	<!-- page sidebar  -->
		<?php load_template(MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-sidebar-page.php');?>
	<!-- end sidebar -->
	<div class="page-content-wrapper">	
		<div class="page-content">
			<div class="page-bar">
				<h1 class="wp-heading-inline" id="dynamic_heading_title"><?php esc_html_e('My Account');?></h1>
			</div>
            <div class="tab-pane active my_account">
			    <div class="portlet light bg-inverse">
			       <div class="portlet-body form">
			            <div class="form-horizontal">			          
			               <?php do_action('myofs_myaccount_content');?>			              
			            </div>	

			        </div>
		      	</div>
		    </div>
		</div>
    </div>
</div>	
<div class="myofs_plugin_version">
	<span><?php esc_html_e(MYOFS_FOOTER); ?></span>
</div>