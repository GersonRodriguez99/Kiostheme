<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

function checkout_kiosk()
{
  $_SESSION['ordertype'] = $_POST['ordertype'];
  echo do_shortcode('[woocommerce_checkout]');
  die();
}
add_action('wp_ajax_checkout_kiosk', 'checkout_kiosk');
add_action('wp_ajax_nopriv_checkout_kiosk','checkout_kiosk');
function get_categories_kiosk()
{
    $site_Id =  get_theme_mod( 'kiosk_location_setting' ) ;
    global $wpdb;
    $pf          = $wpdb->prefix;
    $siteId      = $site_Id;
    $siteDetails = getSitedetails();
    $menu_id     = getMenuId($siteId,'Default',$siteDetails["token"],$siteDetails["ecm_url"],$siteDetails["oeapi_url"]);
    $query       = "SELECT DISTINCT p.term_id, p.name, p.slug FROM " . $pf . "terms p, " . $pf . "termmeta m1, " . $pf . "termmeta m2 WHERE p.term_id = m1.term_id and p.term_id = m2.term_id AND m1.meta_key = 'site_id' AND m1.meta_value = '".$siteId."' AND m2.meta_key = 'menu_id' AND m2.meta_value = '".$menu_id."' order by p.term_id ";
    $categories  = $wpdb->get_results($query);
    $output="";
    $result=[];
    foreach ($categories as $category) {
        $image_id           = get_term_meta( $category->term_id, 'thumbnail_id', true );
        $post_thumbnail_img = wp_get_attachment_image_src( $image_id, 'large' );
        $category->image    = $post_thumbnail_img[0];
        $result[]           = $category;
      }
    wp_send_json($result);
    wp_die();
}
add_action('wp_ajax_get_categories_kiosk', 'get_categories_kiosk');
add_action('wp_ajax_nopriv_get_categories_kiosk','get_categories_kiosk');

