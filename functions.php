<?php
add_action("template_redirect", 'redirection_function');
require 'plugin-update-checker/plugin-update-checker.php';

function redirection_function(){
    global $woocommerce;
    if( is_cart() && WC()->cart->cart_contents_count == 0){
        wp_safe_redirect( home_url('/') );
    }
}

remove_filter('woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );
remove_filter( 'gettext', 'change_woocommerce_return_to_shop_text' );
function wc_empty_cart_redirect_url_theme() {

	return home_url('/');
}
add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url_theme' );

/**
 * Changes the text for the Return To Shop button in the cart.
 *
 * @return string
 */

add_filter( 'gettext', 'change_woocommerce_return_to_shop_text_theme', 20, 3 );

function change_woocommerce_return_to_shop_text_theme( $translated_text, $text, $domain ) {

        switch ( $translated_text ) {

            case 'Return to shop' :

                $translated_text = __( 'Return to Home', 'woocommerce' );
                break;
            case 'Return to locations' : 

                $translated_text = __( 'Return to Home', 'woocommerce' );
        }

    return $translated_text;
}

function my_register_script_method () {
    wp_register_script( 'jqueryexample', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jqueryexample.min.js');
}
add_action('wp_enqueue_scripts', 'my_register_script_method');

/* WooCommerce: The Code Below Removes Checkout Fields */
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_first_name']);
unset($fields['billing']['billing_last_name']);
unset($fields['billing']['billing_company']);
unset($fields['billing']['billing_address_1']);
unset($fields['billing']['billing_address_2']);
unset($fields['billing']['billing_city']);
unset($fields['billing']['billing_postcode']);
unset($fields['billing']['billing_country']);
unset($fields['billing']['billing_state']);
unset($fields['billing']['billing_phone']);
unset($fields['order']['order_comments']);
unset($fields['billing']['billing_email']);
unset($fields['account']['account_username']);
unset($fields['account']['account_password']);
unset($fields['account']['account_password-2']);

return $fields;
}

include( 'configure/cpt-taxonomy.php' );

// Utilities

include( 'configure/utilities.php' );

// CONFIG

include( 'configure/configure.php' );

// JAVASCRIPT & CSS

include( 'configure/js-css.php' );

// SHORTCODES

include( 'configure/shortcodes.php' );

// ACF

include( 'configure/acf.php' );

// LIBS

include( 'lib/kiosk.php' );

// HOOKS ADMIN

if(is_admin()) {
	include( 'configure/admin.php' );
}




$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://bitbucket.org/cbsnorthstar/theme-cbs-kiosk',
	__FILE__,
	'theme-cbs-kiosk'
);
$myUpdateChecker->setAuthentication(array(
	'consumer_key' => '269DV59QBY83f7BJkN',
	'consumer_secret' => 'EE73ym5LSmZj4ckKJaRe4QgWHyre4pCN',
));
//Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('staging');
