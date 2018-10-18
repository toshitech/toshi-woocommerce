<?php 

/**
 * Taken from Bitcube's Huntsman original WC plugin.
 */
function toshi_get_cart_data()
{
    $orderObj = (object) [];
    $addressObj = (object) [];
    $priceObj = (object) [];

    $priceObj->value = WC()->cart->subtotal;
    $priceObj->currency = get_woocommerce_currency();

    foreach (WC()->cart->get_cart() as $cart_item) {

        $orderItemObj = (object) [];

        // Get the item name
        $orderItemObj->name = $cart_item['data']->get_title();

        // Get the item size
        $orderItemObj->size = $cart_item['variation']['attribute_pa_size'];

        // Get the item SKU
        $orderItemObj->sku = $cart_item['data']->get_sku();

        // Get the item quantity
        $orderItemObj->qty = $cart_item['quantity'];

        // Get the item price
        $orderItemObj->unitPrice = $cart_item['data']->get_price();

        // Get the item image URL
        $orderItemObj->imageUrl = get_the_post_thumbnail_url($cart_item['product_id'], 'post-thumbnail');

        // Get the variations
        // $tickets = new WC_Product_Variable( $cart_item['product_id'] );
        // $variables = $tickets->get_available_variations();
        $variation_id = $cart_item['variation_id']; // Product Variation ID

        $variation_post_details = get_post($variation_id); // Get Variation Details

        if ($variation_id != 0) {
            $product_variation_obj = wc_get_product($cart_item['product_id']); // Product variation Object

            $product_variations = $product_variation_obj->get_available_variations(); // Get all available variations for the item

            if ($variation_post_details->menu_order == 0) { // Check if the menu order of the current item and reverse the order if no menu order is present
                $product_variations = array_reverse($product_variations);
            }

            $key = array_search($cart_item['variation_id'], array_column($product_variations, 'variation_id')); // Get the current item's position in the variation array

            if ($key + 1 < count($product_variations)) { // Get the upsize item
                $upsize_obj = $product_variations[$key + 1];

                if ($upsize_obj['is_in_stock'] && $upsize_obj['variation_is_active'] && $upsize_obj['is_purchasable'] && $upsize_obj['variation_is_visible']) {
                    $orderItemObj->sizeUpSize = $upsize_obj['attributes']['attribute_pa_size'];
                    $orderItemObj->sizeUpSku = $upsize_obj['sku'];
                }

            }

            if ($key != 0) { // Get the downsize item
                $downsize_obj = $product_variations[$key - 1];

                if ($downsize_obj['is_in_stock'] && $downsize_obj['variation_is_active'] && $downsize_obj['is_purchasable'] && $downsize_obj['variation_is_visible']) {
                    $orderItemObj->sizeDownSize = $downsize_obj['attributes']['attribute_pa_size'];
                    $orderItemObj->sizeDownSku = $downsize_obj['sku'];
                }
            }

        }

        $orderObj->product[] = $orderItemObj;
    }

    toshi_set_quote_reference(toshi_create_quote_reference());

    return array(
        'products' => $orderObj->product,
        'orderTotalPrice' => WC()->cart->subtotal,
        'orderCurrency' => get_woocommerce_currency(),
        'quoteNumber' => toshi_get_quote_reference(),
    );
}
