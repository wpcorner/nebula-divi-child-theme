<?php
/**
 * Nebula Divi Child Theme
 * Functions.php
 *
 * ===== NOTES ==================================================================
 * 
 * Unlike style.css, the functions.php of a child theme does not override its 
 * counterpart from the parent. Instead, it is loaded in addition to the parent's 
 * functions.php. (Specifically, it is loaded right before the parent's file.)
 * 
 * In that way, the functions.php of a child theme provides a smart, trouble-free 
 * method of modifying the functionality of a parent theme. 
 * 
 * =============================================================================== */
 
function divichild_enqueue_scripts() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'divichild_enqueue_scripts' );

// To change add to cart text on single product page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text' ); 
function woocommerce_custom_single_add_to_cart_text() {
    return __( 'Buy it Now', 'woocommerce' ); 
}

// To change add to cart text on product archives(Collection) page
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text' );  
function woocommerce_custom_product_add_to_cart_text() {
    return __( 'Buy it Now', 'woocommerce' );
}

function wc_billing_field_strings( $translated_text, $text, $domain ) {
    switch ( $translated_text ) {
        case 'Billing details' :
            $translated_text = __( 'Billing and Shipping Info', 'woocommerce' );
            break;
    }
    return $translated_text;
}
add_filter( 'gettext', 'wc_billing_field_strings', 20, 3 );


// Remove company field
function wc_remove_checkout_fields( $fields ) {

// Billing fields
unset( $fields['billing']['billing_company'] );
return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'wc_remove_checkout_fields' );

// To redirect users after logging in

function custom_login_redirect($redirect_to, $request, $user) {
   
// Is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        // check for admins
        if (in_array('administrator', $user->roles)) {
            
//Redirect them to the default place
            return home_url('/wp-admin');
        } else {
            //Redirect other users to the my-account page
            return home_url('/my-account');
        }
    } else {

//Redirect guests to the login page
        return home_url('/my-account');
    }
}

add_filter('login_redirect', 'custom_login_redirect', 10, 3);

// To redirect users to /logout page after logging out

function custom_logout_redirect() {
    wp_redirect(home_url('/logout'));
    exit();
}

add_action('wp_logout', 'custom_logout_redirect');

// Stop redirecting loggin ins from /my-account page to /wp-login.php

function custom_disable_login_redirect($redirect_to, $request, $user) {

// Is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        
// If the user is logging in from the /my-account page, do not redirect
        if ($request === home_url('/my-account') && in_array('customer', $user->roles)) {
            return home_url('/my-account');
        }
    }

// Continue with the default redirection
    return $redirect_to;
}

add_filter('login_redirect', 'custom_disable_login_redirect', 10, 3);

