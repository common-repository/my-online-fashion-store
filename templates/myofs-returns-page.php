<div class="wrap">	
	<!-- page sidebar  -->
	<?php load_template(MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-sidebar-page.php');?>
	<!-- end sidebar -->
	<div class="page-content-wrapper" style="position: relative;">
		<div class="page-content" style="min-height: 1312px;">
			<div class="page-bar">
				<h1 class="wp-heading-inline" id="dynamic_heading_title"><?php esc_html_e('Returns');?></h1>
			</div>
			<div class="row" style="margin:0px">
				<div class="myofs_return" id="myofs_returndata" style="margin:0px">
					<div class="myofs-layout" id="myofs-layout__notice-list"></div>
					<div class="col-md-12">
						<div class="return-header">
							<div class="title"> 
								<i class="fa fa-truck"></i> 
								<?php esc_html_e('FREE RETURNS');?>
							</div>
						</div>
						<p>
							<?php esc_html_e( 'We will accept all authorized returns.'); ?>
						</p>
						<p>
							<?php esc_html_e( 'To get an authorization number please fill out the return form below.'); ?>
						</p>
						<p>
							<?php esc_html_e( 'Once approved we will email you a prepaid label you can forward to your customer.' ); ?>
						</p>
						<p>
							<?php esc_html_e( 'Your customer will have 30 days to send the item back for full refund.' ); ?>
						</p>
						<p>
							<?php esc_html_e( 'Please note free return labels are available for items being shipped within 48 continental states only.'); ?>
						</p>

						<form action="" class="free_returns" id="free_returns" name="free_returns" method="post" novalidate="novalidate">
							<div class="form-body">
								<div class="row">	
									<div class="col-md-7">
										<div class="form-group">						
											<label for="contactperson" class="col-md-12">
												<?php esc_html_e( 'Contact Person:', 'my-online-fashion-store' ); ?>
												<span class="required"> *</span>
											</label> 
											<div class="col-md-12">	
											  <input id="contactperson" name="contactperson" value="" class="form-control" type="text">
											</div>	
										</div>	
									</div>
								</div>
							</div>
							<div class="row">	
								<div class="col-md-7">
									<div class="form-group">					
										<label for="email" class="col-md-12"><?php esc_html_e( 'E-mail Address:', 'my-online-fashion-store' ); ?>
											<span class="required"> *</span>
										</label> 
										<div class="col-md-12">	
											<input id="email" name="email" value="" class="form-control" type="email">								
										</div>							
									</div>	
								</div>
							</div>
							<div class="row">	
								<div class="col-md-7">
									<div class="form-group">
										<label for="ordernumber" class="col-md-12">
											<?php esc_html_e( 'Order Number:', 'my-online-fashion-store' ); ?>
											<span class="required"> *</span>
										</label> 
										<div class="col-md-12">	
											<input id="ordernumber" name="ordernumber" value="" class="form-control" type="number">								
										</div>							
									</div>	
								</div>
							</div>
							<div class="row">	
								<div class="col-md-7">
									<div class="form-group">
										<label for="itemqty" class="col-md-12">
											<?php esc_html_e( 'ID, Number of items returning and Quantity:', 'my-online-fashion-store' ); ?>
											<span class="required"> *</span>
										</label> 
										<div class="col-md-12">						
											<input id="itemqty" name="itemqty" value="" class="form-control" type="number">
										</div>							
									</div>	
								</div>
							</div>
							<div class="row">	
								<div class="col-md-7">
									<div class="form-group">
										<label class="col-md-12">
											<?php esc_html_e( 'Reason For Return:', 'my-online-fashion-store' ); ?>
										<span class="required"> *</span>
										</label> 
										<div class="col-md-12">
											<textarea id="reasonreturn" name="reasonreturn" class="form-control"></textarea>								
										</div>							
									</div>	
								</div>
							</div>
							<div class="row">	
								<div class="col-md-7">
									<div class="form-group">
										<label class="col-md-12">
											<?php esc_html_e( 'Additional Note:', 'my-online-fashion-store' ); ?>
										</label> 
										<div class="col-md-12">
											<textarea id="additionaln" name="additionaln" value="" class="form-control"></textarea>	
											<span class="help-text">
												<?php esc_html_e( 'Original shipping fee is non refundable:', 'my-online-fashion-store' ); ?>
											</span>
										</div>							
									</div>	
								</div>
							</div>	
							<div class="form-actions fluid" >
								<div class="row">
									<div class="col-md-6">
										<button type="submit" class="btn sbold green" id="submit_btn_click" name="submit">
											<i class="fa fa-check"></i>
											<?php esc_html_e( 'Submit', 'my-online-fashion-store' ); ?>
										</button>
										<input type="reset" class="btn default" id="reset1" value="Reset">
										<div class="spin_main" style="display: none">
											<div class="spin"></div>
										</div>
									 </div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="myofs_plugin_version">
	<span><?php esc_html_e(MYOFS_FOOTER); ?></span>
</div>