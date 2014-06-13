<?php
session_start();

/**
 * PayPal API
 */
if ( ! class_exists('WPCAds_PayPalAPI') ) {

  class WPCAds_PayPalAPI {
  
    /**
     * Start express checkout
     */
    function StartExpressCheckout() {

      global $redux_demo; 
      $paypal_api_environment = $redux_demo['paypal_api_environment'];
      $paypal_success = $redux_demo['paypal_success'];
      $paypal_fail = $redux_demo['paypal_fail'];
      $paypal_api_username = $redux_demo['paypal_api_username'];
      $paypal_api_password = $redux_demo['paypal_api_password'];
      $paypal_api_signature = $redux_demo['paypal_api_signature'];
      
      if ( $paypal_api_environment != '1' && $paypal_api_environment != '2' )
        trigger_error('Environment does not defined! Please define it at the plugin configuration page!', E_USER_ERROR);
      
      /*if ( $paypal_fail === FALSE || ! is_numeric($paypal_fail) )
        trigger_error('Cancel page not defined! Please define it at the plugin configuration page!', E_USER_ERROR);
      
      if ( $paypal_success === FALSE || ! is_numeric($paypal_success) )
        trigger_error('Success page not defined! Please define it at the plugin configuration page!', E_USER_ERROR);*/

      global $wpdb;
      $result = $wpdb->get_results( "SELECT * FROM wpcads_url" );

      foreach ( $result as $info ) {
        $url = $info->url;
      }
      
      // FIELDS
      $fields = array(
              'USER' => urlencode($paypal_api_username),
              'PWD' => urlencode($paypal_api_password),
              'SIGNATURE' => urlencode($paypal_api_signature),
              'VERSION' => urlencode('72.0'),
              'PAYMENTREQUEST_0_PAYMENTACTION' => urlencode('Sale'),
              'PAYMENTREQUEST_0_AMT0' => urlencode($_POST['AMT']),
              'PAYMENTREQUEST_0_CUSTOM' => urlencode($_POST['PAYMENTREQUEST_0_CUSTOM']),
              'PAYMENTREQUEST_0_AMT' => urlencode($_POST['AMT']),
              'PAYMENTREQUEST_0_ITEMAMT' => urlencode($_POST['AMT']),
              'ITEMAMT' => urlencode($_POST['AMT']),
              'PAYMENTREQUEST_0_CURRENCYCODE' => urlencode($_POST['CURRENCYCODE']),
              'RETURNURL' => urlencode( $url.'/paypal/form-handler.php?func=confirm'),
              'CANCELURL' => urlencode(get_permalink($paypal_fail)),
              'METHOD' => urlencode('SetExpressCheckout')
          );

      $fields['PAYMENTREQUEST_0_CUSTOM'] = $_POST['PAYMENTREQUEST_0_CUSTOM'];
      
      if ( isset($_POST['PAYMENTREQUEST_0_DESC']) )
        $fields['PAYMENTREQUEST_0_DESC'] = $_POST['PAYMENTREQUEST_0_DESC'];
      
      if ( isset($_POST['RETURN_URL']) )
        $_SESSION['RETURN_URL'] = $_POST['RETURN_URL'];
      
      if ( isset($_POST['CANCEL_URL']) )
        $fields['CANCELURL'] = $_POST['CANCEL_URL'];
      
      $fields['PAYMENTREQUEST_0_QTY0'] = 1;
      $fields['PAYMENTREQUEST_0_AMT'] = $fields['PAYMENTREQUEST_0_AMT'];
      
      
      if ( isset($_POST['TAXAMT']) ) {
        $fields['PAYMENTREQUEST_0_TAXAMT'] = $_POST['TAXAMT'];
        $fields['PAYMENTREQUEST_0_AMT'] += $_POST['TAXAMT'];
      }
      
            
      if ( isset($_POST['HANDLINGAMT']) ) {
        $fields['PAYMENTREQUEST_0_HANDLINGAMT'] = $_POST['HANDLINGAMT'];
        $fields['PAYMENTREQUEST_0_AMT'] += $_POST['HANDLINGAMT'];
      }
            
      if ( isset($_POST['SHIPPINGAMT']) ) {
        $fields['PAYMENTREQUEST_0_SHIPPINGAMT'] = $_POST['SHIPPINGAMT'];
        $fields['PAYMENTREQUEST_0_AMT'] += $_POST['SHIPPINGAMT'];
      }
      
      $fields_string = '';

      foreach ( $fields as $key => $value ) 
        $fields_string .= $key.'='.$value.'&';
        
      rtrim($fields_string,'&');
      
      // CURL
      $ch = curl_init();
      
      if ( $paypal_api_environment == '1' )
        curl_setopt($ch, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
      elseif ( $paypal_api_environment == '2' )
        curl_setopt($ch, CURLOPT_URL, 'https://api-3t.paypal.com/nvp');
        
      curl_setopt($ch, CURLOPT_POST, count($fields));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      
      //execute post
      $result = curl_exec($ch);
      
      //close connection
      curl_close($ch);
      
      parse_str($result, $result);
      
      if ( $result['ACK'] == 'Success' ) {
        
        if ( $paypal_api_environment == '1' )
          header('Location: https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token='.$result['TOKEN']);
        elseif ( $paypal_api_environment == '2' )
          header('Location: https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token='.$result['TOKEN']);
        exit;
        
      } else {
        print_r($result);
      }
      
    }
    
    /**
     * Validate payment
     */
    function ConfirmExpressCheckout() {
    
      global $redux_demo; 
      $paypal_api_environment = $redux_demo['paypal_api_environment'];
      $paypal_success = $redux_demo['paypal_success'];
      $paypal_fail = $redux_demo['paypal_fail'];
      $paypal_api_username = $redux_demo['paypal_api_username'];
      $paypal_api_password = $redux_demo['paypal_api_password'];
      $paypal_api_signature = $redux_demo['paypal_api_signature'];
      
      // FIELDS
      $fields = array(
              'USER' => urlencode($paypal_api_username),
              'PWD' => urlencode($paypal_api_password),
              'SIGNATURE' => urlencode($paypal_api_signature),
              'VERSION' => urlencode('72.0'),
              'TOKEN' => urlencode($_GET['token']),
              'METHOD' => urlencode('GetExpressCheckoutDetails')
          );
      
      $fields_string = '';
      foreach ( $fields as $key => $value )
        $fields_string .= $key.'='.$value.'&';
      rtrim($fields_string,'&');
      
      // CURL
      $ch = curl_init();
      
      if ( $paypal_api_environment == '1' )
        curl_setopt($ch, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
      elseif ( $paypal_api_environment == '2' )
        curl_setopt($ch, CURLOPT_URL, 'https://api-3t.paypal.com/nvp');
        
      curl_setopt($ch, CURLOPT_POST, count($fields));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      
      //execute post
      $result = curl_exec($ch);
      //close connection
      curl_close($ch);
      
      parse_str($result, $result);
      
      if ( $result['ACK'] == 'Success' ) {
        WPCAds_PayPalAPI::SavePayment($result, 'pending');
        WPCAds_PayPalAPI::DoExpressCheckout($result);
      } else {
        WPCAds_PayPalAPI::SavePayment($result, 'failed');
      }
    }
    
    /**
     * Close transaction
     */
    function DoExpressCheckout($result) {
    
      global $redux_demo; 
      $paypal_api_environment = $redux_demo['paypal_api_environment'];
      $paypal_success = $redux_demo['paypal_success'];
      $paypal_fail = $redux_demo['paypal_fail'];
      $paypal_api_username = $redux_demo['paypal_api_username'];
      $paypal_api_password = $redux_demo['paypal_api_password'];
      $paypal_api_signature = $redux_demo['paypal_api_signature'];
    
      // FIELDS
      $fields = array(
              'USER' => urlencode($paypal_api_username),
              'PWD' => urlencode($paypal_api_password),
              'SIGNATURE' => urlencode($paypal_api_signature),
              'VERSION' => urlencode('72.0'),
              'PAYMENTREQUEST_0_PAYMENTACTION' => urlencode('Sale'),
              'PAYERID' => urlencode($result['PAYERID']),
              'TOKEN' => urlencode($result['TOKEN']),
              'PAYMENTREQUEST_0_AMT' => urlencode($result['AMT']),
              'METHOD' => urlencode('DoExpressCheckoutPayment')
          );
      
      $fields_string = '';
      foreach ( $fields as $key => $value)
        $fields_string .= $key.'='.$value.'&';
      rtrim($fields_string,'&');
      
      // CURL
      $ch = curl_init();
      
      if ( $paypal_api_environment == '1' )
        curl_setopt($ch, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
      elseif ( $paypal_api_environment == '2' )
        curl_setopt($ch, CURLOPT_URL, 'https://api-3t.paypal.com/nvp');
      
      curl_setopt($ch, CURLOPT_POST, count($fields));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      
      //execute post
      $result = curl_exec($ch);
      //close connection
      curl_close($ch);
      
      parse_str($result, $result);
      
      if ( $result['ACK'] == 'Success' ) {
        WPCAds_PayPalAPI::UpdatePayment($result, 'success');
      } else {
        WPCAds_PayPalAPI::UpdatePayment($result, 'failed');
      }
    }
    
    /**
     * Save payment result into database
     */
    function SavePayment($result, $status) {
      global $wpdb;

      $update_data = array('currency' => $result['CURRENCYCODE'],
                           'status' => 'pending',
                           'firstname' => $result['FIRSTNAME'],
                           'lastname' => $result['LASTNAME'],
                           'email' => $result['EMAIL'],
                           'description' => $result['PAYMENTREQUEST_0_DESC'],
                           'summary' => serialize($result),
                           'token' => $result['TOKEN']);

      $where = array('id' => $result['PAYMENTREQUEST_0_CUSTOM']);
      
      $update_format = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');

      $wpdb->update('wpcads_paypal', $update_data, $where, $update_format);

    }
    
    /**
     * Update payment
     */
    function UpdatePayment($result, $status) {
      global $wpdb;

      $update_data = array('transaction_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
                           'status' => $status);
      
      $where = array('token' => $result['TOKEN']);
      
      $update_format = array('%s', '%s');

      $wpdb->update('wpcads_paypal', $update_data, $where, $update_format);
    }
    
  }
  
}