function getAllProducts_kiosk()
{
  global $wpdb;
  $offset= $_POST["offset"];

  $site_Id =  get_theme_mod( 'kiosk_location_setting' ) ;
  $pf          = $wpdb->prefix;
  $siteId      = $site_Id;
  $siteDetails = getSitedetails();
  $menu_id     = getMenuId($siteId,'Default',$siteDetails["token"],$siteDetails["ecm_url"],$siteDetails["oeapi_url"]);
  $query       = "SELECT DISTINCT p.term_id FROM " . $pf . "terms p, " . $pf . "termmeta m1, " . $pf . "termmeta m2 WHERE p.term_id = m1.term_id and p.term_id = m2.term_id AND m1.meta_key = 'site_id' AND m1.meta_value = '".$siteId."' AND m2.meta_key = 'menu_id' AND m2.meta_value = '".$menu_id."' order by p.name ";
  $result     = null;

  $counter= $wpdb->get_results("SELECT p.ID FROM wp_posts p,wp_term_relationships tr,wp_postmeta pm,wp_postmeta pm2,wp_postmeta pm3 where  p.ID=tr.object_id and pm.post_id=p.ID and pm2.post_id=p.ID and pm3.post_id=p.ID and pm.meta_key='_price'  and  p.post_status='publish' and pm2.meta_key='_type'and  pm2.meta_value=0 and pm3.meta_key='_stock_status' and pm3.meta_value='instock' and p.post_parent=0 and p.post_type=\"product\" and tr.term_taxonomy_id in ({$query})  group by p.ID");
 $ccounter= count($counter);

 $sqlnew     = "SELECT p.ID, p.post_title,  group_concat(pm.meta_value,'')  as price,tr.term_taxonomy_id as cat_id FROM wp_posts p,wp_term_relationships tr,wp_postmeta pm,wp_postmeta pm2,wp_postmeta pm3 where  p.ID=tr.object_id and pm.post_id=p.ID and pm2.post_id=p.ID and pm3.post_id=p.ID and pm.meta_key='_price'  and  p.post_status='publish' and pm2.meta_key='_type'and  pm2.meta_value=0 and pm3.meta_key='_stock_status' and pm3.meta_value='instock' and p.post_parent=0 and p.post_type=\"product\" and tr.term_taxonomy_id in ({$query})  group by p.ID,tr.term_taxonomy_id limit 16 offset {$offset}";
 // $result     = $wpdb->get_results("SELECT p.ID, p.post_title,pm.meta_value as price FROM wp_posts p,wp_term_relationships tr,wp_postmeta pm where  p.ID=tr.object_id and pm.post_id=p.ID and pm.meta_key='_price'  and  p.post_status='publish' and p.post_type='product' and tr.term_taxonomy_id={$categoryId}");
  $result     = $wpdb->get_results($sqlnew);
  $products   = [];
  $last=0;
  foreach($result as $product)
  {
    $image_id           = get_post_meta( $product->ID, '_thumbnail_id', true );
    $post_thumbnail_img = wp_get_attachment_image_src( $image_id, 'large' );
    $post_thumb_img = wp_get_attachment_image_src( $image_id, 'thumbnail' );
    if($image_id){
      $product->image   = $post_thumbnail_img[0];
    }else{{
      $product->image   = esc_url( get_template_directory_uri() . '/assets/images/woocommerce-placeholder-600x600.png');
    }}
    
    $products[]=$product;
    $last=$product->cat_id;
  }
  $response= array(
    "count"=>$ccounter,
    "lastCat"=>$last,
    "produtcs"=>$products
  );
  wp_send_json($response,200) ;
  wp_die();  
}
add_action('wp_ajax_getAllProducts_kiosk', 'getAllProducts_kiosk');
add_action('wp_ajax_nopriv_getAllProducts_kiosk','getAllProducts_kiosk');
function getProductsByCategoryId_kiosk()
{
  global $wpdb;
  $result     = null;
  $categoryId = $_POST["catid"];
  $sqlnew     = "SELECT p.ID, p.post_title,  group_concat(pm.meta_value,'')  as price FROM wp_posts p,wp_term_relationships tr,wp_postmeta pm,wp_postmeta pm2,wp_postmeta pm3 where  p.ID=tr.object_id and pm.post_id=p.ID and pm2.post_id=p.ID and pm3.post_id=p.ID and pm.meta_key='_price'  and  p.post_status='publish' and pm2.meta_key='_type'and  pm2.meta_value=0 and pm3.meta_key='_stock_status' and pm3.meta_value='instock' and p.post_parent=0 and p.post_type=\"product\" and tr.term_taxonomy_id={$categoryId}  group by p.ID";
 // $result     = $wpdb->get_results("SELECT p.ID, p.post_title,pm.meta_value as price FROM wp_posts p,wp_term_relationships tr,wp_postmeta pm where  p.ID=tr.object_id and pm.post_id=p.ID and pm.meta_key='_price'  and  p.post_status='publish' and p.post_type='product' and tr.term_taxonomy_id={$categoryId}");
  $result     = $wpdb->get_results($sqlnew);
  $products   = [];
  foreach($result as $product)
  {
    $image_id           = get_post_meta( $product->ID, '_thumbnail_id', true );
    $post_thumbnail_img = wp_get_attachment_image_src( $image_id, 'large' );
    $post_thumb_img = wp_get_attachment_image_src( $image_id, 'thumbnail' );
    if($image_id){
      $product->image   = $post_thumbnail_img[0];
    }else{{
      $product->image   = esc_url( get_template_directory_uri() . '/assets/images/woocommerce-placeholder-600x600.png');
    }}
    
    $products[]=$product;
  }

  wp_send_json($products,200) ;
  wp_die();
}
add_action('wp_ajax_getProductsByCategoryId_kiosk', 'getProductsByCategoryId_kiosk');
add_action('wp_ajax_nopriv_getProductsByCategoryId_kiosk','getProductsByCategoryId_kiosk');

