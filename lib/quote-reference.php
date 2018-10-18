<?php

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
