<?php ob_start();
/**
 * Plugin Name: WooCommerce With fetchr
 * Plugin URI: http://fetchr.us
 * Description: A plugin to integrate fetchr shipping with woo commerce.
 * Version: 1.3.0
 * Author: fetchr
 * Author URI: http://www.fetchr.us
 */

/*
* 2015-2016 Fetchr
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@fetchr.us so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Fetchr Shipping to newer
* versions in the future. If you wish to customize Fetchr Shipping for your
* needs please refer to http://www.fetchr.us for more information.
*
*  @author Fetchr <contact@fetchr.us>
*  @author Asaad Abdo <a.abdo@fetchr.us>
*  @copyright  2015-2016 Fetchr
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  Fetchr.us
*/


add_action('admin_menu', 'test_plugin_setup_menu');
//define(ERP_URL);
function test_plugin_setup_menu(){
    add_menu_page( 'fetchr Integration', 'fetchr', 'manage_options', 'test-plugin', 'mena_setting_page' );
    add_action( 'admin_init', 'register_setting_options' );
}

function register_setting_options()
{
    register_setting( 'mena-settings-group', 'mena_merchant_name' );
    register_setting( 'mena-settings-group', 'mena_merchant_password' );
    register_setting( 'mena-settings-group', 'mena_pickup_location' );
    register_setting( 'mena-settings-group', 'mena_fetch_status');
    register_setting( 'mena-settings-group', 'mena_servcie_type');
    register_setting( 'mena-settings-group', 'mena_merchant_phone_number');
    // register_setting( 'mena-settings-group', 'erp_user_name');
    register_setting( 'mena-settings-group', 'mena_is_production');
    register_setting( 'mena-settings-group', 'mena_is_uae_only');
    register_setting( 'mena-settings-group', 'mena_is_auto_push');

}

function mena_setting_page() {
    ?>
    <div class="wrap">
        <h2> fetchr Integration</h2>

        <form method="post" action="options.php">
            <?php settings_fields( 'mena-settings-group' ); ?>
            <?php do_settings_sections( 'mena-settings-group' ); ?>
            <table class="form-table">


                <!-- <tr valign="top">
                    <th scope="row">ERP Username/merchant for fulfilment</th>
                    <td><input type="text" name="erp_user_name" value="<?php echo esc_attr(get_option('erp_user_name')); ?>" /></td>
                </tr> -->

                <tr valign="top">
                    <th scope="row">fetchr Username</th>
                    <td><input type="text" name="mena_merchant_name" value="<?php echo esc_attr( get_option('mena_merchant_name') ); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">fetchr Password</th>
                    <td><input type="text" name="mena_merchant_password" value="<?php echo esc_attr( get_option('mena_merchant_password') ); ?>" /></td>
                </tr>


                <tr valign="top">
                    <th scope="row">Merchant registered phone number</th>
                    <td><input type="text" name="mena_merchant_phone_number" value="<?php echo esc_attr( get_option('mena_merchant_phone_number') ); ?>" /></td>
                </tr>


                <tr valign="top">
                    <th scope="row">Service Type</th>
                    <td>

                        <select name="mena_servcie_type" >
                            <option value="delivery" <?php if (get_option('mena_servcie_type') == "delivery"): ?> selected="selected" <?php endif; ?>>Delivery Only</option>
                            <option value="fulfil_delivery" <?php if (get_option('mena_servcie_type') == "fulfil_delivery"): ?> selected="selected" <?php endif; ?>> Fulfilment + Delivery</option>
                        </select>

                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Live Server</th>
                    <td>
                        <input name="mena_is_production" type="checkbox" value="1" <?php if (get_option('mena_is_production') == "1"): ?> checked="checked" <?php endif; ?> />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">For UAE Only</th>
                    <td>
                        <input name="mena_is_uae_only" type="checkbox" value="1" <?php if (get_option('mena_is_uae_only') == "1"): ?> checked="checked" <?php endif; ?> />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Auto Push?</th>
                    <td>
                        <input name="mena_is_auto_push" type="checkbox" value="1" <?php if (get_option('mena_is_auto_push') == "1"): ?> checked="checked" <?php endif; ?> />
                    </td>
                </tr>

            </table>

            <?php submit_button(); ?>


        </form>
        <form method="post" action="">
          <input type="submit" name="push_orders" id="push_orders" class="button button-primary" value="Push Orders">
        </form>
    </div>
    <?php

if (isset($_POST['push_orders']) ){
   hit_mena_api();
}

}
// setting a  cron to run hourly
add_action( 'wp', 'setup_schedule_event' );
function setup_schedule_event()
{
    if ( ! wp_next_scheduled( 'prefix_hourly_event' ) )
    {
        wp_schedule_event( time(), 'hourly', 'prefix_hourly_event');
    }
}

 if (get_option('mena_is_auto_push') == "1"){
   add_action( 'prefix_hourly_event', 'hit_mena_api' );
  }

