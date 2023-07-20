<?php

// Alla oleva koodi lisätään Teeman / Theme functions.php tiedostoon.  Käytä avuksi Teeman tiedostoeditoria. 


add_action( 'woocommerce_before_calculate_totals', 'lisaa_automaattinen_alennus' );
function lisaa_automaattinen_alennus( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }

    // Tarkista, onko alennuskoodi jo lisätty
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
        return;
    }

    // Tarkista postinumerot, joihin haluat tarjota alennuksen
    $halutut_postinumerot = array( 'POSTINUMERO', 'POSTINUMERO', 'POSTINUMERO' ); // Lisää postinumerot joille alennus annetaan.

    $postinumero = WC()->customer->get_shipping_postcode();

    if ( in_array( $postinumero, $halutut_postinumerot ) ) {
        // Laske alennuksen prosenttiosuus
        $alennus_prosentti = 0.3; // Muokkaa desimaalia prosenttien määritetyksi. Esim 0.3 on 30%

        // Luo alennuskupongille uniikki koodi
        $coupon_code = 'AUTOM_ALENNUS_Postinumerolla_' . uniqid();

        // Luo alennuskuponin
        $coupon = array(
            'post_title' => $coupon_code,
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'shop_coupon'
        );

        // Luo alennus kuponille
        $coupon_id = wp_insert_post( $coupon );

        // Aseta alennuskupongille tiedot
        update_post_meta( $coupon_id, 'discount_type', 'percent' );
        update_post_meta( $coupon_id, 'coupon_amount', $alennus_prosentti * 100 );
        update_post_meta( $coupon_id, 'individual_use', 'yes' );
        update_post_meta( $coupon_id, 'product_ids', '' );
        update_post_meta( $coupon_id, 'exclude_product_ids', '' );
        update_post_meta( $coupon_id, 'usage_limit', '1' );
        update_post_meta( $coupon_id, 'expiry_date', date( 'Y-m-d', strtotime( '+1 day' ) ) );
        update_post_meta( $coupon_id, 'apply_before_tax', 'yes' );
        update_post_meta( $coupon_id, 'free_shipping', 'no' );

        // Lisää alennuskuponin ostoskoriin
        $cart->add_discount( $coupon_code );
    }
}
