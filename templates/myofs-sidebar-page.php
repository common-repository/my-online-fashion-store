<?php
$myofs_api = new MYOFS_API();
$callmethod =  $myofs_api->DynamicImagesApi();
$getimage = $callmethod['data']['image']['left_bar']; 

$cpage = sanitize_text_field( filter_input( INPUT_GET, 'current_page' ) );
$lm   = sanitize_text_field( filter_input( INPUT_GET, 'limit' ) );
$srch = sanitize_text_field( filter_input( INPUT_GET, 'search' ) );
$srby = sanitize_text_field( filter_input( INPUT_GET, 'sortby' ) );

$catid = sanitize_text_field( filter_input( INPUT_GET, 'category_id' ) );
$catnm = sanitize_text_field( filter_input( INPUT_GET, 'category_name' ) );
$mnu = sanitize_text_field( filter_input( INPUT_GET, 'menu' ) );

$page = isset($cpage) && !empty($cpage) ? $cpage : '1';
$limit = isset($lm) && !empty($lm) ? $lm : '30';
$search = isset($srch) && !empty($srch) ? base64_decode($srch) : '';
$sortby = isset($srby) && !empty($srby) ? $srby : '';
$optval = isset($sortby) && !empty($sortby) ? trim( strtolower($sortby) ) : 'not';
$filterby = array('nto'=>'Newest to Oldest','otn'=>'Oldest to Newest','clh'=>'Cost: Low to High','chl'=>'Cost: High to Low');
$category_id = isset($catid) && !empty($catid) ? $catid : '';
$menu = isset($mnu) && !empty($mnu) ? $mnu : '';
$menucls1 = isset($menu) && !empty($menu) ? sanitize_html_class('sidebar-menu-expand') : '';
$menucls2 = isset($menu) && !empty($menu) ? sanitize_html_class('sidebar-expand') : sanitize_html_class('sidebar-collapse');

