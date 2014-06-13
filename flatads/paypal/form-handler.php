<?php
session_start();
/**
 * Form posting handler
 */
require '../../../../wp-load.php';

/**
* Add transaction info to database 
*/

if ( isset($_GET['func']) && $_GET['func'] == 'addrow') {

  global $wpdb;

  $wpdb->query('CREATE TABLE IF NOT EXISTS `wpcads_url` (
                      `main_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                      `url` TEXT NOT NULL 
                      ) ENGINE = MYISAM ;');

  $url = $_POST['url'];

  $url_information = array(
    'url' => $url
  ); 

  $insert_format_url = array('%s');
        
  $wpdb->insert('wpcads_url', $url_information, $insert_format_url);



  $wpdb->query('CREATE TABLE IF NOT EXISTS `wpcads_paypal` (
                      `main_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                      `user_id` TEXT NOT NULL ,
                      `id` TEXT NOT NULL ,
                      `name` TEXT NOT NULL ,
                      `token` TEXT NOT NULL ,
                      `price` FLOAT UNSIGNED NOT NULL ,
                      `currency` TEXT NOT NULL ,
                      `ads` TEXT ,
                      `days` TEXT NOT NULL ,
                      `date` TEXT NOT NULL ,
                      `status` TEXT NOT NULL ,
                      `used` TEXT NOT NULL ,
                      `transaction_id` TEXT NOT NULL ,
                      `firstname` TEXT NOT NULL ,
                      `lastname` TEXT NOT NULL ,
                      `email` TEXT NOT NULL ,
                      `description` TEXT NOT NULL ,
                      `summary` TEXT NOT NULL ,
                      `created` INT( 4 ) UNSIGNED NOT NULL
                      ) ENGINE = MYISAM ;');




  $planID = $_POST['PAYMENTREQUEST_0_CUSTOM'];
  $user_ID = $_POST['user_ID'];
  $amount = $_POST['AMT'];
  $plan_name = $_POST['plan_name'];
  if($_POST['plan_ads'] == "") {
    $plan_ads = "∞";
    add_user_meta( $user_ID, 'unlimited', 'yes', $unique );
  } else {
    $plan_ads = $_POST['plan_ads'];
  }
  $plan_price = $_POST['plan_price'];
  $plan_time = $_POST['plan_time'];
  $date = $_POST['date'];
  $token = $_Post['TOKEN'];
   
  $price_plan_information = array(
    'id' => $planID,
    'user_id' => $user_ID,
    'name' => $plan_name,
    'token' => "",
    'price' => $plan_price,
    'currency' => "",
    'ads' => $plan_ads,
    'days' => $plan_time,
    'date' => $date,
    'status' => "in progress",
    'used' => "0",
    'transaction_id' => "",
    'firstname' => "",
    'lastname' => "",
    'email' => "",
    'description' => "",
    'summary' => "",
    'created' => time()
  ); 

  $insert_format = array('%s', '%s', '%s','%s', '%f', '%s', '%d', '%d', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s');
        
  $wpdb->insert('wpcads_paypal', $price_plan_information, $insert_format);

}

/**
* End function
*/



require TEMPLATEPATH.'/paypal/paypalapi.php';

if ( isset($_GET['func']) && $_GET['func'] == 'confirm' && isset($_GET['token']) && isset($_GET['PayerID']) ) {
  WPCAds_PayPalAPI::ConfirmExpressCheckout();
  
  if ( isset( $_SESSION['RETURN_URL'] ) ) {
    $url = $_SESSION['RETURN_URL'];
    unset($_SESSION['RETURN_URL']);
    header('Location: '.$url);
    exit;
  }
  
  if ( is_numeric(get_option('paypal_success_page')) && get_option('paypal_success_page') > 0 )
    header('Location: '.get_permalink(get_option('paypal_success_page')));
  else
    header('Location: '.home_url());
  exit;
}

if ( ! count($_POST) )
  trigger_error('Payment error code: #00001', E_USER_ERROR);

$allowed_func = array('start');
if ( count($_POST) && (! isset($_POST['func']) || ! in_array($_POST['func'], $allowed_func)) )
  trigger_error('Payment error code: #00002', E_USER_ERROR);
  
if ( count($_POST) && (! isset($_POST['AMT']) || ! is_numeric($_POST['AMT']) || $_POST['AMT'] < 0) )
  trigger_error('Payment error code: #00003', E_USER_ERROR);
  
switch ( $_POST['func'] ) {
  case 'start':
    WPCAds_PayPalAPI::StartExpressCheckout();
    break;
}