function getCartItems_kiosk()
{
  $_SESSION['siteid']= get_theme_mod( 'kiosk_location_setting' ) ;
 $items=WC()->cart->get_cart(); 
 $subtotal = WC()->cart->subtotal;
 $totals =  WC()->cart->total;
// $tax = $_SESSION['TAX'];
$taxes = WC()->cart->get_totals();
$tax=$taxes["fee_total"];
$products=[];
 foreach($items as $product)
 {
  $item = wc_get_product($product["product_id"]);
  $image_id  = get_post_meta( $product["product_id"], '_thumbnail_id', true );
  $post_thumb_img = wp_get_attachment_image_src( $image_id, 'thumbnail' );
  $product["name"]=(string)$item->get_title();
  if($image_id){
    $product["image"] = $post_thumb_img[0]; 
  }else{{
    $product["image"]   = esc_url( get_template_directory_uri() . '/assets/images/woocommerce-placeholder-600x600.png');
  }}
  
  $products[]=$product;
}

 do_action("woocommerce_cart_calculate_fees ",WC()->cart->get_cart());
 $response= [
  "items" => $products,
  "count" => WC()->cart->get_cart_contents_count(),
  "subtotal" => $subtotal,
  "tax"   => (float)$tax , 
  "total" => $totals
 ];
 echo wp_send_json($response);
}

add_action('wp_ajax_getCartItems_kiosk', 'getCartItems_kiosk');
add_action('wp_ajax_nopriv_getCartItems_kiosk','getCartItems_kiosk');

/**
 *    helper functions
 */
function getSitedetails()
{
    global $wpdb;
    $get_token          = $wpdb->get_results( "SELECT token,instance,id FROM cbs_configure_details order by id desc limit 1" );
    $token_id           = $get_token[0]->id;
    $site_token         = $get_token[0]->token;
    $site_instance      = $get_token[0]->instance;
    $get_instance_url   = $wpdb->get_results("SELECT instance_ecmurl,instance_oeapiurl FROM cbs_instances where instance_name='".$site_instance."'");
    $instance_ecm_url   = $get_instance_url[0]->instance_ecmurl;
    $instance_oeapi_url = $get_instance_url[0]->instance_oeapiurl;
    $site=[
    "token"     => $site_token,
    "ecm_url"   => $instance_ecm_url,
    "oeapi_url" => $instance_oeapi_url,
    ];
    return $site;
}
function getMenuId($siteId,$menutype,$site_token,$instance_ecm_url,$instance_oeapi_url){
    try
    {
        $url        = $instance_oeapi_url.'/sites/'.$siteId;
        $token      = $site_token;
        $token_type = 'Token';
        $response   = get_api_data_kiosk($url,$token,$token_type);
        $menu_id    = $response->Data->DefaultWebOrderingMenuId;  // get menu id's from site id
        return $menu_id;

     }

    catch(Exception $e) 
    {
      write_log('Message: ' .$e->getMessage());
    }
}

add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
        
function woocommerce_ajax_add_to_cart() {

            $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
            $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
            $variation_id = absint($_POST['variation_id']);
            $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
            $product_status = get_post_status($product_id);

            if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

                do_action('woocommerce_ajax_added_to_cart', $product_id);

                if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
                    wc_add_to_cart_message(array($product_id => $quantity), true);
                }

                WC_AJAX :: get_refreshed_fragments();
            } else {

                $data = array(
                    'error' => true,
                    'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

                echo wp_send_json($data);
            }

            wp_die();
        }
        add_action('wp_ajax_woocommerce_remove_product_from_cart', 'woocommerce_remove_product_from_cart');
        add_action('wp_ajax_nopriv_woocommerce_remove_product_from_cart', 'woocommerce_remove_product_from_cart');
function woocommerce_remove_product_from_cart() {
     

           $cart_item_key = $_POST['product_id'];
           if ( $cart_item_key ) {
             WC()->cart->remove_cart_item( $cart_item_key );
             return true;
             wp_die();
           }
        }
