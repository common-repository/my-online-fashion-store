<?php
/**
 *  All Inventory and My Inventory Page
 *
 * @package My Online Fashion Store\ All Inventory
 * @package My Online Fashion Store\ My Inventory
 */
defined( 'ABSPATH' ) || exit;
/**
 * MYOFS_All_Inventory Class
 *
 * Provides a load inventory/my inventory products and load sidebar categories.
 */
class MYOFS_All_Inventory extends MYOFS_API{
	private $wpdb;
	function __construct() {
		global $wpdb;
		$this->myofs_tbl = $wpdb->prefix .MYOFS_DB_TABLE;
        	$this->wpdb = $wpdb;
		add_action('myofs_sidebar_categories', array( &$this, 'GetCategoriesListing' ), 10,3);
		add_action('myofs_all_inventory_products', array( &$this, 'GetInventoryProducts' ), 10,3);
		add_action('myofs_my_inventory_products', array( &$this, 'GetMyInventoryProducts' ), 10,3);
	}
	/*
	* Get All Inventory Product On the Plugin Inventory Page
	* @Used API:GetAllProducts
	* @Used Function:GetQueryStringData()
	* InventoryProductsAfterContent()
	* InventoryProductsBeforeContent()
	* InventoryProductsMiddleContent()
	* NoInventoryProductFound()
	* RemoveProductConfirmContent()
	*/	
	function GetInventoryProducts(){
		$echo = "selected";		
		$qry_str = $this->GetQueryStringData();	
		$page = $qry_str['page'];
		$limit = $qry_str['limit'];		
		$result = MYOFS_API::GetAllProducts($qry_str);
		if(array_key_exists(200,$result['status_code'])){
			$ProductData = $result['data'];	
			$total_row = $ProductData['product_count'];
			if (empty($ProductData['data']) && $ProductData['status'] == 0) {
				$this->NoInventoryProductFound();			
			} else {
				$this->StoreMyinvProductIds();
				$this->InventoryProductsBeforeContent($total_row,$limit,$page);?>
				<div class="row inventory-lists">
					<?php
					foreach ($ProductData['data'] as $allproduct) {
						$itemadded = $allproduct['inventory'] == 'yes' ? sanitize_html_class('item_added_pro_c') : '';
						?>
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
							<div class="portlet light portlet-fit bordered <?php echo $itemadded; ?> ">								
								<?php
								echo $this->InventoryProductsMiddlebeforeContent($allproduct);
								if ($allproduct['inventory'] == 'yes') {
									?>											
									<div class="pro_item_added"><span><?php echo esc_html_e( 'Item Added', 'my-online-fashion-store' ); ?></span></div>
									<?php
								}
								$this->InventoryProductsMiddleafterContent($allproduct);
								if ($allproduct['inventory'] == 'yes') {
									$this->RemoveProductConfirmContent($allproduct['product']['id']);
								}
								?>								
							</div>
						</div>
						<?php
					}?>
				</div>
				<?php
				$this->RemoveMultipleProductConfirmContent();
				$this->InventoryProductsAfterContent($total_row,$limit,$page);
			}
		}else{
			$keycode = implode('',array_keys($result['status_code']));
			esc_html_e( $keycode.' - '.$result['status_code'][$keycode], 'my-online-fashion-store' );
						
		}
	}
	/*
	* Get All Added Inventory Products to woocommerce store  On the Plugin My Inventory Page
	* @Used API:GetMyInventory
	* @Used Function:GetQueryStringData()
	* InventoryProductsAfterContent()
	* InventoryProductsBeforeContent()
	* InventoryProductsMiddleContent()
	* NoInventoryProductFound()
	* RemoveProductConfirmContent()
	*/	
	function GetMyInventoryProducts(){		
			
		$qry_str = $this->GetQueryStringData();	
		$page  = $qry_str['page'];
		$limit = $qry_str['limit'];			

		$result  = MYOFS_API::GetMyInventory($qry_str);
		if(array_key_exists(200,$result['status_code'])){
			$ProductData = $result['data'];	
			$total_row   = $ProductData['product_count'];
			if (empty($ProductData['data']) && $ProductData['status'] == 0) 
			{
				$this->NoInventoryProductFound();
			} else {
				$this->StoreMyinvProductIds();
				$this->InventoryProductsBeforeContent($total_row,$limit,$page);?>
				<div class="row inventory-lists">
					<?php
					foreach ($ProductData['data'] as $allproduct) {?>
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
							<?php
							if ($allproduct['inventory'] == 'yes'){?>
								<div class="portlet light portlet-fit bordered item_added_pro_c">
									<?php
										$this->InventoryProductsMiddlebeforeContent($allproduct);
										$this->InventoryProductsMiddleafterContent($allproduct);
										$this->RemoveProductConfirmContent($allproduct['product']['id']);?>
								</div>
								<?php
							}?>
						</div>
						<?php
					}?>
				</div>
				<?php
				$this->RemoveMultipleProductConfirmContent();
				$this->InventoryProductsAfterContent($total_row,$limit,$page);
			}
		}else{
			$keycode = implode('',array_keys($result['status_code']));
			esc_html_e( $keycode.' - '.$result['status_code'][$keycode], 'my-online-fashion-store' );			
		}
	}
	/*
	* Get Single Product Details 
	* Show on the Plugin All Inventory/My Inventory Detail Popup
	* @Used API:GetSingleProduct
	*/
	public function GetSingleProductData() {
		$return_arr = array();
		$productid  = sanitize_text_field( filter_input( INPUT_GET, 'productid'));
		$row_data   = MYOFS_API::GetSingleProduct($productid);
		if(array_key_exists(200,$row_data['status_code'])){
			$productdetails = $row_data['data']['data'];
			$image  = esc_url(MYOFS_ASSETS_URL.'images/placeholder.png');
			$allimage = $desc = $optname = $option = $options = $tag  = '';
			if (isset($productdetails) && !empty($productdetails)) {
					
				if (!empty($productdetails['product']['imageradio']) && isset($productdetails['product']['imageradio'])) {
					$image = $productdetails['product']['imageradio'];

				} else {
					if (!empty($productdetails['image'][0]['image']) && isset($productdetails['image'][0]['image'])) {
						$image = $productdetails['image'][0]['image'];
					}
				}
				if (isset($productdetails['image']) && !empty($productdetails['image'])) {			
					$allimage = $productdetails['image'];
				}
				if (isset($productdetails['option']) && !empty($productdetails['option'])) {
					$option = $productdetails['option'];
				}
				if (isset($productdetails['optionS']) && !empty($productdetails['optionS'])) {			
					
					$options = $productdetails['optionS'];
				}
				$tagarr = array();
				if (isset($productdetails['tag']) && !empty($productdetails['tag']) && count($productdetails['tag']) > 0) {
					for ($ti=0; $ti < count($productdetails['tag']) ; $ti++) { 
						array_push($tagarr, $productdetails['tag'][$ti]['category_name']);
					}				
					
				}
				if (isset($productdetails['product']['stock']) && $productdetails['product']['stock'] != '') {
					$stock = $productdetails['product']['stock'];
				}else{ $stock = 0; }
				if (isset($productdetails['optionname']) && !empty($productdetails['optionname'])) {
					$optname = $productdetails['optionname'];
				}
				if (isset($productdetails['product']['decc']) && !empty($productdetails['product']['decc'])) {
					$desc = base64_decode($productdetails['product']['decc']);
				}
				$discount_value = ($productdetails['product']['cost_price'] / 100) * 20;
				$discounted_price = $productdetails['product']['cost_price'] - $discount_value;

				$return_arr['status'] = 1;
				$return_arr['data']   = array(
						'id'  => $productdetails['product']['id'],
						'name' => $productdetails['product']['name'],
						'sku' => $productdetails['product']['sku'],
						'weight' => $productdetails['product']['weight'].' lb',
						'featured_image' => $image,
						'gallery_image' => $allimage,
						'stock' => $stock,
						'inventory' => $productdetails['inventory'],
						'price' => array(
							'your_cost_monthly_plan' => '$'.$productdetails['product']['cost_price'],
							'your_cost_annual_plan' => '$'.number_format($discounted_price, 2),
							'default_selling_price' => '$'.$productdetails['product']['price'],
							'msrp' => '$'.$productdetails['product']['retail_price'],
						),
						'optionname' => $optname,
						'option' => $option,
						'options' => $options,
						'description'=> $desc ,
						'tags' => $tagarr

					);	
				}else{
					$return_arr['status'] = 0;
					$return_arr['error']   = 'Product Detail not found';
					
				}
		}else{
			$keycode = implode('',array_keys($row_data['status_code']));
			$return_arr['status'] = 0;
			$return_arr['error']   = $keycode.' - '.$row_data['status_code'][$keycode];
													
		}
		echo json_encode($return_arr);		
		exit();
	}
	/*
	* Get All Dashboard Categories 
	* show on Categories on sidebar
	* @Used API:GetAllCategories
	*
	*/
	function GetCategoriesListing(){
		$result = MYOFS_API::GetAllCategories();
		if(array_key_exists(200,$result['status_code'])){
			$categoryData = $result['data']; 	
			if( $categoryData['status'] == 1 && isset($categoryData['data']) && !empty($categoryData['data']) ){
				echo '<ul class="mainultop">';
				$this->generateCategoryList($categoryData['data'],'sub-menu','arrow');	
				echo "</ul>";			
			}else{?>
				<span><?php esc_html_e( 'Category Not Found', 'my-online-fashion-store' ); ?></span>
				<?php
			}
		}else{
			$keycode = implode('',array_keys($result['status_code']));
			esc_html_e( $keycode.' - '.$result['status_code'][$keycode], 'my-online-fashion-store' );
		}
	}
	function generateCategoryList($categories,$clsName,$aowCls) {
		$i = 1;
	    foreach ($categories as $categoryValue) {
        		$categoryID   = $categoryValue["category_id"];
			$categoryName = $categoryValue["category_name"];
			$subcatcnt    = isset($categoryValue['subcategories']) && !empty($categoryValue['subcategories']) ? count($categoryValue['subcategories']) : '';
			$count   = isset($categoryValue["count"]) && !empty($categoryValue["count"]) ? $categoryValue["count"] : 0;
			$catcnt  = $count > 0 ? '(' . $count . ')' : '';
			$catarrw = $subcatcnt > 0 ? '<span class="'.$aowCls.'"></span>' : '';?>
	          <li class="myofs-nav-item">
	         		<a href="javascript:;" class="nav-link nav-toggle">
			        	<?php
			          printf(
			               wp_kses(
			               	__('<span class="title" id="%1$s">%2$s %3$s</span>%4$s', 'my-online-fashion-store'),
			               	array(
			                    	'span' => array(
			                        		'class' => array(),
			                        		'id' => array()
		                    		)
		                		)
			            	),
		            		$categoryID,
		            		$categoryName,
		           		$catcnt,
		            		$catarrw
			          );?>
	        		</a>
	        		<?php
				if (!empty($categoryValue['subcategories'])) { ?>
				  	<ul class="<?php echo $clsName;?>">
				  		<?php $this->generateCategoryList($categoryValue['subcategories'],'sub-menu'.$i,'arrow'.$i); ?>
			   		</ul>
				<?php }?>
			</li>
			<?php
			$i++;
	    }
	}
	/*
	* Get Product Tag and WC Categories 
	* show on add to woocommerce popup
	* @Used API:GetSingleProduct
	* GetStoreCategories
	*/
	public function GetTagCatgoryForAddPopup(){
		$return_arr   = array();
		$productid    = array_map( 'sanitize_text_field', wp_unslash( $_GET['productid'] ) );
		$product_type = sanitize_text_field( filter_input( INPUT_GET, 'product_type'));	
		if ( $product_type == 'single') {
			$pid_im = implode(",",$productid);
			if(isset($pid_im) && !empty($pid_im)){
				$getproductdata  = MYOFS_API::GetSingleProduct($pid_im);		
				if(array_key_exists(200,$getproductdata['status_code'])){
					$gettags = $getproductdata['data']['data'];
					$tagarr = array();		
					if (isset($gettags['tag']) && !empty($gettags['tag']) && count($gettags['tag']) > 0) {
						$tag = $gettags['tag'];
						foreach ($tag as $tagvalue) {
							array_push($tagarr, $tagvalue['category_name']);
						}
						$taglist = implode(",",$tagarr);
						$return_arr['data']['tags'] = $taglist;
					}else{					
						$return_arr['data']['tags'] = ' ';
					}
				}else{
					$keycode = implode('',array_keys($getproductdata['status_code']));
					$return_arr['data']['error'][] = $keycode.' - '.$getproductdata['status_code'][$keycode];								
				}
			}else{
				$return_arr['data']['error'][] = 'Something went wrong! product tags not get please try again after sometime';							
			}
		}
		$category_arr = array();
		$wcCategory   = MYOFS_API::GetStoreCategories();
		if(array_key_exists(200,$wcCategory['status_code'])){
			if ($wcCategory['data']['status'] == 1) {
				$getCategory  = $wcCategory['data']['data'];			
				if (isset($getCategory) && !empty($getCategory)) {				
					foreach ($getCategory as $catvalue) {
						$category_arr[] = array(
							'id'   => $catvalue['id'],
							'name' => $catvalue['name']
						);
					}			
				}
			}else{
				if (isset($wcCategory['data']['error_wcmsg']) && !empty($wcCategory['data']['error_wcmsg'])) {
					
					$return_arr['data']['error'][] = $wcCategory['data']['error_wcmsg'];
					$return_arr['data']['errorcode'][] = $wcCategory['data']['error_wccode'];
				}else{
					$return_arr['data']['error'][] = 'Category not found!';
				}
			}
		}else{
			$keycode = implode('',array_keys($wcCategory['status_code']));
			$return_arr['data']['error'][] = $keycode.' - '.$wcCategory['status_code'][$keycode];
						
		}
		$return_arr['status'] = 1;
		$return_arr['data']['category'] = $category_arr;
		echo json_encode($return_arr);		
		exit;
	}
	/*
	* Add Inventory Product from the Woocommerce Store
	* @Used API:AddProducttoStore	
	* @DB Table: MYOFS_DB_TABLE	
	*/	
    public function addProducTowcStore() {
             
         $return_arr = array();
         $return_arr['error']['product'] = '';
         $return_arr['error']['category'] = '';
         $return_arr['error']['amount'] = '';
         $return_arr['error']['api_error'] = '';
         $return_arr['status'] = '';

         $qry_str = array();
         $change_price = sanitize_text_field($_POST['change_price']);
         $markup_price = sanitize_text_field($_POST['markup_price']);
         $product_id = sanitize_text_field($_POST['product_id']);
         $modal_pro_tags = sanitize_text_field($_POST['modal_pro_tags']);
         $combain_product = sanitize_text_field($_POST['combain_product']);
         $collection_type = sanitize_text_field($_POST['collection_type']);
         $amnt = sanitize_text_field($_POST['amount']);
         $myofscat = '';
         if (isset($_POST['myofs_categoryid']) && !empty($_POST['myofs_categoryid'])) {

              $myofscat = array_map('sanitize_text_field', wp_unslash($_POST['myofs_categoryid']));
         }
         $mymlcat = sanitize_text_field($_POST['categoryid_nm']);
         if (isset($product_id) && !empty($product_id)) {
              $modal_pro_tags64 = '';
              //$p_msrp = $pro_stock = $p_measuremntinfo = $p_inc_sizechart = 0;
              $p_msrp = $pro_stock = $p_inc_sizechart = 0;
              $pro_name = '';
              if (isset($myofscat) && !empty($myofscat)) {
                   if ($collection_type == 'select') {
                        $qry_str['wix_categoryid'] = implode(",", $myofscat);
                   } else {
                        $qry_str['wix_categoryid'] = $myofscat;
                   }
              } elseif (isset($mymlcat) && !empty($mymlcat)) {
                   $qry_str['wix_categoryid'] = $mymlcat;
              }
              if (isset($amnt) && $amnt >= 0) {
                   $qry_str['amount'] = $amnt;
              } else {
                   $qry_str['amount'] = '';
              }

              if (isset($modal_pro_tags) && !empty($modal_pro_tags)) {
                   $modal_pro_tags64 = base64_encode($modal_pro_tags);
              }
              if (isset($_POST['p_msrp']) && !empty($_POST['p_msrp'])) {
                   $p_msrp = 1;
              } else {
                   $p_msrp = 0;
              }
              if (isset($_POST['p_inc_sizechart']) && !empty($_POST['p_inc_sizechart']) && $_POST['p_inc_sizechart'] == 'on') {
                   $p_inc_sizechart = 1;
              } else {
                   $p_inc_sizechart = 0;
              }

              /*if (isset($_POST['p_measuremntinfo']) && !empty($_POST['p_measuremntinfo']) && $_POST['p_measuremntinfo'] == 'on') {
                   $p_measuremntinfo = 1;
              }*/

              $qry_str['change_price'] = $change_price;
              $qry_str['markup_price'] = $markup_price;
              $qry_str['product_id'] = $product_id;
              $qry_str['modal_pro_tags'] = $modal_pro_tags64;
              $qry_str['p_msrp'] = $p_msrp;
              $qry_str['p_inc_sizechart'] = $p_inc_sizechart;
              //$qry_str['p_measuremntinfo'] = $p_measuremntinfo;
              $qry_str['combain_product'] = $combain_product;
              $qry_str['collection_type'] = $collection_type;
              $result = MYOFS_API::AddProducttoStore($qry_str);
              
              $product_ex = explode(',', $product_id);
              if (array_key_exists(200, $result['status_code'])) {
                   if (isset($result['data']) && !empty($result['data'])) {
                        $product_cnt = count($product_ex);
                        if ($result['data']['sync_err'] == 0) {
                             $wc_product_id = $wc_catids = '';
                             if ((isset($result['data']['product_id']) && !empty($result['data']['product_id'])) && (isset($result['data']['errortype']) && $result['data']['errortype'] == 1)) {
                                  if (!empty($result['data']['product_id'])) {
                                       $noproim = $result['data']['product_id'];
                                       $noproim_arr = $proim_arr = array();
                                       for ($pi = 0; $pi < $product_cnt; $pi++) {
                                            if (in_array($product_ex[$pi], $noproim)) {
                                                 array_push($noproim_arr, $product_ex[$pi]);
                                            } else {
                                                 array_push($proim_arr, $product_ex[$pi]);
                                            }
                                       }
                                       $return_arr['total_noproduct'] = count($noproim_arr);
                                       $return_arr['total_product'] = count($proim_arr);
                                  } else {
                                       $return_arr['total_noproduct'] = 0;
                                       $return_arr['total_product'] = $product_cnt;
                                  }

                                  if (isset($result['data']['woocommerce_id']) && !empty($result['data']['woocommerce_id'])) {

                                       $wc_product_id = array_filter($result['data']['woocommerce_id']);
                                  }
                                  if (isset($result['data']['wc_category_id']) && !empty($result['data']['wc_category_id'])) {

                                       $wc_catids = array_filter($result['data']['wc_category_id']);
                                  }
                                  $this->storeWcDatatoDB($wc_product_id, $wc_catids);
                                  $this->StoreMyinvProductIds();
                                  $return_arr['status'] = 1;
                                  $return_arr['count'] = $result['data']['sync_succ'];
                             } else {
                                  if (isset($result['data']['woocommerce_id']) && !empty($result['data']['woocommerce_id'])) {
                                       $wc_product_id = array_filter($result['data']['woocommerce_id']);
                                  }
                                  if (isset($result['data']['wc_category_id']) && !empty($result['data']['wc_category_id'])) {
                                       $wc_catids = array_filter($result['data']['wc_category_id']);
                                  }
                                  $this->storeWcDatatoDB($wc_product_id, $wc_catids);
                                  $this->StoreMyinvProductIds();
                                  $return_arr['status'] = 1;
                                  $return_arr['total_product'] = $product_cnt;
                                  $return_arr['count'] = $result['data']['sync_succ'];
                             }
                        } else {
                             $return_arr['status'] = 0;
                             if (isset($result['data']['errortype']) && $result['data']['errortype'] == 1) {
                                  $return_arr['error']['product'] = 'Please choose another amount because the calculated amount is returned to the zero';
                             } else {
                                  if (isset($result['data']['error_wcmsg']) && !empty($result['data']['error_wcmsg'])) {
                                       $return_arr['error']['product'] = $result['data']['error_wcmsg'];
                                  } else {

                                       $return_arr['error']['product'] = 'Something went to wrong please try again!';
                                  }
                             }
                        }
                   } else {
                        $return_arr['status'] = 1;
                        $return_arr['count'] = count($product_ex);
                   }
              } elseif (array_key_exists(204, $result['status_code'])) {
                   $return_arr['status'] = 1;
                   $return_arr['count'] = count($product_ex);
              } else {
                   $keycode = implode('', array_keys($result['status_code']));
                   $return_arr['status'] = 0;

                   $return_arr['error']['product'] = $keycode . ' - ' . $result['status_code'][$keycode];
              }
         } else {
              $return_arr['status'] = 0;
              $return_arr['error']['product'] = 'Something went wrong! Product data is not fetched please try again';
         }
         
         echo json_encode($return_arr);
         exit();
    }