function hit_mena_api()
{
    $server_url = "http://dev.menavip.com/";

    if (get_option('mena_is_production') == "1")
    {
        $server_url = "http://menavip.com/";
    }

    //echo $server_url;

    if ( get_option("mena_fetch_status") )
    {
        $where = array( get_option("mena_fetch_status") );
    }
    else
    {
        // $where = array("wc-processing");

      if (get_option('mena_is_auto_push') == "1"){
        $where = array("wc-processing");
      }else{
        $where = array("wc-ship-with-fetchr");
      }
        //$where = array_keys( wc_get_order_statuses() );
    }
    $orders = get_posts( array(
	          'numberposts'       => -1,
            'post_type'   => 'shop_order',
            'post_status' => $where
        )
    );
    // var_dump($orders);
    // exit;
    foreach ($orders as $order)
    {
        $shipping_country = get_post_meta($order->ID,'_shipping_country',true);
        $order_wc = new WC_Order( $order->ID );
        //  Check if the plugin for UAE only
        if (get_option('mena_is_uae_only') == "1" &&  $shipping_country !=="AE")
            continue;

        if( get_option('mena_servcie_type') == "fulfil_delivery")
        {
            // fulfilment + delivery
            $products = $order_wc->get_items();
            menavip_fulfil_delivery ($order,$order_wc,$products,$server_url);
        }
        else
        {
            // delivery only
            menavip_delivery_only ($order,$order_wc,$server_url);
        }

    }

}

/* =================================================== Adding Status and Color icon ============================================================= */
function register_erp_order_status()
{
    register_post_status( 		'wc-fetchr-processing', array(
            'label'                     => 'fetchr Processing',
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'fetchr Processing <span class="count">(%s)</span>', 'fetchr Processing <span class="count">(%s)</span>' )
        )
    );
    register_post_status( 		'wc-ship-with-fetchr', array(
            'label'                     => 'Ship With Fetchr',
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Ship With Fetchr <span class="count">(%s)</span>', 'Ship With Fetchr <span class="count">(%s)</span>' )
        )
    );
}
add_action( 'init', 'register_erp_order_status' );
// Add to list of WC Order statuses
function add_fetchr_processing_to_order_statuses( $order_statuses ) {

    $new_order_statuses = array();
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {

        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key )
        {
            $new_order_statuses['wc-fetchr-processing'] = 'Fetchr Processing';
            $new_order_statuses['wc-ship-with-fetchr'] = 'Ship With Fetchr';
        }

    }

    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_fetchr_processing_to_order_statuses' );

// setting a wp test cron to run hourly
// Colors for icons