function single_product_components_kiosk()
        {
          $product_id = $_POST['product_id'];
          $response=array();
          $apicall_count = 0;
          $productComponents = get_post_meta( $product_id, '_components', true);
          $NumberOfPlacements = get_post_meta( $product_id, '_numberofplacement', true);
          $isCombo = get_post_meta( $product_id, '_type', true);
          $productComponents = unserialize($productComponents);
          $product = wc_get_product( $product_id);
          $response["variations"] = array();
          $response["description"] = $product->get_description();
          $response["id"]           = $product->get_id();
          $response["name"]         = $product->get_name();
          $image_id                = get_post_meta( $product->get_id(), '_thumbnail_id', true );
          $post_thumbnail_img      = wp_get_attachment_image_src( $image_id, 'large' );
          $response["image"]       = $post_thumbnail_img[0];
          if ($product->is_type('variable')) {
            $variations_data = []; // Initializing

            // Loop through variations data
            foreach ($product->get_available_variations() as $variation) {
              // Set for each variation ID the corresponding price in the data array (to be used in jQuery)
              $variations_data[$variation['variation_id']] = 
              [
                "dispaly_price" => $variation['display_price'],
                "attributes"=>$variation['attributes']
            ];
            $list[]=$variation['attributes'];
            //creating atributes list
            
            }
            $response["variations"]  = $variations_data;
       
           
          }
          $attList=[];
          foreach ($list as $key => $value) {
            foreach ($value as $key2 => $value2) {
             if(!array_key_exists($key2,$attList)){
              $attList[$key2][]=$value2;
             }
             else{
              if(!in_array($value2, $attList[$key2])){
                $attList[$key2][]=$value2;
              }
             }
            }
          }
          $output ='';
          $response["attributes_list"]=   $attList;
          //print_r($isCombo);
          $output.= '<div id="maxallowedmsg" style="color:red; font-weight:600"></div>';
          $output.=  '<div class="component-section">';
          $count_row=1;
          foreach ($productComponents as $keyy => $Components) {
            if (!empty($Components)) :

              $comp_name_id=explode('###', $keyy);
              $comp_cat_name=$comp_name_id[0];
              $comp_cat_id=$comp_name_id[1];
              $MaxAllowed = 1000;
              $output.=  '<div class="northstar_comp_catid_'.$comp_cat_id.'">';
              $output.=  '<div class="maincomponentcategory cbs_InnerHead">
              <h4>Choose <span id="max_allowed_'.$comp_cat_name.'"></span> ' . $comp_cat_name . '</h4> 
              <h5 id="sel_msg_'.$comp_cat_id.'" class="sel_msg_'.$comp_cat_name.'"></h5>
              <h6 id="sel_qty_'.$comp_cat_id.'" data-minreq="0" class="cbs_Quantity sel_qty_'.$comp_cat_name.'">0</h6>
              </div>';
              $output.=  '<div class="componentelement" style="display:none">';
                $count_default=0;
           
              foreach ($Components as $Component) {
                $Componentval = explode('###', $Component);
                $componentName = $Componentval[0];
                $componentPrice = $Componentval[1];
                $is_default_component = $Componentval[2];
                $component_id = $Componentval[5];
                $ruleId = $Componentval[3];
                $siteId = $Componentval[4];
                $MaxAllowed = 1000;
                $MinRequired = 0;
                $FreeAfter = '';
                $FreeUpTo = 0;
                $MaxUnique = 0;
                $ruleId=(!empty($ruleId))?$ruleId:"Default";
                $response["components"][$comp_cat_name]["info"] = [
                  "catName" => $comp_cat_name,
                  "componentCatId" => $comp_cat_id,
                ];
                $response["components"][$comp_cat_name]["items"][$componentName]=[
                  "componentName"      => $componentName,
                  "componentPrice"     => $componentPrice,
                  "isDefault"          => $is_default_component ,
                  "ruleId"             => $ruleId  ,
                  "siteId"             => $siteId,
                  "NumberOfPlacements" => $NumberOfPlacements,
                  "componentCatId"     => $comp_cat_id,
                  "componentId"        => $component_id 

                
                ];
               
              
        
                $getrules = get_components_rule($siteId, $apicall_count);
                $apicall_count++;
                $rules = $getrules->Data;
        
                foreach ($rules as $key => $rule) {
        
                  if ($rule->RuleId == $ruleId) {
        
                    $MaxAllowed = $rule->MaxAllowed;
                    $MinRequired = $rule->MinRequired;
                    $FreeAfter = $rule->FreeAfter;
                    $FreeUpTo = $rule->FreeUpTo;
                    $MaxUnique = $rule->MaxUnique;

                    $response["components"][$comp_cat_name]["info"]["rules"][$ruleId]=[
                      "MaxAllowed"  => $MaxAllowed ,
                      "MinRequired" => $MinRequired,
                      "FreeAfter"   => $FreeAfter,
                      "FreeUpTo"    => $FreeUpTo,
                      "MaxUnique"   => $MaxUnique,
    
                    ];
                    }elseif($ruleId=="Default")
                    {
                      $response["components"][$comp_cat_name]["info"]["rules"][$ruleId]=[
                        "MaxAllowed"  => $MaxAllowed ,
                        "MinRequired" => $MinRequired,
                        "FreeAfter"   => $FreeAfter,
                        "FreeUpTo"    => $FreeUpTo,
                        "MaxUnique"   => $MaxUnique,
  
                      ];
                    }

                }
                
                if($count_row % 2 ==0)
                {
                  $row_order='even';
                }
                else
                {
                  $row_order='odd';
                }
        
                if ($is_default_component) {
                  $count_default++;
                  $output.=  '<div class="half_and_half roworder'.$row_order.'">';
                  $output.=  '<label class="comp_lbl default cbs_labelCustom"><input type="checkbox" checked  class="productcomponent"  name="productcomponent" data-Ruleid="' . $ruleId . '" value="' . $key . '###' . $Component . '" data-Compname="'.$componentName.'_'.$comp_cat_id.'" data-radioshowhide="'.$key . '###' . $Component.'_portion" data-Compcatid="'.$comp_cat_id.'" data-compcat_name="'.$comp_cat_name.'" /><div class="cbs_w-100">' . $componentName.'</div>';
                  if (!empty($componentPrice)) : $output.=  '<span class="span_base_price">' . get_woocommerce_currency_symbol() . number_format($componentPrice, 2) . '</span>';
                  endif;
                  $output.=  '</label>';
                  
                      if(isset($NumberOfPlacements) && $NumberOfPlacements>1)
                      {
                        $output.=  '<div id="' . $key . '###' . $Component . '_portion" class="cbs_checkBox custom-radio-buttons">
                  <label class="customradio1">
                  <input type="radio" onclick="callmultiplefunc(&#39;' . $ruleId . '&#39;)" value="' . $key . '###' . $Component . '_left" name="' . $componentName . '_'.$comp_cat_id.'"></input>
                   <span class="checkmark"></span>
                  </label>
        
                  <label class="customradio1 customradio2">
                  <input type="radio" onclick="callmultiplefunc(&#39;' . $ruleId . '&#39;)" value="' . $key . '###' . $Component . '" name="' . $componentName . '_'.$comp_cat_id.'" checked></input>
                  <span class="checkmark"></span>
                  </label>
        
                  <label class="customradio1 customradio3">
                  <input type="radio" onclick="callmultiplefunc(&#39;' . $ruleId . '&#39;)" value="' . $key . '###' . $Component . '_right" namea="' . $componentName . '_'.$comp_cat_id.'"></input>
                                    <span class="checkmark"></span>
                  </label>
                  </div>';
                      }
                      $output.=  '<div class="numberCounter">
                              <span id="minus_sign_' . $key . '###' . $Component . '" class="minus cbsminus" data-minus-compname="'.$componentName.'_'.$comp_cat_id.'" data-Ruleid="' . $ruleId . '" data-Compcatid="'.$comp_cat_id.'">-</span>
        
                              <input type="number" id="' . $key . '###' . $Component . '" data-Compname="'.$componentName.'_'.$comp_cat_id.'" name="' . $componentName . '" min="1" value="1" max="' . $MaxAllowed . '" onclick="callmultiplefunc(&#39;' . $ruleId . '&#39;)" class="sh_input_num" data-FreeAfter="' . $FreeAfter . '" data-FreeUpto="' . $FreeUpTo . '" data-MinRequired="' . $MinRequired . '" data-MaxAllowed="' . $MaxAllowed . '" data-Ruleid="' . $ruleId . '" data-Catname="' . $comp_cat_name . '" data-Compcatid="'.$comp_cat_id.'">
        
                              <span id="plus_sign_' . $key . '###' . $Component . '" class="plus cbsplus" data-plus-compname="'.$componentName.'_'.$comp_cat_id.'" data-Ruleid="' . $ruleId . '" data-Compcatid="'.$comp_cat_id.'">+</span>
                      </div>';
                      $output.=  '</div>';
                } else {
        
                  $output.=  '<div class="half_and_half roworder'.$row_order.'">';
                  $output.=  '<label class="comp_lbl cbs_labelCustom"><input type="checkbox" class="productcomponent" name="productcomponent" data-Ruleid="' . $ruleId . '" value="' . $key . '###' . $Component . '" data-Compname="'.$componentName.'_'.$comp_cat_id.'" data-radioshowhide="'.$key . '###' . $Component.'_portion" data-Compcatid="'.$comp_cat_id.'" data-compcat_name="'.$comp_cat_name.'" /><div class="cbs_w-100">' . $componentName.'</div>';
                  if (!empty($componentPrice)) : $output.=  '<span class="span_base_price">' . get_woocommerce_currency_symbol() . number_format($componentPrice, 2) . '</span>';
                  endif;
                  $output.=  '</label>';
        
                    if(isset($NumberOfPlacements) && $NumberOfPlacements>1)
                      {
                        $output.=  '<div id="' . $key . '###' . $Component . '_portion" class="cbs_checkBox custom-radio-buttons">
                  <label class="customradio1">
                  <input type="radio" onclick="callmultiplefunc(&#39;' . $ruleId . '&#39;)" value="' . $key . '###' . $Component . '_left" name="' . $componentName . '_'.$comp_cat_id.'"></input>
                   <span class="checkmark"></span>
                  </label>
        
                  <label class="customradio1 customradio2">
                  <input type="radio" onclick="callmultiplefunc(&#39;' . $ruleId . '&#39;)" value="' . $key . '###' . $Component . '" name="' . $componentName . '_'.$comp_cat_id.'" checked></input>
                  <span class="checkmark"></span>
                  </label>
        
                  <label class="customradio1 customradio3">
                  <input type="radio" onclick="callmultiplefunc(&#39;' . $ruleId . '&#39;)" value="' . $key . '###' . $Component . '_right" name="' . $componentName . '_'.$comp_cat_id.'"></input>
                                    <span class="checkmark"></span>
                  </label>
                  </div>';
                      }
        
        
                      $output.=  '<div class="numberCounter">
                              <span id="minus_sign_' . $key . '###' . $Component . '" class="minus cbsminus" data-minus-compname="'.$componentName.'_'.$comp_cat_id.'" data-Ruleid="' . $ruleId . '" data-Compcatid="'.$comp_cat_id.'">-</span>
        
                              <input type="number" id="' . $key . '###' . $Component . '" data-Compname="'.$componentName.'_'.$comp_cat_id.'" name="' . $componentName . '" min="1" value="1" max="' . $MaxAllowed . '" onclick="callmultiplefunc(&#39;' . $ruleId . '&#39;)" class="sh_input_num" data-FreeAfter="' . $FreeAfter . '" data-FreeUpto="' . $FreeUpTo . '" data-MinRequired="' . $MinRequired . '" data-MaxAllowed="' . $MaxAllowed . '" data-Ruleid="' . $ruleId . '" data-Catname="' . $comp_cat_name . '" data-Compcatid="'.$comp_cat_id.'">
        
                              <span id="plus_sign_' . $key . '###' . $Component . '" class="plus cbsplus" data-plus-compname="'.$componentName.'_'.$comp_cat_id.'" data-Ruleid="' . $ruleId . '" data-Compcatid="'.$comp_cat_id.'">+</span>
                      </div>';
        
                      $output.=  '</div>';
                }
        
                $count_row++;
              }
              $output.=  '<p style="display:none"><input type="checkbox" class="productcomponent" name="productcomponent" data-Ruleid="' . $ruleId . '" value="' . $key . 'test###' . $Component . '"  checked />';
              $output.=  '<input type="number" id="' . $key . 'test###' . $Component . '" name="' . $componentName . '" min="0" value="0" max="' . $MaxAllowed . '"  data-FreeAfter="' . $FreeAfter . '" data-FreeUpto="' . $FreeUpTo . '" data-MinRequired="' . $MinRequired . '" data-MaxAllowed="' . $MaxAllowed . '" data-Ruleid="' . $ruleId . '" data-Catname="' . $comp_cat_name . '"></p>';
        
              $output.=  '</div>';
              $output.=  '</div>';
            endif;
          }
          $output.=  '</div>';
        
          $output.= '  <div class="productcomponentprice price-section" style="display:none">
            <table>
              <tr>
                <td>Product Base Price</td>
                <td><span> '.get_woocommerce_currency_symbol().'</span><span id="pro_price"> '. number_format($product->get_price(),2).'</span>
        
                </td>
              </tr>
              <tr>
                <td> Custom Component Price</td>
                <td><label>  '.get_woocommerce_currency_symbol().'<span id="customprice"></span></td>
              </tr>
              <tr>
                <td> Subtotal</td>
                <td><label>'. get_woocommerce_currency_symbol() . '<span id="custom_subtotal"></span></td>
              </tr>
            </table>
          </div>
          <input type="hidden" name="selComponents" id="selComponents" value="" />
          <input type="hidden" name="selComponentsPrice" id="selComponentsPrice" value="" />
          <input type="hidden" name="selComponentsQty" id="selComponentsQty" value="" />
          <input type="hidden" name="product_price_input" id="product_price_input" value="" />';

          wp_send_json($response);
         // echo $output;
          wp_die();

        }
        add_action('wp_ajax_single_product_components_kiosk', 'single_product_components_kiosk');
        add_action('wp_ajax_nopriv_single_product_components_kiosk', 'single_product_components_kiosk');
            
