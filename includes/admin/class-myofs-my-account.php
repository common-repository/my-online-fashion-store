<?php
/**
 * My Account Page
 * @package My Online Fashion Store\My Account Page
 */
defined( 'ABSPATH' ) || exit;
/**
 * MYOFS_Free_Return Class
 *
 * Provides a purchased subscription plan data.
 */
class MYOFS_My_Account extends MYOFS_API{

	public function __construct(){
		add_action('myofs_myaccount_content',array( $this, 'GetMyAccountDetail' ), 7);
	}
	public function GetMyAccountDetail(){
		
		$result     = MYOFS_API::GetMyAccount();
		$getoption  = MYOFS_OPTDATA;
		$email      = base64_encode(sanitize_email($getoption['email']));
		$response = $result['data']; 
		$data_html = '';
		?>
		<div class="form-body">
           <h2 class="margin-bottom-20"><?php esc_html_e('Active Plan Information','my-online-fashion-store')?> </h2>
			<?php

			if ($response['status'] == 1 && (isset($response['data']) && !empty($response['data'])) ) {
				if ($response['data']['plan_detail'] == 'Monthly Plan') {
					$url = MYOFS_PLAN_URL.'?auth='.$email;
					
					$endDate = date('Y-m-d',strtotime($response['data']['date']." +1 month"));				
				}elseif ($response['data']['plan_detail'] == 'Yearly Plan') {
					$endDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($response['data']['date'])) . " + 365 day"));
					
				}
				//$plan_price   = 'price';
				$plan_price = $response['data']['plan_price'].' '.$response['data']['currency'];
				$plan_array = array(
					'Plan Name' => $response['data']['plan_detail'],
					'Plan Price' => $plan_price,
					'Plan Type' => str_replace("Plan"," ",$response['data']['plan_detail']),
					'Plan Start Date' => $response['data']['date'],
					'Plan Ends Date' => $endDate,
					'Status' => 'Active'
				);				
							
				foreach ($plan_array as $plan_key => $plan_value) {?>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label col-md-3"><?php esc_html_e($plan_key.':', 'my-online-fashion-store')?></label>
								<div class="col-md-9">
									<p class="form-control-static">
										<?php esc_html_e($plan_value, 'my-online-fashion-store');?>
									</p>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				
			}else{?>
				<div class="notfound"><span><?php esc_html_e( 'Plan not purchsed.', 'my-online-fashion-store' ); ?></span></div>
				<?php
			}?>
		</div>
		<?php if ($response['data']['plan_detail'] == 'Monthly Plan') { ?>
			<div class="form-actions">
				<div class="row">
					<div class="col-md-8">
						<div class="row">
							<div class="col-md-offset-3 col-md-12">
								<a class="btn green" href="<?php echo $url; ?>" target="blank">Upgrade Subscription</a>
							</div>
						</div>
					</div>
					<div class="col-md-6"> </div>
				</div>
			</div>
			<?php
		}
	}	
}
?>