    public function storeWcDatatoDB($wc_product_id,$wc_catids){
		if (isset($wc_product_id) && !empty($wc_product_id)) {
			$this->wpdb->insert($this->myofs_tbl, array(
			    'wc_product_id' => $wc_product_id,
			    'status' => 1,
			));
		}
		if (isset( $wc_catids ) && !empty( $wc_catids )) {			
			update_term_meta($wc_catids,'myofs_category',1);		
		}
	}
	/*
	* remove Inventory Product from the Woocommerce Store
	* @Used API:RemoveProducttoStore
	* @DB Table: MYOFS_DB_TABLE	
	*/
	public function removeProductTowcStore(){
		
		$product_ids = array_map( 'sanitize_text_field', wp_unslash( $_GET['ids'] ) );
		$return_arr  = array();
		if (isset($product_ids) && !empty($product_ids)) {
			$ids     = implode(",",$product_ids);
			$qry_str = array('removeid' =>$ids);
			$result  = MYOFS_API::RemoveProducttoStore($qry_str);
			if(array_key_exists(200,$result['status_code'])){
				if ( $result['data']['sync_err'] == 0) {
					$wc_ids     = implode(",",$result['data']['woocommerce_id']);
					if (isset($wc_ids) && !empty($wc_ids)) {
						$this->wpdb->query( "DELETE FROM $this->myofs_tbl WHERE wc_product_id IN($wc_ids)" );
					}
	       			$return_arr['status'] = 1;	
	       			$return_arr['message'] = 'product has been deleted successfully, please allow few minutes as the product delete to your products section.';

				}else{
	       			$return_arr['status'] = 0;				
	       			$return_arr['message'] = 'something went to wrong please try again';
				}
			}elseif(array_key_exists(204,$result['status_code'])){
				$return_arr['status'] = 1;	
       			$return_arr['message'] = 'product has been deleted successfully, please allow few minutes as the product delete to your products section.';
			}else{
				$keycode = implode('',array_keys($result['status_code']));
				$return_arr['status'] = 0;					
				$return_arr['message'] = $keycode.' - '.$result['status_code'][$keycode];
							
			}

		}else{
   			$return_arr['status'] = 0;				
			$return_arr['message']   = 'something went to wrong please try again';

		}
		echo json_encode($return_arr);
		exit();	
	}
	/*
	* Check Selected Products are combine or not.
	* If products are combine than add to woocommerce
	* @Used API:CombineProducts
	*/
	public function checkCombineProducts(){
		$response = array();  
		$ids = array_map( 'sanitize_text_field', wp_unslash( $_GET['productid'] ) );
		if (isset($ids) && !empty($ids)) {	
			$count = count($ids); 			
			if(trim($count) > 1){
				$product_ids = implode(',', $ids);
				$qry_str     = array('product_ids'=> $product_ids);
				$result      = MYOFS_API::CombineProducts($qry_str);
				if(array_key_exists(200,$result['status_code'])){

					if(isset($result['data']) && !empty($result['data'])){
						$response['status']  = $result['data']['status'];
						$response['message'] = $result['data']['message'];
					}else{
						$response['status']  = '0';
						$response['message'] = 'Please select more than 1 product for the add combine products.';
					}
				}else{
					$keycode = implode('',array_keys($result['status_code']));
					$return_arr['status'] = 0;					
					$return_arr['message'] = $keycode.' - '.$result['status_code'][$keycode];					
				}

			}else{
				$response['status']  ='0';
				$response['message'] = 'Please select more than 1 product for the add combine products.';
			} 
		}else{
			$response['status']  = '0';
			$response['message'] = 'Please select more than 1 product for the add combine products.';
		} 

		echo json_encode($response);
		exit();	
	}
	/*
	* Get sidebar,pagination,per page query string data from Inventory/My Inventory Page 
	*/
	function GetQueryStringData(){
		$search   = $catid   = $sort    = '';	
		$limit    = 30;
		$qry_str  = array();
		$sortby   = sanitize_text_field(filter_input( INPUT_GET, 'sortby'));
		$getlimit = sanitize_text_field(filter_input( INPUT_GET, 'limit'));
		$getsrch  = sanitize_text_field(filter_input( INPUT_GET, 'search'));
		$getcat   = sanitize_text_field(filter_input( INPUT_GET, 'category_id'));
		$getcrrp  = sanitize_text_field(filter_input( INPUT_GET, 'current_page'));
		
		if (isset($sortby) && !empty( $sortby ) ){ 
			$sort = $sortby; 
			$qry_str['sortby'] = $sort;
		}

		if (isset($getlimit) && !empty( $getlimit )) { 
			$limit = $getlimit; 
		}		

		if (isset($getsrch) && !empty( $getsrch ) ) {
			$search = base64_decode($getsrch);
			$qry_str['searh'] = $search;
		}

		if (isset($getcat) && !empty($getcat) ) { 
			$catid = $getcat;
			$qry_str['category_id'] = $catid;
		}

		if (isset($getcrrp) && !empty($getcrrp)) { $page = $getcrrp; } else { $page = 1; };
		
		$qry_str['limit'] = $limit;
		$qry_str['page']  = $page;
		return $qry_str;
	}
	/*
	* Load Inventory/My Inventory Before HTML
	*/
	function InventoryProductsBeforeContent($total_row,$limit,$page){
		$product_html = ''; 
		$echo    = "selected";
		$previous_btn = true;
		$next_btn     = true;
		$first_btn    = true;
		$last_btn     = true;
		$start        = ($page - 1) * $limit;
		$showpagi     = 0;
		?>
			<div class="col-md-12 col-md-12 margin-bottom-10 margin-top-10 top_header_sec">
				<div class="col-md-4 first_btn" style="float:left;">
					<div class="btn-toolbar margin-top-10">
						<div class="btn-group"  id="selectionbox">
							<button class="btn blue dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> <?php esc_html_e( 'Select', 'my-online-fashion-store' ); ?><i class="fa fa-angle-down"></i></button>							 
							<ul class="dropdown-menu" role="menu">
								<li>
									<a class="myofs_seltag" data-val="selectall">
										<?php esc_html_e( 'Select All', 'my-online-fashion-store' ); ?> 
									</a>
								</li>
								<li class="divider"> </li>
								<li>
									<a class="myofs_seltag" data-val="removeselectall">
										<?php esc_html_e( 'Remove Selection', 'my-online-fashion-store' ); ?> 
									</a>
								</li>
								<li class="divider"> </li>
								<li>
									<a class="myofs_seltag" data-val="inverseselectall">
										<?php esc_html_e( 'Inverse Selection', 'my-online-fashion-store' ); ?>
									</a>
								</li>								
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-4 secound_btn" style="float:right;">
					<div class="btn-toolbar margin-top-10" style="float:left;">
						<div class="btn-group Pagination" style=" margin-top: 0; ">
							<?php
							if (isset($total_row) && !empty($total_row) && $total_row > 30) {
								?>
								<select name="limit" id="limitbox">
									<option value="30" <?php selected( $limit, 30 ); ?>>
										<?php esc_html_e( '30 per page', 'my-online-fashion-store' ); ?>
									</option>
									<option value="60" <?php selected( $limit, 60 ); ?>>
										<?php esc_html_e( '60 per page', 'my-online-fashion-store' ); ?>
									</option>
									<option value="90" <?php selected( $limit, 90 );?>>
										<?php esc_html_e( '90 per page', 'my-online-fashion-store' ); ?>
									</option>
								</select>
								<?php
							}?>
						</div>
					</div>
					<div class="btn-toolbar margin-top-10" style="float:right;">
						<?php
						if ($showpagi == 0 && $total_row > $limit) {
					        $no_of_paginations = ceil($total_row / $limit);
					        /* Pagination */
					        $pagination = array(
					            "cur_page" => $page,
					            "no_of_paginations" => $no_of_paginations,
					            "first_btn" => $first_btn,
					            "previous_btn" => $previous_btn,
					            "last_btn" => $last_btn,
					            "next_btn" => $next_btn
					        );
					        MYOFS_API::Pagination($pagination);
					    }?>
					</div>
				</div>
			</div>
			<div id="section_n" class="col-md-12"></div>
			<div id="product_details"><?php $this->SingleProductDetailPopup()?></div>
		<?php
	}
	/*
	* Load Inventory/My Inventory Product Loop HTML
	*/
	function InventoryProductsMiddlebeforeContent($allproduct){
		?>
		<div class="portlet-title">
			<div class="caption">
				<span class="check_cnt">
				<input type="checkbox"  class="product_id"  name="product_id" id="<?php echo $allproduct['product']['id'];?>" value="<?php echo $allproduct['product']['id'];?>">
				</span>
				<label class="caption-subject font-green bold uppercase" for="<?php echo $allproduct['product']['id'];?>"><?php echo $allproduct['product']['name'];?></label>
			</div>
		</div>
		<div class="portlet-body" data-product-id="<?php echo $allproduct['product']['id'];?>">
		<?php
	}
	function InventoryProductsMiddleafterContent($allproduct){
		$discount_value = ($allproduct['product']['cost_price'] / 100) * 20;
		$discounted_price = $allproduct['product']['cost_price'] - $discount_value;?>
			<div class="mt-element-overlay">
				<div class="row">
					<div class="col-md-12">
						<div class="mt-overlay-6">
							<?php
							$image = '';
							if (!empty($allproduct['product']['imageradio']) && isset($allproduct['product']['imageradio'])) {
								$image = $allproduct['product']['imageradio'];
							} else {
								$image = '';
								if (!empty($allproduct['image'][0]['image']) && isset($allproduct['image'][0]['image'])) {
									$image = $allproduct['image'][0]['image'];
								}
							}
							$stock = 0;
							if ($allproduct['product']['stock'] != '') {
								$stock = $allproduct['product']['stock'];
							}
							if (!empty($image)) {?>
								<img  src="<?php echo esc_url('https://app.ccwholesaleclothing.com/timthumb.php?src=' . $image . '?h=300&w=300&c=1');?>" alt=""  >
								<?php
							} else {?>
								<img src="<?php echo esc_url(MYOFS_ASSETS_URL.'images/placeholder.png');?>" style="width:100%;" >
								<?php
							}?>
							<div class="mt-overlay">
								<p class="pro-detl">
									<?php
									printf(
										wp_kses(
											__('<a class="detail-view uppercase" data-product-id="%1$s" >%2$s</a>','my-online-fashion-store'),
											array(
												'a' => array(
													'class' => array(),
													'data-product-id' => array()
												)
											)
										),
										$allproduct['product']['id'],
										'Detailed view'
									);
									?>
								</p>
								<?php
								if ($allproduct['inventory'] == 'yes'){?>
									<p class="pro-add-rmv">
										<?php
										printf(
											wp_kses(
												__('<a class="myofs-remove-wc uppercase" id="myofs-remove-wc" data-toggle="modal" data-product-id="%1$s" >%2$s</a>','my-online-fashion-store'),
												array(
													'a' => array(
														'class' => array(),
														'id' => array(),
														'data-toggle' => array(),
														'data-product-id' => array()
													)
												)
											),
											$allproduct['product']['id'],
											'Remove From Woocommerce'
										);
										?>									
									</p>
									<?php
								}else{ ?>
									<p class="pro-add-rmv">
										<?php
										printf(
											wp_kses(
												__('<a class="myofs-add-wc uppercase"  data-product-id="%1$s"  data-product-name="%2$s" data-product-stock = "%3$s" data-product-sku = "%4$s" id="myofs_add_wc">%5$s</a>','my-online-fashion-store'),
												array(
													'a' => array(
														'class' => array(),
														'data-product-id' => array(),
														'data-product-name' => array(),
														'data-product-stock' => array(),
														'data-product-sku' => array(),
														'id' => array()
														
													)
												)
											),
											$allproduct['product']['id'],
											$allproduct['product']['name'],
											$allproduct['product']['stock'],
											$allproduct['product']['sku'],
											'Add to Woocommerce'
										);
										?>									
									</p>
									<?php
								}?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="portlet-desc">
			<div class="row">
				<div class="col-md-12 uppercase" style="text-align: right;">
					<?php echo esc_html_e( $allproduct['product']['sku'], 'my-online-fashion-store' ); ?>
				</div>
				<div class="col-md-12">
					<a  class="btn btn-sm green qty_c_btn" style=" float: left; ">
						<i class="fa fa-shopping-basket"></i>
						<?php echo esc_html_e( $stock . ' QTY IN STOCK', 'my-online-fashion-store' ); ?>
					</a>
					<div class="price">
						<?php
							printf(
								wp_kses(
									__( '<b>Your Cost Monthly Plan:</b>$%1$s<br><b>Your Cost Annual Plan:</b>$%2$s<br><b>Default Selling Price:</b>$%3$s<br>','my-online-fashion-store'),
									array(
										'b' => array(),
										'br' => array(),
									)
								),
								$allproduct['product']['cost_price'],
								number_format($discounted_price, 2),
								$allproduct['product']['price']
							);
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	/*
	* Load Inventory/My Inventory After HTML
	*/
	function InventoryProductsAfterContent($total_row,$limit,$page){
		$product_html = '';		
		$previous_btn = true;
		$next_btn     = true;
		$first_btn    = true;
		$last_btn     = true;
		$start        = ($page - 1) * $limit;
		$showpagi     = 0;
		?>
		<div class="col-md-6 bottom_pagination" style="float:left;clear:both;">
			<?php
			if ($showpagi == 0 && $total_row > $limit) {
				$no_of_paginations = ceil($total_row / $limit);
				/* Pagination */
				$pagination = array(
					"cur_page" => $page,
					"no_of_paginations" => $no_of_paginations,
					"first_btn" => $first_btn,
					"previous_btn" => $previous_btn,
					"last_btn" => $last_btn,
					"next_btn" => $next_btn
				);
			   MYOFS_API::Pagination($pagination);
			}
			?>
		</div>
		<?php
	}
	
	/*
	* Load Inventory/My Inventory Product Not Exist HTML
	*/
	function NoInventoryProductFound(){
		?>
		<div class="col-md-12 col-md-12 margin-bottom-10 margin-top-10 top_header_sec">		
			<span class="myofs-noproduct"><?php esc_html_e( 'No Product Found', 'my-online-fashion-store' ); ?></span>
		</div>
		<?php
	}
	/*
	* Load Inventory/My Inventory Remove Product Popup HTML
	*/
	function RemoveProductConfirmContent($product_id){
		?>
		<div class="modal fade removeconfirm" id="large<?php echo $product_id;?>" tabindex="-1" role="basic" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><?php esc_html_e( 'Remove Product from Woocommerce', 'my-online-fashion-store' ); ?></h4>
					</div>
					<div class="modal-body"><?php esc_html_e( 'Are you sure you want remove this product?', 'my-online-fashion-store' ); ?></div>
					<div class="modal-footer" data-product-id="<?php echo $product_id;?>">
						<button type="button" class="btn red" id="rmv_product"><?php esc_html_e( 'REMOVE', 'my-online-fashion-store' ); ?></button>
						<button type="button" class="btn dark btn-outline rmv_cancel" data-dismiss="modal"><?php esc_html_e( 'CANCEL', 'my-online-fashion-store' ); ?></button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	/*
	* Load Inventory/My Inventory Remove Multiple Product Popup HTML
	*/
	function RemoveMultipleProductConfirmContent(){
		?>
		<div class="modal fade removeconfirm" id="multiallinventory" tabindex="-1" role="basic" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><?php esc_html_e( 'Remove Product from Woocommerce', 'my-online-fashion-store' ); ?></h4>
					</div>
					<div class="modal-body"><?php esc_html_e( 'Are you sure you want remove this product?', 'my-online-fashion-store' ); ?></div>
					<div class="modal-footer">
						<button type="button" class="btn dark btn-outline rmv_mulcancel" data-dismiss="modal"><?php esc_html_e( 'CANCEL', 'my-online-fashion-store' ); ?></button>
						<button type="button" class="btn red" id="rmv_mulpro"><?php esc_html_e( 'REMOVE', 'my-online-fashion-store' ); ?></button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	function SingleProductDetailPopup(){
		?>
		<div class="modal fade" id="singleprodetail" tabindex="-1" role="dialog" aria-hidden="true" style="display: none; padding: 50px;">
			<div class="apploaderp"><div class="loader"></div></div>
			<div class="modal-dialog modal-full">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><?php esc_html_e( 'Product Detailed View', 'my-online-fashion-store' ); ?></h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div style="margin-top: 30px" class="col-md-6">
								<div id="big-image">
									<!-- load featured image -->
								</div>
								<div id="thumbs" class="detail-slider">
									<!-- load gallery images -->
								</div>
							</div>
							<div style="margin-top: 30px" class="col-md-6">
								<h2 class="item-title ng-binding"><!-- product title --></h2>
								<div id="inv_btn">
									<!-- load button -->
								</div>
								<div id="proddata">
									<!-- load price -->
								</div>
								<div id="optsdata">
									<!-- load option data -->
								</div>
								<div id="skdecsdata">
									<!-- load stock-description data -->
								</div>
								<div id="tags">
									<!-- load tag -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	function StoreMyinvProductIds(){
		
		$result  = MYOFS_API::StoreMyinventoryProductIds();
		if(array_key_exists(200,$result['status_code'])){
			if ( isset($result['data']) && !empty($result['data'])) { 
				$product_ex  = $result['data']['woo_data'];
				$check_id = '';
				foreach($product_ex as $product){
					if (isset($product['woo_product_id']) && !empty($product['woo_product_id'])) {
						$check_id   = $this->wpdb->get_results("SELECT wc_product_id FROM ".$this->myofs_tbl." WHERE wc_product_id = '".$product['woo_product_id']."'");
						if (empty($check_id)) {
							$this->storeWcDatatoDB($product['woo_product_id'],$product['woo_category_id']);						
						}
					}
				}
			}
		}
	}
	function addProducItemAddedLbl(){
		header("Content-Type: application/json");
		$limit    = 30;
		$qry_str  = array();
		$sortby   = sanitize_text_field(filter_input( INPUT_GET, 'sortby'));
		$getlimit = sanitize_text_field(filter_input( INPUT_GET, 'limit'));
		$getsrch  = sanitize_text_field(filter_input( INPUT_GET, 'search'));
		$getcat   = sanitize_text_field(filter_input( INPUT_GET, 'cat_id'));
		$getcrrp  = sanitize_text_field(filter_input( INPUT_GET, 'page'));

		if (isset($sortby) && !empty( $sortby ) ){ 
			$sort = $sortby; 
			$qry_str['sortby'] = $sort;
		}

		if (isset($getlimit) && !empty( $getlimit )) { 
			$limit = $getlimit; 
		}		

		if (isset($getsrch) && !empty( $getsrch ) ) {
			$search = base64_decode($getsrch);
			$qry_str['searh'] = $search;
		}

		if (isset($getcat) && !empty($getcat) ) { 
			$catid = $getcat;
			$qry_str['category_id'] = $catid;
		}

		if (isset($getcrrp) && !empty($getcrrp)) { $page = $getcrrp; } else { $page = 1; };
		
		$qry_str['limit'] = $limit;
		$qry_str['page']  = $page;
		$result  = MYOFS_API::GetAddedProducts($qry_str);

		$ProIds  = $data = array();	
		$ProIds['item_add'] = array();
		$ProIds['item_rmv'] = array();
		if(array_key_exists(200,$result['status_code'])){
			$ProductData = $result['data'];
			foreach ($ProductData['data'] as $allproduct) {
				$itemadded = $allproduct['inventory'];
				if ($itemadded == 'yes') {
					$product_id = $allproduct['product']['id'];
					$rmvhtml = '<div class="modal fade removeconfirm" id="large'.$product_id.'" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">Remove Product from Woocommerce</h4>
										</div>
										<div class="modal-body">Are you sure you want remove this product?</div>
										<div class="modal-footer" data-product-id="'.$product_id.'">
											<button type="button" class="btn red" id="rmv_product">REMOVE</button>
											<button type="button" class="btn dark btn-outline rmv_cancel" data-dismiss="modal">CANCEL</button>
										</div>
									</div>
								</div>
							</div>';
					$ProIds['item_add'][] = array(
						'id' => $allproduct['product']['id'],
						'rmv_html' => $rmvhtml 
					);
				}else{
					$ProIds['item_rmv'][] = array(
						'id'    => $allproduct['product']['id'],
						'name'  => $allproduct['product']['name'],
						'sku'   => $allproduct['product']['sku'],
						'stock' => $allproduct['product']['stock']
					);
				}
			}
		}
		if (isset($ProIds) && !empty($ProIds)) {
			
			$data['data']   = $ProIds;
			$data['status'] = 1;
		}else{
			$data['data']    = '';
			$data['status']  = 1;
		}
		echo json_encode($data);
		exit();
	}
}
?>