function product_component_add_cart_item_kiosk($cart_item_data, $product_id, $variatin_id, $quantity)
        {
          $productPrice = filter_input(INPUT_POST, 'product_price_input');
          $productComponent = filter_input(INPUT_POST, 'selComponents');
          
        
          $productComponentPrice = filter_input(INPUT_POST, 'selComponentsPrice');
          $productComponentQty = filter_input(INPUT_POST, 'selComponentsQty');
          if (!empty($productComponentQty)) {
            $productComponentQty = explode(',', $productComponentQty);
          }
          if (!empty($productComponent)) {
            $productComponent = explode(',', $productComponent);
            $componentHtml = '';
            $componentname[] = '';
            $componentids = [];
            foreach ($productComponent as $key => $componentvalue) {
        
              foreach ($productComponentQty as $key => $pcq) {
                $componentsidqty = explode('@@@', $pcq);
                $componentid = $componentsidqty[0];
                $componentqty = $componentsidqty[1];
        
        
                if ($componentid == $componentvalue) {
                  $compqty = $componentqty;
                }
              }
        
              $components = explode('###', $componentvalue);
              $componentCatname = $components[0];
              $componentId = $components[6];
              $comp_id=$componentId; // for adding left and right position to item
        
        
              $leftflag= strpos($comp_id,"left");
                 $rightflag= strpos($comp_id,"right");
                  if($leftflag)
                  {
                    $addon_loc="(Left)";
                  }
                 elseif($rightflag)
                  {
                    $addon_loc="(Right)";
                  }
                  else
                  {
                    $addon_loc="";
                  }
        
              $componentName = $components[1]. $addon_loc . "(" . $compqty . ")";
              if ($compqty != 0) {
                if (!empty($componentname[$componentCatname])) :
                  array_push($componentname[$componentCatname], $componentName);
                  $componentids[$componentId] = $compqty;
                else :
                  $componentname[$componentCatname] = array($componentName);
                  $componentids[$componentId] = $compqty;
                endif;
              }
            }
        
            foreach ($componentname as $key => $componentsvalue) {
              if (!empty($componentsvalue)) {
                  $leftstatus=0;
                     $rightstatus=0;
                     $wholestatus=0;
                $componentHtml .= '<div class="cart_components"><h6 class="head_cart_comp"></h6><dl>';
                foreach ($componentsvalue as $jke => $value) {
            
                  $leftflag= strpos($value,"(Left)");
                     $rightflag= strpos($value,"(Right)");
              
                      if($leftflag)
                      {
                        $leftstatus=1;
                        $componentHtmlLeft = $componentHtmlLeft .'<dd>' . $value . '</dd>';
                      }
                     elseif($rightflag)
                      {
                        $rightstatus=1;
                        $componentHtmlRight = $componentHtmlRight.'<dd>' . $value . '</dd>';
                      }
                      else
                      {
                        $wholestatus=1;
                        $componentHtmlWhole = $componentHtmlWhole. '<dd>' . $value . '</dd>';
                      }
        
                }
        
                if($leftstatus)
                {
                 $componentHtmlLeft_dd='<dt>Left</dt>'.$componentHtmlLeft;
                }
                if($rightstatus)
                {
                $componentHtmlRight_dd='<dt>Right</dt>'.$componentHtmlRight;	
                }
                if($wholestatus)
                {
                $componentHtmlWhole_dd='<dt>Whole</dt>'.$componentHtmlWhole;	
                }
                $componentHtml .= $componentHtmlLeft_dd.$componentHtmlRight_dd.$componentHtmlWhole_dd ;
                $componentHtml .= '</dl></div>';
              }
            }
        
            //Add item data here
        
            $cart_item_data['product_component'] = $componentHtml;
            $cart_item_data['product_component_id'] = $componentids;
        
            $product = wc_get_product($product_id);
            if ($productPrice != '') {
              $price = number_format($productPrice,2);
            } else {
              $price = number_format($product->get_price(),2);
            }
        
            $componentPrice =  $productComponentPrice;
            $cart_item_data['total_price'] = $price + $componentPrice;
          }
        
          return $cart_item_data;
        }
        add_filter('woocommerce_add_cart_item_data', 'product_component_add_cart_item', 10, 4);        

