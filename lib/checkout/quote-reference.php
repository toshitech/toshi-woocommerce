<?php
/**
 * Helpers to generate, store and fetch the quote reference. 
 * This is set when the page is loaded, assigned to the order
 * as "store_order_number" and then used to confirm 
 * the order when the customer pays.
 */

if (! session_id()) {
  session_start();
}

function toshi_set_quote_reference($reference) {
  $_SESSION['toshiQuoteReference'] = $reference;
}

function toshi_get_quote_reference() {  
  return $_SESSION['toshiQuoteReference'] ?? null;
}

function toshi_create_quote_reference() {
  return uniqid();
}
