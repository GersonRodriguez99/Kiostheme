<?php

// Shortcode functions here
/* WooCommerce: The Code Below Removes Checkout Fields */
function get_products_all()
{
    global $wpdb;
    $offset= $_GET["num"];
    $site_Id =  get_theme_mod( 'kiosk_location_setting' ) ;
    $pf          = $wpdb->prefix;
    $siteId      = $site_Id;
    $siteDetails = getSitedetails();
    $menu_id     = getMenuId($siteId,'Default',$siteDetails["token"],$siteDetails["ecm_url"],$siteDetails["oeapi_url"]);
    $query       = "SELECT DISTINCT p.term_id FROM " . $pf . "terms p, " . $pf . "termmeta m1, " . $pf . "termmeta m2 WHERE p.term_id = m1.term_id and p.term_id = m2.term_id AND m1.meta_key = 'site_id' AND m1.meta_value = '".$siteId."' AND m2.meta_key = 'menu_id' AND m2.meta_value = '".$menu_id."' order by p.name ";
    $result     = null;
    $sqlnew     = "SELECT p.ID, p.post_title,  group_concat(pm.meta_value,'')  as price,tr.term_taxonomy_id as cat_id FROM wp_posts p,wp_term_relationships tr,wp_postmeta pm,wp_postmeta pm2,wp_postmeta pm3 where  p.ID=tr.object_id and pm.post_id=p.ID and pm2.post_id=p.ID and pm3.post_id=p.ID and pm.meta_key='_price'  and  p.post_status='publish' and pm2.meta_key='_type'and  pm2.meta_value=0 and pm3.meta_key='_stock_status' and pm3.meta_value='instock' and p.post_parent=0 and p.post_type=\"product\" and tr.term_taxonomy_id in ({$query})  group by p.ID,tr.term_taxonomy_id limit 16 offset {$offset}";
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
   $response="";
   foreach ($products as $p) {
    $response .= "
                        <div class=\"card item\"> 
                        <a href=\"#". $p->ID. " \" data-prodid=\"" . $p->ID ." \" class=\"modal-opener\" onClick=\"openModal(this)\">
                        <img class=\"card-img-top\" src=\"". $p->image. "\" alt=\"Card image cap\">
                        <div class=\"card-body\">
                        <h5 class=\"card-title\">". $p->post_title."</h5>
                        <div class=\"card-text\">" .$p->ID ."</div>
                        </div>
                        </a>
                        </div>
              
              ";          ;
   }
   $offset=$offset+12;
   $response .= '<nav id="page_nav" style="display: none;">
   <a href="get-more-products/?num='.$offset.'"></a>
 </nav>';
   echo $response;
}

add_shortcode('get_products_all', 'get_products_all');