$chechv   = $myofs_api->CheckPluginVersion();
if ($chechv['data']['status'] == 0) {
	?>
	<div class="myofs-layout" id="myofs-layout__notice-list"><div class="update-message notice inline notice-warning notice-alt"><p>There is a new version of My online fashion store <?php echo $chechv['data']['message']; ?> available.Please <a href="<?php echo admin_url('plugins.php');?>">upgrade now</a>.</p></div>
	<?php
}
?>
<div class="page-sidebar-wrapper <?php echo $menucls1; ?>" id="myofs-sidebar">
	<div class="page-sidebar navbar-collapse collapse">
		<div id="sidebar-content">
		 	<span id="topsidebar-icon" class="sidebar-icon"></span>
			<ul class="page-sidebar-menu  page-header-fixed <?php echo $menucls2;?>">
				<li class="sidebar-toggler-wrapper" id="sidebar-collapse" aria-expanded="true">
                    <div class="sidebar-toggler">
                        <span class="sidebar-collapse-icon"></span>
                        <span class="sidebar-collapse-label"><?php esc_html_e('Collapse Sidebar'); ?></span>
                    </div>
				</li>
				<div id="cat_disp">
					<?php 
						if (isset($catnm) && !empty($catnm) ) {?>
							<ul class='selected-cat'>
								<li>
									<a href='javascript:void(0);' id='clear_category'><?php esc_html_e(trim($catnm),'my-online-fashion-store');?>   
										<i class='icon-close'></i>
									</a>
								</li>
							</ul>
							<?php 
						}
					?>
				</div>
				<!-- Categories -->
				<div class="side-panels">	
					<li class="heading Categories">
                        <h3 class="side-panel-title"><?php esc_html_e( 'Categories','my-online-fashion-store'); ?></h3>
                    </li>
					<div id="category_data">
						<?php do_action('myofs_sidebar_categories');?>
					</div>	
				</div>
				<!-- Search Input -->
				<div class="side-panels">	
					<li class="heading Search">
                       <h3 class="side-panel-title"><?php esc_html_e( 'Search','my-online-fashion-store'); ?></h3>
                    </li>
					<div>						
                        <li class="sidebar-search-wrapper">
                            <div class="sidebar-search" id="inventory_search">
                            	<?php if (isset($search) && !empty($search)) {?>
                                	<a href="javascript:;" class="btn submit" id="clear_search_inventory">
                                   		<?php esc_html_e('Clear','my-online-fashion-store'); ?> <i class="fa fa-close"></i>
                               		</a>
                               	<?php } ?>
                               <div class="input-group">
                                   <input type="text" class="form-control" name="serach" id="search" placeholder="Search..." value="<?php if (isset($search) && !empty($search)) { echo base64_decode($search);}?>">
                                   <span class="input-group-btn">
                                       <a href="javascript:;" class="btn submit" id="search_filter">
                                           <i class="fa fa-search"></i>
                                       </a>										  
                                   </span>
                               </div>
                           	</div>
                        </li>
					</div>
				</div>
				<!-- Filter By -->
				<div class="side-panels">
					<li class="heading Filter">
						<h3 class="side-panel-title"><?php esc_html_e( 'Filter By','my-online-fashion-store'); ?></h3>
					</li>
					<div>
						<li class="myofs-filter">
							<div class="input-group input-medium date date-picker">
								
								<select id="filter_by" name="filter_by" class="form-control">
									<?php
										foreach ($filterby as $key => $value) {
											printf(
												wp_kses(
													__('<option value="%1$s" %2$s>%3$s</option>','my-online-fashion-store'),
													array(
														'option' => array(
															'value' => array()
														)
													)
												),
												$key,
												selected( $optval, $key ),
												$value
											);
										}
									?>
								</select>
							</div>
						</li>
					</div>
				</div>
				<!-- Banner Images -->
				<?php if(isset($getimage['left_images']) && !empty($getimage['left_images'])){ 
					$i = 0;
					foreach( $getimage['left_images'] as $key => $gimg ){
						?>
						<div class="side-panels side-banner" id="side_banner<?php echo $i; ?>">
							<?php if( $key == 'upgrade_save' ){ ?>
								<a href="<?php echo esc_url(MYOFS_PLANUPGRADE_PATH);?>">
							<?php }else if(isset($gimg['link']) && !empty($gimg['link'])){ ?>
								<a href="<?php echo esc_url($gimg['link']);?>">
							<?php } ?>
								<img src="<?php echo esc_url( $gimg['url']);?>">
							<?php if( $key == 'upgrade_save' || (isset($gimg['link']) && !empty($gimg['link'])) ){ ?>
								</a>
							<?php } ?>
						</div>
						<?php
						$i++;
					}
				}
				?>
				<!-- Helpful Tips & Update -->
				<div class="LeftCarousel"  id="sb-slider"> 
					<div class="LeftCarousel_inner"> 
						<?php if (isset($getimage['left_slider']) && !empty($getimage['left_slider'])) {
							foreach($getimage['left_slider'] as $key => $slider){
									?>
									<div class="LeftCarousel_grid"><img src="<?php echo esc_url($slider);?>" alt="<?php esc_html_e( $key,'my-online-fashion-store');?>"></div>
									<?php
							}
						}?>				
		
					</div>
				</div>
			</ul>
			<input value="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" id="admin-url" class="admin-url" type="hidden" />
			<input type="hidden" name="page" id="page" value="<?php echo esc_attr($page); ?>">
			<input type="hidden" name="limit" id="limit" value="<?php echo esc_attr($limit); ?>">
			<input type="hidden" name="sortby" id="sortby" value="<?php echo esc_attr($sortby); ?>">
			<input type="hidden" name="category_id" id="category_id" value="<?php echo esc_attr($category_id);?>">
			<input type="hidden" name="myofs_plugin_url" id="myofs_plugin_url" value="<?php echo esc_url(get_admin_url('',MYOFS_PLUGIN_URL)); ?>">
		</div>
	</div> 
</div>