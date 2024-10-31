<?php
/**
 * Activation Key Page
 *
 * @class MYOFS_Activation_Key
 * @extends  MYOFS_API
 * @package my-online-fashion-store\activation-key
 * @version 1.0.8
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'MYOFS_Activation_Key', false ) ) :
	class MYOFS_Activation_Key extends MYOFS_API{
		/**
		* Get activation data and store on the DB 
		* @var email
		* @var activation key
		* @return array success or error message
		*/
		public function myofs_activate_keys(){
			$email    = sanitize_email($_POST['activation_email']);
			$acvkey   = sanitize_text_field($_POST['activation_key']);
			$response = array();	
			$response['status']   = 0;
			if ( !empty($email) && !empty($acvkey) ) {
				if (is_email($email)) {					
					$url     = MYOFS_SITE_URL;
					$chklink = get_option( 'permalink_structure' );
					$getex = ini_get('max_execution_time'); 					

					if( parse_url($url, PHP_URL_SCHEME) != 'http' && (isset($chklink) && !empty($chklink)) && $getex >= 30 ){
						$qry_str = array('user_email' => $email,'activation_key' => $acvkey,'store_url'=> $url);
						$result  = MYOFS_API::GetActivationPlan($qry_str);
						if(isset($result['data']) && !empty($result['data']) ){						
							$res    = $result['data'];
							if ($res['status'] == 1 ) {
								if (isset($res['auth_token']) && !empty($res['auth_token'])) {							
									$authkey = base64_encode($res['auth_token']);
									$storeres = array(
										'email'      => $email, 
										'auth_token' => $authkey
									);
									update_option(MYOFS_OPT_NAME,$storeres);
									$restkeys = MYOFS_API::StoreGeneratedWcRestApiKeys($authkey);				
									$response['status']   = 1;
									$response['success']  = 'Successfully Activted';		
								}else{
									$response['error'] = 'Somthing went to wrong.Please try again';
								} 
							}else{
								if (isset($res['store_url']) && !empty($res['store_url'])) {
									$response['error'] = 'Plugin is activated on the <a href="'.$res['store_url'].'">'.$res['store_url'].'</a> site.if you want activate this plugin then first uninstall form the <a href="'.$res['store_url'].'">'.$res['store_url'].'</a> site after you will be able to activate the plugin';
								}else{
									$response['error'] = 'Invaild Activation Data';
								} 
							}
						}else{
							$response['error'] = $result['error'];
						}

					}elseif(empty($chklink) && parse_url($url, PHP_URL_SCHEME) == 'http'){
						$response['error'] = "<ul><li>Your site does not appear to be using a secure connection. For, use this plugin need your site to be secure(HTTPS connection)</li><li>Kindly change the <a href ='https://www.wpbeginner.com/wp-tutorials/how-to-create-custom-permalinks-in-wordpress/#change-wordpress-permalink' target='_blank'>Common Settings</a> on your WordPress dashboard to any other option apart from 'Plain' URL.</li></ul>";
					}elseif(empty($chklink) && parse_url($url, PHP_URL_SCHEME) != 'http'){
						$response['error'] = "Kindly change the <a href ='https://www.wpbeginner.com/wp-tutorials/how-to-create-custom-permalinks-in-wordpress/#change-wordpress-permalink' target='_blank'>Common Settings</a> on your WordPress dashboard to any other option apart from 'Plain' URL.";
					}elseif($getex < 30){
						$response['error'] = "Kindly The server max_execution_time is less than 30 in that case the plugin is not the right fit or compatible with the server. Therefore, you need to set the max_execution_time to 30.Click <a href ='https://www.php.net/manual/en/info.configuration.php' target='_blank'>here</a> for more info";
					}else{
						$response['error'] = 'Your site does not appear to be using a secure connection. For, use this plugin need your site to be secure(HTTPS connection)';
					}				
				}else{ 
					$response['error'] = "Invalid email format";
				}
				
			}else{
				$response['error']   = 'Email & Activation key fields is required!';
			}
			echo json_encode($response);
			exit();
			

		}
	}
endif;

return new MYOFS_Activation_Key();
?> 