function get_api_data_kiosk($url,$token,$token_type) {
  try{
   write_log('inside get_api_data function fetching url:'. $url);
   $url = $url;
   $token= $token;
  // $token='Wrfdr/2v9juN5PCb/h6feTkGTqt3ZXgPxhC4AAHLYBN1q1qx6u+Jx6w=';
   $curl = curl_init();
   curl_setopt_array($curl, array(
   CURLOPT_URL => $url,
   CURLOPT_RETURNTRANSFER => true,
   CURLOPT_ENCODING => '',
   CURLOPT_MAXREDIRS => 10,
   CURLOPT_TIMEOUT => 0,
   CURLOPT_FOLLOWLOCATION => true,
   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
   CURLOPT_CUSTOMREQUEST => 'GET',
   CURLOPT_HTTPHEADER => array(
       "Authorization: $token_type ".$token
     ),
   ));
   $response = json_decode(curl_exec($curl));
    if($response->ErrorMessage!="")
               {
          $err_msg="API response: ".$response->ErrorMessage; 
          write_log($err_msg);
              }
   curl_close($curl);
   if(!empty($response->Data)) {
       return $response;
   }

     }

       catch(Exception $e) 
       {
         write_log('Message: ' .$e->getMessage());
       }
   
}

function update_taxes_kiosk()
{
  do_action( 'woocommerce_checkout_create_order' );  
}
add_action('wp_ajax_checkout_kiosk', 'checkout_kiosk');
add_action('wp_ajax_nopriv_checkout_kiosk','checkout_kiosk');


function remove_items_from_cart() {
  global $woocommerce;
  $woocommerce->cart->empty_cart();
  return true;
  }
  add_action('wp_ajax_remove_items_from_cart', 'remove_items_from_cart');
  add_action('wp_ajax_nopriv_remove_items_from_cart', 'remove_items_from_cart');

  function setGuestIdintifier() {
    $_SESSION["guestPhone"] = $_POST["guestPhone"];
    }
    add_action('wp_ajax_setGuestIdintifier', 'setGuestIdintifier');
    add_action('wp_ajax_nopriv_setGuestIdintifier', 'setGuestIdintifier');