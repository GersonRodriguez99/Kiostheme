<?php

// Utilities functions here
function add_woocommerce_support(){
    add_theme_support('woocommerce');
}
add_action('after_setup_theme' , 'add_woocommerce_support');

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

function hex2rgb( $colour ) {
    if ( $colour[0] == '#' ) {
            $colour = substr( $colour, 1 );
    }
    if ( strlen( $colour ) == 6 ) {
            list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
    } elseif ( strlen( $colour ) == 3 ) {
            list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
    } else {
            return false;
    }
    $r = hexdec( $r );
    $g = hexdec( $g );
    $b = hexdec( $b );
    return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}
remove_action( 'woocommerce_cart_calculate_fees','cbs_custom_tax_surcharge', 10 );

add_action( 'woocommerce_cart_calculate_fees','cbs_custom_tax_surcharge_kiosk', 10, 1 );
function cbs_custom_tax_surcharge_kiosk( $cart ) {
        if ( is_admin() && ! defined('DOING_AJAX') ) return;
          global $wpdb;
          $get_token = $wpdb->get_results( "SELECT token,instance FROM cbs_configure_details order by id desc limit 1" );
          $token=$get_token[0]->token;
          $_SESSION['token']= $token; 
          $site_instance=$get_token[0]->instance;
          $siteid=$_SESSION['siteid'];
      
          $get_instance_url = $wpdb->get_results("SELECT instance_ecmurl,instance_oeapiurl FROM cbs_instances where instance_name='".$site_instance."'");
          $instance_ecm_url=$get_instance_url[0]->instance_ecmurl;
          $instance_oeapi_url=$get_instance_url[0]->instance_oeapiurl;
      
          $delivery_date=date("Y-m-d H:i:s");
          if($_SESSION['table_num']!="" && $_SESSION['pay_later_control']=="Enabled"){
            $table_num=(int)$_SESSION['table_num'];
            $order_type=0;
          }else{
            $table_num=0;
            $order_type=1;
          }
          if(isset( $_SESSION["guestPhone"] ))
          {
            $cart->get_customer()->set_billing_phone( $_SESSION["guestPhone"] );
          }
        
          $northstar_json='{
          "guestName": "'.$cart->get_customer()->get_billing_first_name().'",
          "guestPhoneNumber": "'.$cart->get_customer()->get_billing_phone().'",
          "orderType":'.$order_type.',
          "pickupTime":"'.$delivery_date.'",
          "subTotal": '.$cart->subtotal.',';
          $northstar_json.='"orderItems": ['; // Order item main
          
          foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
      
            $product = $cart_item['data'];
            $product_id = $cart_item['product_id'];
            $menuid=get_post_meta($product_id,'_menuid',true);
            $menuitemid=get_post_meta($product_id,'_itemid',true);
            $step=1;
            $itmqty = $cart_item['quantity'];
            $price =$product->get_price();
            
            while($step<=$itmqty){
              $northstar_json.='{'; // Order items
              $northstar_json.='
              "menuId": "'.$menuid.'",
              "menuItemId": "'.$menuitemid.'",
              "price": '.$price.',';
              $northstar_json.='"components": ['; //Components Main
              $componentsid = $cart_item['product_component_id'];
              
              foreach ($componentsid as $comp_id => $comp_qty) {
                $flag=1;
                
                while($flag<=$comp_qty){ 
                  $northstar_json.='{'; //Components
                  $leftflag= strpos($comp_id,"left");
                  $rightflag= strpos($comp_id,"right");
                  
                  if($leftflag){
                    $addon_loc="Left";
                    $comp_id=str_replace("_left","",$comp_id);
                    $northstar_json.='"componentId": "'.$comp_id.'",';
                    $northstar_json.='"placementLocation": "'.$addon_loc.'"';
                    $comp_id=$comp_id."_left"; //adding left to check for $lastkey
                  }elseif($rightflag){
                    $addon_loc="Right";
                    $comp_id=str_replace("_right","",$comp_id);
                    $northstar_json.='"componentId": "'.$comp_id.'",';
                    $northstar_json.='"placementLocation": "'.$addon_loc.'"';
                    $comp_id=$comp_id."_right"; //adding right to check for $lastkey
                  }else{
                    $addon_loc="All";
                    $northstar_json.='"componentId": "'.$comp_id.'",';
                    $northstar_json.='"placementLocation": "'.$addon_loc.'"';
                  }
                  $lastKey = key(array_slice($componentsid, -1, 1, true));
                  
                  if ($comp_id === $lastKey && $flag==$comp_qty){
                    $northstar_json.='}'; // Components end
                  }else{
                    $northstar_json.='},'; // Components end
                  }
                  $flag++;
                } // end of while loop
              }
              
              $northstar_json.='],'; //Components Main end
              $item_selected_for=$cart_item['item_selected_for'];
              $northstar_json.='"Memos": ["'.$item_selected_for.'"],';
              $northstar_json.='"servingOptions": ['; // Serving options main
              $variation_id = $cart_item ['variation_id'];
              $variation_id_menu=get_post_meta($variation_id,'_menu_variation_id',true);
              $variation_id_menu_array = get_post_meta($variation_id,'_menu_variation_id_array',true);
              
              if(!empty($variation_id_menu)){
                $northstar_json.='{'; //Serving options
                $northstar_json.='"servingOptionId": "'.$variation_id_menu.'"';
                $northstar_json.='}'; // Serving options end
              }elseif(!empty($variation_id_menu_array)){
                $variation_id_menu_array_values = unserialize($variation_id_menu_array);
                
                foreach ($variation_id_menu_array_values as $key => $value) {
                  $northstar_json.='{"servingOptionId": "'.$value.'"},';
                }
              }
              
              // get_variation_attributes Serving foreach end
              $northstar_json.='],'; // Serving options main end
              $northstar_json.='"quantity": '.$itmqty;
              $lastKey = key(array_slice($cart->get_cart(), -1, 1, true));
              
              if ($item_id === $lastKey && $step==$itmqty){
                $northstar_json.='}'; //Order Items end
              }else{
                $northstar_json.='},'; //Order Items end
              }
              $step++;
            } // end of while loop for item
          } // end of foreach loop for $cart->get_cart()
          
          $northstar_json.='],'; // Order item main end
          $northstar_json.='
            "guestCount": 1,
            "arrived": true';
          $northstar_json.='}'; // Main end
          $url = '/checks/validate/';
          $token_type = 'Token';
          
          $connection = new Woapi_connection();
          $response = $connection->post_data($siteid, $url, $token_type , $northstar_json );
          if($response->Ok)
          {
            $taxtotal=$response->Data->TaxTotal;
       
            // Add the fee (tax third argument disabled: false)
            $cart->add_fee( __( 'TAX', 'woocommerce'), $taxtotal, false );
            $_SESSION['TAX'] = $taxtotal;
          }else{
            if($response->ErrorMessage!=""){
              $err_msg="API response: ".$response->ErrorMessage;
             // wc_print_notice(__($err_msg), 'error'); 
              write_log($err_msg);
            }
          }    
      }


