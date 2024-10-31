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
				<h1 class="wp-heading-inline" id="dynamic_heading_title"><?php esc_html_e( 'All Inventory', 'my-online-fashion-store' ); ?></h1>
			</div>
			<div class="myofs_inventory productdta" id="myofs_productdta">
				<?php do_action('myofs_all_inventory_products');?>
			</div>
		</div>
	</div>
	<!-- product add to wc popup -->
	<div  class="modal fade" id="addtowc_modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none; padding: 50px;">
		<div class="apploaderp"><div class="loader"></div></div>
	  	<div class="modal-dialog" role="document">
		    <div class="modal-content">
			  	<div class="modal-header">
			        <h4 class="modal-title"><?php esc_html_e( 'Add Product to Woocommerce', 'my-online-fashion-store' ); ?></h4>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			  	</div>
		      	<div class="modal-body"> 
					<div class="myofs-layout" id="myofs-layout__notice-list"></div>

		      		<form id="addtostore_form">
						<div class="form-group row" id="collection_select">
							<label class="col-sm-4 col-form-label"><?php esc_html_e( 'COLLECTION', 'my-online-fashion-store' ); ?></label>
							<div class="col-sm-4" id="myofs_storecat">
								<!-- load categories -->							
							</div>
							<div class="col-sm-4 addcatbtn" >
								<a class="add_categorytostore" href="javascript:void(0);">+ <?php esc_html_e( 'Add Collection', 'my-online-fashion-store' ); ?></a>
							</div>
						</div>	
						<div class="form-group row" id="collection_manully" style="display:none">
							<label class="col-sm-4 col-form-label"><?php esc_html_e( 'COLLECTION', 'my-online-fashion-store' ); ?></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="categoryid_nm" id="categoryid_nm" >
								<span><?php esc_html_e( 'Add categories by comma separator.ex:accessories,clothes', 'my-online-fashion-store' ); ?></span>
							</div>
							<div class="col-sm-4">
								<a class="gocategorylist" href="javascript:void(0);"><?php esc_html_e( 'Go To Collection', 'my-online-fashion-store' ); ?>
								</a>
							</div>
						</div>				
					  	<div class="form-group row">
							<label class="col-sm-4 col-form-label"><?php esc_html_e( 'CHANGE PRICE', 'my-online-fashion-store' ); ?></label> 
							<div class="col-sm-8">
								<select class="form-control" id="change_price" name="change_price" onchange="jQuery('#change_price_t').html(this.value);">
									<option value="up"><?php esc_html_e( 'UP', 'my-online-fashion-store' ); ?> (+)</option>
									<option value="down"><?php esc_html_e( 'DOWN', 'my-online-fashion-store' ); ?> (-)</option>
								</select>
							</div>
					  	</div>
					  	<div class="form-group row">
							<label class="col-sm-4 col-form-label"><?php esc_html_e( 'MARK ', 'my-online-fashion-store' ); ?> <span id="change_price_t" style="text-transform: uppercase;"><?php esc_html_e( 'UP', 'my-online-fashion-store' ); ?></span><?php esc_html_e( ' PRICE', 'my-online-fashion-store' ); ?></label> 
							<div class="col-sm-8">
								<select class="form-control" id="markup_price" name="markup_price" >
									<option value="fix"><?php esc_html_e( 'FIX', 'my-online-fashion-store' ); ?></option>
									<option value="percentage"><?php esc_html_e( 'PERCENTAGE', 'my-online-fashion-store' ); ?> (%)</option>
								</select>
							</div>
					  	</div>
					  	<div class="form-group row">
							<label class="col-sm-4 col-form-label"><?php esc_html_e( 'AMOUNT', 'my-online-fashion-store' ); ?></label>
							<div class="col-sm-8">
								<input class="form-control" id="amount" name="amount" type="number" min="1" value="">
							</div>
					  	</div>					
					  	<p class="text-center"><b><?php esc_html_e( ' BY DEFAULT RETAIL PRICE IS SET AT DOUBLE YOUR COST. EXAMPLE IF YOUR COST IS $5 THE RETAIL PRICE BY DEFAULT IS SET AT $10 TO MAKE CHANGES YOU CAN DO IT HERE OR JUST CLICK ON ADD TO WOOCOMMERCE.', 'my-online-fashion-store' ); ?></b></p>
					  	<div class="form-group row" id="pro_default_tag">
							<label class="col-sm-4 col-form-label"><?php esc_html_e( 'DEFAULT TAGS', 'my-online-fashion-store' ); ?>
								<span class="tag_msg"><?php esc_html_e( 'You can Edit/Add Product Tags here', 'my-online-fashion-store' ); ?> &gt;&gt;</span>
							</label>
							<div class="col-sm-8">
								<!-- load tags -->
								<textarea rows="" cols="" class="form-control" name="modal_pro_tags" id="modal_pro_tags"></textarea>
							</div>
					  	</div>
					  	<div class="form-group row">
						  	<div class="col-sm-6">
								<div class="form-check">
								  <input class="form-check-input" type="checkbox" name="p_msrp" id="p_msrp">
								  <label class="form-check-label"><b><?php esc_html_e( 'INCLUDE MSRP', 'my-online-fashion-store' ); ?></b></label>
								</div>
							</div>
						  	<div class="col-sm-6">
								<div class="form-check">
								  <input class="form-check-input" type="checkbox" name="p_inc_sizechart" id="p_inc_sizechart">
								  <label class="form-check-label"><b><?php esc_html_e( 'INCLUDE SIZE CHART', 'my-online-fashion-store' ); ?></b></label>
								</div>
							</div>
						</div>
						<!--<div class="form-group row" >
							<div class="col-sm-6">
								<div class="form-check">
								  <input class="form-check-input" type="checkbox" name="p_measuremntinfo" id="p_measuremntinfo">
								  <label class="form-check-label"><b><?php //esc_html_e( 'INCLUDE MEASUREMENT INFO', 'my-online-fashion-store' ); ?></b></label>
								</div>
							</div>
						</div>-->
					  	<input type="hidden" name="product_id" id="modal_pro_id">
						<input type="hidden" name="combain_product" id="combain_product">
					  	<input type="hidden" name="product_name" id="modal_pro_name">
					  	<input type="hidden" name="product_stock" id="modal_pro_stock">
					  	<input type="hidden" name="product_sku" id="product_sku" >
					  	<input type="hidden" name="collection_type" id="collection_type">
					  	<input type="hidden" name="action" id="action" value="productaddtowcstore">
					</form>
				</div>
		      	<div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="addtocancle"><?php esc_html_e( 'Cancel', 'my-online-fashion-store' ); ?></button>
			        <button type="button" id="addtomyofs_btn" class="btn btn-primary"><?php esc_html_e( 'Add To Woocommerce', 'my-online-fashion-store' ); ?></button>
		      	</div>
		    </div>
	  	</div>
	</div>
</div>
<div class="myofs_plugin_version">
	<span><?php esc_html_e(MYOFS_FOOTER); ?></span>
</div>