function menavip_delivery_only ($order,$order_wc,$url)
{

  $str_search_for = array('"','&');
  $str_replace_with = array('\'\'', 'and');


        if($order_wc->payment_method == "cod"){
            $payment_method = "COD";
            $grand_total = $order_wc->get_total();
        }else{
            $payment_method = "CD";
            $grand_total = "0";
        }
        // str_replace($str_search_for,$str_replace_with, $array)
    $data = array(
        'username' 		 => get_option('mena_merchant_name'),
        'password' 		 => get_option('mena_merchant_password'),
        'method' 		 => 'create_orders',
        'pickup_location'=> 'dubai',  //get_option('mena_pickup_location'),
        'data' => array(
            array(
                'order_reference'  	=> 	  $order->ID,
                'name' 		       		=> 	  str_replace($str_search_for,$str_replace_with, $order_wc->shipping_first_name." ".$order_wc->shipping_last_name),
                'email' 			      =>    $order_wc->billing_email,
                'phone_number'	 	  =>    $order_wc->billing_phone,
                'address' 			    =>    str_replace($str_search_for,$str_replace_with, $order_wc->get_formatted_shipping_address()),
                'city' 				      =>    str_replace($str_search_for,$str_replace_with, $order_wc->shipping_city),
                'payment_type' 	   	=>    $payment_method,
                'amount' 			      =>    $grand_total,
                'description'	    	=>	  time(),
                'comments'		    	=>	  str_replace($str_search_for,$str_replace_with, $order_wc->customer_message."   ".$order_wc->customer_note),
                //'item'
            )));
    // echo '<pre>';
    // print_r($data);
    // exit;

    $url = $url."client/api/";
    $data_string = "args=" . json_encode($data, JSON_UNESCAPED_UNICODE);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    $results = curl_exec($ch);
    print $results;
    $results = json_decode($results);
    if ($results->status == "success")
    {
        // Change Status Here to ERP Processing
        $order_wc->update_status( 'wc-fetchr-processing' );
        // Create A custom field Airway bill number and update it

        if ( ! update_post_meta ($order->ID, 'awb', $results->shipment_data ))
        {
            add_post_meta($order->ID, 'awb', $results->shipment_data, true );
        }

    }elseif($results["awb"] == "SKU not found"){
        echo "<br />Missing SKU's for order ($order->ID):<br />";
        foreach($item_list as $item){
        echo "- ". $item['sku'] ."<br>";
        }
    }

}

function menavip_fulfil_delivery ($order,$order_wc,$products,$url)
{



  $str_search_for = array('"','&');
  $str_replace_with = array('\'\'', 'and');

   //print_r($product);
    $item_list  = array ();
    $products_qty = array ();

  // foreach ($products as $product)
  //   {
  //       $sku = $product_obj->get_sku();
  //       $products_qty[$sku] += $product['qty'];
  //   }
  //   //print_r($product);


    $item_list  = array ();

    foreach ($products as $product)
    {
        if($product['variation_id'] != 0){
            $product_obj = new WC_Product($product['variation_id']);
        }else{
            $product_obj = new WC_Product($product['product_id']);
        }
        $sku = $product_obj->get_sku();
        $n_product = array (
            'client_ref'  	 	  => $order->ID,
            'name' 		 	    	  => str_replace($str_search_for,$str_replace_with, $product["name"]),
            'sku'		 	       	  => $sku,
            'quantity' 	 	   	  => $product["qty"],
            'merchant_details' 	=> array(
                'mobile' 			  => trim(get_option('mena_merchant_phone_number')),
                'phone'			  	=> trim(get_option('mena_merchant_phone_number')),
                'name' 		   		=> get_option('mena_merchant_name'),
                'address' 			=> ' NO '
            ),
            'COD' 			       	=> $order_wc->get_total_shipping(),// $order_wc->order_shipping
            'price'		 	       	=> $product_obj->price ,//,$product_obj->get_regular_price()
            'is_voucher'    		=> 'No'
        );
        array_push($item_list,$n_product);

    }// product foreach loop

    $coupon_amount = 0;
    if( $order_wc->get_used_coupons() ) {
        foreach( $order_wc->get_used_coupons() as $coupon) {
          $WC_Coupon = new WC_Coupon($coupon);
          $coupon_amount = $coupon_amount + $WC_Coupon->coupon_amount;
        }
    }

    if($order_wc->payment_method == "cod"){
        $payment_method = "COD";
        $grand_total = $order_wc->get_total();
    }else{
        $payment_method = "CD";
        $grand_total = "0";
    }
    $datalist = array(array ('order' => array(
        'items' => $item_list,
        'details' => array(
            'status' 				      => '',
            'discount'			 	    => $coupon_amount,
            'grand_total'	        => $grand_total,
            'payment_method' 		  => $payment_method,
            'order_id' 			    	=> $order->ID,
            'customer_firstname' 	=> str_replace($str_search_for,$str_replace_with,$order_wc->shipping_first_name),
            'customer_lastname' 	=> str_replace($str_search_for,$str_replace_with,$order_wc->shipping_last_name),
            'customer_mobile'		  => $order_wc->billing_phone,
            'customer_email' 		  => $order_wc->billing_email,
            'order_address' 	   	=> str_replace($str_search_for,$str_replace_with,$order_wc->get_formatted_shipping_address())
        )
    )));

    // echo '<pre>';
    // print_r($datalist);
    // exit;

    $ERPdata 		= "ERPdata=".json_encode($datalist, JSON_UNESCAPED_UNICODE);
    $erpuser		=  get_option('mena_merchant_name');	// "apifulfilment";
    $erppassword	=  get_option('mena_merchant_password'); //"apifulfilment";
    $merchant_name	= "MENA360 API";// get_option('erp_user_name');  //"API Test";//
    $ch 			= curl_init();
    $url 			= $url."client/gapicurl/";
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $ERPdata."&erpuser=".$erpuser."&erppassword=".$erppassword."&merchant_name=".$merchant_name);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec ($ch);
    echo $response;
    $results = json_decode($response,TRUE);
    #echo '<pre>';
    #print_r($results);
    #exit;
    if ($results["success"] == 1)
    {
        // Change Status Here to ERP Processing
        $order_wc->update_status( 'wc-fetchr-processing' );
        // $order_wc->reduce_order_stock();
        // Create A custom field Airway bill number and update it

        if ( ! update_post_meta ($order->ID, 'awb', $results['response']['tracking_no'] ))
        {
            add_post_meta($order->ID, 'awb', $results['response']['tracking_no'], true );
        }

    }


    curl_close ($ch);


} // END of function



