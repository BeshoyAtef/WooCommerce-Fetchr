<?php ob_start();
/**
 * Plugin Name: WooCommerce With fetchr
 * Plugin URI: http://fetchr.us
 * Description: A plugin to integrate fetchr shipping with woo commerce.
 * Version: 1.3.5
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

// Report all errors except E_NOTICE
// error_reporting(E_ALL ^ E_NOTICE);

// foreach ( glob( plugin_dir_path( 'google-cloud-print-library' ) . "*.php" ) as $file ) {
//     include_once $file;
// }
// include(plugin_dir_path( 'google-cloud-print-library' ) . 'google-cloud-print-library.php');
require_once( ABSPATH . '/wp-content/plugins/google-cloud-print-library/google-cloud-print-library.php');




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
                    <th scope="row">Pickup Location Adrress</th>
                    <td><input type="text" name="mena_pickup_location" value="<?php echo esc_attr( get_option('mena_pickup_location') ); ?>" /></td>
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
        <hr>
        <table>
            <tr>
                <td>
                    <form method="post" action="">
                      <input type="submit" name="push_orders" id="push_orders" class="button button-primary" value="Push Orders">
                    </form>
                </td>
                <td>
                    <form method="post" action="">
                      <input type="submit" name="updateOrderStatus" id="updateOrderStatus" class="button button-primary" value="Update Order Status">
                    </form>
                </td>
                <td>
                    <form method="post" action="">
                      <input type="submit" name="test_print" id="test_print" class="button button-primary" value="test">
                    </form>
                </td>
            </tr>
        </table>



            <hr>
        <form method="post" action="">
            <table class="form-table">
                <tr valign="top">
                    <th>Order Status to send</th>
                    <td>
                         <select name="order_status">
                            <option value=''> All</option>
                        <?php $wcr = wc_get_order_statuses(); ?>
                         <?php $p=1;asort($wcr);reset($wcr);
                         while (list ($p, $val) = each ($wcr)) {
                         echo '<option value="'.$p.'">'.$val;
                         } ?>
                         </select>
                
                    </td>

                </tr>
                <tr valign="top">
                    <th>Msg to Send: تباء الرسالة ب عزيزي تم اسم العميل</th>
                    <td><textarea type="textarea" name="msg_to_send" value="" rows="10" cols="20" /> Add the text you want to send via whats app</textarea></td>
                </tr>
                <tr valign="top">
                    <th> Send now -> </th>
                    <td><input type="submit" name="Test_send" id="test_send" class="button button-primary" value="Test_send"></td>
                </tr>
                  
                
            </table>

        </form>

    </div>

    <?php

if (isset($_POST['push_orders']) ){
   hit_mena_api();
}

if (isset($_POST['updateOrderStatus']) ){

   updateOrderStatus();

}

if (isset($_POST['Test_send']) ){
     $args = array(
        'status' => ''.$_POST['order_status'],
        
        'limit' => -1,
    );
     print_r($args);
    $orders = wc_get_orders( $args );
    foreach ($orders as $order_wc)
    {
        $order_data = $order_wc->get_data(); // The Order data
        $name= $order_data['shipping']['first_name']." ".$order_data['shipping']['last_name'];
        $number=$order_data['billing']['phone'];
        $msg="عزيزي ".$name.$_POST['msg_to_send'];
        send_whatsapp($msg,$number);
    }

   

}


if (isset($_POST['test_print']) ){

     $args = array(
        'status' => 'wc-fetchr-processing',
        
        'limit' => 1,
    );
    $orders = wc_get_orders( $args );
   
    foreach ($orders as $order_wc)
    {
            $description = '';
            $products = $order_wc->get_items();
           foreach ($products as $product) {
             $description = $description . $product['name'].' - Qty: '.$product['qty'].', ';
           }

             $str_search_for = array('"','&');
             $str_replace_with = array('\'\'', 'and');
		
		

             $order_id = (string)$order_wc->get_order_number();
		$grand_total=0;
		
		    $data = array(
        'username'       => get_option('mena_merchant_name'),
        'password'       => get_option('mena_merchant_password'),
        'method'         => 'pickup_orders',
//         'pickup_location'=> get_option('mena_pickup_location'),
        'data' => array(
            array(
                'order_reference'   =>    "$order_id",
                'name'                  =>    str_replace($str_search_for,$str_replace_with, $order_wc->shipping_first_name." ".$order_wc->shipping_last_name),
                'email'                   =>    $order_wc->billing_email,
                'phone_number'        =>    $order_wc->billing_phone,
                'address'               =>    str_replace($str_search_for,$str_replace_with, $order_wc->get_formatted_shipping_address()),
                'city'                    =>    str_replace($str_search_for,$str_replace_with, $order_wc->shipping_city),
                'payment_type'      =>    'CD',
                'amount'                  =>   $grand_total ,
                'description'           =>    str_replace($str_search_for,$str_replace_with, $description),
                'comments'              =>    str_replace($str_search_for,$str_replace_with, $order_wc->customer_message."   ".$order_wc->customer_note),
                //'item'
            )));



   


               $url = "http://menavip.com/client/api/";
				$data_string = "args=" . json_encode($data, JSON_UNESCAPED_UNICODE);
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				$results = curl_exec($ch);
				// print $results;
				$results = json_decode($results);
				curl_close($ch);

               print_r($results);

        }

    }

}

    function send_whatsapp($msg,$number){
        $text=$msg;

        $datalist_wab = array(

            'token' => '6b359ae0c0b1cec41b1c892fbd2f975f5afa812429b0e',
            'uid' => '201285304127',
            'to' => ''.$number,
            'text' => $text
        );

        $url_wab = "https://www.waboxapp.com/api/send/chat";
        $ch_wab = curl_init($url_wab);
        curl_setopt($ch_wab, CURLOPT_POST, true);
        curl_setopt($ch_wab, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_wab, CURLOPT_POSTFIELDS, $datalist_wab);
        $results_wab = curl_exec($ch_wab);
        curl_close($ch_wab);
        return $results_wab;   
    }

    function updateOrderStatus(){
     $args = array(
        'status' => 'wc-fetchr-processing',
        
        'limit' => -1,
    );
    $orders = wc_get_orders( $args );
    // print_r($orders);
    $tracking_orders= array();
    $tracking_list= array();
    foreach ($orders as $order)
    {
        $meta_array=$order->get_meta('awb',false,'view');
        if (!empty($meta_array)) {
         // list is not empty.
            print("<br><br>------------ meta array <br>");
            print_r($meta_array);
            $meta_obj = array_pop(array_reverse($meta_array));
            print("<br><br>------------ OBJ <br>");
            print_r($meta_obj);
            try {$meta_data = $meta_obj->get_data();
                } catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            $awb_no=end($meta_data);
            print("<br><br>------------ Data <br>");
            print_r($meta_data);
            print("<br><br>------------ AWB <br>");
            print_r($awb_no);

            $tracking_orders[$order->get_id()]=$awb_no;
            array_push($tracking_list, $awb_no );

            // print("------------".$meta->get_data());
            // print_r($tracking_orders);
            // print_r($tracking_list);
        }
    }
     $BulkStatus=getBulkStatus($tracking_list);
    print("<br><br>------------ Bulk Status <br>");
     print_r($BulkStatus);

     foreach ($orders as $order)
     {
        $meta_array=$order->get_meta('awb',false,'view');
        if (!empty($meta_array)) {
            print("<br><br>------------ meta array <br>");
            print_r($meta_array);
            $meta_obj = array_pop(array_reverse($meta_array));
            print("<br><br>------------ OBJ <br>");
            print_r($meta_obj);
            try {$meta_data = $meta_obj->get_data();
                } catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            $awb_no=end($meta_data);
            print("<br><br>------------ Data <br>");
            print_r($meta_data);
            print("<br><br>------------ AWB <br>");
            print_r($awb_no);

            // $meta_data = array_pop(array_reverse($meta_obj));
             
             $awb_no=end($meta_data);
            print '<br> '.$order->get_id().' : Order Status'.$BulkStatus[$awb_no];
            if ( ! update_post_meta ($order->get_id(), 'order status',$BulkStatus[$awb_no]))
            {
                add_post_meta($order->get_id(), 'order status',$BulkStatus[$awb_no], true );
            }
                print ('<br>---------- Status: ');
                print_r($BulkStatus[$awb_no]);
                print ('<br>---------- : ');

            if(strpos($BulkStatus[$awb_no], 'Delivered on') !== false){
                $order->update_status( 'completed' );
            }elseif (strpos($BulkStatus[$awb_no], 'Cancelled on') !== false ) {
                # code...
                $order->update_status( 'cancelled' );
            }elseif (strpos($BulkStatus[$awb_no], 'Returned') !== false ) {
                # code...
                $order->update_status( 'wc-customer-cancel' );
            }elseif (strpos($BulkStatus[$awb_no], 'Cancelled') !== false ) {
                # code...
                $order->update_status( 'wc-customer-cancel' );
            }elseif (strpos($BulkStatus[$awb_no], 'Attempted delivery') !== false ) {
                # code...
                if ($order->get_meta('whatsapp_followup',false,'view')){
                    //no-sing at all
                }else{
                    $datalist_wab = array(

                        'token' => '6b359ae0c0b1cec41b1c892fbd2f975f5afa812429b0e',
                        'uid' => '201285304127',
                        'to' => ''.$order->billing_phone,
                        'text' => 'اهلا يا '.$order->shipping_first_name.' 
                        لقد حاولنا توصيل الطلب لكم و لم نتمكن من الوصول اليكم - لسرعة الشحن يرجي اختيار معاد التوصيل الجديد من الرابط التالي '.' https://track.fetchr.us/schedule/?tracking_id='.$awb_no.' |-| '
                    );

                    $url_wab = "https://www.waboxapp.com/api/send/chat";
                    // $data_string = "args=" . json_encode($datalist, JSON_UNESCAPED_UNICODE);
                    $ch_wab = curl_init($url_wab);
                    curl_setopt($ch_wab, CURLOPT_POST, true);
                    curl_setopt($ch_wab, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_wab, CURLOPT_POSTFIELDS, $datalist_wab);
                    $results_wab = curl_exec($ch_wab);
                    // print $results_wab;
                    // print $results->$order_id; // under testing
                    // $results_wab = json_decode($results_wab);
                    curl_close($ch_wab);

                    if ( ! update_post_meta ($order->get_id(), 'whatsapp_followup',$results_wab))
                    {
                        add_post_meta($order->get_id(), 'whatsapp_followup',$results_wab, true );
                    }
                }
            }elseif (strpos($BulkStatus[$awb_no], 'Customer care') !== false ) {
                # code...
                if ($order->get_meta('whatsapp_followup_custumercare',false,'view')){
                    //no-sing at all
                }else{
                    $datalist_wab = array(

                        'token' => '6b359ae0c0b1cec41b1c892fbd2f975f5afa812429b0e',
                        'uid' => '201285304127',
                        'to' => ''.$order->billing_phone,
                        'text' => 'اهلا يا '.$order->shipping_first_name.' 
                        لقد حاولنا التواصل معكم لتحديد معاد استلام الطلب و لم نتمكن  - لسرعة الشحن يرجي اختيار معاد التوصيل الجديد من الرابط التالي '.' https://track.fetchr.us/schedule/?tracking_id='.$awb_no.' |-| '
                    );

                    $url_wab = "https://www.waboxapp.com/api/send/chat";
                    // $data_string = "args=" . json_encode($datalist, JSON_UNESCAPED_UNICODE);
                    $ch_wab = curl_init($url_wab);
                    curl_setopt($ch_wab, CURLOPT_POST, true);
                    curl_setopt($ch_wab, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_wab, CURLOPT_POSTFIELDS, $datalist_wab);
                    $results_wab = curl_exec($ch_wab);
                    // print $results_wab;
                    // print $results->$order_id; // under testing
                    // $results_wab = json_decode($results_wab);
                    curl_close($ch_wab);
                    
                    if ( ! update_post_meta ($order->get_id(), 'whatsapp_followup_custumercare',$results_wab))
                    {
                        add_post_meta($order->get_id(), 'whatsapp_followup_custumercare',$results_wab, true );
                    }

                }     
            }elseif (strpos($BulkStatus[$awb_no], 'Received at distribution') !== false ) {
                # code...
                if ($order->get_meta('at_center_Followup',false,'view')){
                    //no-sing at all
                }else{
                    $datalist_wab = array(

                        'token' => '6b359ae0c0b1cec41b1c892fbd2f975f5afa812429b0e',
                        'uid' => '201285304127',
                        'to' => ''.$order->billing_phone,
                        'text' => 'اهلا يا '.$order->shipping_first_name.' 
                         لسرعة الشحن يرجي اختيار معاد التوصيل من الرابط التالي '.' https://track.fetchr.us/schedule/?tracking_id='.$awb_no.' |-| سيتم تفعيل الرابط حتي تتمكن من اختيار معاد التسليم او تعقب الطلب'
                    );

                    $url_wab = "https://www.waboxapp.com/api/send/chat";
                    // $data_string = "args=" . json_encode($datalist, JSON_UNESCAPED_UNICODE);
                    $ch_wab = curl_init($url_wab);
                    curl_setopt($ch_wab, CURLOPT_POST, true);
                    curl_setopt($ch_wab, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_wab, CURLOPT_POSTFIELDS, $datalist_wab);
                    $results_wab = curl_exec($ch_wab);
                    // print $results_wab;
                    // print $results->$order_id; // under testing
                    // $results_wab = json_decode($results_wab);
                    curl_close($ch_wab);
                    
                    if ( ! update_post_meta ($order->get_id(), 'at_center_Followup',$results_wab))
                    {
                        add_post_meta($order->get_id(), 'at_center_Followup',$results_wab, true );
                    }

                }     
            }
            
        }
     }
    }

    function getBulkStatus($order_ids)
    {
        $ordersStatus = array();
        $data   =   array(          
                            'username'       => 'Twista123',
                            'password'       => 'Twista123',
                            'method' => 'get_status_bulk',
                            'data' =>  $order_ids
                         );

        $url            = 'http://menavip.com/api/get-status/';
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        $jsonResponse = curl_exec($ch);
        curl_close($ch);
        // print("<br><br>------------ Json Response <br>");
        // print_r($jsonResponse);
        // print $jsonResponse;
        $arrayResponse  = json_decode($jsonResponse,true);
        $arrayResponse = $arrayResponse['response'];
        print_r($arrayResponse);

        if (!empty($arrayResponse)) {
            foreach($arrayResponse as $ark => $arv) {
                $ordersStatus[$arv['tracking_no']] = $arv['package_state']; 
            }
        }

        return $ordersStatus;
    }
    // function getBulkStatus($order_ids)
    // {
    //     $ordersStatus = array();
    //     $data   =   array(  'username' => 'Twista123',
    //                         'password' => 'Twista123',
    //                         'method' => 'get_status_bulk',
    //                         'data' =>  $order_ids
    //                      );
  
    //     $data_string    = json_encode($data);
    //     $url            = 'http://menavip.com/api/get-status/';
    //     $jsonResponse   = \Httpful\Request::post($url)
    //                     ->sendsJson()
    //                     ->body($data_string)
    //                     ->sendIt();
        
    //     $arrayResponse  = json_decode($jsonResponse->body, true);
    //     foreach($arrayResponse['response'] as $ark => $arv) {
    //         $ordersStatus[$arv['tracking_no']] = $arv['package_state']; 
    //     }
    //     return json_encode($ordersStatus);
    // }


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
      }
      if (isset($_POST['push_orders'])) {
        $where = array("wc-ship-with-fetchr");
      }
        //$where = array_keys( wc_get_order_statuses() );
    }
    $orders = get_posts( array(
              'numberposts'       => 9,
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

        $products = $order_wc->get_items();

        if( get_option('mena_servcie_type') == "fulfil_delivery")
        {
            // fulfilment + delivery
            menavip_fulfil_delivery ($order,$order_wc,$products,$server_url);
        }
        else
        {
            $gcpl = new GoogleCloudPrintLibrary_GCPL_v2();
            // delivery only
            menavip_delivery_only ($order,$order_wc,$products,$server_url,$gcpl);
        }

    }

}

/* =================================================== Adding Status and Color icon ============================================================= */
function register_erp_order_status()
{
    register_post_status(       'wc-fetchr-processing', array(
            'label'                     => 'fetchr Processing',
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'fetchr Processing <span class="count">(%s)</span>', 'fetchr Processing <span class="count">(%s)</span>' )
        )
    );
    register_post_status(       'wc-ship-with-fetchr', array(
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

function menavip_delivery_only ($order,$order_wc,$products,$url,$gcpl)
{
$description = '';

foreach ($products as $product) {
  $description = $description . $product['name'].' - Qty: '.$product['qty'].', ';
}

  $str_search_for = array('"','&');
  $str_replace_with = array('\'\'', 'and');

  $order_id = (string)$order_wc->get_order_number();


        if($order_wc->payment_method == "cod"){
            $payment_method = "COD";
            $grand_total = $order_wc->get_total();
        }else{
            $payment_method = "CD";
            $grand_total = "0";
        }
        // str_replace($str_search_for,$str_replace_with, $array)
    $data = array(
        'username'       => get_option('mena_merchant_name'),
        'password'       => get_option('mena_merchant_password'),
        'method'         => 'create_orders',
        'pickup_location'=> 'Villa 186, Banafseg 10 , Tagamo3 1 , New Cairo , Cairo, Egypt',
        'data' => array(
            array(
                'order_reference'   =>    "$order_id",
                'name'                  =>    str_replace($str_search_for,$str_replace_with, $order_wc->shipping_first_name." ".$order_wc->shipping_last_name),
                'email'                   =>    $order_wc->billing_email,
                'phone_number'        =>    $order_wc->billing_phone,
                'address'               =>    str_replace($str_search_for,$str_replace_with, $order_wc->get_formatted_shipping_address()),
                'city'                    =>    str_replace($str_search_for,$str_replace_with, $order_wc->shipping_city),
                'payment_type'      =>    $payment_method,
                'amount'                  =>    $grand_total,
                'description'           =>    str_replace($str_search_for,$str_replace_with, $description),
                'comments'              =>    str_replace($str_search_for,$str_replace_with, $order_wc->customer_message."   ".$order_wc->customer_note),
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
    // print $results;
    $results = json_decode($results);
    curl_close($ch);
    if ($results->status == "success")
    {
        // Change Status Here to ERP Processing
        // to-do : add checkups to check if meta exists then ignore
        $order_wc->update_status( 'wc-fetchr-processing' );
        if ( ! update_post_meta ($order->ID, 'awb', $results->$order_id ))
        {
            add_post_meta($order->ID, 'awb', $results->$order_id, true );
        }
        if ( ! update_post_meta ($order->ID, 'ywot_tracking_code', $results->$order_id ))
        {
            add_post_meta($order->ID, 'ywot_tracking_code', $results->$order_id, true );
        }
        if ( ! update_post_meta ($order->ID, 'ywot_carrier_name', 'Fetchr' ))
        {
            add_post_meta($order->ID, 'ywot_carrier_name', 'Fetchr', true );
        }
        
        
        print('<br>Awb:'.$results->$order_id);
        
        $results_wab=send_whatsapp_tracking($order_wc,$order,$results->$order_id,$description);
        if ( ! update_post_meta ($order->ID, 'wab_uid', $results_wab ))
        {
            add_post_meta($order->ID, 'wab_uid', $results_wab, true ); 
        }
        print(' | Whats App:'.$results_wab);
        

        try {
            $file_url=get_awb_pdf($results->$order_id);
            print(' | pdf:'.$file_url);
            $printed_awb=print_awb($file_url,$order_wc->get_id(),$gcpl); 
        } catch (Exception $e) {
            $file_url=$e->getMessage();
            $printed_awb=$e->getMessage();
        }
        if ( ! update_post_meta ($order->ID, 'wab_uid', $results_wab ))
        {
            add_post_meta($order->ID, 'printed_awb', $printed_awb, true ); 
        }
        print(' | pdf:'.$file_url);
        print(' | Printed:'.$printed_awb);
        
        try {
            WC()->mailer()->get_emails()['WC_Email_New_Order']->trigger( $order_id );
            add_post_meta($order->ID, 'Invoice Sent', 'True', true ); 
        } catch (Exception $e) {
            add_post_meta($order->ID, 'Invoice Sent', $e->getMessage(), true ); 
        }


        $order_id = $order_wc->get_id();
        wc_reduce_stock_levels( $order_id );


    }

}


    function send_whatsapp_tracking($order_wc,$order,$tracking_no,$description){
        $text='عزيزي '.$order_wc->shipping_first_name.' لقد تم تأكيد طلبك بنجاح -
            : المتجات :'.$description.' 
            : علي العنوان '.$order_wc->get_formatted_shipping_address().'
            : مجموع الطلب بالشحن'.$order_wc->get_total().'';

        $datalist_wab = array(

            'token' => '6b359ae0c0b1cec41b1c892fbd2f975f5afa812429b0e',
            'uid' => '201285304127',
            'to' => ''.$order_wc->billing_phone,
            'text' => $text
        );

        $url_wab = "https://www.waboxapp.com/api/send/chat";
        // $data_string = "args=" . json_encode($datalist, JSON_UNESCAPED_UNICODE);
        $ch_wab = curl_init($url_wab);
        curl_setopt($ch_wab, CURLOPT_POST, true);
        curl_setopt($ch_wab, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_wab, CURLOPT_POSTFIELDS, $datalist_wab);
        $results_wab = curl_exec($ch_wab);
        // print $results_wab;
        // print $results->$order_id; // under testing
        // $results_wab = json_decode($results_wab);
        curl_close($ch_wab);

        return $results_wab;   
    }
// 
    function get_awb_pdf($tracking_no) {
    // function get_awb_pdf($results,$url) {
        // $url = $url."client/api/awb";
        $ch = curl_init('https://business.fetchr.us/api/client/awb');
        $authorization_token ="5a0308b95bcd81b626fadfbcb7a0dc945e";
        $data = json_encode(["format" => 'pdf',
                    "type" => 'mini',
                    "search_key" => 'tracking_no',
                    "search_value" => [$tracking_no],
                    "start_date" => null,
                    "end_date" => null,
                ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER,
                        array(
                            "authorization: $authorization_token",
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($data)
                        )
                    );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result_awb = curl_exec($ch);
        $result_awb = json_decode($result_awb,true);
        // print $result_awb;
        curl_close($ch);
        return $result_awb['data']; 
    }


    function print_awb($pdf_url,$order_id,$gcpl) {

        if (class_exists('GoogleCloudPrintLibrary_GCPL_v2')) {

            // The first parameter to print_document() is the printer ID. Use false to send to the default. You can use the get_printers() method to get a list of those available.


            $pdf=array("pdf-file"=>$pdf_url);
            // print_r($pdf);

            $printed = $gcpl->print_document(false, ''.$order_id, $pdf, $prepend = false, $copies = false);

            // Parse the results
            if (!isset($printed->success)) {
                trigger_error('Unknown response received from GoogleCloudPrintLibrary_GCPL->print_document()', E_USER_NOTICE);
            } elseif ($printed->success !== true) {
                trigger_error('GoogleCloudPrintLibrary_GCPL->print_document(): printing failed: '.$printed->message, E_USER_NOTICE);
            }
        }
        return $printed->success;

    }


function menavip_fulfil_delivery ($order,$order_wc,$products,$url)
{



  $str_search_for = array('"','&');
  $str_replace_with = array('\'\'', 'and');

  $order_id = (string)$order_wc->get_order_number();


   //print_r($product);


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
            'client_ref'          => "$order_id",
            'name'                    => str_replace($str_search_for,$str_replace_with, $product["name"]),
            'sku'                     => $sku,
            'quantity'            => $product["qty"],
            'merchant_details'  => array(
                'mobile'              => trim(get_option('mena_merchant_phone_number')),
                'phone'             => trim(get_option('mena_merchant_phone_number')),
                'name'              => get_option('mena_merchant_name'),
                'address'           => ' NO '
            ),
            'COD'                   => $order_wc->get_total_shipping(),// $order_wc->order_shipping
            'price'                 => $product_obj->price ,//,$product_obj->get_regular_price()
            'is_voucher'            => 'No'
        );
        array_push($item_list,$n_product);

    }// product foreach loop

    //
    // $discount_amount = 0;
    // if( $order_wc->get_used_coupons() ) {
    //     foreach( $order_wc->get_used_coupons() as $coupon) {
    //       $WC_Coupon = new WC_Coupon($coupon);
    //       $discount_amount = $discount_amount + $WC_Coupon->coupon_amount;
    //     }
    // }

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
            'status'                      => '',
            'discount'                  => $order_wc->get_total_discount(),
            'grand_total'           => $grand_total,
            'payment_method'          => $payment_method,
            'order_id'                  => $order_id,
            'customer_firstname'    => str_replace($str_search_for,$str_replace_with,$order_wc->shipping_first_name),
            'customer_lastname'     => str_replace($str_search_for,$str_replace_with,$order_wc->shipping_last_name),
            'customer_mobile'         => $order_wc->billing_phone,
            'customer_email'          => $order_wc->billing_email,
            'order_address'         => str_replace($str_search_for,$str_replace_with,$order_wc->get_formatted_shipping_address())
        )
    )));

    // echo '<pre>';
    // print_r($datalist);
    // exit;
// var_dump($datalist);exit;
    $ERPdata        = "ERPdata=".json_encode($datalist, JSON_UNESCAPED_UNICODE);
    $erpuser        =  get_option('mena_merchant_name');    // "apifulfilment";
    $erppassword    =  get_option('mena_merchant_password'); //"apifulfilment";
    $merchant_name  = "MENA360 API";// get_option('erp_user_name');  //"API Test";//
    $ch             = curl_init();
    $url            = $url."client/gapicurl/";
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

        $order_id = $order_wc->get_id();
        wc_reduce_stock_levels( $order_id );

        // Create A custom field Airway bill number and update it

        if ( ! update_post_meta ($order_id, 'awb', $results['response']['tracking_no'] ))
        {
            add_post_meta($order_id, 'awb', $results['response']['tracking_no'], true );
        }

    }elseif($results["awb"] == "SKU not found"){
        echo "<br />$order_id Missing SKU's:<br />";
        foreach($item_list as $item){
        echo "- ". $item['sku'] ."<br>";
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
     font-family: woocommerce !important; content: "\e039" !important;

 height: 30px;
 width: 50px;
 background-image: url("https://m.fetchr.us/image/logo_full.png");
 background-size: contain;
 background-repeat: no-repeat;
 }
 .widefat .column-order_status mark.ship-with-fetchr, .view.ship-with-fetchr::after {
   background-image: url("https://m.fetchr.us/image/favicon.png");
      font-family: woocommerce !important; content: "\e039" !important;

   height: 28px;
   width: 28px;
 }
  .widefat .column-order_status mark.fetchr-processing, .widefat .column-order_status mark.on-hold, .view.on-hold::after{
    font-family: woocommerce !important; content: "\e018" !important;
 height: 30px;
 width: 50px;
 background-size: contain;
 background-repeat: no-repeat;
 }
 .widefat .column-order_status mark.on-hold, .view.on-hold::after {
    font-family: woocommerce !important; content: "\e018" !important;
    
   height: 24px;
   width: 24px;
 }
   .widefat .column-order_status mark.fetchr-processing, .widefat .column-order_status mark.cancelled, .view.cancelled::after{
    font-family: woocommerce !important; content: "\e013" !important;
 height: 30px;
 width: 50px;
 background-size: contain;
 background-repeat: no-repeat;
 }
 .widefat .column-order_status mark.cancelled, .view.cancelled::after {
    font-family: woocommerce !important; content: "\e013" !important;

   height: 24px;
   width: 24px;
 }
    .widefat .column-order_status mark.fetchr-processing, .widefat .column-order_status mark.address, .view.address::after{
    font-family: woocommerce !important; content: "\e604" !important;
 height: 30px;
 width: 50px;
 background-size: contain;
 background-repeat: no-repeat;
 }
 .widefat .column-order_status mark.address, .view.address::after {
    font-family: woocommerce !important; content: "\e604" !important;

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
            jQuery('<option>').val('mark_ship-with-fetchr').text('<?php _e('Mark Fetchr Ship')?>').appendTo("select[name='ship-with-fetchr']");

        });
     </script>
     <?php
    }
  }


  add_filter( 'woocommerce_admin_order_actions', 'add_fetchr_ship_actions_button', PHP_INT_MAX, 2 );
  function add_fetchr_ship_actions_button( $actions, $the_order ) {
    if ( $the_order->has_status( array( 'pending' ) ) ) { // if order is not cancelled yet...
          $actions['ship-with-fetchr'] = array(
              'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=ship-with-fetchr&order_id=' . $the_order->get_id() ), 'woocommerce-mark-order-status' ),
              'name'      => __( 'Ship with Fetchr', 'woocommerce' ),
              'action'    => "view ship-with-fetchr", // setting "view" for proper button CSS
          );
          $actions['on-hold'] = array(
              'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=on-hold&order_id=' . $the_order->get_id() ), 'woocommerce-mark-order-status' ),
              'name'      => __( 'On-Hold', 'woocommerce' ),
              'action'    => "view on-hold", // setting "view" for proper button CSS
          );
          $actions['cancle'] = array(
              'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=cancelled&order_id=' . $the_order->get_id() ), 'woocommerce-mark-order-status' ),
              'name'      => __( 'cancelled', 'woocommerce' ),
              'action'    => "view cancelled", // setting "view" for proper button CSS
          );
        $customername = get_post_meta( $the_order->get_id(), '_billing_first_name', true);
      $customermobile = get_post_meta( $the_order->get_id(), '_billing_phone', true);
      $customeraddress = get_post_meta( $the_order->get_id(), '_billing_address_1', true);  
      $text='اهلا يا '.$customername.'برجاء العلم انه لا يمكننا اكمال طلبك حيث ان العنوان غير كامل او غير صحيح ['.$customeraddress.']- برجاء ارسال عنوان السكن بالتفصيل حتى تتمكن شركة الشحن من توصيل الاوردر بسهولة (رقم العمارة - اسم الشارع - المنطقة - علامة مميزة - الدور - رقم الشقة) ';
      $link_ref='https://www.waboxapp.com/api/send/chat?token=6b359ae0c0b1cec41b1c892fbd2f975f5afa812429b0e&uid=201285304127&to='.$customermobile.'&text='.urlencode($text);
           $actions['address'] = array(
              'url'       => $link_ref,
              'name'      => __( 'address', 'woocommerce' ),
              'action'    => "view address", // setting "view" for proper button CSS
          );
        }
      return $actions;
    }

