<?php
/**
 * Free Return Page
 * @package My Online Fashion Store\Return Page
 */
defined( 'ABSPATH' ) || exit;
/**
 * MYOFS_Free_Return Class
 *
 * Provides a free return form submit ajax call.
 */
class MYOFS_Free_Return extends MYOFS_API{

	public function submitFormReturnData(){
          $return_arr = $qrystr = array();
          $error      = '';
          $contactperson = sanitize_text_field($_POST['contactperson']);
          $email         = sanitize_email($_POST['email']);
          $ordernumber   = sanitize_text_field($_POST['ordernumber']);
          $itemqty       = sanitize_text_field($_POST['itemqty']);
          $reasonreturn  = sanitize_textarea_field($_POST['reasonreturn']);
          //Contact Person
          if (isset($contactperson) && !empty($contactperson)) {
               $qrystr['contactperson'] = $contactperson;
          }else{
               //error
               $error .= '<span>Contact Person Field is required!</span><br/>';
          }
          //E-mail Address
          if (isset($email) && !empty($email)) {
               if ( is_email( $email ) ) {
                    $qrystr['email'] = $email;
               }else{
                    $error .= '<span>Please enter valid email address</span><br/>';
               }
               
          }else{
               $error .= '<span>E-mail Address Field is required!</span><br/>';
          }
          //Order Number
          if (isset($ordernumber) && !empty($ordernumber)) {
               $qrystr['ordernumber'] = $ordernumber;               
          }else{
               $error .= '<span>Order Number Field is required!</span><br/>';
          }
          //ID, Number of items returning and Quantity
          if (isset($itemqty) && !empty($itemqty)) {
               $qrystr['itemqty'] = $itemqty;               
          }else{
               $error .= '<span>ID, Number of items returning and Quantity Field is required!</span><br/>';
          }
          //Reason For Return
          if (isset($reasonreturn) && !empty($reasonreturn)) {
               $qrystr['reasonreturn'] = $reasonreturn;                       
          }else{
               $error .= '<span>Reason For Return Field is required!</span></br>';
          }
		  $addln = sanitize_textarea_field($_POST['additionaln']);
          if (isset( $addln ) && !empty( $addln )) {               
               $additionaln = $addln;
          }else{
               $additionaln = '';
          }
          $qrystr['additionaln'] = $additionaln;
          
          if (empty($error)) {
               $return  = MYOFS_API::ReturnFormSubmit($qrystr);
               $data    = $return['data'];
               if ($data['status'] == 1) {
                    $return_arr['status'] = 1;
                    $return_arr['success']   = 'Thank you! Your information has been successfully submitted. We will contact you very soon!';
               }else{
                    $return_arr['status'] = 0;
                    $return_arr['error']  = 'Sorry! Getting issue on the submission data.Please try again';
               }               
          }else{               
               $return_arr['status'] = 0;
               $return_arr['error']   = $error;
          }

          echo json_encode($return_arr);
          exit();
	}

}


?>