function wc_order_status_styling() {
    echo '<style>
 .widefat .column-order_status mark.on-hold:after, .widefat .column-order_status mark.completed:after,
 .widefat .column-order_status mark.cancelled:after, .widefat .column-order_status mark.processing:after,
 .widefat .column-order_status mark.fetchr-processing:after {
 font-size: 2em;
 }
 /* Processing Ellipsis */
 .widefat .column-order_status mark.processing:after {
 color: #2529d7;
 content: "\e011";
 }
 .fetchr-processing.tips:after {
  color: #73a724 !important;
}
 /* On-Hold Dash */
 .widefat .column-order_status mark.on-hold:after {
 color: #555555;
 content: "\e033";
 }
 /* Cancelled X */
 .widefat .column-order_status mark.cancelled:after {
 color: #d72525;
 content: "\e013";
 }
 /* Completed Checkmark */
 .widefat .column-order_status mark.completed:after {
 color: #32d725;
 content: "\e015";
 }

 .widefat .column-order_status mark.fetchr-processing, .widefat .column-order_status mark.ship-with-fetchr, .view.ship-with-fetchr::after{
 color: #32d725 !important;
 content: " ";
 height: 30px;
 width: 50px;
 background-image: url("https://m.fetchr.us/image/logo_full.png");
 background-size: contain;
 background-repeat: no-repeat;
 }
 .widefat .column-order_status mark.ship-with-fetchr, .view.ship-with-fetchr::after {
   background-image: url("https://m.fetchr.us/image/favicon.png");
   height: 24px;
   width: 24px;
 }
 </style>';
}
add_action('admin_head', 'wc_order_status_styling');



add_action('admin_footer-edit.php', 'custom_bulk_admin_footer');

function custom_bulk_admin_footer() {

    global $post_type;

    if($post_type == 'shop_order') {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('<option>').val('mark_ship-with-fetchr').text('<?php _e('Mark Fetchr Ship')?>').appendTo("select[name='action']");
        });
    </script>
    <?php
    }
  }


  add_filter( 'woocommerce_admin_order_actions', 'add_fetchr_ship_actions_button', PHP_INT_MAX, 2 );

  
  function add_fetchr_ship_actions_button( $actions, $the_order ) {
    if ( ! $the_order->has_status( array( 'ship-with-fetchr' ) ) ) { // if order is not cancelled yet...
          $actions['ship-with-fetchr'] = array(
              'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=ship-with-fetchr&order_id=' . $the_order->id ), 'woocommerce-mark-order-status' ),
              'name'      => __( 'Ship with Fetchr', 'woocommerce' ),
              'action'    => "view ship-with-fetchr", // setting "view" for proper button CSS
          );
        }
      return $actions;
    }
